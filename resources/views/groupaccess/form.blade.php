@extends('layouts.header')
@section('content')
  <h2 class="text-danger">Group</h2>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white"></div>
    <div class="card-body card-block mt-2">
      <form action="" id="group_form">
        <input type="hidden" name="edit_id" value="" id="edit_id" />
        {{ csrf_field()}}
        <div class="row">
          <div class="col-md-12">
            <div class="form-group row">
              <label for="inputIsValid" class="form-control-label col-md-2"><span class="req">*</span>Group Name</label>
              <div class="col-md-4">
                <input type="text" id="group_name" name="group_name" class="form-control group_name" value="" required
                  style="width:100%;">
                <span class="btn btn-danger dup_name" style="display:none;"></span>
              </div>

              <label for="inputIsValid" class="form-control-label col-md-2">Description</label>
              <div class="col-md-4">
                <input type="text" id="description" name="description" class="form-control  description" value=""
                  style="width: 100%;">
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 text-center mt-4">
          <button type="button" class="btn btn-success saveform px-4" value="SAVE">Save</button>
        </div>
      </form>
    </div>
  </div>

  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <table id="GroupTable" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Group Name</th>
            <th>Description</th>
            <th>Actions</th>
          </tr>
          <tr class="table-info">
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Name" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search Description" />
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
    // data table funcrion	
    $(document).ready(function () {
      var table = $('#GroupTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('GroupsData') }}",
        columns: [
          { data: 'group_name', name: 'group_name' },
          { data: 'description', name: 'description' },
          {
            data: 'group_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
        <button class="btn btn-sm btn-primary me-1 edit-btn" 
          data-id="${row.group_id}" 
          data-code="${row.group_name}" 
          data-name="${row.description}"> 
          <i class="bi bi-pencil"></i> 
        </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
        <button class="btn btn-sm btn-danger delete-btn" data-id="${row.group_id}">
        <i class="bi bi-trash"></i> 
        </button>`;
              }
              return buttons;
            }

          }
        ]
      });

      // Individual column search
      $('#GroupTable thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });

    // save function
    let dup_chk = true;

    $(document).on('click', '.saveform', function () {

      var form = $("#group_form");
      form.parsley().validate();

      if (form.parsley().isValid() && dup_chk == true) {
        var $btn = $(this);
        $btn.prop('disabled', true);
        $.ajax({
          url: "{{ URL::to('groupaccess/save') }}",
          type: "POST",
          data: form.serialize(),
          success: function (data) {
            // Show success message
            showCustomAlert('Saved successfully!', 'success');
            // Clear the form (optional)
            form[0].reset();
            $('.select2').val('').trigger('change');
            // Reload DataTable
            window.location.reload();
          },
          error: function (xhr) {
            showCustomAlert('Save failed. Try again.', 'error');
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
      $('input[name="edit_id"]').val(id);
      $('input[name="group_name"]').val(code);
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
          url: "{{ url('groupaccess/delete') }}/" + deleteId,
          type: "GET",
          success: function (response) {
            $('#globalDeleteModal').modal('hide');
            showCustomAlert(response.message,'success');
            $('#GroupTable').DataTable().ajax.reload();

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