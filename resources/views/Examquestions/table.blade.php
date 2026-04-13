@extends('layouts.header')
@section('content')
<h3 class="text-danger">Exam Questions</h3>
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

            <th>Topic Name</th>
            <th>Remarks</th>
			<th>Actions</th>
    </tr>

    <tr class="table-success">
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
    ajax: "{{ route('examquestionsgriddata') }}",
    columns: [
      { data: 'topic_name', name: 'topic_name' },
      { data: 'remarks', name: 'remarks' },

      {
        data: 'exam_questions_hdr_id',
        name: 'actions',
        width: '120px',
        orderable: false,
        searchable: false,
render: function (data, type, row) {
			   let buttons = '';
			  console.log(window.toolbarButtons);
			    if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                    buttons += `
					<button  class="btn btn-sm btn-info me-1 edit-btn" data-id="${data}"><i class="bi bi-pencil"></i></button>`;
           		 }

			   if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
				buttons += `<button class="btn btn-sm btn-danger delete-btn" data-id="${data}" data-url="{{ url('companydelete') }}"><i class="bi bi-trash"></i>  </button>`;
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

	// create
	$(".create").click(function(){
    
var url="{{ URL::to('examquestionscreate/0')}}";
window.location.replace(url);
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
</script>
@endpush
