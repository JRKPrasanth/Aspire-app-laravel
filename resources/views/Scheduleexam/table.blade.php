@extends('layouts.header')
@section('content')
<h3 class="text-danger">Schedule Exam</h3>
@include('layouts.breadcrumb')
<div id="toolbar-container" class="create mb-3"></div>

 <div class="card shadow-lg rounded-4 border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-3">
    </div>
    <div class="table-responsive">
	<table id="TopicTbl" class="table table-striped table-bordered w-100">
    <thead>
    <tr class="table-warning">
            <th>Schedule Date</th>
            <th>Schedule Type</th>
            <th>Topic</th>
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
    ajax: "{{ route('scheduleexamgriddata') }}",
    columns: [
     { data: 'schedule_date', name: 'schedule_date'},
      { data: 'schedule_type', name: 'schedule_type' },
      { data: 'topic_name', name: 'topic_name' },
      { data: 'remarks', name: 'remarks' },
     
      {
        data: 'schedule_exam_hdr_id',
        name: 'actions',
		width:'100px',
        orderable: false,
        searchable: false,
        render: function (data, type, row) {
            let buttons = '';
            if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                    buttons += `
				<button type="button" class="btn btn-sm btn-info edit-btn"
				  data-id="${row.schedule_exam_hdr_id}">
				  <i class="bi bi-pencil"></i>
				</button>`;
            }
            if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                    buttons += `
					<button type="button" class="btn btn-sm btn-danger delete-btn"
					  data-id="${row.schedule_exam_hdr_id}">
					  <i class="bi bi-trash"></i>
					</button>`;
            }
            return buttons;
          }
          
      }
    ]
  });


  $('#TopicTbl thead').on('keyup change', '.column-search', function () {
    let index = $(this).closest('th').index();
    table.column(index).search(this.value).draw();
  });
});	
	
	
	
// Add create button purpose
	      $(document).ready(function () {
        if (window.toolbarButtons?.some(btn => btn.attr.id === 'create')) {
          $('#toolbar-container').append(`
            <button class="btn btn-primary create me-2">Create
              <i class="bi bi-plus-circle"></i> 
            </button>
          `);
        }
      });	
	
/* -- Start Create Function -- */
$(".create").click(function(){
    
var url="{{ URL::to('scheduleexamcreate/0')}}";
window.location.replace(url);
});	
	
	//edit function
    $(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');
    const url = "{{ url('scheduleexamcreate') }}/" + id;
    window.location.href = url;
    });
	
	//view function
$(document).on('click', '.view-btn', function () {
  const id = $(this).data('id');
  const url = "{{ url('scheduleexamview') }}/" + id;
  window.location.href = url;
});	
	
	

  </script>

@endpush