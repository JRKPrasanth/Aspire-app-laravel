@extends('layouts.header')
@section('content')
<h3 class="text-danger">Job Work Out Order</h3>
@include('layouts.breadcrumb')
<div id="toolbar-container" class="create mb-3 mt-2"></div>

<div class="card shadow-lg rounded-4 border-0">
  <div class="container mt-4">
    <div class="table-responsive">
      <table id="JobTbl" class="table table-bordered table-striped w-100">
        <thead>
          <tr class="table-warning">

                <th>Actions</th>
                <th>Jobworkoutorder No</th>
                <th>Subcontract Supplier</th>
                <th>Return Date</th>
                <th>Remarks</th>
                <th>Created By</th>
          </tr>

          <tr class="table-info">

            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
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
	
	
// create function
	 $(".create").click(function()
{
  var url="{{ URL::to('jobworkoutordercreate/0')}}";
    window.location.replace(url);
});		
	
	
  // table data

  $(document).ready(function () {

    var table = $('#JobTbl').DataTable({
      processing: true,
      serverSide: true,
      ajax: "getjobworkoutorderData",

      columns: [

        {

          data: 'jobworkoutorder_hdr_id',
          name: 'actions',
          orderable: false,
          searchable: false,
                render: function (data, type, row) {
                    let buttons = '';

                    if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                        buttons += `
                            <button type="button" class="btn btn-sm btn-primary edit-btn"
                                data-id="${row.jobworkoutorder_hdr_id}">
                                <i class="bi bi-pencil"></i>
                            </button>`;
                    }

                    if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                        buttons += `
                            <button type="button" class="btn btn-sm btn-warning view-btn"
                                data-id="${row.jobworkoutorder_hdr_id}">
                                <i class="bi bi-eye"></i>
                            </button>`;
                    }


                    if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                        buttons += `
                            <button type="button" class="btn btn-sm btn-danger delete-btn"
                                data-id="${row.jobworkoutorder_hdr_id}">
                                <i class="bi bi-eye"></i>
                            </button>`;
                    }
					
                    return buttons;
                },

        },

        { data: 'joboutorder_no', name: 'joboutorder_no' },
        { data: 'subcontract_name', name: 'subcontract_name' },
        { data: 'return_date', name: 'return_date' },
        { data: 'remarks', name: 'remarks' },
        { data: 'first_name', name: 'first_name.first_name' },


      ]
    });


    $('#JobTbl thead').on('keyup change', '.column-search', function () {
      let index = $(this).closest('th').index();
      table.column(index).search(this.value).draw();
    });
  });			
	
	
	
	//edit function
    $(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');
    const url = "{{ url('jobworkoutorderedit') }}/" + id;
    window.location.href = url;
    });	
	
	
	//view function
$(document).on('click', '.view-btn', function () {
  const id = $(this).data('id');
  const url = "{{ url('jobworkoutorderview') }}/" + id;
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
					url: "{{ url('jobworkoutorderdelete') }}/" + deleteId,
					type: "GET",
					success: function (data) {
						
					if(data =='0')
					{
						$('#globalDeleteModal').modal('hide');
						showCustomAlert('Deleted successfully!', 'success');
						$('#JobTbl').DataTable().ajax.reload();
					}
					if(data =='1')
					{
						$('#globalDeleteModal').modal('hide');
						showCustomAlert('Machine Used in SomeWhere!', 'error');
						$('#JobTbl').DataTable().ajax.reload();
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
