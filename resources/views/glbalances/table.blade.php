@extends('layouts.header')
@section('content')
<h3 class="text-danger">  General Ledger Balances </h3>
@include('layouts.breadcrumb')

 <div class="card shadow-lg rounded-4 border-0">
  <div class="container mt-4 table-responsive">
    <table id="AccTbl" class="table table-bordered table-striped w-100">
      <thead>
        <tr class="table-warning">
          <th>Journal Name</th>
          <th>Journal Date</th>
          <th>Account Name</th>
          <th>Debit Amount</th>
          <th>Credit Amount</th>
        </tr>
        <tr class="table-danger">
          <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
          <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
          <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
          <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
          <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
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
  var table = $('#AccTbl').DataTable({
    processing: true,
    serverSide: true,
    ajax: "getglbalanceData",
    columns: [

      { data: 'journal_name', name: 'journal_name' },
      { data: 'journal_date', name: 'journal_date' },
      { data: 'account_id', name: 'account_id' },
      { data: 'debit_amount', name: 'debit_amount' },
      { data: 'credit_amount', name: 'credit_amount' }

    ]
  });

  // Column-specific search
  $('#AccTbl thead').on('keyup change', ".column-search", function () {
    var colIndex = $(this).parent().index();
    table.column(colIndex).search(this.value).draw();
  });
    
});	
	

	
</script>

@endpush
