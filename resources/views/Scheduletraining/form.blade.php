@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Schedule Trainning</h3>
  @include('layouts.breadcrumb')



  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white rounded-top-4">
    </div>

    <div class="card-body">
      <form method="post" id="scheduletraining" class="scheduletraining" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="save_status" id="save_status">

        <div class="row g-4">

          <!-- ========================== COLUMN 1 ========================== -->
          <div class="col-md-4">

            <!-- Topic -->
            <div class="mb-3">
              <label class="form-label required">Topic</label>
              <input type="hidden" name="schedule_training_hdr_id" value="{{ $row->schedule_training_hdr_id }}">
              <input type="hidden" name="reference_id" value="{{ $row->reference_id }}">
              <select name="topic_id" id="topic_id" class="form-select select2 topic_id">
                {!! $topic_id !!}
              </select>
            </div>

            <!-- Schedule Create Date -->
            <div class="mb-3">
              <label class="form-label">Schedule Create Date</label>
              <input type="text" name="schedule_date" id="schedule_date" class="form-control schedule_date"
                value="{{ $row->schedule_date }}" required>
            </div>

            <!-- Need Exam -->
            <div class="mb-3">
              <label class="form-label">Need Exam?</label>
              <select name="need_exam" class="form-select select2 need_exam" required>
                <option value="">--Please Select--</option>
                <option value="Yes" {{ $row->need_exam == 'Yes' ? 'selected' : '' }}>Yes</option>
                <option value="No" {{ $row->need_exam == 'No' ? 'selected' : '' }}>No</option>
              </select>
            </div>

            <!-- Schedule Type -->
            <div class="mb-3">
              <label class="form-label">Schedule Type</label>
              <select name="schedule_type" class="form-select select2 schedule_type" required>
                <option value="">--Please Select--</option>
                <option value="Manual" {{ $row->schedule_type == 'Manual' ? 'selected' : '' }}>Direct</option>
                <option value="Request" {{ $row->schedule_type == 'Request' ? 'selected' : '' }}>Request</option>
                <option value="Online" {{ $row->schedule_type == 'Online' ? 'selected' : '' }}>Online</option>
              </select>
            </div>

          </div>
          <!-- ======================= END COLUMN 1 ======================= -->


          <!-- ========================== COLUMN 2 ========================== -->
          <div class="col-md-4">

            <!-- Departments -->
            <div class="mb-3">
              <label class="form-label">Departments</label>
              <select name="department_id" class="form-select department_id select2" required multiple>
                {!! $departments !!}
              </select>
            </div>

            <!-- Meeting Start Datetime -->
            <div class="mb-3">
              <label class="form-label">Meeting Start DateTime</label>
              <input type="text" name="start_time" id="start_time" class="form-control start_time"
                value="{{ $row->start_time }}" required>
            </div>

            <!-- Meeting End Datetime -->
            <div class="mb-3">
              <label class="form-label">Meeting End DateTime</label>
              <input type="text" name="end_time" id="end_time" class="form-control end_time" value="{{ $row->end_time }}"
                required>
            </div>

            <!-- Remarks -->
            <div class="mb-3">
              <label class="form-label">Remarks</label>
              <input type="text" name="remarks" id="remarks" class="form-control remarks" value="{{ $row->remarks }}">
            </div>

          </div>
          <!-- ======================= END COLUMN 2 ======================= -->


          <!-- ========================== COLUMN 3 ========================== -->
          <div class="col-md-4">


            <div class="mb-3 Zone">
              <label class="form-label">Zone</label>
              <select class="form-select zone select2" multiple>
                {!! $zone !!}
              </select>
            </div>
            <!-- Trainer Type -->
            <div class="mb-3">
              <label class="form-label">Trainer Type</label>
              <select name="trainer_type" class="form-select select2 trainer_type" required>
                <option value="">--Please Select--</option>
                <option value="Internal" {{ $row->trainer_type == 'Internal' ? 'selected' : '' }}>Internal</option>
                <option value="External" {{ $row->trainer_type == 'External' ? 'selected' : '' }}>External</option>
              </select>
            </div>

            <!-- Trainer Name Select -->
            <div class="mb-3 trainersel">
              <label class="form-label">Trainer Name</label>
              <select name="trainer_name[]" class="form-select select2 trainer_name" multiple></select>
            </div>

            <!-- Trainer Name Text -->
            <div class="mb-3 trainertext">
              <label class="form-label">Trainer Name</label>
              <input type="text" name="exttrainer_name[]" class="form-control trainer_name1">
            </div>

            <!-- Trainer Email -->
            <div class="mb-3 trainertext">
              <label class="form-label">Trainer Mail</label>
              <input type="text" name="trainer_mail" class="form-control trainer_mail">
            </div>

            <!-- Description -->
            <div class="mb-3">
              <label class="form-label">Description</label>
              <textarea name="description" id="description" class="form-control description">
                                    {{ $row->description }}
                 </textarea>
            </div>

            <!-- Schedule Status -->
            <div class="mb-3">
              <label class="form-label">Schedule Status</label>
              <select name="schedule_status" class="form-select schedule_status select2" style="pointer-events:none;">
                <option value="OPEN">OPEN</option>
                <option value="COMPLETED">COMPLETED</option>
                <option value="CANCELED">CANCEL</option>
              </select>
            </div>

          </div>
          <!-- ======================= END COLUMN 3 ======================= -->

        </div>






        <!--****************Linedata ********************-->
        <div class="row mt-4">
          <div class="col-12 linetable">
            <div class="row mb-2">
              <div class="col-md-4">
                <input type="text" id="tableSearch" class="form-control" placeholder="Search Employee Number / Name">
              </div>
            </div>

            <div class="table-responsive" style="max-height: 300px;overflow-y: auto;">
              <table class="table table-bordered clone_table">
                <thead class="table-light topfreeze">

                  <tr>

                    <th>Line No</th>
                    <th>Employee Number</th>
                    <th>Employee Name</th>
                    <?php if ($row->schedule_type == 'Request') { ?>
                    <th></th>
                    <?php } else {?>
                    <th class="text-center"><input type="checkbox" id="check_all" class="check_all"></th>
                    <?php }?>
                  </tr>

                </thead>
                <tbody class="clone_lines_body">
                  <?php

  if (count($linedata) >= 1) {  ?>
                  @foreach($linedata as $key => $value)

                    <tr>
                      <td>
                        <input type="hidden" name="bulk_schedule_training_line_id[]"
                          class="form-control input-sm bulk_schedule_training_line_id"
                          value="{{ $value->schedule_training_line_id }}">
                        <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no"
                          value="{{ $key + 1 }}" readonly="readonly">
                      </td>
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
                        <input type="checkbox" name="bulk_check[]" class="bulk_check" checked
                          value="{!! $value->employee_id !!}">
                        <input type="hidden" name="counter[]">
                      </td>

                    </tr>

                  @endforeach
                  <?php }
  if (count($linedata) < 1) { ?>
                  @foreach($employee_id as $key => $value)
                    <tr class="clone  cloneRow rcopy">
                      <td>
                        <input type="hidden" name="bulk_schedule_training_line_id[]"
                          class="form-control input-sm bulk_schedule_training_line_id" value="">
                        <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="1"
                          readonly="readonly">
                      </td>

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
                  <?php } ?>

                </tbody>


              </table>
              <input type="hidden" name="enable-masterdetail" value="true">
            </div>
          </div>
        </div>
        <!--****************Linedata End********************-->


        <div class="row mt-4">
          <div class="col-lg-12 col-md-12">
            <div class="form-group text-center">
              <button type="button" class="btn btn-success saveform px-4 me-2" value="save">Schedule</button>
              <button type="button" class='btn btn-danger px-4 me-2'
                onclick='location.href="{{ url($pageMethod) }}"'>Cancel</button>
            </div>
          </div>
        </div>
    </div>
    </form>

  </div>
  </div>



