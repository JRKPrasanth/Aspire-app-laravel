@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Breakdown Severity</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">

      <form action="" id="Ser_form">
        @csrf
        <input type="hidden" name="edit_id" id="edit_id">
        <input type="hidden" class="form-control" id="breakdownseverity_id" name="breakdownseverity_id" readonly>

        <div class="row">
          <!-- Left Column -->
          <div class="col-md-6">
            <div class="mb-3 row">
              <label for="severity_name" class="col-md-5 col-form-label">
                <span class="text-danger">*</span> Severity Name
              </label>
              <div class="col-md-7">
                <input type="text" id="severity_name" name="severity_name" class="form-control" tabindex="1" required>
                <span class="btn btn-danger dup_name mt-2 d-none"></span>
              </div>
            </div>
          </div>

          <!-- Right Column -->
          <div class="col-md-6">
            <div class="mb-3 row">
              <label for="description" class="col-md-5 col-form-label">Description</label>
              <div class="col-md-7">
                <input type="text" id="description" name="description" class="form-control" tabindex="2">
              </div>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="row mt-3">
          <div class="col-12 text-center">
            <button type="button" class="btn btn-success saveform px-4" tabindex="5">Save</button>
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
        <table id="SerTbl" class="table table-bordered table-striped w-100">
          <thead>
            <tr class="table-warning">
              <th>Severity Name</th>
              <th>Description</th>
              <th>Actions</th>
            </tr>
            <tr class="table-info">
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th></th>
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

    $(document).ready(function () {

      var table = $('#SerTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('griddata') }}",
        columns: [
          { data: 'severity_name', name: 'severity_name' },
          { data: 'description', name: 'description' },

          {
            data: 'breakdownseverity_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',
            width: '140px',
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
            <button  class="btn btn-sm btn-info me-1 edit-btn" data-id="${data}"
            data-code="${row.description}"
            data-name="${row.severity_name}"><i class="bi bi-pencil"></i></button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `<button class="btn btn-sm btn-danger delete-btn" data-id="${data}"><i class="bi bi-trash"></i>  </button>`;
              }

              return buttons;
            }

          }
        ]
      });

      // Individual column search
      $('#SerTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });


    // Save Form
    $(document).on('click', '.saveform', function () {
      const form = $("#Ser_form"); // change ID if needed
      form.parsley().validate();

      if (form.parsley().isValid()) {
        var $btn = $(this);
        $btn.prop('disabled', true);
        const formData = form.serialize();
        $.ajax({
          url: "{{ url('severitysave') }}",
          type: "POST",
          data: formData,
          success: function (response) {
            if (response.status === "success") {
              showCustomAlert("Saved successfully!", "success");
              form[0].reset();
              $('#SerTbl').DataTable().ajax.reload();
            } else {
              showCustomAlert("Save failed. " + (response.message || ""), "error");
            }
          },
          error: function () {
            showCustomAlert("Unexpected error occurred.", "error");
          }
        });
      } else {
        showCustomAlert("Please fill all required fields.", "warning");
      }
      window.location.reload();
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
          url: "{{ url('severitydelete') }}/" + deleteId,
          type: "GET",
          success: function (response) {
            $('#globalDeleteModal').modal('hide');
            showCustomAlert('Deleted successfully!', 'success');
            $('#SerTbl').DataTable().ajax.reload();

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
      const code = $(this).data('code');
      const name = $(this).data('name');


      // Fill form fields
      $('input[name="breakdownseverity_id"]').val(id);
      $('input[name="description"]').val(code);
      $('input[name="severity_name"]').val(name);

    });
  </script>
@endpush