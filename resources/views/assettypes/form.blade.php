@extends('layouts.header')
@section('content')
<h3 class="text-danger"> Asset Types</h3>
@include('layouts.breadcrumb')

<div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
    </div>

    <div class="card-body">
        <form id="asset_form" data-parsley-validate>
            {{ csrf_field() }}
            <input type="hidden" name="savestatus" id="savestatus" value="">
            <input type="hidden" name="edit_id" id="edit_id" class="asset_type_id" value="{{ $row->asset_type_id }}">

            <div class="row g-4">
                <!-- Asset Type Name -->
                <div class="col-md-4">
                    <label for="asset_type_name" class="form-label">
                        <span class="text-danger">*</span> Asset Type Name
                    </label>
                    <input type="text" name="asset_type_name" id="asset_type_name" class="form-control asset_type_name"
                        value="{{ $row->asset_type_name }}" required tabindex="1">
                    <span class="text-danger small dup_name d-none"></span>
                </div>

                <!-- Created By -->
                <div class="col-md-4 none">
                    <label for="created_by" class="form-label">Created By</label>
                    <select name="created_by" id="created_by" class="form-select select2 created_by" tabindex="2">
                        {!! $created_by !!}
                    </select>
                </div>

                <!-- Description -->
                <div class="col-md-4">
                    <label for="description" class="form-label">Description</label>
                    <input type="text" name="description" id="description" class="form-control description"
                        value="{{ $row->description }}" tabindex="3">
                </div>

                <!-- Active -->
                <div class="col-md-4">
                    <label for="active" class="form-label">Active</label>
                    <select name="active" id="active" class="form-select select2 active" tabindex="4">
                        <option value="Yes" {{ $row->active == 'Yes' ? 'selected' : '' }}>Yes</option>
                        <option value="No" {{ $row->active == 'No' ? 'selected' : '' }}>No</option>
                    </select>
                </div>
            </div>

            <!-- Buttons -->
            <div class="text-center mt-4">
                <button type="button" class="btn btn-success px-4 save_form">Submit</button>
            </div>
        </form>
    </div>
</div>


<div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
        <table id="AccTbl" class="table table-bordered table-striped w-100">
            <thead>
                <tr class="table-warning">
                    <th>Asset Type Name</th>
                    <th>Description</th>
                    <th>Active</th>
                    <th>Created By</th>
                    <th>Actions</th>
                </tr>
                <tr class="table-danger">
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                    </th>
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                    </th>
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                    </th>
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                    </th>
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                    </th>

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
        var table = $('#AccTbl').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('getAssettypesData') }}",
            columns: [
                { data: 'asset_type_name', name: 'asset_type_name' },
                { data: 'description', name: 'description' },
                { data: 'active', name: 'active' },
                { data: 'first_name', name: 'tb_users.first_name' },

                {
                    data: 'asset_type_id',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',

                    render: function (data, type, row) {
                        let buttons = '';

                        if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                            buttons += `
        <button class="btn btn-sm btn-primary edit-btn" data-id="${row.asset_type_id}"
         data-active="${row.active}"
         data-name="${row.asset_type_name}" 
          data-desc="${row.description}">
          <i class="bi bi-pencil"></i>
        </button>`;
                        }

                        if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                            buttons += `
        <button class="btn btn-sm btn-danger delete-btn" data-id="${row.asset_type_id}">
          <i class="bi bi-trash"></i>
        </button>`;
                        }
                        return buttons;
                    }
                }
            ]
        });

        // Individual column search
        $('#AccTbl thead').on('keyup change', ".column-search", function () {
            var colIndex = $(this).parent().index();
            table.column(colIndex).search(this.value).draw();
        });
    });


    /* purpose:To check Duplicate entry*/
    var dup_chk = true;
    function duplicate_validate() {
        var asset_type_name = $(".asset_type_name").val();
        var edit_id = $("#edit_id").val();

        $.ajax({
            cache: false,
            url: "{{ URL::to('assettypescheckname/') }}",
            type: 'GET',
            dataType: 'json',
            async: false,
            data: { asset_type_name: asset_type_name, edit_id: edit_id },
            success: function (response) {
                if (response == 1) {
                    $('.dup_name').html('Asset Types:' + asset_type_name + ' Already Exists');
                    $('.dup_name').show();
                    dup_chk = false;

                } else if (response == 0) {
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
    /*end*/



    $(document).on('click', '.save_form', function () {
        var url = "{{URL::to('assettypessave')}}";
        var form = $('#asset_form');
        form.parsley().validate();
        var form = $('#asset_form');
        form.parsley().validate();

        duplicate_validate();
        var data = $('#asset_form').serialize();
        if (form.parsley().isValid() && dup_chk == true) {
            $.post(url, data, function (data) {
                var status = data.status;
                var msg = data.message;
                showCustomAlert(msg, status);
                form[0].reset();
                $('.select2').val('').trigger('change');
                // Reload DataTable
                $('#AccTbl').DataTable().ajax.reload();
            });
        }
    });

    /* Purpose for Upper Case */
    $('.asset_type_name').on('keyup', function () {
        this.value = this.value.toUpperCase();
    });

    // edit function
    $(document).on('click', '.edit-btn', function () {

        const id = $(this).data('id');
        const name = $(this).data('name');
        const desc = $(this).data('desc');
        const active = $(this).data('active');


        // Fill form fields
        $('input[name="edit_id"]').val(id);
        $('input[name="asset_type_name"]').val(name);
        $('input[name="description"]').val(desc);
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
                url: "{{ url('assettypesdelete') }}/" + deleteId,
                type: "GET",
                success: function (data) {
                    if (data == '0') {
                        $('#globalDeleteModal').modal('hide');
                        showCustomAlert('Deleted successfully!', 'success');
                        $('#AccTbl').DataTable().ajax.reload();
                    }
                    if (data == '1') {
                        $('#globalDeleteModal').modal('hide');
                        showCustomAlert("You Can't delete , Used in SomeWhere.", 'error');
                        $('#AccTbl').DataTable().ajax.reload();
                    }
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