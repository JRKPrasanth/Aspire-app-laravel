@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Maintenance Frequency</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <form action="" id="Mac_form">
        {{ csrf_field() }}
        <input type="hidden" name="edit_id" id="edit_id" value="">

        <div class="row g-4">
          <!-- Left Column -->
          <div class="col-md-6">
            <div class="mb-3 row">
              <label for="frequency_name" class="col-sm-5 col-form-label">
                <span class="text-danger">*</span> Frequency Name
              </label>
              <div class="col-sm-7">
                <input type="text" id="frequency_name" name="frequency_name" class="form-control" required tabindex="1">
              </div>
              <div class="col-sm-12 mt-2">
                <span class="btn btn-danger dup_name d-none"></span>
              </div>
            </div>
          </div>

          <!-- Right Column -->
          <div class="col-md-6">
            <div class="mb-3 row">
              <label for="description" class="col-sm-5 col-form-label">Description</label>
              <div class="col-sm-7">
                <input name="description" id="description" class="form-control">
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12 text-center mt-3">
            <button type="button" class="btn btn-success saveform px-4" tabindex="5">Save</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <table id="MacTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Frequency Name</th>
            <th>Description</th>
            <th>Actions</th>
          </tr>
          <tr class="table-info">
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
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

    $(document).ready(function () {
      var table = $('#MacTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('frequencygrid') }}",
        columns: [
          { data: 'frequency_name', name: 'frequency_name' },
          { data: 'description', name: 'description' },
          {
            data: 'frequency_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            width: '120px',
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
                    <button type="button" class="btn btn-sm btn-info edit-btn"
                      data-id="${row.frequency_id}"
                      data-code="${row.frequency_name}"
                      data-name="${row.description}">
                      <i class="bi bi-pencil"></i>
                    </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
                        <button type="button" class="btn btn-sm btn-danger delete-btn"
                          data-id="${row.frequency_id}">
                          <i class="bi bi-trash"></i>
                        </button>`;
              }
              return buttons;
            }

          }
        ]
      });


      $('#MacTbl thead').on('keyup change', '.column-search', function () {
        let index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });


    // save function
    let dup_chk = true;

    $(document).on('click', '.saveform', function () {

      var form = $("#Mac_form");
      form.parsley().validate();

      if (form.parsley().isValid() && dup_chk == true) {

        var $btn = $(this);
        $btn.prop('disabled', true);

        $.ajax({
          url: "{{ URL::to('frequencysave/{id}') }}",
          type: "POST",
          data: form.serialize(),
          success: function (data) {
            // Show success message
            showCustomAlert('Saved successfully!', 'success');
            // Clear the form (optional)
            form[0].reset();
            $('.select2').val('').trigger('change');
            // Reload DataTable
            $('#MacTbl').DataTable().ajax.reload();
          },
          error: function (xhr) {
            showCustomAlert('Save failed. Try again.', 'error');
          }
        });
        window.location.reload();
      }
    });
    // edit function
    $(document).on('click', '.edit-btn', function () {
      const id = $(this).data('id');
      const code = $(this).data('code');
      const name = $(this).data('name');

      // Fill form fields
      $('input[name="frequency_id"]').val(id);
      $('input[name="frequency_name"]').val(code);
      $('input[name="description"]').val(name);
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
          url: "{{ url('frequencydelete') }}/" + deleteId,
          type: "GET",
          success: function (response) {
            $('#globalDeleteModal').modal('hide');
            showCustomAlert(response.message, 'success');
            $('#MacTbl').DataTable().ajax.reload();

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