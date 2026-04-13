@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Monthly Attendance</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white fw-semibold">
    </div>
    <div class="card-body card-block">
      <form action="" id="monthlyattendance" data-parsley-validate>
        <?php $data = \Session::get('data');
  if (isset($data[$pageMethod]['save'])) { ?>
        <input type="hidden" name="edit_id" value="" id="edit_id" />
        {{ csrf_field() }}
        <div class="row g-4">
          <div class="col-md-4">
            <div class="row mb-3">
              <label for="start_date" class="col-md-5 col-form-label"><span class="req">*</span>Employee</label>
              <div class="col-md-7">
                <select name='employee_id' id="employee_id" class='form-select select2' data-show-subtext="true"
                  data-live-search="true" required></select>
              </div>
            </div>
            <div class="row mb-3">
              <label for="no_of_days" class="col-md-5 col-form-label"><span class="req">*</span>No of Days</label>
              <div class="col-md-7">
                <input type="text" id="no_of_days" name="no_of_days" class="form-control no_of_days" required>
              </div>
            </div>
            <div class="row mb-3">
              <label for="c_l" class="col-md-5 col-form-label">Casual leave</label>
              <div class="col-md-7">
                <input type="text" id="c_l" data-index="c_l" name="c_l" class="form-control c_l" maxlength="3">
              </div>
            </div>
            <div class="row mb-3">
              <label for="ot_hours" class="col-md-5 col-form-label">OT Hours</label>
              <div class="col-md-7">
                <input type="text" id="ot_hours" name="ot_hours" class="form-control ot_hours" maxlength="3">
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="row mb-3">
              <label for="start_date" class="col-md-5 col-form-label"><span class="req">*</span>Start Date</label>
              <div class="col-md-7">
                <input class="form-control start_date" id="start_date" name="start_date" required type="text">
              </div>
            </div>
            <div class="row mb-3">
              <label for="no_of_present_days" class="col-md-5 col-form-label"><span class="req">*</span>No of Days
                Present</label>
              <div class="col-md-7">
                <input type="text" id="no_of_present_days" name="no_of_present_days"
                  class="form-control no_of_present_days" required>
              </div>
            </div>
            <div class="row mb-3">
              <label for="s_l" class="col-md-5 col-form-label">Sick leave</label>
              <div class="col-md-7">
                <input type="text" id="s_l" data-index="s_l" name="s_l" class="form-control s_l" maxlength="3">
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="row mb-3">
              <label for="end_date" class="col-md-5 col-form-label"><span class="req">*</span>End Date</label>
              <div class="col-md-7">
                <input class="form-control end_date" id="end_date" name="end_date" required type="text">
              </div>
            </div>
            <div class="row mb-3 align-items-center">
              <label class="col-md-5 col-form-label"><span class="req">*</span>Loan Deduction:</label>
              <div class="col-md-7">
                <div class="form-check form-check-inline">
                  <input class="form-check-input loan_on" type="radio" name="loan_deduction" id="loan_yes" value="1">
                  <label class="form-check-label" for="loan_yes">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input loan_off" type="radio" name="loan_deduction" id="loan_no" value="0"
                    checked>
                  <label class="form-check-label" for="loan_no">No</label>
                </div>
              </div>
            </div>
            <div class="row mb-3">
              <label for="e_l" class="col-md-5 col-form-label">Earn leave</label>
              <div class="col-md-7">
                <input type="text" id="e_l" data-index="e_l" name="e_l" class="form-control e_l" maxlength="3">
              </div>
            </div>
          </div>
        </div>

        <div class="row mt-4 text-center">
          <div class="col-12">
            <button type="button" class="btn btn-success save_form px-4">Save</button>
          </div>
        </div>
        <?php } ?>
      </form>
    </div>
  </div>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4 table-responsive">
      <table id="AttTbl" class="table table-bordered table-striped w-100">
        <thead>


          <tr class="table-warning">
             <th>Actions</th>
            <th style="display:none;">Employee Id</th>
            <th>Employee Name</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Loan Status</th>
            <th>Total Days</th>
            <th>Loan Deduction</th>
            <th>Days Present</th>
           

          </tr>
          <tr class="table-info">

            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;"></span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;"></span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;"></span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;"></span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;"></span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;"></span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;"></span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;"></span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;"></span></th>

          </tr>
        </thead>
        <tbody>

        </tbody>
      </table>
    </div>
  </div>

@endsection
@push('scripts')

  <script>

    // dropdown

    loadDropdown(
      "#employee_id",
      "{{ URL::to('jcomboform') }}?table=hr_employee_t:employee_id:employee_number|first_name",
      "",
      "-- Select Employee --"
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


    // table data	
    $(document).ready(function () {
      var table = $('#AttTbl').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        fixedHeader: true,
        ajax: "{{ route('monthlytblData') }}",
        columns: [

                    {
            data: 'monthly_atten_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
            <button type="button" class="btn btn-sm btn-primary edit-btn"
              data-id="${row.monthly_atten_id}"
              data-code="${row.organization_code}"
              data-name="${row.organization_name}"
              data-type="${row.lookuplines_id}"
              data-active="${row.active}">
              <i class="bi bi-pencil"></i>
            </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
              <button type="button" class="btn btn-sm btn-danger delete-btn"
                data-id="${row.monthly_atten_id}">
                <i class="bi bi-trash"></i>
              </button>`;
              }
              return buttons;
            }

          },
          
          { data: 'employee_id', name: 'employee_id', visible: false },
          { class: 'freeze', data: 'first_name', name: 'hr_employee_t.first_name' },
          { data: 'start_date', name: 'start_date' },
          { data: 'end_date', name: 'end_date' },
          { data: 'loan_status', name: 'loan_status' },
          { data: 'no_of_days', name: 'no_of_days' },
          { data: 'loan_deduction', name: 'loan_deduction' },
          { data: 'no_of_present_days', name: 'no_of_present_days' },

        ]
      });

      $('#AttTbl thead').on('keyup change', '.column-search', function () {
        let index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });

    //save

    $(document).on('click', '.save_form', function () {
      var url = "{{URL::to('monthlysave')}}";
      var form = $('#monthlyattendance');
      form.parsley().validate();
      if (form.parsley().isValid()) {
        var $btn = $(this);
        $btn.prop('disabled', true);
        var data = $('#monthlyattendance').serialize();
        $.post(url, data, function (data1) {
          var data1 = $.trim(data1);

          if (data1 == 1) {
            showCustomAlert('Monthly Attendance Saved Successfully', 'success');
            window.location.reload();

          }
          if (data1 == 2) {
            showCustomAlert('Monthly Attendance Already Generated Successfully', 'warning');
            window.location.reload();

          }
          if (data1 == 3) {
            showCustomAlert('Check the employee Payproposal details', 'error');
            window.location.reload();

          }

        });
      }
    });

    // delete function

    $(document).on('click', '.delete-btn', function () {
      deleteId = $(this).data('id');
      $('#globalDeleteModal').modal('show');
    });

    $('#globalConfirmDeleteBtn').on('click', function () {
      if (deleteId) {
        $.ajax({
          url: "{{ url('monthlydelete') }}/" + deleteId,
          type: "GET",
          success: function (data) {

            $('#globalDeleteModal').modal('hide');

            if (data == 1) {


              showCustomAlert('Cannot Be Delete.Which is in Approved State or Used in Some Where', 'warning');
              $('#AttTbl').DataTable().ajax.reload();

            } else if (data == 0) {

              showCustomAlert('Monthly Attendance Details Deleted Successfully', 'success');
              $('#AttTbl').DataTable().ajax.reload();
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

  </script>

@endpush