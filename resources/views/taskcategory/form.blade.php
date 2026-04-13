@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Webops Track Category</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <form id="taskcatsave" method="post" action="" data-parsley-validate>

        <input type="hidden" name="savestatus" id="savestatus" value="" />
        <input type="hidden" name="edit_id" id="edit_id" value="{{ $row->task_category_id }}" />
        {{ csrf_field() }}

        <div class="row g-3">
          <div class="col-md-6">

            <!-- Department Name -->
            <div class="row align-items-center">
              <label class="col-md-5 col-form-label"><span class="text-danger">*</span> Department Name</label>
              <div class="col-md-6">
                <select name="department_id" class="form-select select2 department_id" required tabindex="1">
                  {!! $department_line_id !!}
                </select>
              </div>
            </div>

            <!-- Task Category Name -->
            <div class="row align-items-center mt-3">
              <label class="col-md-5 col-form-label"><span class="text-danger">*</span> Task Category Name</label>
              <div class="col-md-6">
                <input type="text" name="category_name" id="category_name" value="{{ $row->category_name }}"
                  class="form-control category_name" required tabindex="3">
                <span class="btn btn-danger dup_name mt-2 d-none"></span>
              </div>
            </div>

            <!-- Created By -->
            <div class="row align-items-center mt-3 none">
              <label class="col-md-5 col-form-label">Created By</label>
              <div class="col-md-6">
                <select name="created_by" class="form-select select2 created_by" id="created_by">
                  {!! $created_by !!}
                </select>
              </div>
            </div>

          </div>

          <div class="col-md-6">

            <!-- Description -->
            <div class="row align-items-center">
              <label class="col-md-5 col-form-label">Description</label>
              <div class="col-md-7">
                <input type="text" name="description" id="description" value="{{ $row->description }}"
                  class="form-control description" tabindex="2">
              </div>
            </div>

            <!-- Active -->
            <div class="row align-items-center mt-3">
              <label class="col-md-5 col-form-label">Active</label>
              <div class="col-md-7">
                <select name="active" class="form-select select2 active" tabindex="4">
                  <option value="Yes" {{ $row->active == 'Yes' ? 'selected' : '' }}>Yes</option>
                  <option value="No" {{ $row->active == 'No' ? 'selected' : '' }}>No</option>
                </select>
              </div>
            </div>

          </div>
        </div>

        <div class="row mt-4">
          <div class="col text-center">
            <button type="button" id="save" class="btn btn-success saveform px-4" value="SAVE">Save</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <table id="taskcatTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Department Name</th>
            <th>Category Name</th>
            <th style="display:none;">Category Name</th>
            <th>Description</th>
            <th>Active</th>
            <th>Created By</th>
            <th>Actions</th>
          </tr>
          <tr class="table-info">
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" style="display:none;" class="form-control form-control-sm column-search"
                placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
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
      var table = $('#taskcatTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('getTaskcategory') }}",
        columns: [
          { data: 'sub_department_name', name: 'm_department_lines_t.sub_department_name' },
          { data: 'category_name', name: 'category_name' },
          { data: 'department_line_id', name: 'm_department_lines_t.department_line_id', visible: false },
          { data: 'description', name: 'description' },
          { data: 'active', name: '.active' },
          { data: 'first_name', name: 'tb_users.first_name' },
          {
            data: 'task_category_id',
            name: 'actions',
            width: '140px',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
            <button type="button" class="btn btn-sm btn-primary edit-btn"
              data-id="${row.task_category_id}"
              data-code="${row.first_name}"
              data-dept="${row.department_line_id}"
              data-name="${row.category_name}"
              data-type="${row.description}"
              data-active="${row.active}">
              <i class="bi bi-pencil"></i>
            </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
              <button type="button" class="btn btn-sm btn-danger delete-btn"
                data-id="${row.task_category_id}">
                <i class="bi bi-trash"></i>
              </button>`;
              }
              return buttons;
            }

          }
        ]
      });


      $('#taskcatTbl thead').on('keyup change', '.column-search', function () {
        let index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });

    /*kannan purpose:To check Duplicate entry*/
    var dup_chk = true;
    function duplicate_validate() {
      var category_name = $(".category_name").val();
      var department_line_id = $('.department_id').val();

      var edit_id = $("#edit_id").val();

      $.ajax({
        cache: false,
        url: "{{ URL::to('taskcategorycheckname') }}", /*this is your uri*/
        type: 'GET',
        dataType: 'json',
        async: false,
        data: { category_name: category_name, department_line_id: department_line_id, edit_id: edit_id },
        success: function (response) {
          if (response == 1) {
            $('.dup_name')
              .html('Category Name: ' + category_name + ' Already Exists')
              .removeClass('d-none')
              .addClass('d-block');

            $(".category_name").val('');
            dup_chk = false;
          }
          else if (response == 0) {
            var html = "";
            $('.dup_name').hide();
            dup_chk = true;

          }

        },
        error: function (xhr, resp, text) {
          console.log(xhr, resp, text);
        }
      });
    }

    // save function


    $(document).on('click', '.saveform', function () {

      let dup_chk = true;
      var form = $("#taskcatsave");
      form.parsley().validate();

      if (form.parsley().isValid() && dup_chk == true) {
        var $btn = $(this);
        $btn.prop('disabled', true);

        $.ajax({
          url: "{{ URL::to('taskcategorysave') }}",
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
      const type = $(this).data('type');
      const dept = $(this).data('dept');
      const active = $(this).data('active');

      // Fill form fields
      $('input[name="edit_id"]').val(id);
      $('input[name="category_name"]').val(name);
      $('input[name="description"]').val(type);

      // For select2 fields, use .val().trigger('change')

      $('select[name="department_id"]').val(dept).trigger('change');
      $('select[name="active"]').val(active).trigger('change');
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
          url: "{{ url('taskcategorydelete') }}/" + deleteId,
          type: "GET",
          success: function (response) {
            $('#globalDeleteModal').modal('hide');
            showCustomAlert(response.message, 'success');
            $('#taskcatTbl').DataTable().ajax.reload();

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