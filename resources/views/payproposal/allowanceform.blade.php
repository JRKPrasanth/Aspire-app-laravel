@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Employee Payproposal</h3>
  @include('layouts.breadcrumb')

  <?php include('tools_menu.php'); ?>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">

      <form id="allowanceindex" data-parsley-validate>
        <input type="hidden" name="edit_id" id="edit_id" />
        {{ csrf_field() }}

        <div class="row g-4">

          <!-- Employee -->
          <div class="col-md-4">
            <label for="employee_id" class="form-label fw-semibold">
              <span class="text-danger">*</span> Employee
            </label>
            <div class="input-group">
              <select name="employee_id" id="employee_id" required class="form-select select2">
                <!-- Options will be loaded dynamically -->
              </select>
            </div>
          </div>

          <!-- Employee Type (Disabled) -->
          <div class="col-md-4">
            <label for="employee_type" class="form-label fw-semibold">
              <span class="text-danger">*</span> Employee Type
            </label>
            <select id="employee_type" name="employee_type" class="form-select select2" required>
              {!! $employee_type !!}
            </select>
          </div>

          <!-- Department (Disabled) -->
          <div class="col-md-4">
            <label for="department_id" class="form-label fw-semibold">
              <span class="text-danger">*</span> Department
            </label>
            <select id="department_id" name="department_id" class="form-select select2" required>
              {!! $department_id !!}
            </select>
          </div>

          <!-- Month -->
          <div class="col-md-4">
            <label for="month" class="form-label fw-semibold">
              <span class="text-danger">*</span> Month
            </label>
            <select id="month" name="month" class="form-select select2" required>
              {!! $month !!}
            </select>
          </div>

          <!-- Year -->
          <div class="col-md-4">
            <label for="year" class="form-label fw-semibold">
              <span class="text-danger">*</span> Year
            </label>
            <select id="year" name="year" class="form-select select2" required>
              {!! $year !!}
            </select>
          </div>

          <!-- Allowance Placeholder -->
          <div class="col-12">
            <div class="allowance">
              <!-- Allowance fields will be dynamically appended here -->
            </div>
          </div>

          <!-- Pay Proposal Details -->
          <div class="col-md-4">
            <label class="form-label fw-semibold">Pay Proposal Details</label>
            <div>
              <a href="#" class="btn btn-outline-primary contactdetail" title="Pay Proposal Details">
                <i class="fa fa-plus"></i> Add
              </a>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="text-center mt-4">
          <button type="button" class="btn btn-success px-4 save_form">
            Save
          </button>
        </div>
      </form>
    </div>
  </div>



  <!-- pop up -->
  <!-- Modal -->
  <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content" style="height: 100vh;">

        <!-- Modal Header -->
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title fw-bold" id="contactModalLabel">Payproposal Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <input type="hidden" class="conindex" value="">
        </div>

        <!-- Modal Body -->
        <div class="modal-body">
          <div class="poppayprosal">

          </div>
        </div>

      </div>
    </div>
  </div>

  <!--end-->


  <div class="card">
    <div class="container mt-4">
      <table id="payTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Employee ID</th>
            <th>Employee Type</th>
            <th>Department ID</th>
            <th>Allowance</th>
            <th>Employee Name</th>
            <th>Employee Type</th>
            <th>Department</th>
            <th>Month</th>
            <th>Year</th>
            <th>Actions</th>
          </tr>
          <tr class="table-info">
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>

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

    // onchange	
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

    // Employee
    var employeeUrl = "{{ URL::to('jcomboform') }}?table=hr_employee_t:employee_id:employee_number|first_name";
    loadDropdown("#employee_id", employeeUrl, "", "-- Select Employee --");

    // Employee Type
    var conditionEmpType = 'and lookup_type="EMPLOYEE_TYPE"';
    var empTypeUrl = "{{ URL::to('jcomboform1') }}?table=a_lookuplines_t:lookuplines_id:lookup_code&parent=" + encodeURIComponent(conditionEmpType) + "&order_by=lookup_type asc";
    loadDropdown("#employee_type", empTypeUrl, "", "-- Select Employee Type --");



    // validations	

    $(document).on('keypress', '#basic,#da,#hra,.allowance,#annual_allowance,#gratuity', function (ev) {
      var regex = new RegExp("^[0-9.]+$");
      var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
      if (regex.test(str)) {
        return true;
      }
      ev.preventDefault();
      return false;
    });
    // numbers only validation
    $(document).on('keypress', '.amount', function (ev) {
      var regex = new RegExp("^[0-9.]+$");
      var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
      if (regex.test(str)) {
        return true;
      }
      ev.preventDefault();
      return false;
    });



    $('#employee_id').on('change', function () {

      var emp_type = $(this).val();
      var month = $('.month').val();
      var year = $(this).val();
      $('.poppayprosal').html("");
      $('.allowance').html("");
      if (emp_type != '') {
        var url = "{{ URL::to('getemployeetypeallowancedata') }}/" + emp_type + "?year=" + year + "&month=" + month;
        $.get(url, function (data) {
          if ($.trim(data['html']) == '') {
            $('.allowance').html('');
          } else {
            $('.allowance').html(data['html']);

          }

          $('.poppayprosal').html(data['phtml']);


          $('.employee_type').select2('val', [data['id']]);
          $('.department_id').select2('val', [data['depart']]);
          $(".employee_type").parsley().destroy();

        });
      }

    });


    $('.contactdetail').click(function () {
      $('#contactModal').modal('show');

    });


    // save form 
    $(document).on('click', '.save_form', function () {
      var url = "{{URL::to('employeemanualallowance')}}";
      var form = $('#allowanceindex');
      form.parsley().validate();
      var form = $('#allowanceindex');
      var data = $('#allowanceindex').serialize();
      form.parsley().validate();
      if (form.parsley().isValid()) {
        var $btn = $(this);
        $btn.prop('disabled', true);
        $.post(url, data, function (data1) {
          if (data1 == 1) {
            showCustomAlert('Pay Proposal Saved Successfully', 'success');
            window.location.reload();
            form[0].reset();
            $('.select2').val('').trigger('change');
          }
          else {
            showCustomAlert('Pay Proposal Updated Successfully', 'success');
            window.location.reload();
            form[0].reset();
            $('.select2').val('').trigger('change');
          }
        });
      }
    });


    // table data
    $(document).ready(function () {
      var table = $('#payTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('allowanceindexgriddata') }}",
        columns: [
          { data: 'employee_id', name: 'employee_id', visible: false },
          { data: 'employee_type', name: 'employee_type', visible: false },
          { data: 'department_id', name: 'department_id', visible: false },
          { data: 'allowance', name: 'allowance', visible: false },
          { data: 'first_name', name: 'first_name' },
          { data: 'employee_type_name', name: 'employee_type_name' },
          { data: 'department_name', name: 'department_name' },
          { data: 'month', name: 'month' },
          { data: 'year', name: 'year' },

          {
            data: 'id',
            name: 'actions',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
          <button type="button" class="btn btn-sm btn-primary edit-btn"
                  data-id="${row.id}">
            <i class="bi bi-pencil"></i>
          </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
            <button type="button" class="btn btn-sm btn-danger delete-btn"
              data-id="${row.id}">
              <i class="bi bi-trash"></i>
            </button>`;
              }
              return buttons;
            }

          }
        ]
      });


      $('#payTbl thead').on('keyup change', '.column-search', function () {
        let index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });


    // delete

    $(document).on('click', '.delete-btn', function () {
      deleteId = $(this).data('id');
      $('#globalDeleteModal').modal('show');
    });

    $('#globalConfirmDeleteBtn').on('click', function () {
      if (deleteId) {
        $.ajax({
          url: "{{ url('allowancepayproposaldelete') }}/" + deleteId,
          type: "GET",
          success: function (response) {
            $('#globalDeleteModal').modal('hide');
            showCustomAlert('Deleted', 'success');
            $('#payTbl').DataTable().ajax.reload();

          },
          error: function (xhr) {
            $('#globalDeleteModal').modal('hide');
            const errorMsg = xhr.responseJSON?.message || 'Delete failed.';
            showCustomAlert(errorMsg, 'error');
          }
        });
      }
    });


    // edit
    $(document).on('click', '.edit-btn', function () {
      var form = $("#allowanceindex");
      form.parsley().destroy(); // reset validation

      // Get the row data using DataTables API
      var table = $('#payTbl').DataTable();
      var tr = $(this).closest('tr');
      var row = table.row(tr).data();

      if (row) {
        $('#edit_id').val(row.id);

        $('#employee_id').val(row.employee_id).trigger('change');
        $('#department_id').val(row.department_id).trigger('change');
        $('#employee_type').val(row.employee_type).trigger('change');
        $('#month').val(row.month).trigger('change');
        $('#year').val(row.year).trigger('change');

        var myObject = JSON.parse(row.allowance); // allowance is a JSON string

        console.log(myObject);

        var url = "{{ URL::to('allowancegetidasjust') }}/" + row.employee_id;
        $.get(url, function (data) {
          var payallow = data;

          if (payallow.length > 0) {
            $.each(myObject, function (key, value) {
              var k = key + 1;

              // Delay may be necessary if elements are dynamically rendered
              setTimeout(function () {
                $('.allowance_id' + k).val(value[payallow[key]]);
              }, 200);
            });
          }
        });

        // Show modal (if applicable)
        $('#contactModal').modal('show');
      } else {
        showCustomAlert('Please select Employee', 'info');
      }
    });



  </script>

@endpush