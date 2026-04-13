@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Schedule Exam</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-sm rounded-4">
    <div class="card-body">
      <form method="post" action="" id="scheduleexam" class="scheduleexam" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="save_status" id="save_status">
        <input type="hidden" name="schedule_exam_hdr_id" id="schedule_exam_hdr_id"
          value="{{ $row->schedule_exam_hdr_id }}">
        <input type="hidden" name="reference_id" id="reference_id" value="{{ $row->reference_id }}">

        <div class="row g-3">
          <!-- Column 1 -->
          <div class="col-md-4">
            <div class="mb-3 row">
              <label class="col-sm-5 col-form-label"><span class="text-danger">*</span>Topic</label>
              <div class="col-sm-7">
                <select name="topic_id" id="topic_id" class="form-select select2" required>
                  {!! $topic_id !!}
                </select>
              </div>
            </div>

            <div class="mb-3 row">
              <label class="col-sm-5 col-form-label"><span class="text-danger">*</span>Schedule Date</label>
              <div class="col-sm-7">
                <input type="text" name="schedule_date" id="schedule_date" class="form-control start_date"
                  value="{{ $row->schedule_date }}" required>
              </div>
            </div>
          </div>

          <!-- Column 2 -->
          <div class="col-md-4">
            <div class="mb-3 row">
              <label class="col-sm-5 col-form-label"><span class="text-danger">*</span>Start Time</label>
              <div class="col-sm-7">
                <input type="text" name="start_time" id="start_time" class="form-control start_date_time"
                  value="{{ $row->start_time }}" required>
              </div>
            </div>

            <div class="mb-3 row">
              <label class="col-sm-5 col-form-label"><span class="text-danger">*</span>End Time</label>
              <div class="col-sm-7">
                <input type="text" name="end_time" id="end_time" class="form-control end_date_time"
                  value="{{ $row->end_time }}" required>
              </div>
            </div>
          </div>

          <!-- Column 3 -->
          <div class="col-md-4">
            <div class="mb-3 row">
              <label class="col-sm-5 col-form-label"><span class="text-danger">*</span>Schedule Type</label>
              <div class="col-sm-7">
                <select name="schedule_type" class="form-select select2" required readonly>
                  <option value="">--Please Select--</option>
                  <option value="Manual" {{ $row->schedule_type == 'Manual' ? 'selected' : '' }}>Manual</option>
                  <option value="Request" {{ $row->schedule_type == 'Request' ? 'selected' : '' }}>Request</option>
                </select>
              </div>
            </div>

            <div class="mb-3 row">
              <label class="col-sm-5 col-form-label">Remarks</label>
              <div class="col-sm-7">
                <input type="text" name="remarks" id="remarks" class="form-control" value="{{ $row->remarks }}">
              </div>
            </div>
          </div>
        </div> <!-- row -->
      </form>
    </div>

    <!--****************Linedata ********************-->
    <div class="col-12 linetable">

      <div class="table-responsive" style="height:350px !important">
        <div class="card shadow-sm rounded-4">
          <table class="table table-bordered company_table">
            <thead class="table-light">
              <tr>
                <th style="width: 80px;">Line No</th>
                <th>Employee Number</th>
                <th>Employee Name</th>
                <th class="text-center"><input type="checkbox" id="check_all" class="check_all"></th>
              </tr>
            </thead>
            <tbody class="company_lines_body">
              @if(count($linedata) > 0)
                @foreach($linedata as $key => $value)
                  <tr class="line-row">

                    <td><input type="hidden" name="bulk_schedule_exam_line_id[]"
                        class="form-control input-sm bulk_schedule_exam_line_id" value=""><input type="text"
                        name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="1" readonly="readonly"></td>
                    <td>
                      <input type="text" name="bulk_employee_number[]" id="bulk_employee_number"
                        class="form-control input-sm bulk_employee_number " value="{!! $value->emp_number !!}"
                        readonly="readonly">
                    </td>
                    <td>
                      <input type="hidden" name="bulk_employee_id[]" id="bulk_employee_id"
                        class="form-control input-sm bulk_employee_id " value="{!! $value->employee_id !!}"
                        readonly="readonly">
                      <input type="text" name="bulk_employee_name[]" id="bulk_employee_name"
                        class="form-control input-sm bulk_employee_name " value="{!! $value->employee_name !!}"
                        readonly="readonly">
                    </td>
                    <td class="emp text-center">
                      <input type="checkbox" name="bulk_check[]" class="bulk_check" value="{!! $value->employee_id !!}">
                      <input type="hidden" name="counter[]">
                    </td>
                  </tr>
                @endforeach
              @else
                @foreach($employee_id as $key => $value)
                  <tr class="clone  cloneRow rcopy">
                    <td><input type="hidden" name="bulk_schedule_exam_line_id[]"
                        class="form-control input-sm bulk_schedule_exam_line_id" value=""><input type="text"
                        name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="1" readonly="readonly"></td>
                    <td>
                      <input type="text" name="bulk_employee_number[]" id="bulk_employee_number"
                        class="form-control input-sm bulk_employee_number " value="{!! $value->emp_number !!}"
                        readonly="readonly">
                    </td>
                    <td>
                      <input type="hidden" name="bulk_employee_id[]" id="bulk_employee_id"
                        class="form-control input-sm bulk_employee_id " value="{!! $value->employee_id !!}"
                        readonly="readonly">
                      <input type="text" name="bulk_employee_name[]" id="bulk_employee_name"
                        class="form-control input-sm bulk_employee_name " value="{!! $value->employee_name !!}"
                        readonly="readonly">
                      <!--<select name="bulk_employee_id[]" id="bulk_employee_id" class="form-control select2 bulk_employee_id " required="required"></select>-->
                    </td>
                    <td class="emp text-center">
                      <input type="checkbox" name="bulk_check[]" class="bulk_check" value="{!! $value->employee_id !!}">
                      <input type="hidden" name="counter[]">
                    </td>

                  </tr>
                @endforeach
              @endif
            </tbody>

          </table>
        </div>
        <!-- END -->
      </div>
      <div class="text-center mt-4">
        <button type="button" class="btn btn-success saveform px-4 me-2">Save</button>
        <a href="{{ url($pageModule) }}" class="btn btn-secondary px-4">Cancel</a>
      </div>
      </form>

    </div>

