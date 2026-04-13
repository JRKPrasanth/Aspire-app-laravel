@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Attendance Setting</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <form method="post" action="" id="ats_form" class="ats_form" data-parsley-validate enctype="multipart/form-data">
        <input type="hidden" value="" name="edit_id" id="edit_id" />
        {{ csrf_field() }}

        <div class="row g-3">
          <!-- Employee Type -->
          <div class="col-md-6">
            <div class="row g-2 align-items-center">
              <label for="employee_type" class="form-label col-md-4"><span class="req">*</span>Employee Type</label>
              <div class="col-md-6">
                <select class="select2 form-control employee_type" name="employee_type" id="employee_type" required
                  style="width: 100%;"></select>
              </div>
            </div>
          </div>
        </div>

        <!-- Attendance Rules -->
        <div class="row g-3 mt-3">
          <!-- Rule Template -->
          <div class="col-md-6">
            <div class="form-check">
              <input class="form-check-input early1 early" type="checkbox" name="attendance_rules[]" id="early_punch"
                value="1">
              <label class="form-check-label" for="early_punch">Consider Early coming Punch</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-check">
              <input class="form-check-input early2 early" type="checkbox" name="attendance_rules[]" id="Late_punch"
                value="2">
              <label class="form-check-label" for="Late_punch">Consider Late going Punch</label>
            </div>
          </div>

          <!-- Repeat similar structure for each rule below -->
          <!-- Rule 5 -->
          <div class="col-md-6">
            <div class="form-check">
              <input class="form-check-input early5 early" type="checkbox" name="attendance_rules[]" value="5">
              <label class="form-check-label">Calculate If HalfDay If work Duration Is Less Than</label>
            </div>
          </div>
          <div class="col-md-6">
            <input type="text" name="rules_data[]" class="breakhours1 form-control" placeholder="mins">
          </div>

          <!-- Rule 6 -->
          <div class="col-md-6">
            <div class="form-check">
              <input class="form-check-input early6 early" type="checkbox" name="attendance_rules[]" value="6">
              <label class="form-check-label">Calculate Absent If work Duration Is Less Than</label>
            </div>
          </div>
          <div class="col-md-6">
            <input type="text" name="rules_data[]" class="breakhours2 form-control" placeholder="mins">
          </div>

          <!-- Rule 7 -->
          <div class="col-md-6">
            <div class="form-check">
              <input class="form-check-input early7 early" type="checkbox" name="attendance_rules[]" value="7">
              <label class="form-check-label">On Partial day Calculation HalfDay If Work Duration is Less Than</label>
            </div>
          </div>
          <div class="col-md-6">
            <input type="text" name="rules_data[]" class="breakhours3 form-control" placeholder="mins">
          </div>

          <!-- Rule 8 -->
          <div class="col-md-6">
            <div class="form-check">
              <input class="form-check-input early8 early" type="checkbox" name="attendance_rules[]" value="8">
              <label class="form-check-label">On Partial day Calculation Absent If Work Duration is Less Than</label>
            </div>
          </div>
          <div class="col-md-6">
            <input type="text" name="rules_data[]" class="breakhours4 form-control" placeholder="mins">
          </div>

          <!-- Rule 9 -->
          <div class="col-md-12">
            <div class="form-check">
              <input class="form-check-input early9 early" type="checkbox" name="attendance_rules[]" value="9">
              <label class="form-check-label">Mark weekly Off and Holiday as Absent for Prefix Day is Absent</label>
            </div>
          </div>

          <!-- Rule 10 -->
          <div class="col-md-12">
            <div class="form-check">
              <input class="form-check-input early10 early" type="checkbox" name="attendance_rules[]" value="10">
              <label class="form-check-label">Mark weekly Off and Holiday as Absent for Suffix Day is Absent</label>
            </div>
          </div>

          <!-- Rule 11 -->
          <div class="col-md-12">
            <div class="form-check">
              <input class="form-check-input early11 early" type="checkbox" name="attendance_rules[]" value="11">
              <label class="form-check-label">Mark weekly Off and Holiday as Absent if both Suffix Day and Prefix Day is
                Absent</label>
            </div>
          </div>

          <!-- Rule 12 to 15 -->
          <!-- Each with checkbox + input -->
          <div class="col-md-6">
            <div class="form-check">
              <input class="form-check-input early12 early" type="checkbox" name="attendance_rules[]" value="12">
              <label class="form-check-label">Mark FullDay Absent When late For</label>
            </div>
          </div>
          <div class="col-md-6">
            <input type="text" name="rules_data[]" class="breakhours5 form-control" placeholder="mins">
          </div>

          <div class="col-md-6">
            <div class="form-check">
              <input class="form-check-input early13 early" type="checkbox" name="attendance_rules[]" value="13">
              <label class="form-check-label">Mark Half Day If late by</label>
            </div>
          </div>
          <div class="col-md-6">
            <input type="text" name="rules_data[]" class="breakhours6 form-control" placeholder="mins">
          </div>

          <div class="col-md-6">
            <div class="form-check">
              <input class="form-check-input early14 early" type="checkbox" name="attendance_rules[]" value="14">
              <label class="form-check-label">Mark Half Day If Early Going by</label>
            </div>
          </div>
          <div class="col-md-6">
            <input type="text" name="rules_data[]" class="breakhours7 form-control" placeholder="mins">
          </div>

          <div class="col-md-6">
            <div class="form-check">
              <input class="form-check-input early15 early" type="checkbox" name="attendance_rules[]" value="15">
              <label class="form-check-label">Per Month If Late By</label>
            </div>
          </div>
          <div class="col-md-6">
            <input type="text" name="rules_data[]" class="breakhours8 form-control" placeholder="mins">
          </div>
        </div>

        <!-- Submit Button -->
        <div class="row mt-4">
          <div class="col text-center">
            <input type="hidden" name="submit_type" class="submit_type" id="submit_type">
            <button type="button" class="btn btn-success save_form px-4" value="SAVE">Save</button>
          </div>
        </div>

      </form>
    </div>
  </div>



  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="PosTbl" class="table table-bordered table-striped w-100">
          <thead>
            <tr class="table-warning">

              <th style="display:none;">department_name</th>
              <th style="display:none;">attendance_rules</th>
              <th style="display:none;">rules_data</th>
              <th>Employee Type</th>
              <th>Actions</th>

            </tr>

            <tr class="table-info">
              <th style="display:none;"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">department_name</span></th>
              <th style="display:none;"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">attendance_rules</span></th>
              <th style="display:none;"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">rules_data</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Employee
                  Type</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;"></span></th>

            </tr>
          </thead>

          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>






