@extends('layouts.header')
@section('content')
<style>
 @media  only screen and (min-width: 1500px) {

.bulk_line_no{width: 100px !important;}
.bulk_product_id{width: 250px !important;}
.bulk_uom_code_id{width: 250px !important;}
.bulk_comments{width: 400px !important;}
 }

.bulk_line_no{width: 100px;}
.bulk_product_id{width: 200px;}
.bulk_product_description{width: 150px;}
.bulk_uom_code_id{width: 200px;}
.bulk_comments{width: 250px;}
	.jobstatus{
	pointer-events:none;	
	}
</style>
<span class="ui_close_btn"></span>

<h2 class="heads">Job Card
<span class="ui_close_btn"><a href="{{ URL::to('filling') }}" class="collapse-close pull-right btn-danger" ></a></span>

</h2>

<div class="card">




<div class="card-body card-block">
<form method="post"  id="jobcard" class="jobcard"  enctype="multipart/form-data" data-parsley-validate>
	<input type="hidden" value="" name="save_status" id="save_status" />
	 {{ csrf_field()}}
	<div class="col-md-4">
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Job No</label>
		<div class="col-md-6">

			<input class="form-control w_jobs_hdr_id" id="w_jobs_hdr_id" name="w_jobs_hdr_id" size="16" type="hidden" value="{{ $row->w_jobs_hdr_id }}" readonly>
	        <input type="hidden" id="reference_source" name="reference_source" class="form-control reference_source" value="{{ $row->reference_source}}" readonly>
			
			 <input type="hidden" id="seq_count" name="seq_count" class="form-control seq_count" value="{{ $row->seq_count}}" readonly>
			
			<input type="hidden" id="reference_source_id" name="reference_source_id" class="form-control reference_source_id" value="{{ $row->reference_source_id}}" readonly>
			<input type="text" id="job_no" name="job_no" class="form-control job_no" value="{{ $row->job_no}}" readonly>
		</div>
		</div>


	   <div class="form-group row">
        <label for="start_date" class="form-control-label col-md-4">Job Date</label>
        <div class="col-md-6">
        <div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
			<input class="form-control job_date" id="job_date" name="job_date" size="16" type="text" value="{{ $row->job_date }}" readonly="true">

			</div>

        </div>
      </div>

  <div class="form-group row">
        <label for="start_date" class="form-control-label col-md-4">Job Completion Date</label>
        <div class="col-md-6">
        <div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
        <input class="form-control job_completion_date datepicker" id="job_completion_date" name="job_completion_date" size="16" type="text" value="{{ $row->job_completion_date }}"  >
        <!-- <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> -->
        </div>

        </div>
      </div>
		<div class="form-group row">
        <label for="Jobstatus" class="form-control-label col-md-4">Batch No</label>
        <div class="col-md-6">

        <input type="text" name="batch_no" id="batch_no" class="form-control batch_no "  
          value="{{ $row->batch_no }} " readonly="true"/>
        </div>
      </div>

<div class="form-group row">
        <label for="Jobstatus" class="form-control-label col-md-4">Job Status</label>
        <div class="col-md-6 jobstatus">
  <select name='job_status' rows='5' class='select2 job_status'  data-show-subtext="true" data-live-search="true" readonly>
           <option value="">--select--</option>
           <option <?php if($row->job_status =="JOBCARD FILLING PROCESS") { echo "selected"; } else { echo ""; } ?>value="JOBCARD FILLING PROCESS">JOBCARD FILLING PROCESS</option>
 <option <?php if($row->job_status =="JOBCARD PACKING") { echo "selected"; } else { echo ""; } ?> value="JOBCARD PACKING">JOBCARD PACKING</option>
 <option <?php if($row->job_status =="JOBCARD LABEL PRINTING") { echo "selected"; } else { echo ""; } ?> value="JOBCARD LABEL PRINTING">JOBCARD LABEL PRINTING</option>

          </select>
       
        </div>
      </div>
	</div>

