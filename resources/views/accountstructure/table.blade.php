@extends('layouts.header')

@section('content')
<h3 class="text-danger">Account Structure</h3>
@include('layouts.breadcrumb')

<div id="toolbar-container" class="mb-2 mt-1"></div>

<div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">

        <table id="AccountsTbl" class="table table-bordered table-striped w-100">
            <thead>
                <!-- Column titles -->
                <tr class="table-warning">
                    <th>Company</th>
                    <th>Location</th>
                    <th>Costcenter</th>
                    <th>Main Account</th>
                    <th>Sub Account</th>
                    <th>Account Name</th>
                    <th>Group Name</th>
                    <th>Full Name</th>
                    <th>Active</th>
                    <th>Created By</th>
                    <th style="width:15%">Actions</th>
                </tr>

                <!-- Column search -->
                <tr class="table-danger">
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search"></th>
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search"></th>
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search"></th>
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search"></th>
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search"></th>
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search"></th>
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search"></th>
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search"></th>
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search"></th>
                    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search"></th>
                    <th></th>
                </tr>
            </thead>

            <tbody></tbody>
        </table>

    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {

    /* =============================
       DataTable Initialization
    ============================== */
    
    var table = $('#AccountsTbl').DataTable({
        processing: true,
        serverSide: true,
        orderCellsTop: true,
        fixedHeader: true,
        scrollX: true,
        autoWidth: false,
        order: [[0, 'desc']],
        ajax: "{{ route('getAccountstructData') }}",

        columns: [
            { data: 'company_code', name: 'm_company_t.company_code' },
            { data: 'location_code', name: 'm_location_t.location_code' },
            { data: 'sub_department_name', name: 'm_department_lines_t.sub_department_name', visible: false },
            { data: 'main_account_code', name: 'f_account_class_t.main_account_code' },
            { data: 'account_code_meaning', name: 'f_account_codes_lines_t.account_code_meaning' },
            { data: 'account_name', name: 'f_account_structure_t.account_name' },
            { data: 'account_description', name: 'f_account_structure_t.account_description' },
            { data: 'concatenated_segments', name: 'f_account_structure_t.concatenated_segments' },
            { data: 'active', name: 'f_account_structure_t.active' },
            { data: 'first_name', name: 'tb_users.first_name' },
            {
                data: 'f_account_structure_id',
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function (data, type, row) {
                    let buttons = '';

                    if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                        buttons += `
                          <button class="btn btn-sm btn-warning view-btn" data-id="${data}">
                            <i class="bi bi-eye"></i>
                          </button>`;
                    }

                    if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                        buttons += `
                          <button class="btn btn-sm btn-primary edit-btn" data-id="${data}">
                            <i class="bi bi-pencil"></i>
                          </button>`;
                    }

                    if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                        buttons += `
                          <button class="btn btn-sm btn-danger delete-btn" data-id="${data}">
                            <i class="bi bi-trash"></i>
                          </button>`;
                    }

                    return buttons;
                }
            }
        ]
    });

    /* =============================
       Column Wise Search
    ============================== */
    $('#AccountsTbl thead tr:eq(1) th').each(function (i) {
        $('input', this).on('keyup change clear', function () {
            if (table.column(i).search() !== this.value) {
                table
                    .column(i)
                    .search(this.value)
                    .draw();
            }
        });
    });

    /* =============================
       Create Button
    ============================== */
    if (window.toolbarButtons?.some(btn => btn.attr.id === 'create')) {
        $('#toolbar-container').html(`
            <button class="btn btn-success px-4 create">
                Create <i class="bi bi-plus-circle"></i>
            </button>
        `);
    }

});

/* =============================
   CRUD Actions
============================== */

$(document).on('click', '.create', function () {
    window.location.href = "{{ url('accountstructurecreate') }}";
});

$(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');
    window.location.href = "{{ url('accountstructurecreate') }}/" + id;
});

$(document).on('click', '.view-btn', function () {
    const id = $(this).data('id');
    window.location.href = "{{ url('accountstructureview') }}/" + id;
});

let deleteId = null;

$(document).on('click', '.delete-btn', function () {
    deleteId = $(this).data('id');
    $('#globalDeleteModal').modal('show');
});

$('#globalConfirmDeleteBtn').on('click', function () {
    if (!deleteId) return;

    $.ajax({
        url: "{{ url('accountstructuredelete') }}/" + deleteId,
        type: "GET",
        success: function (res) {
            $('#globalDeleteModal').modal('hide');

            if (res == '0') {
                showCustomAlert('Deleted successfully!', 'success');
            } else {
                showCustomAlert("You can't delete. Record is in use.", 'error');
            }

            table.ajax.reload(null, false);
        },
        error: function () {
            $('#globalDeleteModal').modal('hide');
            showCustomAlert('Delete failed!', 'error');
        }
    });
});
</script>
@endpush