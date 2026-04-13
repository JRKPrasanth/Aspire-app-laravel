@extends('layouts.header')
@section('content')
<h3 class="text-danger">Advance Approval</h3>
@include('layouts.breadcrumb')


<div class="card shadow-lg rounded-4 border-0">
<div class="container mt-4">
  <table id="AdvTbl" class="table table-bordered table-striped w-100">
    <thead>
      <tr class="table-warning">
      <th>Employee Name</th>
      <th>Advance Date</th>
      <th>Effective Date</th>
      <th>Amount</th>
      <th>Paid Amount</th>
      <th>Remaining Amount</th>
      <th>Report To</th>
      <th>Status</th>
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
  var table = $('#AdvTbl').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('approveData') }}",
    columns: [

    { data: 'first_name', name: 'm_advances_t.first_name' },
    { data: 'advance_date', name: 'm_advances_t.advance_date' },
    { data: 'effective_date', name: 'm_advances_t.effective_date' },
    { data: 'amount', name: 'm_advances_t.amount' },
    { data: 'paid_amount', name: 'm_advances_t.paid_amount' },
    { data: 'remaining_amount', name: 'm_advances_t.remaining_amount' },
    { data: 'forwarded_id', name: 'm_advances_t.forwarded_id' },
    { data: 'approved_status', name: 'approved_status' },

      {
        data: 'advance_id',
        name: 'actions',
        orderable: false,
        searchable: false,
        render: function (data, type, row) {
            let buttons = '';
            if (window.toolbarButtons?.some(btn => btn.attr.id === 'approve')) {
                    buttons += `
					<button type="button" class="btn btn-sm btn-success approved"
					  data-id="${row.advance_id}">
					 Approve
					</button>`;
            }
            return buttons;
          }
          
      }
    ]
  });


  $('#AdvTbl thead').on('keyup change', '.column-search', function () {
    let index = $(this).closest('th').index();
    table.column(index).search(this.value).draw();
  });
});	
	
	
//approve

		$(document).on('click', '.approved', function () {

                    Id = $(this).data('id'); 
                    var url= "{{URL::to('advanceapprove')}}/"+Id;
                    window.location.href=url;

        });
	
	

</script>

@endpush
