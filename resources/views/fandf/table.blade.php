@extends('layouts.header')
@section('content')
<h3 class="text-danger"> F & F Approval</h3>
@include('layouts.breadcrumb')
        
  

<div class="card shadow-lg rounded-4 border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-3">
    </div>
    <div class="table-responsive">
      <table id="fandfTbl" class="table table-bordered table-striped w-100">
        <thead>
  <tr class="table-warning">
      <th>Employee Name</th>
      <th>Imprest Amount</th>
      <th>Expense Amount</th>
      <th>Salary Amount</th>
      <th>Balance</th>
      <th>Actual Paid</th>
      <th>Actions</th>
  </tr>
  <tr class="table-info">
    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
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

	$(document).ready(function() {

    var table = $('#fandfTbl').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('getfandfData') }}",
      columns: [
        { data: 'emp_id', name: 'emp_id' },
        { data: 'imp_amount', name: 'imp_amount' },
         { data: 'exp_amount', name: 'exp_amount' },
        { data: 'sal_amount', name: 'sal_amount' },
        { data: 'balance_amount', name: 'balance_amount' },
        { data: 'paid_amount', name: 'paid_amount' },

        {
          data: 'hr_ff_id',
          name: 'actions',
          orderable: false,
          searchable: false,
		      className: 'text-center',
          width: '140px', 
          render: function (data, type, row) {
              return `
                  <button class="btn btn-sm btn-success me-1 edit-btn" value="Generate" data-id="${data}">
                      Approve
                  </button>
                  `;
                  
          }
        }
      ]
    });
  
    // Individual column search
    $('#fandfTbl thead').on('keyup change', ".column-search", function() {
      var colIndex = $(this).parent().index();
      table.column(colIndex).search(this.value).draw();
    });
  });
	
	
// approve	
	
    $(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');
    const url = "{{ url('fandfcreate') }}/" + id;
    window.location.href = url;
    });	
	
	

    </script>

@endpush
