@extends('layouts.header')
@section('content')
<h3 class="text-danger">Miss Punch Approval</h3>
@include('layouts.breadcrumb')

<div class="card shadow-lg rounded-4 border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-3">
    </div>
    <div class="table-responsive">
      <table id="missTbl" class="table table-bordered table-striped w-100">
        <thead>
  <tr class="table-warning">
        <th>Employee Name</th>
        <th>Reporting Name</th>
        <th>Missed Punch Name</th>
        <th>Date</th>
        <th>Reason</th>
        <th>Status</th>
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

    var table = $('#missTbl').DataTable({
      processing: true,
      serverSide: true,
      order: [[3, 'desc']],
      ajax: "{{ route('punchrequestapprovaldata') }}",
      columns: [

        { data: 'first_name', name: 'first_name' },
        { data: 'reporting_name', name: 'reporting_name' },
        { data: 'lookup_meaning', name: 'lookup_meaning' },
        { data: 'date', name: 'date' },
        { data: 'reason', name: 'reason' },
        { data: 'status', name: 'status' },

        {
          data: 'miss_id',
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
    $('#missTbl thead').on('keyup change', ".column-search", function() {
      var colIndex = $(this).parent().index();
      table.column(colIndex).search(this.value).draw();
    });
  });
	
	
	
$(document).on('click', '.edit-btn', function () {
  const id = $(this).data('id');
  const url = "{{ url('approvepunch') }}/" + id;
  window.location.href = url;
});	
	
	
	
	
	
	

</script>

@endpush
