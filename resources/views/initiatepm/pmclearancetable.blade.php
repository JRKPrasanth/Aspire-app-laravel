@extends('layouts.header')
@section('content')
<h3 class="text-danger">PM Clearance</h3>
@include('layouts.breadcrumb')


	<div class="card shadow-lg rounded-4 border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-3">
    </div>
    <div class="table-responsive">
      <table id="pmclearTbl" class="table table-bordered table-striped w-100">
  <thead>
  <tr class="table-warning">
    <th>PM No</th>
    <th>Machine Name</th>
    <th>Department Name</th>
    <th>Plan PM Date</th>
    <th>Initiate Date</th>
    <th>Clearance Allocated To</th>
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

    var table = $('#pmclearTbl').DataTable({
      processing: true,
      serverSide: true,
      order: [[4, 'desc']],
      ajax: "{{ route('pmclearanceData') }}",
      columns: [
        { data: 'pm_no', name: 'pm_no' },
        { data: 'machine_name', name: 'machine_name' },
        { data: 'department_name', name: 'department_name' },
        { data: 'actual_pm_date', name: 'actual_pm_date' },
        { data: 'initiate_date', name: 'initiate_date' },
        { data: 'user_clearance_by', name: 'user_clearance_by' },
        {
          data: 'initiate_pm_id',
          name: 'actions',
          orderable: false,
          searchable: false,
		  className: 'text-center',
          width: '200px', 
          render: function (data, type, row) {
			   let buttons = '';
			    if (window.toolbarButtons?.some(btn => btn.attr.id === 'clearance')) {
                    buttons += `
					<button  class="btn btn-sm btn-primary me-1 edit-btn" data-id="${data}">CLEARANCE </button>`;
           		 }

	return buttons;
          }
  
        }
      ]
    });
  
    // Individual column search
    $('#pmclearTbl thead').on('keyup change', ".column-search", function() {
      var colIndex = $(this).parent().index();
      table.column(colIndex).search(this.value).draw();
    });
  });	
	

	//edit function
    $(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');
    const url = "{{ url('pmclearancecreate') }}/" + id;
    window.location.href = url;
    });	
	
</script>
@endpush