<div class="col-md-4">
     <div class="form-group row" style="display:none;">
        <label for="Product" class="form-control-label col-md-4"><span style="color:red;">*</span> Product</label>
        <div class="col-md-6">

        <select name="product_id" id="product_id" class="form-control product_id " required="required" style="width: 100%;">  {!! $product_id !!}</select>
			<input type="text" name="job_qty" class="form-control input-sm job_qty input_qty_width" value="{!! $row->job_qty !!}" minlength="1" readonly="true" >
        </div>
      </div>
 <div class="form-group row">
        <label for="job_created_by" class="form-control-label col-md-4"><span style="color:red;">*</span> Job Created By</label>
        <div class="col-md-6">
       <select name='job_created_by' rows='5' class='form-control job_created_by'  data-show-subtext="true" data-live-search="true">
            {!! $job_created_by !!}

          </select>
        </div>

       </div>
  <div class="form-group row">
        <label for="start_date" class="form-control-label col-md-4">Start Date</label>
        <div class="col-md-6">
        <div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
        <input class="form-control start_date datepicker" id="start_date" name="start_date" size="16" type="text" value="{{ $row->start_date }}"  >
        <!-- <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> -->
        </div>

        </div>
      </div>

      <div class="form-group row">
          <label for="end_date" class="form-control-label col-md-4">End Date</label>
          <div class="col-md-6">
          <div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
          <input class="form-control end_date datepicker" id="end_date" name="end_date" size="16" type="text" value="{{ $row->end_date }}"  >
          <!-- <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> -->
          </div>

          </div>
        </div>
	<div class="form-group row">
        <label for="organization_id" class="form-control-label col-md-4"><span style="color:red;">*</span>Process</label>
        <div class="col-md-6">

          <select name='job_process' rows='5' class='select2 job_process'  data-show-subtext="true" data-live-search="true"  required readonly>
           <option value="">--select--</option>
           <option <?php if($row->job_process =="FILLING") { echo "selected"; } else { echo ""; } ?>value="FILLING">FILLING</option>
 <option <?php if($row->job_process =="PACKING") { echo "selected"; } else { echo ""; } ?> value="PACKING">PACKING</option>
 <option <?php if($row->job_process =="LABEL PRINTING") { echo "selected"; } else { echo ""; } ?> value="LABEL PRINTING">LABEL PRINTING</option>

          </select>
        </div>
      </div>
	 <div class="form-group row">
        <label for="organization_id" class="form-control-label col-md-4">Organization</label>
        <div class="col-md-6">

          <select name='organization_id' rows='5' class='form-control organization_id'  data-show-subtext="true" data-live-search="true"  required readonly>
            {!! $organization_id !!}

          </select>
        </div>
      </div>  

</div>
	<div class="col-md-4">
	<div class="form-group row">
        <label for="machine_hdr_id" class="form-control-label col-md-4"><span style="color:red;">*</span> Machine Name</label>
        <div class="col-md-6">
       <select name='machine_hdr_id' rows='5' class='form-control select2 machine_hdr_id'  data-show-subtext="true" data-live-search="true" required>
            {!! $machine_hdr_id !!}

          </select>
        </div>

      </div>
  	
		 <div class="form-group row">
        <label for="Required Qty" class="form-control-label col-md-4"> Capacity</label>
        <div class="col-md-6">
          <input type="text" name="machine_capacity" class="form-control input-sm machine_capacity input_qty_width" value="{!! $row->machine_capacity !!}" minlength="1" readonly="true">
        </div>
      </div>
		
 <div class="form-group row">
        <label for="job_assigned_to" class="form-control-label col-md-4"><span style="color:red;">*</span> Job Assigned To</label>
        <div class="col-md-6">
       <select multiple="multiple" name='job_assigned_to[]' rows='5' class='form-control select2 job_assigned_to'  data-show-subtext="true" data-live-search="true" required>
            {!! $job_assigned_to !!}

          </select>
        </div>

      </div>
  <?php if($row->reference_source=="REWORK"){ ?>
		 <div class="form-group row">
        <label for="material_issue_req" class="form-control-label col-md-4"><span style="color:red;">*</span> Material Issue Required</label>
        <div class="col-md-6">
       <select name='material_issue_req' rows='5' class='form-control select2 material_issue_req'  data-show-subtext="true" data-live-search="true">
           <option value="">--Please Select--</option>
           <option value="Yes" <?php if($row->material_issue_req =='Yes'){ echo "selected";  }?> >Yes</option>
                                            <option value="No" <?php if($row->material_issue_req =='No'){ echo "selected";  }?> >No</option>

          </select>
        </div>

      </div>
	<?php } ?>	
      <div class="form-group row">
  			<label for="inputIsValid" class="form-control-label col-md-4">Remarks</label>
  			<div class="col-md-6">
  				<input type="text" name="remarks" class="form-control remarks" value="{!! $row->remarks !!}" style="width:100%"/>
  			</div>
  		</div>

		</div>
	

  <div id="preview-area" class="chandru">
    <table class="overflow-y preview fill_table">
      <thead>
        <tr>
          <th>Line No</th>  
          <th>Product Name</th>  
          <th>UOM Code</th>  
          <th>Job Qty</th>  
          <th>Comments</th>  
        </tr>
      </thead>
      <tbody class="fill_tablebody">
        <?php if(count($linedata)>=1) { ?>
        @foreach($linedata as $key=>$value)
          <tr>
            <td></td>
            <td>
				<input type="hidden" name="bulk_fillings_hdr_id[]" class="form-control bulk_fillings_hdr_id" value="">
				<input type="hidden" name="bulk_fillings_lines_id[]" class="form-control bulk_fillings_lines_id" value="">
				<input type="hidden" name="bulk_reference_source[]" class="form-control bulk_reference_source" value="{{$value->reference_source}}">
				<input type="hidden" name="bulk_total_qty[]" class="form-control bulk_total_qty" value="{{ $value->total_qty }}">
				<input type="hidden" name="bulk_reference_hdr_id[]" class="form-control bulk_reference_hdr_id" value="{{$value->reference_hdr_id}}">
				<input type="hidden" name="bulk_reference_line_id[]" class="form-control bulk_reference_line_id" value="{{$value->reference_line_id}}">
              <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" readonly value="{{$value->line_no}}">
            </td>
            <td>
              <select name="bulk_product_id[]" class="form-control select2 bulk_product_id"  >
                {!! $value->product_id !!}
              </select>
            </td>
            <td>
              <select name="bulk_uom_code_id[]" class="form-control select2 bulk_uom_code_id" >
                  {!! $value->uom_code_id !!}
              </select>
            </td>
			  <td>
              <input type="text" name="bulk_qty[]" class="form-control input-sm  bulk_qty" value="{{$value->qty}}">
            </td>
            <td>
              <input type="text" name="bulk_comments[]" class="form-control input-sm  bulk_comments" value="{{$value->comments}}">
				<input type="hidden" name="counter[]">
            </td>
          </tr>
        @endforeach
        <?php }?> 
      </tbody>
    </table>
  </div>


	<div class="row">
		<div class="col-lg-12 col-md-12">
			<div class="form-group text-center">
				<button type="button" class="btn save saveform" value = "save" >Save</button>
				<a href="{{ URL::to('filling') }}" class='btn cancel'>Cancel</a>

			</div>
		</div>
	</div>

