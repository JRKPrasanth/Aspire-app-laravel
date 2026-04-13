@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Employee Expenses</h3>
  @include('layouts.breadcrumb')
  <div id="toolbar-container" class="create mb-3 mt-1"></div>

  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4 table-responsive">
      <table id="AccTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
            <th>Actions</th>
			<th>Expense No</th>
			<th>Bill No</th>
			<th>Expense Date</th>
			<th>Expense Status</th>
			<th>Expense Amount</th>
			<th>Remarks</th>
			<th>Created By</th>
            
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
              <button class="btn btn-success text-white px-4 create me-2">Create
                <i class="bi bi-plus-circle"></i> 
              </button>
            `);
      }
    });


    // data table funcrion	
    $(document).ready(function () {
       var status ="{{$status}}";

      var table = $('#AccTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "getempExpenseindexData?status="+status,
		order: [[2, 'desc']],
        columns: [
                    {
            data: 'expense_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',

            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `
        <button class="btn btn-sm btn-warning view-btn" data-id="${row.expense_id}">
          <i class="bi bi-eye"></i>
        </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                buttons += `
        <button class="btn btn-sm btn-primary edit-btn" data-id="${row.expense_id}">
          <i class="bi bi-pencil"></i>
        </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                buttons += `
        <button class="btn btn-sm btn-danger delete-btn" data-id="${row.expense_id}">
          <i class="bi bi-trash"></i>
        </button>`;
              }
		if (window.toolbarButtons?.some(btn => btn.attr.id === 'approve')) {
                            buttons += `
        <button class="btn btn-sm btn-success approve-btn" data-id="${row.expense_id}" data-status="${row.expense_status}">
          Approve
        </button>`;
                        }
		
              return buttons;
            }
          },
          { data: 'expense_no', name: 'expense_no', className: 'text-center' },
          { data: 'bill_no', name: 'bill_no', className: 'text-center' },
          { data: 'expense_date', name: 'expense_date', className: 'text-center' },
          { data: 'expense_status', name: 'expense_status', className: 'text-center' },
          { data: 'expense_amount', name: 'expense_amount', className: 'text-center' },
          { data: 'remarks', name: 'remarks', className: 'text-center' },
          { data: 'first_name', name: 'first_name', className: 'text-center' },


        ]
      });

      // Individual column search
      $('#AccTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });


    // create function
    $(".create").click(function () {
      var url = "{{ URL::to('empexpensescreate')}}";
      window.location.replace(url);
    });

    /* Edit Function*/
    $(document).on('click', '.edit-btn', function () {
        const id = $(this).data('id');
        const status = $(this).data('status');


        if (status != "APPROVED") {
            window.location.replace('empexpensescreate/' + id);
        }
        else {
            showCustomAlert("Approved Data Cant Edit", "error");
        }
    });

    //view function
    $(document).on('click', '.view-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('empexpensesview') }}/" + id;
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
          url: "{{ url('empexpensesdelete') }}/" + deleteId,
          type: "GET",
          success: function (data) {
            if (data == '0') {
              $('#globalDeleteModal').modal('hide');
              showCustomAlert('Deleted successfully!', 'success');
              $('#AccTbl').DataTable().ajax.reload();
            }
            if (data == '1') {
              $('#globalDeleteModal').modal('hide');
              showCustomAlert("You Can't delete , Subinventory Used in SomeWhere.", 'error');
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

 
    /* Purpose For approve Function*/
    $(document).on('click', '.approve-btn', function () {

        const id = $(this).data('id');
        const status = $(this).data('status');

        window.location.replace('empexpenseapproval/' + id + '/' + status);

    });


  </script>

@endpush
