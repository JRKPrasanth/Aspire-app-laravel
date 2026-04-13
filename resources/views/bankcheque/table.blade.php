@extends('layouts.header')
@section('content')
<h3 class="text-danger">   Bank Cheque   </h3>
@include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <table id="AccountsTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">
			  	<th></th>
			<th></th>
            <th>Bank Name</th>
            <th>Branch Code</th>
            <th>Account Number</th>
            <th>Created By</th>
            <th>Actions</th>
          </tr>
          <tr class="table-danger">
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
	
    // data table funcrion	
    $(document).ready(function () {
      var table = $('#AccountsTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('getBankchequeData') }}",
        columns: [
			
		{ data: 'bank_cheque_line_id', name: 'bank_cheque_line_id', visible:false },
		{ data: 'bank_account_line_id', name: 'bank_account_line_id', visible:false },
        { data: 'bank_name', name: 'bank_name' },
        { data: 'branch_name', name: 'branch_name' },
        { data: 'account_number', name: 'account_number' },
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
            <button class="btn btn-sm btn-warning view-btn me-1" data-id="${row.bank_account_hdr_id}" data-lid="${row.bank_cheque_line_id}">
              <i class="bi bi-eye"></i>
            </button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'adddata')) {
                buttons += `
            <button class="btn btn-sm btn-primary add-btn" data-id="${row.bank_account_hdr_id}" data-lid="${row.bank_account_line_id}">
              Add
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
	
	
    //view function
    $(document).on('click', '.view-btn', function () {
		
		  const id = $(this).data('id');
          const chequeid = $(this).data('lid');

      if (chequeid != null) {
        window.location.replace('bankchequeview/' + id);
      } else {
        showCustomAlert("Cheque Details Not Updated", "info");
      }
    });
	
	
	
// add
    $(document).on('click', '.add-btn', function () {
		
      const id = $(this).data('id');
      const chequeid = $(this).data('lid');

      window.location.replace('bankchequecreate/' +id+'/'+chequeid);

    });
	
</script>

@endpush
