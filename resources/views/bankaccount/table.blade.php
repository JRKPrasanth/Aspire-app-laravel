@extends('layouts.header')
@section('content')
<h3 class="text-danger">Bank Account</h3>
@include('layouts.breadcrumb')
<div id="toolbar-container" class="create mb-3 mt-1"></div>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <table id="AccountsTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Bank Name</th>
            <th>Bank Type</th>
            <th>Bank Source</th>
            <th>Customer Name</th>
            <th>Supplier Name</th>
            <th>Company Name</th>
            <th>Active</th>
            <th>Created By</th>
            <th style="width:15%;">Actions</th>
          </tr>
          <tr class="table-danger">
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
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
	
  // Add create button purpose
    $(document).ready(function () {
      if (window.toolbarButtons?.some(btn => btn.attr.id === 'create')) {
        $('#toolbar-container').append(`
                  <button class="btn btn-primary text-white px-4 create me-2">Create
                    <i class="bi bi-plus-circle"></i> 
                  </button>
                `);
      }
    });


    // data table funcrion	
    $(document).ready(function () {
      var table = $('#AccountsTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('getBankaccountData') }}",
        columns: [

        { data: 'bank_name', name: 'bank_name' },
        { data: 'bank_type', name: 'bank_type' },
        { data: 'bank_source', name: 'bank_source' },
        { data: 'customer_name', name: 'customer_name' },
        { data: 'supplier_name', name: 'supplier_name' },
        { data: 'company_name', name: 'company_name', visible:false },
        { data: 'active', name: 'active' },
        { data: 'first_name', name: 'first_name' },


          {
            data: 'bank_account_hdr_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
            <button class="btn btn-sm btn-primary edit-btn" data-id="${row.bank_account_hdr_id}">
              <i class="bi bi-pencil"></i>
            </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `
            <button class="btn btn-sm btn-warning view-btn" data-id="${row.bank_account_hdr_id}">
              <i class="bi bi-eye"></i>
            </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
            <button class="btn btn-sm btn-danger delete-btn" data-id="${row.bank_account_hdr_id}">
              <i class="bi bi-trash"></i>
            </button>`;
              }
              return buttons;
            }
          }
        ]
      });

      // Individual column search
      $('#AccountsTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });
	
	
    // create function
    $(".create").click(function () {
      var url = "{{ URL::to('bankaccountcreate')}}";
      window.location.replace(url);
    });
    //edit function
    $(document).on('click', '.edit-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('bankaccountcreate') }}/" + id;
      window.location.href = url;
    });

    //view function
    $(document).on('click', '.view-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('bankaccountview') }}/" + id;
      window.location.href = url;
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
          url: "{{ url('bankaccountdelete') }}/" + deleteId,
          type: "GET",
          success: function (data) {
            if (data == '0') {
              $('#globalDeleteModal').modal('hide');
              showCustomAlert('Deleted successfully!', 'success');
              $('#AccountsTbl').DataTable().ajax.reload();
            }
            if (data == '2') {
              $('#globalDeleteModal').modal('hide');
              showCustomAlert("You Can't delete , Subinventory Used in SomeWhere.", 'error');
              $('#AccountsTbl').DataTable().ajax.reload();
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
