@extends('layouts.header')
@section('content')
<h3 class="text-danger">Comp-Off Approval</h3>
@include('layouts.breadcrumb')

<div class="card shadow-lg rounded-4 border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-3">
    </div>
    <div class="table-responsive">
      <table id="comapThbl" class="table table-bordered table-striped w-100">
        <thead>
  <tr class="table-warning">
        <th>Employee Name</th>
        <th>Approver Name</th>
        <th>Type</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>No of Days</th>
        <th>Actions</th>
  </tr>
  <tr class="table-info">
    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
    <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
    <th><input type="text" class="form-control form-control-sm column-search start_date" placeholder="Search" /></th>
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

    var table = $('#comapThbl').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('compoffapprovalData') }}",
      columns: [

        { data: 'employee_name', name: 'employee_name' },
        { data: 'forwarded_name', name: 'forwarded_name' },
        { data: 'leave_type_name', name: 'leave_type_name' },
        { data: 'start_date', name: 'start_date' },
        { data: 'end_date', name: 'end_date' },
        { data: 'no_of_days', name: 'no_of_days' },


        {
          data: 'compoff_id',
          name: 'actions',
          orderable: false,
          searchable: false,
		      className: 'text-center',
          width: '140px', 
          render: function (data, type, row) {
              return `
                  <button class="btn btn-sm btn-success me-1 edit-btn" data-id="${data}">
                    Approve
                  </button>`;
          }
        }
      ]
    });
  
    // Individual column search
    $('#comapThbl thead').on('keyup change', ".column-search", function() {
      var colIndex = $(this).parent().index();
      table.column(colIndex).search(this.value).draw();
    });
  });
	
	
	
$(document).on('click', '.edit-btn', function () {
  const id = $(this).data('id');
  const url = "{{ url('approvecompoff') }}/" + id;
  window.location.href = url;
});	
	

</script>

@endpush