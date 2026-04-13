@extends('layouts.header')
@section('content')
<h3 class="text-danger">Schedule Training From Request</h3>
@include('layouts.breadcrumb')

 <div class="card shadow-lg rounded-4 border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-3">
    </div>
    <div class="table-responsive">
	<table id="TopicTbl" class="table table-striped table-bordered w-100">
    <thead>
    <tr class="table-warning">
           <th>Request Type</th>
            <th>Topic</th>
            <th>Employee Name</th>
            <th>Remarks</th>
			<th>Actions</th>
    </tr>

    <tr class="table-success">
        <th><input type="text" placeholder="Search" /></th>
        <th><input type="text" placeholder="Search" /></th>
        <th><input type="text" placeholder="Search" /></th>
        <th><input type="text" placeholder="Search" /></th>
		<th></th>
      </tr>
      
  </thead>
</table>
    </div>
  </div>
</div>


@endsection
@push('scripts')

<script>
// data table funcrion	
$(document).ready(function () {
  var table = $('#TopicTbl').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('getrequesttraininggriddata') }}",
    columns: [
      { data: 'request_type', name: 'request_type' },
      { data: 'topic_name', name: 'topic_name' },
      { data: 'employee_name', name: 'employee_name' },  
      { data: 'remarks', name: 'remarks' },

      {
        data: 'training_request_id',
        name: 'actions',
        width: '120px',
        orderable: false,
        searchable: false,
        render: function (data, type, row) {
          return `
            <button type="button" class="btn btn-sm btn-primary sch-btn" data-id="${data}">Schedule</button>`;
        }
      }
    ]
  });



  $('#TopicTbl thead').on('keyup change', '.column-search', function () {
    let index = $(this).closest('th').index();
    table.column(index).search(this.value).draw();
  });
});
	  
/*End*/
    /*approve Function*/
           $(document).on('click', '.sch-btn', function () {
              
				    const id = $(this).data('id');
					const url = "{{ url('scheduletrainingcreate') }}/" + id;
					window.location.href = url;
				
            });
	
	</script>

@endpush

