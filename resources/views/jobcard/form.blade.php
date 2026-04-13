@extends('layouts.header')
@section('content')

<h3 class="text-danger">
	<?php if($row->reference_source=="PLAN") { ?>
	Create Job Card
	<?php } else { ?>
	Job Card Status
	<?php } ?>
</h3>
@include('layouts.breadcrumb')

<style>
.product_id,.uom_code_id,.organization_id,.job_created_by,.job_date {
	pointer-events:none;
	}
</style>


<div class="card shadow-lg rounded-4 border-0">
<div class="card-header bg-primary text-white fw-semibold"></div>
<div class="card-body card-block">
	
<form method="post" action="{{ url('jobcardsave') }}" id="jobcard" class="jobcard" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="save_status" id="save_status" value="">
    <input type="hidden" name="pgurl" id="pgurl" value="{{ $pgurl }}">
    <input type="hidden" name="reworksrc" id="reworksrc" value="{{ $reworksrc }}">

            <div class="row g-3">

                <!-- Job No -->
                <div class="col-md-4">
                    <label class="form-label">Job No</label>
                    			<input class="form-control w_jobs_hdr_id" id="w_jobs_hdr_id" name="w_jobs_hdr_id" size="16" type="hidden" value="{{ $row->w_jobs_hdr_id }}" readonly>
	        <input type="hidden" id="reference_source" name="reference_source" class="form-control reference_source" value="{{ $row->reference_source}}" readonly>
	        <input type="hidden" id="bom_process" name="bom_process" class="form-control bom_process" value="{{ $row->bom_process }}" readonly>
	        <input type="hidden" id="job_process" name="job_process" class="form-control job_process" value="{{ $row->job_process }}" readonly>
	        <input type="hidden" id="qa_submitstage_trx_hdr_id" name="qa_submitstage_trx_hdr_id" class="form-control qa_submitstage_trx_hdr_id" value="{{ $row->qa_submitstage_trx_hdr_id}}" readonly>
			
			 <input type="hidden" id="seq_count" name="seq_count" class="form-control seq_count" value="{{ $row->seq_count}}" readonly>
			
			<input type="hidden" id="reference_source_id" name="reference_source_id" class="form-control reference_source_id" value="{{ $row->reference_source_id}}" readonly>
                    <input type="text" class="form-control" id="job_no" name="job_no" value="{{ $row->job_no }}" readonly>
                </div>

                <!-- Job Date -->
                <div class="col-md-4">
                    <label class="form-label">Job Date</label>
                    <input type="text" class="form-control job_date" id="job_date" name="job_date" value="{{ $row->job_date }}" readonly>
                </div>

                <!-- Completion Date -->
                <div class="col-md-4">
                    <label class="form-label text-danger">* Job Completion Date</label>
                    <input type="text" class="form-control job_completion_date" id="job_completion_date" name="job_completion_date" value="{{ $row->job_completion_date }}" required>
                </div>

                <!-- Batch No -->
                <div class="col-md-4">
                    <label class="form-label">Batch No</label>
                    <input type="text" class="form-control batch_no" id="batch_no" name="batch_no" value="{{ $row->batch_no }}" required>
                </div>

                <!-- Job Status -->
                <div class="col-md-4">
                    <label class="form-label">Job Status</label>
                    <input type="text" class="form-control job_status" id="job_status" name="job_status" value="{{ $job_status }}" readonly>
                </div>

                <!-- Remarks -->
                <div class="col-md-4">
                    <label class="form-label">Remarks</label>
                    <input type="text" class="form-control remarks" name="remarks" value="{!! $row->remarks !!}">
                </div>

                <!-- Product -->
                <div class="col-md-4 none">
                    <label class="form-label text-danger">* Product</label>
                    <select name="product_id" id="product_id" class="form-select select2 product_id" required>
                        {!! $product_id !!}
                    </select>
                </div>

                <!-- UOM -->
                <div class="col-md-4 none">
                    <label class="form-label">UOM Code</label>
                    <select name="uom_code_id" id="uom_code_id" class="form-select select2 uom_code_id">
                        {!! $uom_code_id !!}
                    </select>
                </div>

                <!-- Job Qty -->
                <div class="col-md-4">
                    <label class="form-label">Job Qty</label>
                    <input type="text" class="form-control job_qty" name="job_qty" value="{!! $row->job_qty !!}" readonly>
                </div>

                <!-- Job Completed Qty -->
                @if($row->bom_process!="PROCESS-1" && $row->bom_process!="0")
                <div class="col-md-4">
                    <label class="form-label">Job Completed Qty</label>
                    <input type="text" class="form-control" value="{!! $row->job_completed_qty !!}" readonly>
                </div>
                @endif

                <!-- Process Completed Qty -->
                @if($row->process_completed_qty!="0")
                <div class="col-md-4">
                    <label class="form-label">Job Process Completed Qty</label>
                    <input type="text" class="form-control" value="{!! $row->process_completed_qty !!}" readonly>
                </div>
                @endif

                <!-- Adjusted Qty -->
                <div class="col-md-4">
                    <label class="form-label text-danger">* Job Adjusted Qty</label>
                    <input type="text" class="form-control job_adjusted_qty" name="job_adjusted_qty" value="{!! $row->job_adjusted_qty !!}" required>
                </div>

                <!-- Job Created By -->
                <div class="col-md-4">
                    <label class="form-label text-danger">* Job Created By</label>
                    <select name="job_created_by" class="form-select select2 job_created_by">
                        {!! $job_created_by !!}
                    </select>
                </div>

                <!-- Job Assigned To -->
                <div class="col-md-4">
                    <label class="form-label text-danger">* Job Assigned To</label>
                    <select multiple name="job_assigned_to[]" class="form-select select2 job_assigned_to" required>
                        {!! $job_assigned_to !!}
                    </select>
                </div>

                <!-- BOM Product -->
             
                <div class="col-md-4 bomreq">
                    <label class="form-label text-danger">* Bom Product</label>
                    <select name="bom_product_id" id="bom_product_id" class="form-select select2 bom_product_id">
                        {!! $bom_product_id !!}
                    </select>
                </div>
             

                <!-- Machine Name -->
                <div class="col-md-4">
                    <label class="form-label text-danger">* Machine Name</label>
                    <div class="input-group">
                        <select name="machine_hdr_id" class="form-select select2 machine_hdr_id" required>
                            {!! $machine_hdr_id !!}
                        </select>
                    </div>
                </div>

                <!-- Machine Capacity -->
                <div class="col-md-4">
                    <label class="form-label">Capacity</label>
                    <input type="text" class="form-control machine_capacity" name="machine_capacity" value="{!! $row->machine_capacity !!}" readonly>
                </div>

                <!-- Hour -->
                <div class="col-md-4">
                    <label class="form-label text-danger">* Hour</label>
                    <input type="text" class="form-control hour" name="hour" value="{!! $row->hour !!}" readonly required>
                </div>

                <!-- Packing Box Qty -->
                @if($row->bom_process=="FINALPROCESS")
                <div class="col-md-4">
                    <label class="form-label">Packing Box Qty</label>
                    <input type="text" class="form-control kitpack_no" name="kitpack_no" value="{!! $kitpack_no !!}" readonly>
                </div>
                @endif

            </div>

       <div class="col-12 mt-4 text-center">
            <button type="button" class="btn btn-success px-4 me-2 saveform">Submit</button>
            <a href="{{ URL::to($pgurl) }}" class="btn btn-secondary px-4">Cancel</a>
        </div>

