@extends('layouts.header')
@section('content')
<h3 class="text-danger">Shift Upload</h3>
@include('layouts.breadcrumb')

<div class="card shadow-lg rounded-4 border-0">
	<div class="card-body">
<form action="" id="file_up" enctype="multipart/form-data">
    {{ csrf_field() }}
    <input type="hidden" name="edit_id" id="edit_id" value="">

    <div class="row justify-content-center mb-4">
        <div class="col-md-6">
            <label for="file_upload" class="form-label">
                <i class="fa fa-cloud-upload text-primary"></i> File Upload <span class="text-danger">*</span>
            </label>
            <input type="file" id="file_upload" name="file_upload" class="form-control" required>
            <span class="b_name text-muted mt-2 d-block"></span>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <button type="submit" id="save" class="btn btn-primary me-2 file_upload save_form">
                <i class="fa fa-upload"></i> Upload
            </button>
            <a href="{{ url('/download/employee_shift_details.csv') }}" class="btn btn-warning" download>
                <i class="fa fa-download"></i> Download Template
            </a>
        </div>
    </div>
</form>
</div>                   
 </div>   

 <div class="card shadow-lg rounded-4 border-0">
<div class="container mt-4">
  <table id="shiftTbl" class="table table-bordered table-striped w-100">
    <thead>
      <tr class="table-warning">
        <th>Employee  Name</th>
        <th>Shift Type</th>
        <th>Actions</th>
      </tr>
      <tr class="table-info">
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      {{-- DataTable will populate via AJAX --}}
    </tbody>
  </table>
</div>
</div>



@endsection
@push('scripts')


	<script>
		
	// tables data 	
		
	$(document).ready(function () {
  var table = $('#shiftTbl').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('employeeshiftdetailsgrid') }}",
    columns: [
      { data: 'first_name', name: 'first_name',width:'150px' },
      { data: 'shift_type', name: 'shift_type', width:'800px' },
    
     
      {
        data: 'shift_id',
        name: 'actions',
        orderable: false,
        searchable: false,
          render: function (data, type, row) {
              return `
                  <button class="btn btn-sm btn-warning me-1 view-btn" data-id="${data}">
                      <i class="bi bi-eye"></i>
                  </button>
                       <button class="btn btn-sm btn-primary me-1 edit-btn" data-id="${data}">
                      <i class="bi bi-pencil"></i>
                  </button>
                    <button class="btn btn-sm btn-danger me-1 delete-btn" data-id="${data}">
                      <i class="bi bi-trash"></i>
                  </button>`;
          }
          
      }
    ]
  });


  $('#shiftTbl thead').on('keyup change', '.column-search', function () {
    let index = $(this).closest('th').index();
    table.column(index).search(this.value).draw();
  });
});	
		
		
              // file choose
		$(document).on('change','.file_upload',function(){              
                var file = $(this).val();
                var file_name = $('.file_upload')[0].files[0].name;
                $('.b_name').html(file_name);
             
            });		
		
		
	            $(document).on('click','.file_choose',function(e)
            {
                $("#file_upload").trigger( "click" );
            });	
		
		
  // save
           $(document).on('click','.save_form',function()
            {
         
                var file_upload = $('#file_upload').val();

                if(file_upload != '')
                {

                    var form_data = new FormData(document.getElementById('file_up'));
                    $.ajax({
                          url: "{{URL::to('shiftuploadsave')}}",
                          type: "POST",
                          data: form_data,
                          enctype: 'multipart/form-data',
                          processData: false,  
                          contentType: false,   
                          async:true,
                          xhr: function(){
                              var xhr = $.ajaxSettings.xhr();
                            if (xhr.upload) 
                            {
                                xhr.upload.addEventListener('progress', function(event){
                                }, true);
                            }
                            return xhr;
                    }
                    }).done(function(data,status)
                        {
                            
                            if(data == 1)
                            {
                                
                                showCustomAlert('Details Uploaded  Successfully','success');
                                setTimeout(function(){
                                location.reload();
                                }, 2000);
                            }
                            else if(data == 2)
                            {
                                showCustomAlert('Not a Valid File Extension','error');
                                setTimeout(function(){
                                location.reload();
                                }, 2000);
                            }
                            else
                            {
                                $(".alert-success").hide();
                                $(".alert-danger").fadeIn(800);

                            }
                        }).fail(function(data,status)
                        {

                                $(".alert-success").hide();
                                $(".alert-danger").fadeIn(800);

                        });

                }
                else
                {
                     showCustomAlert('Choose a File','warning');
                }
            }); 		
	
		
	//edit function
    $(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');
    const url = "{{ url('employeeshiftedit') }}/" + id;
    window.location.href = url;
    });
	
	//view function
$(document).on('click', '.view-btn', function () {
  const id = $(this).data('id');
  const url = "{{ url('employeeshiftview') }}/" + id;
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
					url: "{{ url('shiftuploaddelete') }}/" + deleteId,
					type: "GET",
					success: function (response) {
						$('#globalDeleteModal').modal('hide');
						showCustomAlert('Deleted successfully!', 'success');
						$('#shiftTbl').DataTable().ajax.reload();

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