@endsection
@push('scripts')

  <script>

    // table data
    $(document).ready(function () {

      var table = $('#PosTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('getattenancesettingdata') }}",
        columns: [

          { data: "employee_type", visible: false },
          { data: "attendance_rules", visible: false },
          { data: "rules_data", visible: false },
          { data: "lookup_code" },

          {
            data: 'attendance_settings_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',
            width: '140px',
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
          <button type="button" class="btn btn-sm btn-primary edit-btn"
            data-id="${row.attendance_settings_id}"
            data-emp="${row.employee_type}"
            data-arule="${row.attendance_rules}"
            data-rule="${row.rules_data}"
            data-code="${row.lookup_code}">
            <i class="bi bi-pencil"></i>
          </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
            <button type="button" class="btn btn-sm btn-danger delete-btn"
              data-id="${row.attendance_settings_id}">
              <i class="bi bi-trash"></i>
            </button>`;
              }
              return buttons;
            }
          }
        ]
      });

      // Individual column search
      $('#PosTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });


    // employee type	

    var condition = "lookup_type='EMPLOYEE_TYPE'";
    var url = "{{ URL::to('jcomboform') }}?table=a_lookuplines_t:lookuplines_id:lookup_meaning&parent=" + encodeURIComponent(condition);

    loadDropdown(
      ".employee_type",
      url,
      "",
      "-- Select Employee Type --"
    );
    function loadDropdown(selector, url, selectedValue, defaultText = "-- Select --") {
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

          $(selector).html(`<option value="">${defaultText}</option>`);

          $.each(data, function (i, item) {
            let selected = item.val == selectedValue ? 'selected' : '';
            $(selector).append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
          });

          $(selector).trigger('change.select2');
        }
      });
    }


    // conditions	
    // Employee Type check Already Start 
    $(document).on('change', '#employee_type', function () {
      var id = $('#employee_type').val();
      var edit_id = $('#edit_id').val()
      if (edit_id == "" || edit_id == null) {
        if (id == "") {
          console.log('Type');
        }
        else {
          var url = "{{URL::to('emp_type')}}?id=" + id;
          $.get(url, function (data, status) {
            if (data == 2) {
              $('#employee_type').val('');
              setTimeout(function () {
                showCustomAlert('Already created for this Method', 'warning');
              }, 500);
              $('.save_form').attr("disabled", "disabled");
            }
            if (data == 1) {
              console.log('Please Select');
              $('.save_form').attr("disabled", false);
            }
          });
        }
      }
      else {
        $('#employee_type').attr('readonly', 'readonly');
      }
    });

    // while check box check required set
    $(document).on('change', '.early', function () {

      if ($('.early').is(':checked')) {
        var check = $(this).val();
        if (check == 5) {
          $('.breakhours1').attr('required', true);
        }
      } else {

        var check = $(this).val();
        if (check == 5) {
          $('.breakhours1').removeAttr('required', false);
        }
      }
      if ($('.early').is(':checked')) {

        var check = $(this).val();
        if (check == 6) {
          $('.breakhours2').attr('required', true);
        }
      } else {

        var check = $(this).val();
        if (check == 6) {
          $('.breakhours2').removeAttr('required', false);
        }
      }
      if ($('.early').is(':checked')) {

        var check = $(this).val();
        if (check == 7) {
          $('.breakhours3').attr('required', true);
        }
      } else {

        var check = $(this).val();
        if (check == 7) {
          $('.breakhours3').removeAttr('required', false);
        }
      }
      if ($('.early').is(':checked')) {

        var check = $(this).val();
        if (check == 8) {
          $('.breakhours4').attr('required', true);
        }
      } else {

        var check = $(this).val();
        if (check == 8) {
          $('.breakhours4').removeAttr('required', false);
        }
      }
      if ($('.early').is(':checked')) {

        var check = $(this).val();
        if (check == 12) {
          $('.breakhours5').attr('required', true);
        }
      } else {

        var check = $(this).val();
        if (check == 12) {
          $('.breakhours5').removeAttr('required', false);
        }
      }
      if ($('.early').is(':checked')) {

        var check = $(this).val();
        if (check == 13) {
          $('.breakhours6').attr('required', true);
        }
      } else {

        var check = $(this).val();
        if (check == 13) {
          $('.breakhours6').removeAttr('required', false);
        }
      }
      if ($('.early').is(':checked')) {

        var check = $(this).val();
        if (check == 5) {
          $('.breakhours1').attr('required', true);
        }
      } else {

        var check = $(this).val();
        if (check == 5) {
          $('.breakhours1').removeAttr('required', false);
        }
      }
      if ($('.early').is(':checked')) {

        var check = $(this).val();
        if (check == 15) {
          $('.breakhours8').attr('required', true);
        }
      } else {

        var check = $(this).val();
        if (check == 15) {
          $('.breakhours8').removeAttr('required', false);
        }
      }
      if ($('.early').is(':checked')) {

        var check = $(this).val();
        if (check == 14) {
          $('.breakhours7').attr('required', true);
        }
      } else {

        var check = $(this).val();
        if (check == 14) {
          $('.breakhours7').removeAttr('required', false);
        }
      }
    });


    // key press validation

    $(document).on('keypress', '.breakhours1,.breakhours2,.breakhours3,.breakhours4,.breakhours5,.breakhours6,.breakhours7,.breakhours8', function (ev) {
      var regex = new RegExp("^[0-9.]+$");
      var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
      if (regex.test(str)) {
        return true;
      }
      ev.preventDefault();
      return false;
    });


    //save 
    $(document).on('click', '.save_form', function () {

      var btnval = $(this).val();
      var url = "{{ url('attendancesettingsave') }}";
      var red_url = "{{ url('atscreate') }}";

      var formdata = $('#ats_form').serialize();
      var form = $('#ats_form');

      var form = $('#ats_form');
      form.parsley().validate();
      if (form.parsley().isValid()) {
        var $btn = $(this);
        $btn.prop('disabled', true);
        $.post(url, formdata, function (data) {
          if (data == 1) {

            showCustomAlert("Setting Saved Successfully", "success");
            form[0].reset();
            $('.select2').val('').trigger('change');

            window.location.reload();
          } else {

            showCustomAlert("Setting Update Successfully", "success");
            form[0].reset();
            $('.select2').val('').trigger('change');
            window.location.reload();
          }


        });
      }
    });

    // delete

    let deleteId = null;
    $(document).on('click', '.delete-btn', function () {
      deleteId = $(this).data('id');
      $('#globalDeleteModal').modal('show');
    });

    $('#globalConfirmDeleteBtn').on('click', function () {
      if (deleteId) {
        $.ajax({
          url: "{{ url('attendancesettingdelete') }}/" + deleteId,
          type: "GET",
          success: function (data) {


            $('#globalDeleteModal').modal('hide');


            if (data == 1) {

              showCustomAlert('Info', 'Cannot Be Delete.Which is in Approved State or Used in Some Where', 'error');
              $('#PosTbl').DataTable().ajax.reload();

            }
            else if (data == 2) {
              showCustomAlert('Settings Details Deleted Successfully', 'success');
              $('#PosTbl').DataTable().ajax.reload();
            }

          },
          error: function (xhr) {
            $('#globalDeleteModal').modal('hide');
            const errorMsg = xhr.responseJSON?.message || 'Delete failed.';
            showCustomAlert(errorMsg, 'error');
          }
        });
      }
    });


    // edit function	

    $(document).on('click', '.edit-btn', function () {
      const id = $(this).data('id');
      const emp = $(this).data('emp');
      const attendanceRules = $(this).data('arule'); // already an array
      const rulesData = $(this).data('rule');        // already an array
      const code = $(this).data('code');

      // Fill hidden input
      $('input[name="edit_id"]').val(id);

      // Set select2 field
      $('select[name="employee_type"]').val(emp).trigger('change');

      // Clear all checkboxes
      $('input[type="checkbox"]').prop("checked", false);

      // Apply rule checkboxes
      if (Array.isArray(attendanceRules)) {
        attendanceRules.forEach(function (val) {
          $('.early' + val).prop("checked", true);
        });
      }

      // Apply rules_data (break hours)
      if (Array.isArray(rulesData)) {
        rulesData.forEach(function (val, index) {
          $('.breakhours' + (index + 1)).val(val);
        });
      }
    });


  </script>

@endpush