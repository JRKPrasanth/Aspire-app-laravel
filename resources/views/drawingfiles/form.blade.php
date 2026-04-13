@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Drawing Files</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <form name="drawing" action="" id="save" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="edit_id" id="edit_id" />

        <div class="row">
          <!-- Left Column -->
          <div class="col-md-6">
            <!-- Department Name -->
            <div class="mb-3 row">
              <label for="department" class="col-md-4 col-form-label">
                <span class="text-danger">*</span> Department Name
              </label>
              <div class="col-md-7">
                <select name="department" id="department" class="form-select select2" required>
                  {!! $department !!}
                </select>
              </div>
            </div>

            <!-- Document Name -->
            <div class="mb-3 row">
              <label for="document" class="col-md-4 col-form-label">
                <span class="text-danger">*</span> Document Name
              </label>
              <div class="col-md-7">
                <input type="text" name="document" id="document" class="form-control" required>
              </div>
            </div>
          </div>

          <!-- Right Column -->
          <div class="col-md-6">
            <!-- File Upload -->
            <div class="mb-3 row">
              <label for="file" class="col-md-4 col-form-label">
                <span class="text-danger">*</span> File Upload
              </label>
              <div class="col-md-7">
                <input type="file" name="file" id="file" class="form-control" required>
              </div>
            </div>
          </div>
        </div>
      </form>

      <!-- Submit Button -->
      <div class="row">
        <div class="col-12 text-center mt-3">
          <button type="button" class="btn btn-success saveform px-4">
            Save
          </button>
        </div>
      </div>
    </div>
  </div>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="DrawTbl" class="table table-bordered table-striped w-100">
          <thead>
            <tr class="table-warning">
              <th>Department Name</th>
              <th>Document Name</th>
              <th>Upload Date</th>
              <th>Actions</th>
            </tr>
            <tr class="table-info">
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
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

      var table = $('#DrawTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('filegrid') }}",
        columns: [
          { data: 'sub_department_name', name: 'sub_department_name' },
          { data: 'document', name: 'document' },
          { data: 'created_at', name: 'created_at' },

          {
            data: 'drawing_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',
            width: '140px',
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
              <button  class="btn btn-sm btn-info me-1 edit-btn" data-id="${data}"><i class="bi bi-pencil"></i></button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `<button class="btn btn-sm btn-danger delete-btn" data-id="${data}"><i class="bi bi-trash"></i></button>`;
              }

              return buttons;
            }

          }
        ]
      });

      // Individual column search
      $('#DrawTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });

    // save function	
    $(document).on('click', '.saveform', function () {
      var url = "{{url('drawingsave')}}";
      var data = $('#save').serialize();
      // alert(data); 
      var form = $('#save');
      //alert(form);
      var form_data = new FormData(document.getElementById('save'));
      //alert(form);
      form.parsley().validate();
      var form = $('#save');

      form.parsley().validate();

      if (form.parsley().isValid()) {
        var $btn = $(this);
        $btn.prop('disabled', true);
        //	alert("url");


        $.ajax({
          url: "{{ url('drawingsave')}}",
          type: "POST",
          data: form_data,
          enctype: 'multipart/form-data',
          processData: false,
          contentType: false,
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
            showCustomAlert('Drawing Saved Successfully', 'success');
            $('#DrawTbl').DataTable().ajax.reload();
            $("#grid1")[0].triggerToolbar();

            $('#save')[0].reset();
            location.reload();
            $("#edit_id").val('');

            $('#save').each(function () {

              this.reset();

            });
          }
          else {
            showCustomAlert('Drawing Updated Successfully', 'success');
            $('#DrawTbl').DataTable().ajax.reload();
            $("#grid1")[0].triggerToolbar();
            $('#save')[0].reset();
            $('.clear').trigger('click');
          }

        }).fail(function (data, status) {

          $(".alert-success").hide();
          $(".alert-danger").fadeIn(800);

        });
      window.location.reload();
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
          url: "{{ url('drawingdelete') }}/" + deleteId,
          type: "GET",
          success: function (response) {
            $('#globalDeleteModal').modal('hide');
            showCustomAlert(response.message, 'success');
            $('#DrawTbl').DataTable().ajax.reload();

          },
          error: function (xhr) {
            $('#globalDeleteModal').modal('hide');
            const errorMsg = xhr.responseJSON?.message || 'Delete failed.';
            showCustomAlert(errorMsg, 'error');
          }
        });
      }
    });



    function downloadLink(cellvalue, options, rowObject) {
      var MTJobRoleId = rowObject.MTJobRoleId;

      return "<a href='{{asset('upload/drawing/5ce9303ce0b0c.pdf')}}' download='{{asset('upload/drawing/5ce9303ce0b0c.pdf')}}'>View Certificate</a>";

    }











  </script>
@endpush