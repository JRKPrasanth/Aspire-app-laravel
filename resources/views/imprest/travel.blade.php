@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Travel Amount</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <form action="" id="travelform">
        @csrf
        <input type="hidden" name="edit_id" id="edit_id" />

        <div class="row g-3">
          <div class="col-md-4 d-none">
            <label for="employee_id" class="form-label"><span class="text-danger">*</span> Employee</label>
            <select name="employee_id" id="employee_id" class="form-select">
              {!! $employee !!}
            </select>
          </div>

          <div class="col-md-4">
            <label for="group_id" class="form-label"><span class="text-danger">*</span> Grade</label>
            <select name="group_id" id="group_id" class="form-select select2">
              {!! $group_id !!}
            </select>
          </div>

          <div class="col-md-4">
            <label for="amount" class="form-label"><span class="text-danger">*</span> Amount</label>
            <input type="text" name="amount" id="amount" class="form-control" required>
          </div>

          <div class="col-md-4">
            <label for="description" class="form-label"><span class="text-danger">*</span> Reason</label>
            <input type="text" name="description" id="description" class="form-control" required>
          </div>

          <div class="col-md-4">
            <label for="travel_date" class="form-label"><span class="text-danger">*</span> Date</label>
            <input type="text" name="travel_date" id="travel_date" value="{{ $travel_date }}"
              class="form-control start_date" required>
          </div>

          <div class="col-md-4">
            <label for="active" class="form-label"><span class="text-danger">*</span> Active</label>
            <select name="active" id="active" class="form-select select2" required>
              <option value="Yes">Yes</option>
              <option value="No">No</option>
            </select>
          </div>
        </div>

        <div class="mt-4 text-center">
          <button type="button" id="save" class="btn btn-success px-4 me-2 save_form">
            Save
          </button>
        </div>
      </form>

    </div>
  </div>

  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <table id="traTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th style="display:none;"><input type="text" class="column-search" placeholder="Search"><span
                style="display:none;">Employee Id</span></th>
            <th>Grade Name</th>
            <th>Travel Date</th>
            <th>Reason</th>
            <th>Amount</th>
            <th>Active</th>
            <th>Actions</th>

          </tr>

          <tr class="table-info">

            <th style="display:none;"><input type="text" class="column-search" placeholder="Search"><span
                style="display:none;">Employee Id</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Grade
                Name</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Travel
                Date</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Reason</span>
            </th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Amount</span>
            </th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Active</span>
            </th>
            <th></th>


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

    // table data	

    $(document).ready(function () {

      var table = $('#traTbl').DataTable({
        processing: true,
        serverSide: true,
        order: [[2, 'desc']],
        ajax: "{{ route('travelamountgriddata') }}",
        columns: [

          { data: "group_id", visible: false },
          { data: "position" },
          { data: "travel_date" },
          { data: "description" },
          { data: "amount" },
          { data: "active" },

          {
            data: 'travel_id',
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
              data-id="${row.travel_id}"
              data-gid="${row.group_id}"
               data-date="${row.travel_date}"
              data-amount="${row.amount}"
              data-reason="${row.description}"
              data-position="${row.position}"
              data-active="${row.active}">
              <i class="bi bi-pencil"></i>
            </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
              <button type="button" class="btn btn-sm btn-danger delete-btn"
                data-id="${row.travel_id}">
                <i class="bi bi-trash"></i>
              </button>`;
              }
              return buttons;
            }
          }
        ]
      });

      // Individual column search
      $('#traTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });


    // save function


    $(document).on('click', '.save_form', function () {

      var form = $('#travelform');

      form.parsley().validate();
      if (form.parsley().isValid()) {
        var $btn = $(this);
        $btn.prop('disabled', true);
        var data = $("#travelform").serialize();
        $.post('travelamount/save', data, function (data) {
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


    // delete function	

    let deleteId = null;

    $(document).on('click', '.delete-btn', function () {
      deleteId = $(this).data('id');
      $('#globalDeleteModal').modal('show');
    });

    $('#globalConfirmDeleteBtn').on('click', function () {
      if (deleteId) {
        $.ajax({
          url: "{{ url('travelamountdelete') }}/" + deleteId,
          type: "GET",
          success: function (response) {
            $('#globalDeleteModal').modal('hide');
            showCustomAlert('Deleted Successfully', 'success');
            $('#traTbl').DataTable().ajax.reload();

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
      const gid = $(this).data('gid');
      const date = $(this).data('date');
      const amount = $(this).data('amount');
      const position = $(this).data('position');
      const reason = $(this).data('reason');
      const active = $(this).data('active');

      // Fill form fields
      $('input[name="edit_id"]').val(id);
      $('input[name="amount"]').val(amount);
      $('input[name="travel_date"]').val(date);
      $('input[name="description"]').val(reason);

      // For select2 fields, use .val().trigger('change')

      $('select[name="group_id"]').val(gid).trigger('change');
      $('select[name="active"]').val(active).trigger('change');
    });



  </script>

@endpush