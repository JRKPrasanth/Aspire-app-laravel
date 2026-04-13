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
          <th>Status</th>
          <th>Actions</th>
  </tr>

  <tr class="table-success">
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
      ajax: "{{ route('examgriddata') }}",
      columns: [
       { data: 'topic_name', name: 'topic_name'},
        { data: 'schedule_date', name: 'schedule_date' },
        { data: 'start_time', name: 'start_time' },
        { data: 'end_time', name: 'end_time' },
        { data: 'attend', name: 'attend' },
       
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
               Start
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
	

/**********Jqgrid  Start*******/
$( document ).ready(function() {
/* -- Start Create Function -- */
$(".create").click(function(){
    
var url="{{ URL::to('scheduleexamcreate/0')}}";
window.location.replace(url);
});
/* -- End Create Function -- */


/* -- Start Edit Data Function -- */
$("#edit").click(function()
{
	var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
	var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'schedule_exam_line_id');
	var status = jQuery("#grid1").jqGrid ('getCell', gr, 'attend');
	var cellValue1 = jQuery("#grid1").jqGrid ('getCell', gr, 'schedule_exam_hdr_id');
  var type = "<?php echo $urlname ?>";
    
	if(gr)
	{ 
	    if(status!='Attended'){
	       // var url = "{{ url('examcreate') }}/"+cellValue;
        //     window.location.replace(url);
            var url ="{{ URL::to('examschedulechk') }}/" +cellValue;
            $(".ajaxLoading").show();
            
            $.get(url,function(data){ 
                if(data.ret !='0'){
                    var url = "{{ url('examcreate') }}/"+cellValue;
                     window.location.replace(url);
                }else{
                    var status = data.status;
                    var msg    = data.message;
                    notyMsg(status,msg);
                    $(".ajaxLoading").hide();
                }
            });
	    }else{
	        notyMsg('info','Already Attended');
	    }
             
    }else{
	    notyMsg("info","Please Select a Row");
	}
});
/* -- End Edit Data Function -- */


});
  </script>
@endpush
