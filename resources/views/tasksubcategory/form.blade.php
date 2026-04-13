@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Webops Track Subcategory</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <form id="tasksubcat" method="post" action="" data-parsley-validate>
        <input type="hidden" name="savestatus" id="savestatus" value="" />
        <input type="hidden" name="edit_id" id="edit_id" value="" />
        {{ csrf_field() }}

        <div class="row mb-3 g-3">
          <!-- Left Column -->
          <div class="col-md-6">

            <!-- Department Name -->
            <div class="row mb-3 align-items-center">
              <label class="col-md-5 col-form-label">
                <span class="text-danger">*</span> Department Name
              </label>
              <div class="col-md-6">
                <select name="department_id" class="form-select select2 department_id" required tabindex="1">
                  {!! $department_line_id !!}
                </select>
              </div>
            </div>

            <!-- Task Category Name -->
            <div class="row mb-3 align-items-center">
              <label class="col-md-5 col-form-label">
                <span class="text-danger">*</span> Task Category Name
              </label>
              <div class="col-md-6">
                <select name="task_category_id" class="form-select select2 task_category_id" required tabindex="2">
                  {!! $task_category_id !!}
                </select>
              </div>
            </div>

            <!-- Task SubCategory Name -->
            <div class="row mb-3 align-items-center">
              <label class="col-md-5 col-form-label">
                <span class="text-danger">*</span> Task SubCategory Name
              </label>
              <div class="col-md-6">
                <input type="text" name="subcategory_name" id="subcategory_name" class="form-control subcategory_name"
                  required tabindex="3" />
                <span class="btn btn-danger dup_name mt-2 d-none"></span>
              </div>
            </div>

          </div>

          <!-- Right Column -->
          <div class="col-md-6">

            <!-- Description -->
            <div class="row mb-3 align-items-center">
              <label class="col-md-5 col-form-label">Description</label>
              <div class="col-md-7">
                <input type="text" name="description" id="description" class="form-control description" tabindex="4" />
              </div>
            </div>

            <!-- Active -->
            <div class="row mb-3 align-items-center">
              <label class="col-md-5 col-form-label">Active</label>
              <div class="col-md-7">
                <select name="active" class="form-select select2 active" tabindex="5">
                  <option value="Yes" selected>Yes</option>
                  <option value="No">No</option>
                </select>
              </div>
            </div>

            <!-- Created By -->
            <div class="row mb-3 align-items-center">
              <label class="col-md-5 col-form-label">Created By</label>
              <div class="col-md-7">
                <select name="created_by" id="created_by" class="form-select select2 created_by" tabindex="6">
                  {!! $created_by !!}
                </select>
              </div>
            </div>

          </div>
        </div>

        <!-- Submit Button -->
        <div class="row mb-3 mt-4">
          <div class="col text-center">
            <button type="button" class="btn btn-success px-4 saveform" value="save">Save</button>
          </div>
        </div>

      </form>
    </div>
  </div>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <table id="subtaskcatTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Product Group</th>
            <th>Product Category</th>
            <th style="display:none;"></th>
            <th style="display:none;"></th>
            <th>Subcategory Name</th>
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
            <th><input type="text" style="display:none;" class="form-control form-control-sm column-search"
                placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
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
      var table = $('#subtaskcatTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('getTasksubcategoryData') }}",
        columns: [
          { data: 'sub_department_name', name: 'm_department_lines_t.sub_department_name' },
          { data: 'category_name', name: 'category_name' },
          { data: 'department_line_id', name: 'm_department_lines_t.department_line_id', visible: false },
          { data: 'task_category_id', name: 'a_task_category_t.task_category_id', visible: false },
          { data: 'subcategory_name', name: 'subcategory_name' },
          { data: 'description', name: 'description' },
          { data: 'active', name: '.active' },
          { data: 'first_name', name: 'tb_users.first_name' },
          {
            data: 'task_subcategory_id',
            name: 'actions',
            width: '140px',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
            <button type="button" class="btn btn-sm btn-primary edit-btn"
              data-id="${row.task_subcategory_id}"
              data-code="${row.first_name}"
              data-dept="${row.department_line_id}"
              data-name="${row.category_name}"
                      data-sname="${row.subcategory_name}"
              data-cname="${row.task_category_id}"
              data-type="${row.description}"
              data-active="${row.active}">
              <i class="bi bi-pencil"></i>
            </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
              <button type="button" class="btn btn-sm btn-danger delete-btn"
                data-id="${row.task_subcategory_id}">
                <i class="bi bi-trash"></i>
              </button>`;
              }
              return buttons;
            }

          }
        ]
      });


      $('#subtaskcatTbl thead').on('keyup change', '.column-search', function () {
        let index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });


    // save function


    $(document).on('click', '.saveform', function () {

      let dup_chk = true;
      var form = $("#tasksubcat");
      form.parsley().validate();

      if (form.parsley().isValid() && dup_chk == true) {
        var $btn = $(this);
        $btn.prop('disabled', true);
        $.ajax({
          url: "{{ URL::to('tasksubcategorysave') }}",
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
      const sname = $(this).data('sname');
      const cname = $(this).data('cname');
      const type = $(this).data('type');
      const dept = $(this).data('dept');
      const active = $(this).data('active');

      // Fill form fields
      $('input[name="edit_id"]').val(id);
      $('input[name="category_name"]').val(name);
      $('input[name="subcategory_name"]').val(sname);
      $('input[name="description"]').val(type);

      // For select2 fields, use .val().trigger('change')

      $('select[name="department_id"]').val(dept).trigger('change');
      $('select[name="task_category_id"]').val(cname).trigger('change');
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
          url: "{{ url('tasksubcategorydelete') }}/" + deleteId,
          type: "GET",
          success: function (response) {
            $('#globalDeleteModal').modal('hide');
            showCustomAlert(response.message, 'success');
            $('#subtaskcatTbl').DataTable().ajax.reload();

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