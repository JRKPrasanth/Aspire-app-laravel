@extends('layouts.header')
@section('content')
<h3 class="text-danger">PM Clearance</h3>
@include('layouts.breadcrumb')
	
<form method="post" action="" id="pmclearance" data-parsley-validate>
  {{ csrf_field() }}
  <input type="hidden" name="savestatus" id="savestatus" value="">
  <input type="hidden" name="initiate_pm_id" id="initiate_pm_id" class="form-control initiate_pm_id" value="{{ $initiate_pm_id }}" readonly>
  <input type="hidden" name="status" class="status">
  <input type="hidden" name="postpone_status" class="postpone_status">

  
<div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">

      <div class="row g-4">

        <!-- Left Column -->
        <div class="col-md-6">

          <div class="mb-3 row none">
            <label for="pm_no" class="col-md-4 col-form-label">PM No</label>
            <div class="col-md-8">
              <input type="text" id="pm_no" name="pm_no" class="form-control pm_no" value="{{ $pm_no }}">
              <span class="btn btn-danger dup_name d-none"></span>
            </div>
          </div>

          <div class="mb-3 row none">
            <label for="machine_id" class="col-md-4 col-form-label">Machine Name</label>
            <div class="col-md-8">
              <select name="machine_id" id="machine_id" class="form-select select2 machine_id">
                {!! $machine_id !!}
              </select>
            </div>
          </div>

          <div class="mb-3 row none">
            <label for="user_clearance_by" class="col-md-4 col-form-label">User Clearance By</label>
            <div class="col-md-8">
              <select name="user_clearance_by[]" id="user_clearance_by" class="form-select select2 user_clearance_by" multiple>
                {!! $user_clearance_by !!}
              </select>
            </div>
          </div>

          <div class="mb-3 row clearance_date none">
            <label for="postponed_date" class="col-md-4 col-form-label"><span class="text-danger">*</span>Postpone To</label>
            <div class="col-md-8">
              <input type="text" name="postponed_date" id="postponed_date" class="form-control postponed_date datepicker" value="">
            </div>
          </div>

          <div class="mb-3 row shift_timings">
            <label for="shift_timing" class="col-md-4 col-form-label"><span class="text-danger">*</span>Shift Timing</label>
            <div class="col-md-8">
              <input type="time" name="shift_timing" id="shift_timing" data-link-format="yyyy-mm-dd" class="form-control shift_timing timepicker" value="">
            </div>
          </div>

        </div>

        <!-- Right Column -->
        <div class="col-md-6">

          <div class="mb-3 row none">
            <label for="department_id" class="col-md-4 col-form-label">Department Name</label>
            <div class="col-md-8">
              <select name="department_id" id="department_id" class="form-select select2 department_id">
                {!! $department_id !!}
              </select>
            </div>
          </div>

          <div class="mb-3 row">
            <label for="actual_pm_date" class="col-md-4 col-form-label">Initiated Date</label>
            <div class="col-md-8">
              <input type="text" name="actual_pm_date" id="actual_pm_date" class="form-control actual_pm_date datepicker" value="{{ $initiate_date }}" readonly>
            </div>
          </div>

          <div class="mb-3 row">
            <label for="change_date" class="col-md-4 col-form-label"><span class="text-danger">*</span>Change Initiated Date</label>
            <div class="col-md-8">
              <select name="change_date" class="form-select select2 change_date file" required>
                <option value="0">Please Select</option>
                <option value="1">Yes</option>
                <option value="2">No</option>
              </select>
            </div>
          </div>

        </div>
      </div>

      <!-- Buttons -->
      <div class="row mt-4">
        <div class="col text-center">
          <button type="button" class="btn btn-success save_btn saveform px-4 me-2"></i> Accept</button>
          <button type="button" class="btn btn-warning postpone_btn saveform px-4 me-2"></i> Postpone</button>
          <a href="{{ URL::to('pmclearance') }}" class="btn btn-secondary px-4 me-2">Cancel</a>
        </div>
      </div>

    </div>
  </div>
</form>



@endsection
@push('scripts')

<script>
	
 $(document).ready(function()
 {
     
     $(".clearance_date,.shift_timings").hide();
     $(document).on("change",".change_date",function(){ 
    if(($(".change_date").select2("val"))=="1") {
        $(".save_btn").prop("disabled",true);
        $(".postpone_btn").prop("disabled",false);
        $(".clearance_date").show();
        $(".shift_timings").hide();
        $(".postponed_date").val("");
        $(".postponed_date").attr('required', 'required');
        $(".shift_timing").removeAttr('required', false);
    }
    else{
        
       $(".clearance_date").hide(); 
       $(".shift_timings").show();
       $(".save_btn").prop("disabled",false);
       $(".postpone_btn").prop("disabled",true);
       $(".shift_timing").val("");
       $(".shift_timing").attr('required', 'required');
       $(".postponed_date").removeAttr('required', false);
    }
});	
	$(".postpone_btn,.save_btn").prop("disabled",true);
	 
	 $(".postpone_btn").click(function()
			{
				$(".status").val("0");
				$(".postpone_status").val("1");
			});
        
        $(".save_btn").click(function()
        {
           
            $(".status").val("1");
			$(".postpone_status").val("0");
        });
/* purpose:to save function*/
		$('#savestatus').val('');
		$(document).on('click','.saveform',function()
		{      
                      
				var btnval		= $(this).val();
				if(btnval == 'APPLYCHANGES')
					var savestatus = 'APPLY CHANGES';
				else if(btnval == 'SAVE' || btnval == 'SAVENEW')
					var savestatus = 'SAVE';

				$('#savestatus').val(savestatus);
				var url		= "{{ URL::to('pmclearancesave') }}";
				var red_url		="{{ URL::to('pmclearance') }}";
				var create_url	="{{ URL::to('pmclearance') }}";
				var formdata	= $('#pmclearance').serialize();
				var form = $('#pmclearance');
				if(btnval != 'APPLYCHANGES')
				{
					form.parsley().validate();
					var form = $('#pmclearance');
					form.parsley().validate();
					if (form.parsley().isValid())
					{	
								var $btn = $(this);            
			          $btn.prop('disabled', true);
						$.post(url,formdata,function(data)
						{
							var status      = data.status;
							var msg     = '<span style="color:#090065">'+data.message+'</span>  ';
							var id          = data.id;
//							var edit_url	= "{{ URL::to('agencyedit') }}/"+id;
							if(btnval !='SAVE' && btnval !='DRAFT')
							{

								showCustomAlert('Saved successfully!', 'success');
								setTimeout(function(){
								window.location.href=create_url;
								}, 1500);
							}
							else
							{
								
								showCustomAlert(msg,status);
								setTimeout(function(){
								window.location.href=red_url;
								}, 1500);
							}
						});
					return false;
					}
				}
		});
    /*end*/


		});
	
</script>

@endpush