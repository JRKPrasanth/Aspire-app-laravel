@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Imprest</h3>
  @include('layouts.breadcrumb')


  <form action="" id="imprestform">
    <?php $data = \Session::get('data');
  if (isset($data[$pageMethod]['save'])) { ?>
    <input type="hidden" name="edit_id" value="" id="edit_id" />
    <input type="hidden" name="status" value="{{$status}}" />
    <input type="hidden" name="imprest_count" value="" id="imprest_count" />
    <input type="hidden" name="employee_id" id="employee_id" />
    {{ csrf_field() }}

    <div class="card shadow-lg rounded-4 border-0">
      <div class="card-header bg-primary text-white fw-semibold"></div>
      <div class="card-body">

        <div class="row g-3">
          <!-- Imprest Number -->
          <div class="col-md-4">
            <label class="form-label">Imprest Number</label>
            <input type="text" name="imprest_number" value="" class="form-control imprest_number" readonly>
          </div>

          <!-- Employee -->
          <div class="col-md-4" style="pointer-events:none;">
            <label class="form-label"><span class="text-danger">*</span> Employee</label>
            <select name="employee_id" id="employee_id" class="form-select employee_id select2">
              {!! $employee !!}
            </select>
          </div>

          <!-- Imprest Date -->
          <div class="col-md-4">
            <label class="form-label"><span class="text-danger">*</span> Date</label>
            <div class="input-group">
              <input type="text" id="imprest_date" name="imprest_date" class="form-control start_date imprest_date"
                value="<?php  echo date('Y-m-d'); ?>" required>
            </div>
          </div>

          <!-- Amount -->
          <div class="col-md-4">
            <label class="form-label"><span class="text-danger">*</span> Amount</label>
            <input type="text" id="amount" name="amount" class="form-control amount" required>
          </div>

          <!-- Reason -->
          <div class="col-md-4">
            <label class="form-label"><span class="text-danger">*</span> Reason</label>
            <input type="text" id="reason" name="reason" class="form-control reason" required>
          </div>

          <!-- Active -->
          <div class="col-md-4">
            <label class="form-label"><span class="text-danger">*</span> Active</label>
            <select id="active" name="active" class="form-select active select2" required>
              <option value="Yes">Yes</option>
              <option value="No">No</option>
            </select>
          </div>

          <!-- Reporting Manager -->
          <div class="col-md-4 none">
            <label class="form-label"><span class="text-danger">*</span> Reporting Manager</label>
            <div class="input-group">
              <select id="reporting_manager" name="reporting_manager" class="form-select reporting_manager select2"
                required></select>
            </div>
          </div>
        </div>

        <div class="text-center mt-4">
          <button type="button" id="save" class="btn btn-success save_form px-4">
            Save
          </button>
        </div>
      </div>
    </div>
    <?php } ?>
  </form>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <table id="ImpTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Employee Id</th>
            <th>Employee Id</th>
            <th>Imprest Number</th>
            <th>Employee</th>
            <th>Reporting Employee</th>
            <th>Imprest Date</th>
            <th>Reason</th>
            <th>Amount</th>
            <th>Active</th>
            <th>Status</th>
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
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
          </tr>
        </thead>
        <tbody>
          {{-- DataTable will populate via AJAX --}}
        </tbody>
      </table>
    </div>
  </div>





@endsection
@push('scripts')


  <script>

    // table data	

    $(document).ready(function () {
      var table = $('#ImpTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('imprestgrid') }}",
        columns: [
          { data: 'employee_id', name: 'employee_id', visible: false },
          { data: 'report_id', name: 'report_id', visible: false },
          { data: 'imprest_number', name: 'imprest_number' },
          { data: 'employee_number', name: 'employee_number' },
          { data: 'report_number', name: 'report_number' },
          { data: 'imprest_date', name: 'imprest_date' },
          { data: 'reason', name: 'reason' },
          { data: 'amount', name: 'amount' },
          { data: 'active', name: 'active' },
          { data: 'status', name: 'status' },

          {
            data: 'imprest_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
          <button type="button" class="btn btn-sm btn-primary edit-btn"
            data-id="${row.imprest_id}"
            data-num="${row.imprest_number}"
            data-eid="${row.employee_id}"
            data-rid="${row.report_id}"
            data-date="${row.imprest_date}"
            data-reason="${row.reason}"
            data-active="${row.active}"
            data-amount="${row.amount}">
            <i class="bi bi-pencil"></i>
          </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
            <button type="button" class="btn btn-sm btn-danger delete-btn"
              data-id="${row.imprest_id}">
              <i class="bi bi-trash"></i>
            </button>`;
              }
              return buttons;
            }

          }
        ]
      });


      $('#ImpTbl thead').on('keyup change', '.column-search', function () {
        let index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });

    // save 	

    $(document).on('click', '.save_form', function () {

      var form = $('#imprestform');

      form.parsley().validate();
      if (form.parsley().isValid()) {
        var $btn = $(this);
        $btn.prop('disabled', true);
        var data = $("#imprestform").serialize();
        $.post('imprest/save', data, function (data) {
          if (data == 1) {
            showCustomAlert("Saved Successfully", 'success');

          }
          else if (data == 2) {
            showCustomAlert("Updated Successfully", 'success');

          }
          window.location.reload();
        });
      }

    });


    // drop down
    function loadDropdown(selector, url, selectedValue, defaultText = "-- Please Select --") {
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
    var forwarded_id = '{{$forwarded_id}}';
    var logged_id = '{{$logged_id}}';
    var condition = "  employee_id!=" + logged_id;

    // Forwarded ID (Exclude Logged User)
    loadDropdown(
      "#reporting_manager",
      "{{ URL::to('jcomboform') }}?table=hr_employee_t:employee_id:employee_number|first_name&parent=" + encodeURIComponent(condition) + "&order_by=employee_id asc",
      forwarded_id,
      "-- Select Forwarded Employee --"
    );

    // delete 
    $(document).on('click', '.delete-btn', function () {
      deleteId = $(this).data('id');
      $('#globalDeleteModal').modal('show');
    });

    $('#globalConfirmDeleteBtn').on('click', function () {
      if (deleteId) {
        $.ajax({
          url: "{{ url('imprestdelete') }}/" + deleteId,
          type: "GET",
          success: function (response) {
            $('#globalDeleteModal').modal('hide');
            showCustomAlert('Delete Successfully!', 'success');
            $('#ImpTbl').DataTable().ajax.reload();

          },
          error: function (xhr) {
            $('#globalDeleteModal').modal('hide');
            const errorMsg = xhr.responseJSON?.message || 'Delete failed.';
            showCustomAlert(errorMsg, 'error');
          }
        });
      }
    });

    // Edit	

    $(document).on('click', '.edit-btn', function () {

      const id = $(this).data('id');
      const num = $(this).data('num');
      const eid = $(this).data('eid');
      const rid = $(this).data('rid');
      const date = $(this).data('date');
      const active = $(this).data('active');
      const amount = $(this).data('amount');
      const reason = $(this).data('reason');

      // Fill form fields
      $('input[name="edit_id"]').val(id);
      $('input[name="imprest_number"]').val(num);
      $('input[name="imprest_date"]').val(date);
      $('input[name="amount"]').val(amount);
      $('input[name="reason"]').val(reason);
      // For select2 fields, use .val().trigger('change')


      $('select[name="employee_id"]').val(eid).trigger('change');
      $('select[name="reporting_manager"]').val(rid).trigger('change');
      $('select[name="active"]').val(active).trigger('change');


    });



  </script>


@endpush