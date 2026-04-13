@extends('layouts.header')
@section('content')
<h3 class="text-danger">Job Activity</h3>
@include('layouts.breadcrumb')             
<div id="toolbar-container" class="create mb-3 mt-1"></div>


<div class="card shadow-lg rounded-4 border-0">
  <div class="container mt-4">
    <div class="table-responsive">
      <table id="jobactTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">

                <th>Actions</th>
                <th>Employee Name</th>
                <th>Remarks</th>
                <th>Created Date</th>
                <th>Created By Name</th>


          </tr>

          <tr class="table-info">

            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>

          </tr>
        </thead>
        <tbody>
          {{-- DataTable will populate via AJAX --}}
        </tbody>
      </table>
    </div>
  </div>
</div>


@endsection
@push('scripts')

    <script>
		
		
  // table data

  $(document).ready(function () {


    var table = $('#jobactTbl').DataTable({
      processing: true,
      serverSide: true,
      order: [[3, 'desc']],
      ajax: "getjobactivitydata",
      columns: [

        {

          data: 'job_activity_id',
          name: 'actions',
          orderable: false,
          searchable: false,
                render: function (data, type, row) {
                    let buttons = '';

					 if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                        buttons += `
                            <button type="button" class="btn btn-sm btn-warning view-btn me-1"
                                data-id="${row.job_activity_id}">
                                <i class="bi bi-eye"></i>
                            </button>`;
                    }
					
					if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                        buttons += `
                            <button type="button" class="btn btn-sm btn-danger delete-btn me-1"
                                data-id="${row.job_activity_id}">
                                <i class="bi bi-trash"></i> 
                            </button>`;
                    }

                    if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit')) {
                        buttons += `
                            <button type="button" class="btn btn-sm btn-primary edit-btn me-1"
                                data-id="${row.job_activity_id}">
                                <i class="bi bi-pencil"></i> 
                            </button>`;
                    }

                    return buttons;
                },

        },

           
            { data: 'employee_number', name: 'employee_number' },
            { data: 'remarks', name: 'remarks' },
            { data: 'created_at', name: 'created_at' },
            { data: 'first_name', name: 'first_name' },

      ]
    });


    $('#jobactTbl thead').on('keyup change', '.column-search', function () {
      let index = $(this).closest('th').index();
      table.column(index).search(this.value).draw();
    });
  });
		

// Add create button purpose
	      $(document).ready(function () {
        if (window.toolbarButtons?.some(btn => btn.attr.id === 'create')) {
          $('#toolbar-container').append(`
            <button class="btn btn-success create me-2">Create
              <i class="bi bi-plus-circle"></i> 
            </button>
          `);
        }
      });		
		
		
// create

$('.create').click(function(){
    var url = "{{ URL::to('jobactivityedit') }}/0";
      
    window.location.replace(url);
});


	//edit function
		
    $(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');
    const url = "{{ url('jobactivityedit') }}/" + id;
    window.location.href = url;
    });
	
	//view function
		
$(document).on('click', '.view-btn', function () {
  const id = $(this).data('id');
  const url = "{{ url('jobactivityview') }}/" + id;
  window.location.href = url;
});
		
		
	// delete function
	let deleteId = null; 

	$(document).on('click', '.delete-btn', function () {
		deleteId = $(this).data('id');
		$('#globalDeleteModal').modal('show'); 
	});

	$('#globalConfirmDeleteBtn').on('click', function () {
		if (deleteId) {
				$.ajax({
					url: "{{ url('jobactivitydelete') }}/" + deleteId,
					type: "GET",
					success: function (data) {
                    if(data =='0')
                    {
                      $('#globalDeleteModal').modal('hide');
                      showCustomAlert('Deleted successfully!', 'success');
                      $('#jobactTbl').DataTable().ajax.reload();
                    }
                    if(data =='2')
                    {
                       $('#globalDeleteModal').modal('hide');
				          		showCustomAlert("You Cant't delete , Enquiry Used in SomeWhere!!!", 'error');
						          $('#jobactTbl').DataTable().ajax.reload();
                    }

					},
					error: function (xhr) {
						 $('#globalDeleteModal').modal('hide');
						const errorMsg = xhr.responseJSON?.message || 'Delete failed.';
						showCustomAlert(errorMsg, 'error');
					}
				});
		}
	});



    </script>


@endpush