@endsection
@push('scripts')


  <script>


    $(document).ready(function () {
      $('.datetimepicker1').datetimepicker({
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        format: "yyyy-mm-dd hh:ii:ss",
      });

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


      $(document).on('change', '.department_id', function () {

        var department = $(this).val();
        var $loader = $('.ajaxLoading');
        var $container = $('.clone_lines_body');

        if (!department) {
          $container.html('');
          return;
        }

        $.ajax({
          url: "{{ url('getemployeelistbydept') }}/" + department,
          type: "GET",
          beforeSend: function () {
            $loader.show();
          },
          success: function (response) {
            $container.html(response);
          },
          error: function (xhr) {
            console.error(xhr.responseText);
            alert('Failed to load employee list.');
          },
          complete: function () {
            $loader.hide();
          }
        });

      });


      $(document).on('change', '.zone', function () {

        var department = $(this).val();
        var $loader = $('.ajaxLoading');
        var $container = $('.clone_lines_body');

        if (!department) {
          $container.html('');
          return;
        }

        $.ajax({
          url: "{{ url('getemployeelistbyzone') }}/" + department,
          type: "GET",
          beforeSend: function () {
            $loader.show();
          },
          success: function (response) {
            $container.html(response);
          },
          error: function (xhr) {
            console.error(xhr.responseText);
            alert('Failed to load employee list.');
          },
          complete: function () {
            $loader.hide();
          }
        });

      });

      $(document).on('change', '.topic_id', function () {
        var topic = $(this).val();
        var url = "{{url('getdepartmentsbytopic')}}/" + topic;

        $.get(url, function (data) {
          $('.department_id').html(data);
        });

      });


      /* -- Start Save,draft Button function -- */
      $(document).on('click', '.saveform', function () {

        var url = "{{ url('scheduletrainingsave') }}";
        var mailUrl = "{{ url('send-training-mail') }}";
        var red_url = "{{ url('scheduletraining') }}";

        var form = $('#scheduletraining');

        form.parsley().validate();

        if (form.parsley().isValid()) {

          $(".ajaxLoading").show();

          $.ajax({
            url: url,
            type: "POST",
            data: form.serialize(),
            dataType: "json",

            success: function (data) {

              if (data.status == 'success') {

                var scheduleId = data.schedule_id;

                $.post(mailUrl, {
                  schedule_id: scheduleId,
                  _token: "{{ csrf_token() }}"
                });

                showCustomAlert(data.message,'success');

                window.location.href = red_url;

              } else {
                showCustomAlert(data.message, 'error');
              }

              $(".ajaxLoading").hide();
            },

            error: function (xhr) {

              $(".ajaxLoading").hide();
              console.log(xhr.responseText);
              alert("Server Error");
            }
          });
        }
      });



      $('.trainertext').hide();
      $(".trainer_type").change(function () {
        var type = $(this).val();
        if (type == "Internal") {
          $('.trainertext').hide();
          $('.trainersel').show();
          $('.trainer_name').attr('required', true);
          $('.trainer_name1').attr('required', false);
          $('.trainer_mail').attr('required', false);
          var $trainer = $(".trainer_name");

          $.ajax({
            url: "{{ URL::to('jcomboform') }}",
            type: "GET",
            dataType: "json",
            data: {
              table: "hr_employee_t:employee_id:employee_number|first_name"
            },
            beforeSend: function () {
              $trainer.prop('disabled', true);
            },
            success: function (response) {

              $trainer.empty();
              $trainer.append('<option value="">-- Select Trainer --</option>');

              $.each(response, function (i, row) {

                $trainer.append(
                  '<option value="' + row.val + '">' + row.option_name + '</option>'
                );
              });
            },
            error: function () {
              alert('Failed to load trainer list');
            },
            complete: function () {
              $trainer.prop('disabled', false);
            }
          });
        } else {
          $('.trainersel').hide();
          $('.trainertext').show();
          $('.trainer_name1').attr('required', true);
          $('.trainer_mail').attr('required', true);
          $('.trainer_name').attr('required', false);

        }
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

      /* End Remove Class Function */

    });


    var dateToday = new Date();
    var data = "{{\Session::get('j_date_format')}}";
    $("#schedule_date").datepicker({
      changeMonth: true,
      dateFormat: data,
      changeYear: true,
      minDate: 0,
      maxDate: 0,
      //maxDate: null,
      onClose: function () {
        $(this).parsley().validate();
      }

    }).attr('readonly', 'readonly');



    $(document).on("keyup", "#tableSearch", function () {
      var value = $(this).val().toLowerCase();

      $(".clone_lines_body tr").each(function () {
        var empNo = $(this).find(".bulk_employee_number").val().toLowerCase();
        var empName = $(this).find(".bulk_employee_name").val().toLowerCase();

        if (empNo.includes(value) || empName.includes(value)) {
          $(this).show();
        } else {
          $(this).hide();
        }
      });
    });


    $(document).ready(function () {

      const dateFormat = "{{ \Session('j_date_format') ?? 'yy-mm-dd' }}";
      const timeFormat = "HH:mm:ss";


      $(document).on("focus", ".start_time", function () {
        $(this).datetimepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: dateFormat,
          timeFormat: timeFormat,
          controlType: 'select',
          oneLine: true,
          showSecond: true,
          minDate: new Date(),
          showAnim: "slideDown",
          yearRange: "-25:+0",
          onClose: function (selectedDateTime) {
            if (selectedDateTime) {
              const startDate = $(this).datetimepicker("getDate");

              // Reinitialize end_date_time with updated minDate
              $(".end_time").datetimepicker("destroy").datetimepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: dateFormat,
                timeFormat: timeFormat,
                controlType: 'select',
                oneLine: true,
                showSecond: true,
                minDate: new Date(),
                showAnim: "slideDown",
                yearRange: "-25:+0"
              });
            }
          }
        });
      });
    });

  </script>

@endpush