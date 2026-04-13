@extends('layouts.header')
@section('content')
<h3 class="text-danger">Account Class</h3>
@include('layouts.breadcrumb')


<!-- Form Card -->
<div class="card shadow-lg rounded-4 border-0">
    <div class="card-body card-block">
        <div class="col-md-12">
            <form method="post" action="" id="accountclass_form" class="needs-validation" data-parsley-validate enctype="multipart/form-data" novalidate>
                @csrf

                <input type="hidden" class="form-control" id="account_class_id" name="account_class_id" value="{{ $row->account_class_id }}">

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label"><span class="text-danger">*</span> Account Class Name</label>
                        <input type="text" id="account_class_name" name="account_class_name" class="form-control" value="{{ $row->account_class_name }}" required>
                        <div class="invalid-feedback dup_name d-none"></div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label"><span class="text-danger">*</span> Main Account Code</label>
                        <input type="text" id="main_account_code" name="main_account_code" class="form-control" value="{{ $row->main_account_code }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Description</label>
                        <input type="text" id="description" name="description" class="form-control" value="{{ $row->description }}">
                    </div>

                    <div class="col-md-4" style="display:none;">
                        <label class="form-label">Code Startwith</label>
                        <input type="text" id="code_startwith" name="code_startwith" class="form-control" value="false">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label"><span class="text-danger">*</span> Active</label>
                        <select name="active" class="form-select select2" required>
                            <option value="Yes" {{ $row->active == 'Yes' ? 'selected' : '' }}>Yes</option>
                            <option value="No" {{ $row->active == 'No' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div class="col-md-4 none">
                        <label class="form-label">Created By</label>
                        <select name="created_by" class="form-select select2">
                            {!! $created_by !!}
                        </select>
                    </div>
                </div>

                <!-- Save button -->
                <div class="text-center mt-4">
                    <button type="button" class="btn btn-success saveform">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- DataTable Card -->
<div class="card shadow-lg rounded-4 border-0 mt-4">
    <div class="container mt-4">
        <table id="accountclassTable" class="table table-bordered table-striped w-100">
            <thead>
                <tr class="table-warning">
                    <th>Account Class</th>
                    <th>Main Account Code</th>
                    <th>Description</th>
                    <th>Active</th>
                    <th>Created By</th>
                    <th>Actions</th>
                </tr>
                <tr class="table-danger">
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
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
	
$(document).ready(function() {

    // Init DataTable
    var table = $('#accountclassTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ URL::to('getAccountclassData') }}",
        columns: [
            { data: 'account_class_name', name: 'account_class_name' },
            { data: 'main_account_code', name: 'main_account_code' },
            { data: 'description', name: 'description' },
            { data: 'active', name: 'active' },
            { data: 'first_name', name: 'tb_users.first_name' },
            {
                data: 'account_class_id',
                name: 'actions',
                orderable: false,
                searchable: false,
                className: 'text-center',
                width: '140px',
                render: function (data, type, row) {
                    let buttons = '';
                    buttons += `<button class="btn btn-sm btn-info me-1 edit-btn" 
                        data-id="${row.account_class_id}" 
                        data-name="${row.account_class_name}" 
                        data-code="${row.main_account_code}" 
                        data-description="${row.description}" 
                        data-active="${row.active}">
                        <i class="bi bi-pencil"></i>
                    </button>`;
                    buttons += `<button class="btn btn-sm btn-danger delete-btn" data-id="${row.account_class_id}">
                        <i class="bi bi-trash"></i>
                    </button>`;
                    return buttons;
                }
            }
        ]
    });

    // Individual column search
    $('#accountclassTable thead').on('keyup change', ".column-search", function() {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
    });

    // Save function
    $(document).on('click', '.saveform', function () {
        var form = $("#accountclass_form");
        form.parsley().validate();

        if (form.parsley().isValid()) {
            $.ajax({
                url: "{{ URL::to('accountclasssave') }}",
                type: "POST",
                data: form.serialize(),
                success: function (data) {
                    showCustomAlert(data.message, 'success');
                    form[0].reset();
                    $('.select2').val('').trigger('change');
                    $('#accountclassTable').DataTable().ajax.reload();
                },
                error: function (xhr) {
                    showCustomAlert('Save failed. Try again.', 'error');
                }
            });
        }
    });

    // Edit button
    $(document).on('click', '.edit-btn', function () {
        const btn = $(this);

        $('#account_class_id').val(btn.data('id'));
        $('#account_class_name').val(btn.data('name'));
        $('#main_account_code').val(btn.data('code'));
        $('#description').val(btn.data('description'));
        $('select[name="active"]').val(btn.data('active')).trigger('change');
    });

    // Delete button
    let deleteId = null;
    $(document).on('click', '.delete-btn', function () {
        deleteId = $(this).data('id');
        $('#globalDeleteModal').modal('show');
    });

    $('#globalConfirmDeleteBtn').on('click', function () {
        if (deleteId) {
            $.ajax({
                url: "{{ url('accountclassdelete') }}/" + deleteId,
                type: "GET",
                success: function (response) {
                    $('#globalDeleteModal').modal('hide');
                    showCustomAlert('Deleted successfully!', 'success');
                    $('#accountclassTable').DataTable().ajax.reload();
                },
                error: function (xhr) {
                    $('#globalDeleteModal').modal('hide');
                    const errorMsg = xhr.responseJSON?.message || 'Delete failed.';
                    showCustomAlert(errorMsg, 'error');
                }
            });
        }
    });

});
	
</script>

@endpush