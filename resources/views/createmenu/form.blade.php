@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Menu Creation</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white fw-semibold">
      Create Menu
    </div>

    <div class="card-body">
      <form id="menuForm">
        {{ csrf_field() }}
        <input type="hidden" id="edit_id" name="edit_id">

        <div class="row g-4">

          <div class="col-md-4">
            <label class="form-label">
              <span class="text-danger">*</span> Parent Menu
            </label>

            <div class="d-flex gap-2 align-items-start">

              <!-- Select Dropdown -->
              <div class="flex-grow-1" id="parentMenuSelectBox">
                <select class="form-select select2 parent_menu" id="parent_menu" name="parent_menu">
                  {!! $parent_menu !!}
                </select>
              </div>

              <!-- Input Box (Hidden Initially) -->
              <div class="flex-grow-1 d-none" id="parentMenuInputBox">
                <input type="text" class="form-control" id="parent_menu_input" name="parent_menu1"
                  placeholder="Enter Parent Menu">
              </div>

              <!-- Add Icon -->
              <button type="button" class="btn btn-secondary btn-sm" id="addParentMenu">
                <i class="bi bi-plus-lg"></i>
              </button>
            </div>
          </div>

          <div class="col-md-4">
            <label class="form-label">Sub Menu</label>

            <div class="d-flex gap-2 align-items-start">

              <!-- Select Dropdown -->
              <div class="flex-grow-1" id="subMenuSelectBox">
                <select class="form-select select2 sub_menu" id="sub_menu" name="sub_menu">
                  <option value="">-- Select Sub Menu --</option>

                </select>
              </div>

              <!-- Input Box (Hidden Initially) -->
              <div class="flex-grow-1 d-none" id="subMenuInputBox">
                <input type="text" class="form-control" id="sub_menu_input" name="sub_menu1" placeholder="Enter Sub Menu">
              </div>

              <!-- Add Icon -->
              <button type="button" class="btn btn-secondary btn-sm" id="addSubMenu">
                <i class="bi bi-plus-lg"></i>
              </button>
            </div>
          </div>

          <div class="col-md-4">
            <label class="form-label">
              <span class="text-danger">*</span> Menu
            </label>
            <input type="text" class="form-control" id="menu" name="menu">
          </div>

          <div class="col-md-4 mt-4">
            <label class="form-label">URL</label>
            <input type="text" class="form-control" id="url" name="url" placeholder="Controller/Url">
          </div>

          <div class="col-md-4 mt-4 none">
            <label class="form-label">Created By</label>
            <select class="form-select select2" id="created_by" name="created_by">
              {!! $created_by !!}
            </select>
          </div>

          <div class="col-md-4 mt-4">
            <label class="form-label">Active</label>
            <select class="form-select select2" id="active" name="active">
              <option value="Yes">Yes</option>
              <option value="No">No</option>
            </select>
          </div>

        </div>

        <!-- Submit -->
        <div class="text-center mt-4">
          <button type="button" class="btn btn-success px-4 saveform">
            Save
          </button>
        </div>

      </form>
    </div>
  </div>



  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <table id="MenuTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Parent Menu</th>
            <th>Sub Menu</th>

            <th>Parent Menu</th>
            <th>Sub Menu</th>
            <th>Menu Name</th>
            <th>Url</th>
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


      var table = $('#MenuTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('getMenuData') }}",
        columns: [
          { data: 'sub_id', name: 'sub_id',visible:false },
          { data: 'parent_id', name: 'parent_id',visible:false },
          { data: 'parent_menu', name: 'parent_menu' },
          { data: 'sub_menu', name: 'sub_menu' },
          { data: 'menus_name', name: 'menus_name' },
          { data: 'url', name: 'url' },

          {
            data: 'menus_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
              <button type="button" class="btn btn-sm btn-primary edit-btn"
                data-id="${row.menus_id}"
                data-name="${row.menus_name}"
                data-url="${row.url}"
                data-parent="${row.sub_id}"
                 data-user="${row.employee_id}"
                data-sub="${row.parent_id}"
                data-active="${row.active}">
                <i class="bi bi-pencil"></i>
              </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
                <button type="button" class="btn btn-sm btn-danger delete-btn"
                  data-id="${row.menus_id}">
                  <i class="bi bi-trash"></i>
                </button>`;
              }
              return buttons;
            }

          }
        ]
      });


      $('#MenuTbl thead').on('keyup change', '.column-search', function () {
        let index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });

    // new parent and sub menu 
    $("#addParentMenu").on("click", function () {
      $("#parentMenuSelectBox").toggleClass("d-none");
      $("#parentMenuInputBox").toggleClass("d-none");
    });

    $("#addSubMenu").on("click", function () {
      $("#subMenuSelectBox").toggleClass("d-none");
      $("#subMenuInputBox").toggleClass("d-none");
    });


    $(document).on('change', '.parent_menu', function () {
      var id = $(this).val();
      if (id != '') {
        var url = "{{ URL::to('jcomboformlogin') }}?table=tb_menus:menus_id:menus_name&parent= parent_id=" + id + "&order_by=menus_id";

        $.ajax({
          url: url,
          type: 'GET',
          success: function (data) {
            // Parse JSON string if needed
            if (typeof data === "string") {
              try {
                data = JSON.parse(data);
              } catch (e) {
                console.error("Invalid JSON response:", data);
                return;
              }
            }

            $('.sub_menu').html('<option value="">-- Select Sub Menu --</option>');

            $.each(data, function (i, item) {
              let selected = item.val == "{{ $row->sub_menu ?? '' }}" ? 'selected' : '';
              $('.sub_menu').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
            });

            $('.sub_menu').trigger('change.select2');
          }


        });
      }
    });


    // save function


    $(document).on('click', '.saveform', function () {
      let dup_chk = true;
      var form = $("#menuForm");
      form.parsley().validate();
      if (form.parsley().isValid() && dup_chk == true) {
        var $btn = $(this);
        $btn.prop('disabled', true);
        $.ajax({
          url: "{{ URL::to('savemenu') }}",
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


    // delete function
    let deleteId = null;

    $(document).on('click', '.delete-btn', function () {
      deleteId = $(this).data('id');
      $('#globalDeleteModal').modal('show');
    });

    $('#globalConfirmDeleteBtn').on('click', function () {
      if (!deleteId) {
        showCustomAlert('Invalid delete request.', 'error');
        return;
      }

      $.ajax({
        url: "{{ url('menudelete') }}/" + deleteId,
        type: "get",   // safer than GET for delete operation
        data: {
          _token: "{{ csrf_token() }}" // required for Laravel DELETE
        },

        success: function (response) {

          $('#globalDeleteModal').modal('hide');

          // Success message
          if (response.status === 'success') {
            showCustomAlert(response.message, 'success');
          }

          // Error message from backend (menu used somewhere else)
          else if (response.status === 'error') {
            showCustomAlert(response.message, 'error');
          }

          // Reload table in both cases
          $('#MenuTbl').DataTable().ajax.reload(null, false);
        },

        error: function (xhr) {
          $('#globalDeleteModal').modal('hide');

          const errorMsg =
            xhr.responseJSON?.message ||
            'Something went wrong while deleting.';

          showCustomAlert(errorMsg, 'error');

          $('#MenuTbl').DataTable().ajax.reload(null, false);
        }
      });
    });



    // edit function

    $(document).on('click', '.edit-btn', function () {
      var form = $("#menuForm");
      form.parsley().destroy(); // reset validation

      var id = $(this).data('id');
      var name = $(this).data('name');
      var url = $(this).data('url');
      var parent = $(this).data('parent');
      var sub = $(this).data('sub');
      var act = $(this).data('active');
      var created_by = $(this).data('user');

      $('#edit_id').val(id);
      $('#menu').val(name);
      $('#url').val(url);
      $('#parent_menu').val(parent).trigger('change');
      $('#sub_menu').val(sub).trigger('change');
      $('#MenuTbl').DataTable().ajax.reload();


    });


  </script>

@endpush