</form>


</div>
</div>
</div>
</div>




<style>
.product_id,.uom_code_id,.organization_id,.job_created_by,.job_date{
	pointer-events:none;
	}
</style>
<script>

$(document).ready(function(){
var index = $('.clone').closest('tr').index();
changeclassfields();
	
	  $('.add_row').click(function() {
			var cloned = $('.fill_table').find('tr:eq(1)').clone();
cloned.find("input[type=text],input[type=hidden], textarea,input[type=date],select").val("");
cloned.appendTo('.fill_tablebody');
			
            changeclassfields();
        });
$('.job_process').change(function(){
	var jobprocess=$(this).val();
	if(jobprocess=="FILLING"){
	   $('.job_status').select2('val',['JOBCARD FILLING PROCESS']);
	   }else if(jobprocess=="PACKING"){
		$('.job_status').select2('val',['JOBCARD PACKING']);
	   }else{
		$('.job_status').select2('val',['JOBCARD LABEL PRINTING']);		
	 }
	});

	$('#save_status').val('');

	 $(document).on('click','.saveform',function()
    {
            var btnval		= $(this).val();

            var url		= "{{ URL::to('fillingsave') }}";
            var red_url		="{{ URL::to('filling') }}";
            var create_url	="{{ URL::to('fillingcreate') }}/0";
            validationrule('jobcard');
            
            var form = $('#jobcard');
            form.parsley().validate();
		    var formdata	= $('#jobcard').serialize();
		   
                if (form.parsley().isValid())
                {
					 change_date();
                    $.post(url,formdata,function(data)
                    {
                        var status      = data.status;
                        var msg     = '<span style="color:#090065">'+data.jobno+'</span>  '+data.message;
                        var id          = data.id;
                   
                       
                            notyMsg(status,msg);
                            setTimeout(function(){
                            window.location.href=red_url;
                            }, 1500);
                        
                    });
                }
         
    });

$(document).on('change','.product_id',function(){
var pid=$(this).val();
var url="{{ URL::to('productuomdetails')}}/";
	$.get(url+pid,function(data){
		data=$.trim(data);
		$('.uom_code_id').val(data);
	});
	
});
/*deepika purpose:qty validation*/
$('.job_adjusted_qty').change(function(){
	var jobadjqty=parseInt($(this).val());
	var capacity=parseInt($('.machine_capacity').val());
	if(jobadjqty>capacity){
	   notyMsg("error","Job adjusted Qty SHould not exceed Machine Capacity Qty");
		$('.job_adjusted_qty').val('');
	   }
});
/*end*/
/*Deepika purpose:Based on Machine capsity,resource should be load*/	
	$('.machine_hdr_id').change(function()
	{
		var mid=$(this).val();
		var url="{{ URL::to('machinedetails')}}/";
		$.get(url+mid,function(data){
			var dataarray=data[0].assigned_to.split(",");
		$('.machine_capacity').val(data[0].capacity);
		$('.job_assigned_to').val(dataarray);
		$('.job_assigned_to').trigger('change.select2');
			
		});
	});
	
	
	/********************* End ****************************/
/*Deepika purpose:start date&end date validation*/
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
/*end*/
});
	function changeclassfields()
{
	changeClassName('bulk_fillings_hdr_id');
	changeClassName('bulk_fillings_lines_id');
	changeClassName('bulk_reference_source');
	changeClassName('bulk_reference_hdr_id');
	changeClassName('bulk_reference_line_id');
	changeClassName('bulk_line_no');
	changeClassName('bulk_product_id');
	changeClassName('bulk_uom_code_id');
	changeClassName('bulk_qty');
	changeClassName('bulk_comments');
	changeClassName('bulk_pack');
	changeClassName('bulk_total_qty');
}
function changeClassName(className){
$('.' + className).each(function (index) {
$(this).addClass(className);
$(this).addClass(className + index);
if (className == "bulk_line_no") {
$(this).val(index + 1).attr("readonly", 1);
}
});
}
</script>
@include('layouts.php_js_validation')
@endsection
