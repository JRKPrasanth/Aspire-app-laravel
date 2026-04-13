@extends('layouts.header')
@section('content')
<h3 class="text-danger">Exam List</h3>
@include('layouts.breadcrumb')


<div class="card shadow-lg rounded-4 border-0">
<div class="card-body">
  <div class="d-flex justify-content-between mb-3">
  </div>
  <div class="table-responsive">
  <table id="TopicTbl" class="table table-striped table-bordered w-100">
  <thead>
  <tr class="table-warning">
          <th>Topic</th>
          <th>Schedule Date</th>
          <th>Start Time</th>
          <th>End Time</th>
          <th>Schedule Type</th>
          <th>Result Status</th>
          <th>Actions</th>
  </tr>

  <tr class="table-success">
      <th><input type="text" placeholder="Search" /></th>
      <th><input type="text" placeholder="Search" /></th>
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
	
		$(document).ready(function () {
    var table = $('#TopicTbl').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('finishedexamgriddata') }}",
      columns: [
       { data: 'topic_name', name: 'topic_name'},
        { data: 'schedule_date', name: 'schedule_date' },
        { data: 'start_time', name: 'start_time' },
        { data: 'end_time', name: 'end_time' },
        { data: 'schedule_type', name: 'schedule_type' },
        { data: 'generate', name: 'generate' },

       
        {
          data: 'schedule_exam_hdr_id',
          name: 'actions',
          width:'100px',
          orderable: false,
          searchable: false,
          render: function (data, type, row) {
            return 
               ` <button class="btn btn-sm btn-success me-1 start-btn" 
                data-id="${row.schedule_exam_hdr_id}"
               Generate
              </button> `;
          }
            
        }
      ]
    });
  
  
    $('#TopicTbl thead').on('keyup change', '.column-search', function () {
      let index = $(this).closest('th').index();
      table.column(index).search(this.value).draw();
    });
  });
	
	
	
$("#view").click(function()
{
	var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
	var result = jQuery('#grid1').jqGrid('getCell',gr,'generate');
	var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'schedule_exam_hdr_id');
  var type = "<?php echo $urlname ?>";
  	if(gr)
	{
	    if(result=='Result Generated'){
	        var view_url = "{{ url('resultsview') }}/";
	        window.location.replace(view_url +cellValue);
	    }else{
	        notyMsg("info","Result Not yet Generated");
	    }

	}
	else
	{
	notyMsg("info","Please Select a Row");
	}
});	
	
	

  </script>
@endpush