</form>
</div>
</div>


<!-- purpse:save modal-->
<div class="modal fade" id="savedetailsModal" tabindex="-1" aria-labelledby="savedetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md"> <!-- centered & medium size -->
    <div class="modal-content shadow-lg rounded-3">
      
      <!-- Modal Header -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="savedetailsModalLabel">Save Confirmation</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        <input type="hidden" class="soindex" value="">
      </div>
      
      <!-- Modal Body -->
      <div class="modal-body text-center">
        <p class="fs-5 mb-0">
          After submit, you <strong>cannot change Job Qty.</strong><br><br>
          Do you want to save?
        </p>
      </div>
      
      <!-- Modal Footer -->
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-success px-4 savedata" id="savedata">Confirm</button>
        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal" id="canceldata">Cancel</button>
      </div>

    </div>
  </div>
</div>

<!--end-->


@endsection
@push('scripts')

<script>
	
$(document).ready(function(){
	<?php if($row->bom_process=="PROCESS-1"){ ?>
	$('.bom_product_id').attr('required',true);
        <?php } ?>
            <?php if($noprd==1){ ?>
        $('.bom_product_id').attr('required',false);
        <?php }else if ($_GET['jobtype']=="packingjobcard"){  ?>
        $('.bomreq').show();
    <?php } else{   ?>
        $('.bomreq').hide();
    <?php }  ?>
/*  purpose:no validation for job adjusted qty*/
	$(document).on('keypress','.job_adjusted_qty', function(ev){
			var regex = new RegExp("^[0-9.]+$");
					var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
					if (regex.test(str)) {
						return true;
					}
					ev.preventDefault();
					return false;
		});

	/* purpose: to show product in tool tip*/
	$(".ui-tooltip-content").data($("#product_id").data("ui-tooltip-title"));

	
/*  purpose: to check hour assigned for this product based on job qty or job adjusted qty*/	
	var hour=$('.hour').val();
	if(hour==0){
		$('.hour').val("");
	   $('.hourdsc').html("<br><br>Please add hour in Machine Capacity based on qty range.");
	   }else{
		 $('.hourdsc').html("");   
	   }

/* purpose: to set read only field based on page*/	
var pagemode="<?php echo $pagemode; ?>";
	if(pagemode=="edit"){
	   $('.job_adjusted_qty').attr('readonly',true);
	   }else{
		  $('.job_adjusted_qty').attr('readonly',false);  
	   }

/* purpose: to call save function & validate form */	
$('#save_status').val('');

$(document).on('click', '.saveform', function() {

    var btnval = $(this).val();
    var url       = "{{ URL::to('jobcardsave') }}";
    var red_url   = "{{ URL::to($pgurl) }}";
    var create_url = "{{ URL::to('jobcardcreate') }}/0";
    var job_adjusted_qty = $('.job_adjusted_qty').val();

    if (job_adjusted_qty == 0) {
        $('.job_adjusted_qty').val('');
    }

    var form = $('#jobcard');
    form.parsley().validate();

    if (form.parsley().isValid()) {
        var pagemode = "<?php echo $pagemode; ?>";
   
        if (pagemode == "create") {
            $('#savedetailsModal').modal('show');

            $(document).on('click', '.savedata', function() {
				
				    var $btn = $(this);             // Store the button reference
					$btn.prop('disabled', true);
				
                var formdata = $('#jobcard').serialize();
                $.post(url, formdata, function(data) {
                    var status   = data.status;
                    var msg      = data.message;
                    var id       = data.id;
                    var edit_url = "{{ URL::to('jobcardcreate') }}/" + id;

                    showCustomAlert(msg, status);
                    setTimeout(function() {
                        window.location.href = red_url;
                    }, 1500);
                });
            });

        } else {
            var formdata = $('#jobcard').serialize();
			     var $btn = $(this);             // Store the button reference
				$btn.prop('disabled', true);
            $.post(url, formdata, function(data) {
                var status   = data.status;
                var msg      = data.message;
                var id       = data.id;
                var edit_url = "{{ URL::to('jobcardcreate') }}/" + id;

                showCustomAlert(msg, status);
                setTimeout(function() {
                    window.location.href = red_url;
                }, 1500);
            });
        }
    }
});

	
$(document).on('change','.bom_product_id',function(){
 var bom_product= $(".bom_product_id option:selected").text();
  //var bom_product=$(this).val();
  var aa=bom_product.split('-');
  $('.batch_no').val(aa[0]);

});
	
/* purpose: when cancel in save popup model hide*/
$(document).on('click','#canceldata',function()
    {
	$('#savedetailsModal').modal('hide');
});

/* purpose: get uom based on product*/
$(document).on('change','.product_id',function(){
var pid=$(this).val();
var url="{{ URL::to('productuomdetails')}}/";
	$.get(url+pid,function(data){
		data=$.trim(data);
		$('.uom_code_id').val(data);
	});
});

/* purpose:qty validation*/
	
$('.job_adjusted_qty').change(function(){
	var jobadjqty=parseInt($(this).val());
	var capacity=parseInt($('.machine_capacity').val());
		var capqty="<?php echo $quantity_capacity; ?>";
		var pgurl="<?php echo $pgurl; ?>";
		var process=$('.bom_process').val();
		var jobcompqty=$('.job_completed_qty').val();
		var jobqty=$('.job_qty').val();
	if(pgurl=='jobcard'){
	if(jobadjqty>capacity){
	   showCustomAlert("Job adjusted Qty Should not exceed Machine Capacity Qty",'error');
		$('.job_adjusted_qty').val('');
	   }
	
		<?php if(($row->reference_source!="REWORK") && ($row->reference_source!="RETURNREWORK")) {  ?>
	
		if(jobadjqty>capqty){
			 showCustomAlert("Job adjusted Qty Should not exceed  Qty Capacity ",'error');
		$('.job_adjusted_qty').val('');	
				}
		<?php } ?>
				}else{
					
	<?php if($row->reference_source!="REWORK" && $row->reference_source!="RETURNREWORK") {  ?>
	 var jqty=(jobqty*0.5)/100;
	         var ejqty=parseFloat(jqty)+parseFloat(jobqty);

					<?php  if($row->process_completed_qty==0){ ?>
					if(jobadjqty>ejqty){
			 showCustomAlert("Job adjusted Qty Should not exceed Job Qty",'error');
		$('.job_adjusted_qty').val('');	
				}
				<?php } ?>

		<?php } ?>
			
					
				}
	
/* purpose: to get hour based on machine,product & job adjusted qty*/
var machineid=$('.machine_hdr_id').select2('val');
	var productid=$('.product_id').val();
	if(machineid!=""){
	var url="{{URL::to('machinehourdetails')}}/"+machineid+"/"+productid+"/"+jobadjqty;
	   
	$.get(url,function(data){
		var data=$.trim(data);
		if(data!=0){
		$('.hour').val(data);
		}else{
		$('.hour').val('');	
		}
	});
}

});

/* purpose:Based on Machine capacity,resource should be load*/	
	$('.machine_hdr_id').change(function()
	{
		var mid=$('.machine_hdr_id').val();
		var pid=$('.product_id').val();
		var jobcldate=$('.job_completion_date').val();
		
		if(mid!=""){
			
		if(jobcldate!=""){
		
		var url="{{ URL::to('machinedetails')}}/";
		$.get(url+mid+"?prdid="+pid,function(data){
			var dataarray=data[0].assigned_to.split(",");
		$('.machine_capacity').val(data[0].machine_capacity);
		$('.job_assigned_to').val(dataarray);
		$('.job_assigned_to').trigger('change.select2');
		$('.job_adjusted_qty').trigger("change");	
		});
			var url1="{{ URL::to('machinepmdetails')}}/"+mid+"?jobcldate="+jobcldate;
			$.get(url1,function(data1){
			var data1=$.trim(data1);
            if(data1==1){
            $('.saveform').attr('disabled',true);
            	showCustomAlert('Machine Not Available','info');
            }else{
            $('.saveform').attr('disabled',false);
            }	
			});
	}else{
		showCustomAlert('Please Select job completion date','warning');
		$('.machine_hdr_id').select2('val',[''])
	}
	}
	});
	

/* purpose:start date&end date validation*/
	     var dateToday = new Date();
    var dates=$('#start_date,#end_date').datepicker({defaultDate: "today",dateFormat: "dd/mm/yy",
    changeMonth: true,
    numberOfMonths: 1,
    minDate: dateToday,
    onSelect: function(selectedDate) {
        var option = this.id == "start_date" ? "minDate" : "maxDate",
            instance = $(this).data("datepicker"),

            date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
        dates.not(this).datepicker("option", option, date);
    }
    });

});


        $(document).on("focus", ".job_completion_date", function () {

        $(this).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            minDate: 0, 
            showAnim: "slideDown",
            yearRange: "-25:+0",

        });
    });
	
</script>


@endpush
