@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Check List</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <form action="" id="checksave" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="edit_id" id="edit_id" value="">

        <div class="row g-3">
          <!-- Checklist Name -->
          <div class="col-md-4">
            <div class="mb-3 row">
              <label for="checklist_name" class="col-md-5 col-form-label">
                <span class="text-danger">*</span> Checklist Name
              </label>
              <div class="col-md-7">
                <input type="text" id="checklist_name" name="checklist_name" class="form-control" required tabindex="1">
                <span class="btn btn-danger dup_name d-none mt-2"></span>
              </div>
            </div>
          </div>

          <!-- Terms and Conditions -->
          <div class="col-md-4">
            <div class="mb-3 row">
              <label for="terms" class="col-md-5 col-form-label">Terms and Condition</label>
              <div class="col-md-7">
                <input name="terms" id="terms" class="form-control">
              </div>
            </div>
          </div>

          <!-- File Upload -->
          <div class="col-md-4">
            <div class="mb-3 row">
              <label for="file" class="col-md-3 col-form-label">File Upload</label>
              <div class="col-md-6">
                <input type="file" name="file" id="file" class="form-control" accept="image/x-png,image/gif,image/jpeg">
              </div>
            </div>
          </div>
        </div>
      </form>

      <!-- Submit Button -->
      <div class="row mt-3 text-center">
        <div class="col-12">
          <button type="button" class="btn btn-success saveform px-4" tabindex="5">Save</button>
        </div>
      </div>
    </div>
  </div>

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="checkTbl" class="table table-bordered table-striped w-100">
          <thead>
            <tr class="table-warning">
              <th>Checklist Name</th>
              <th>Terms And Conditions</th>
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

      var table = $('#checkTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('checklistgrid') }}",
        columns: [
          { data: 'checklist_name', name: 'checklist_name' },
          { data: 'terms', name: 'terms' },

          {
            data: 'checklist_id',
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
            data-code="${row.terms}"
            data-name="${row.checklist_name}"
            ><i class="bi bi-pencil"></i></button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `<button class="btn btn-sm btn-danger delete-btn" data-id="${data}"


          ><i class="bi bi-trash"></i>  </button>`;
              }

              return buttons;
            }

          }
        ]
      });

      // Individual column search
      $('#checkTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });


    //save function
    $(document).on('click', '.saveform', function () {
      var url = "{{url('checklistsave')}}";
      var data = $('#checksave').serialize();
      var form = $('#checksave');
      var form_data = new FormData(document.getElementById('checksave'));
      form.parsley().validate();
      var form = $('#checksave');
      form.parsley().validate();

      if (form.parsley().isValid()) {
        var $btn = $(this);
        $btn.prop('disabled', true);
        $.ajax({
          url: "{{ url('checklistsave')}}",
          type: "POST",
          data: form_data,
          enctype: 'multipart/form-data',
          processData: false,  // tell jQuery not to process the data
          contentType: false,   // tell jQuery not to set contentType
          async: true,
          xhr: function () {
            var xhr = $.ajaxSettings.xhr();
            if (xhr.upload) {
              xhr.upload.addEventListener('progress', function (event) {
              }, true);
            }
            return xhr;
          }
        }).done(function (data, status) {

          if (data == 1) {
            showCustomAlert('Saved successfully!', 'success');
              window.location.reload();
            $('#checkTbl').DataTable().ajax.reload();
            $("#checklistgrid1")[0].triggerToolbar();
            $('#save')[0].reset();
          }
          if (data == 2) {
            showCustomAlert('Updated successfully!', 'success');
              window.location.reload();
            $("#checklistgrid1")[0].triggerToolbar();
            $('#save')[0].reset();
            $("#edit_id").val('');
          
          }else {
            $(".alert-success").hide();
            $(".alert-danger").fadeIn(800);

          }
        }).fail(function (data, status) {


          $(".alert-success").hide();
          $(".alert-danger").fadeIn(800);

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
          url: "{{ url('checkdelete') }}/" + deleteId,
          type: "GET",
          success: function (response) {
            $('#globalDeleteModal').modal('hide');
            showCustomAlert('Deleted successfully!', 'success');
            $('#checkTbl').DataTable().ajax.reload();

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
      $('input[name="edit_id"]').val(id);
      $('input[name="terms"]').val(code);
      $('input[name="checklist_name"]').val(name);

    });

  </script>
@endpush