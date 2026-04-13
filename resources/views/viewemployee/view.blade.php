@extends('layouts.header')
@section('content')
<h3 class="text-danger">Employee Details</h3>
@include('layouts.breadcrumb')

<form>
    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body">

			<div class="row mb-4 align-items-center mt-2">
                    <div class="col-md-6">
                        <input type="text" name="first_keyword" id="first_keyword" placeholder="Search....." class="form-control col-md-3">
                    </div>
                
                    <div class="col-md-3">
                        <button type="button" name="search" class=" btn btn-primary searched px-4" id="search" style="margin: -1%" > <i class="bi bi-search"></i> Search</button>
                    </div>
			</div>

            @foreach($employee_list as $key => $value)
                @foreach($value as $k => $v)
                    <div class="card mb-4 shadow-lg rounded-4 border-0">
                        <div class="card-body">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-3 text-center">
                                    @php
                                        $image = $v->photo != "" ? $v->photo : "profile_none.jpg";
                                    @endphp
                                    <img src="{{ asset('/images/profile_images/' . $image) }}" class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">

                                    <div class="mt-2">
                                        <span class="badge 
                                            @if(strtoupper($v->active) == 'YES') bg-success
                                            @elseif(strtoupper($v->active) == 'NO') bg-danger
                                            @endif">
                                            {{ $v->employee_number }}
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-9">
                                    <h4 class="text-primary fw-bold">{{ $v->first_name }}</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Email:</strong> {{ $v->email }}</p>
                                            <p><strong>Mobile:</strong> {{ $v->work_telephone_number }}</p>
                                            <p><strong>Employment Type:</strong> {{ $v->lookup_code }}</p>
                                            <p><strong>Date of Birth:</strong> {{ $v->date_of_birth }}</p>
                                            <p><strong>Active:</strong> 
                                                <span class="badge 
                                                    @if(strtoupper($v->active) == 'YES') bg-success
                                                    @elseif(strtoupper($v->active) == 'NO') bg-danger
                                                    @endif">
                                                    {{ $v->active }}
                                                </span>
                                            </p>
                                            <p><strong>Company:</strong> {{ $v->company_name }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Date of Joining:</strong> {{ $v->date_of_joining }}</p>
                                            <p><strong>Position:</strong> {{ strtoupper($v->job_title_name) }}</p>
                                            <p><strong>Grade:</strong> {{ strtoupper($v->position) }}</p>
                                            <p><strong>Area:</strong> {{ strtoupper($v->area_name) }}</p>
                                            <p><strong>Emp. Service Status:</strong> 
                                                <span class="badge 
                                                    @if(strtoupper($v->ff_status) == 'IN SERVICE') bg-success
                                                    @elseif(strtoupper($v->ff_status) == 'RESIGNED') bg-danger
                                                    @elseif(strtoupper($v->ff_status) == 'RESIGNED - F & F PENDING') bg-warning text-dark
                                                    @endif">
                                                    {{ strtoupper($v->ff_status) }}
                                                </span>
                                            </p>
                                            @if($v->active == "No")
                                                <p><strong>Date Of Leaving:</strong> {{ $v->date_of_leaving }}</p>
                                            @endif
                                            <p><strong>Biometric Empno:</strong> {{ $v->biometric_empno }}</p>
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <button type="button" data-action="view" class="btn btn-warning px-4 me-2 action" id="{{ $v->employee_id }}">View</button>

                                        @if($emp_id != '600')
                                            <button type="button" data-action="edit" class="btn btn-primary px-4 me-2 action" id="{{ $v->employee_id }}">Edit</button>
                                            <button type="button" data-action="delete" class="btn btn-danger px-4 action" id="{{ $v->employee_id }}">Delete</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach

        </div>
    </div>
</form>


@endsection
@push('scripts')

<script>


 $(document).on('click','.searched',function()
     {
        var firstkeyword = $('#first_keyword').val();
        var secondkeyword = $('#second_keyword').val();
        var thirdthird = $('#third_third').select2('val');

     
        if(thirdthird == '')
            thirdthird = 0;
        else
            thirdthird =thirdthird;

        var url = "{{url('viewprofile')}}?searchdata="+firstkeyword;
       
        window.location.href = url;

     });

/* End  */

    $(document).on('click','.action',function()
    {
        var view_id = $(this).attr('id');
        var action  = $(this).attr('data-action');
        
        if(action == 'view')
        {
            var url = "{{url('profileview')}}/view_profile/"+view_id;
            window.location.href=url;
        }
        else if(action == 'edit')
        {
            var url = "{{url('editprofile')}}/"+view_id;
            window.location.href=url;
        }
        else if(action == 'delete')
        {
			
			$('#globalDeleteModal').modal('show'); 
        }
    });

		$('#globalConfirmDeleteBtn').on('click', function () {
			
	    var deleteId = $(this).attr('id');
		if (deleteId) {
				$.ajax({
	
					url: "{{ url('viewprofile') }}/" + deleteId,
					type: "GET",
					success: function (response) {
						$('#globalDeleteModal').modal('hide');
						showCustomAlert('Deleted successfully!', 'success');
						$('#PosTbl').DataTable().ajax.reload();

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