@endsection
  @push('scripts')

    <script>


      $(document).ready(function () {


        /**********Up/down/left/right arrow navigation start*******/
        $('input').keyup(function (e) {
          if (e.which == 39) { // right arrow
            $(this).closest('td').next().find('input').focus();

          } else if (e.which == 37) { // left arrow
            $(this).closest('td').prev().find('input').focus();

          } else if (e.which == 40) { // down arrow
            $(this).closest('tr').next().find('td:eq(' + $(this).closest('td').index() + ')').find('input').focus();

          } else if (e.which == 38) { // up arrow
            $(this).closest('tr').prev().find('td:eq(' + $(this).closest('td').index() + ')').find('input').focus();
          }
        });

        /***** by vignesh purpose check all********/
        $('.check_all').change(function () {
          if ($(this).prop("checked") == true) {
            $('.bulk_check').prop('checked', true);
          } else {
            $('.bulk_check').prop('checked', false);
          }
        });



        /* -- Start Save function -- */
        $('#save_status').val('');


        /* -- Start Save,draft Button function -- */
        $(document).on('click', '.saveform', function () {
          var btnval = $(this).val();
          var url = "{{ URL::to('scheduleexamsave') }}";
          var red_url = "{{ url('scheduleexam') }}";
          var create_url = "{{ url('scheduleexamcreate') }}/0";
          var form = $('#scheduleexam');
          if (btnval != 'applychanges') {
            var form = $('#scheduleexam');
            validationrule('scheduleexam');
            form.parsley().validate();

            if (form.parsley().isValid()) {
              change_date();
              var formdata = $('#scheduleexam').serialize();
              var form_data = new FormData(document.getElementById('scheduleexam'));

              $(".ajaxLoading").show();
              $.post(url, formdata, function (data) {
                var status = data.status;
                var msg = data.message;
                var id = data.id;

                if (btnval != 'save') {
                  notyMsg(status, msg);
                  window.location.href = create_url;
                }
                else {
                  notyMsg(status, msg);
                  window.location.href = red_url;
                }
              });


            }
          }
        });
        $('.trainertext').hide();
        $(".trainer_type").change(function () {
          var type = $(this).val();
          if (type == "Internal") {
            $('.trainertext').hide();
            $('.trainer_name').attr('required', true);
            $('.trainer_name1').attr('required', false);
            $('.trainer_mail').attr('required', false);
            $(".trainer_name").jCombo("{{ URL::to('jcomboform?table=hr_employee_t:employee_id:first_name|last_name')}}",
              { selected_value: "" });
          } else {
            $('.trainersel').hide();
            $('.trainertext').show();
            $('.trainer_name1').attr('required', true);
            $('.trainer_mail').attr('required', true);

          }
        });

        $(document).on('change', '.topic_id', function () {
          var topic = $(this).val();
          var url = "{{url('getexamemployeelistbytopic')}}/" + topic;
          $('.ajaxLoading').show();
          $.get(url, function (data) {
            // condition = data;
            $('.schedule_lines_body').html(data);
            $('.ajaxLoading').hide();
            // $('.bulk_employee_id').jCombo("{{ URL::to('jcomboform?table=hr_employee_t:employee_id:employee_number|first_name')}}&parent="+condition+"&order_by=employee_id",
            // {selected_value:""});          
          });
        });


        $(document).on('change', '.bulk_employee_id', function () {
          var index = $(this).closest('tr').index();
          var emp_id = $(this).val();
          var count = 0;
          var count = empcheck(emp_id, index);
          if (count <= 0) {
          } else {
            notyMsgs('info', 'Employee Already Selected');
            $(".bulk_employee_id" + index).select2('val', ['']);

          }

        });


        /* Start Remove Class Function */
        $(document).on('click', '.remove', function () {
          var index = $(this).closest('tr').index();
          var rowCount = $('.schedule_table tbody tr').length;
          if (rowCount > 1) {
            // alert();
            $(this).closest("tr").remove();
            removeClass('bulk_line_no');
            removeClass('bulk_schedule_exam_line_id');
            removeClass('bulk_employee_id');
            removeClass('bulk_check');
          }
          else {
            notyMsg("info", "You can't Delete Atleast one row should be there");
          }
        });
        /* End Remove Class Function */

      });

      /* Start Change Class name Function */
      function changeclassfields() {
        changeClassName('bulk_line_no');
        changeClassName('bulk_schedule_exam_line_id');
        changeClassName('bulk_employee_id');
        changeClassName('bulk_check');
      }

      function changeClassName(className) {
        $('.' + className).each(function (index) {
          $(this).removeClass(className + '0');
          $(this).addClass(className + index);
          if (className == "bulk_line_no") {
            $(this).val(index + 1).attr("readonly", 1);
          }
        });
      }
      /* End Change Class name Function */

      /* Start Remove Class name Function */
      function removeClass(className) {
        var rowCount = $('.scheduleexam tbody tr').length;
        for (var i = 0; i <= rowCount; i++) {
          $('.scheduleexam tbody tr').find('.' + className).removeClass(className + i);
        }
        $('.' + className).each(function (index) {
          if (className == "bulk_line_no") {
            $(this).val(index + 1).attr("readonly", 1);
          }
          $(this).addClass(className + index);
        });

      }
      /* End Remove Class name Function */
    </script>

  @endpush