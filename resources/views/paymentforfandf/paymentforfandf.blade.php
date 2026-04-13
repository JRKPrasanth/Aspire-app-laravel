@extends('layouts.header')
@section('content')
<h3 class="text-danger">Payment For F&F</h3>
@include('layouts.breadcrumb')
<button class="btn btn-success create_payment px-4 me-2 mt-1">Create Payment</button>


<div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4 table-responsive">
 <table id="AccTbl" class="table table-bordered table-striped w-100">
  <thead>
    <tr class="table-warning">
		    <th><input type="checkbox" id="select-all"></th>
            <th>Employee Number</th>
            <th>Employee Name</th>
			<th>Final Payable</th>

    </tr>

    <tr class="table-danger">
		
            <th></th>
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
    ajax: "employeefandfgrid",
    columns: [
      {
        data: 'emp_id',
        orderable: false,
        searchable: false,
        className: 'text-center',
        render: function (data) {
          return `<input type="checkbox" class="row-checkbox" value="${data}">`;
        }
      },

			{ data: "employee_number"},
			{ data: "first_name"},
		    { data: "balance_amount"},


    ]
  });


  $('#AccTbl thead').on('keyup change', ".column-search", function () {
    var colIndex = $(this).parent().index();
    table.column(colIndex).search(this.value).draw();
  });


  $('#select-all').on('click', function () {
    var rows = table.rows({ 'search': 'applied' }).nodes();
    $('input[type="checkbox"].row-checkbox', rows).prop('checked', this.checked);
  });


  $('#AccTbl tbody').on('change', '.row-checkbox', function () {
    if (!this.checked) {
      var el = $('#select-all').get(0);
      if (el && el.checked && ('indeterminate' in el)) {
        el.indeterminate = true;
      }
    }
  });
});			
		
		
// payment request	

 $(document).on('click', '.create_payment', function () {
    var selected = [];
    $("#AccTbl tbody input.row-checkbox:checked").each(function () {
      selected.push($(this).val());
    });

    if (selected.length === 0) {
      showCustomAlert("Please select at least one row", "info");
      return;
    }
	 

    var url = "{{ URL::to('paymentforfandfcreate') }}/" + selected.join(",");
	window.location.replace(url);
	 
  });			
		
   
</script>

@endpush
