@extends('layouts.header')
@section('content')
<h3 class="text-danger">Approve Seperation</h3>
@include('layouts.breadcrumb')

    
<div class="card shadow-lg border-0 rounded-4">
    <div class="card-body card-block">
        <form action="" id="save">
            {{ csrf_field() }}

            <div class="row g-4">
                <!-- Column 1 -->
                <div class="col-md-4">
                    <!-- Employee Name -->
                    <div class="form-group row mb-3">
                        <label class="form-control-label col-md-5">
                            <span class="req">*</span>Employee Name
                        </label>
                        <input type="hidden" name="relieve_id" value="{{$relieve_id}}">
                        <div class="col-md-7" style="pointer-events: none;">
                            <select name="employee_id" id="employee_id" class="form-control select2">
                                {!! $employee !!}
                            </select>
                        </div>
                    </div>

                    <!-- Notice Period -->
                    <div class="form-group row mb-3">
                        <label class="form-control-label col-md-5">
                            <span class="req">*</span>Notice Period
                        </label>
                        <div class="col-md-7" style="pointer-events:none;">
                            <select name="notice_period" id="notice_period" class="select2 form-control">
                                {!! $notice_period !!}
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Column 2 -->
                <div class="col-md-4">
                    <!-- Releive Date -->
                    <div class="form-group row mb-3" style="pointer-events:none;">
                        <label class="form-control-label col-md-5">
                            <span class="req">*</span>Releive Date
                        </label>
                        <div class="col-md-7">
                            <div class="input-group form_date" data-date="" data-link-format="yyyy-mm-dd">
                                <input class="form-control" id="releive_date" name="releive_date" type="text" value="{{$leaving_date}}">

                            </div>
                        </div>
                    </div>

                    <!-- Reporting -->
                    <div class="form-group row mb-3">
                        <label class="form-control-label col-md-5">
                            <span class="req">*</span>Reporting
                        </label>
                        <div class="col-md-7" style="pointer-events: none;">
                            <select name="reporting_id" id="reporting_id" class="form-control select2">
                                {!! $reporting !!}
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Column 3 -->
                <div class="col-md-4">
                    <!-- Releive Reason -->
                    <div class="form-group mb-3">
                        <label class="form-control-label col-md-5">
                            <span class="req">*</span>Releive Reason
                        </label>
                        <div class="col-md-7">
                            <textarea readonly name="releive_reason" style="height:50px;" id="releive_reason" class="form-control">{{$description}}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="row mt-4">
                <div class="col-md-12 text-center mt-4">
                    <button type="button" class="btn btn-success save_form m-2">
                        <i class="bi bi-check-circle"></i> Approve
                    </button>
                    <a href="{{ url('separationapproval') }}"><button  type="button" class="btn btn-secondary ">
                        <i class="bi bi-x-circle"></i> Cancel
						</button></a>
                </div>
            </div>
        </form>
    </div>
</div>






@endsection
@push('scripts')


	<script>
	
// save
		
            $(document).on('click','.save_form',function(){
                    var form = $('#save');
                    form.parsley().validate();
                    if(form.parsley().isValid())
                    {	
                           
                            var data = $("#save").serialize();
                            var url = "{{URL::to('/releiveprocessupdate/')}}";
                           
                            $.post(url, data, function(data)
                            {
                                if(data == 1)
                                {		
                                    showCustomAlert('Seperation Request Approved Successfully','success');
                                    setTimeout(function(){
                                           var url = "{{URL::to('separationapproval')}}";
                                           window.location.href=url;
                                    }, 100);
                                } 
                            });
                    }
            });

	</script>

@endpush
