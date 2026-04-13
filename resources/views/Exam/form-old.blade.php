@extends('layouts.header')
@section('content')
<style>

/*styles for line table to differnt media screens*/


@media (min-width: 1500px){
	.bulk_line_no{
      width: 91px !important;
  }
  .bulk_employee_id{
      width: 250px !important;
  }
  
}
 	.bulk_line_no{
      width: 91px !important;
  }	
 .bulk_employee_id{
    width: 250px;
 }
 <?php if($row->schedule_type=='Request'){ ?>
 .topic,.additem,.emp{
     pointer-events:none;
 }    
<?php } ?>     
</style>

<div class="ajaxLoading"></div>


<span class="ui_close_btn"></span>
<?php //include('tools_menu.php'); ?>
<h2 class="heads">EXAM
<span class="ui_close_btn"><a href="{{URL::to('scheduleexam')}}" class="collapse-close pull-right btn-danger" ></a></span></h2>


<div class="card">
<div class="card-header">

</div>

<div class="card-body card-block">
<form method="post" action="" id="exam" class="exam"  enctype="multipart/form-data">
	<input type="hidden" value="" name="save_status" id="save_status" />
	 {{ csrf_field()}}



  <div class="row">
    <div class="col-md-12">


      <!--*******************************- Body content start here ****************************-->
      <div class="">
    
    <div>
      <div class="row">
        <div class="col-md-4">
          <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-5"><span style="color:red;">*</span>Topic</label>
            <div class="col-md-6 topic">
            <input class="form-control exam_hdr_id" id="exam_hdr_id" name="exam_hdr_id" size="16" type="hidden" value="{{ $row->exam_hdr_id }}" readonly>
            <!--<input class="form-control reference_id" id="reference_id" name="reference_id" size="16" type="hidden" value="{{ $row->reference_id }}" readonly>-->
            <input class="form-control topic_id" id="topic_id" name="topic_id" size="16" type="text" value="{{ $row->topic_id }}" readonly>
   <!--         <select name='topic_id' rows='5' class='select2 topic_id' id="topic_id" >-->
			<!--{!! $topic_id !!}-->
			<!--	</select>-->
               </div>
          </div>
          
				
        <div class="form-group row">
			<label for="active" class="form-control-label col-md-5">Exam Date</label>
			<div class="col-md-6">
				<input type="text" name='schedule_date' rows='5' class='form-control schedule_date datepicker' readonly id="schedule_date" value="{{$row->schedule_date}}">
			
			</div>
		</div>

         
        </div>

      <div class="col-md-4">
          <div class="form-group row">
    			<label for="inputIsValid" class="form-control-label col-md-5">Start Time</label>
    			<div class="col-md-7">
    				<input type="text" id="start_time" name="start_time" class="form-control start_time datetimepicker1 " readonly value="{{$row->start_time}}" row="5"  >
    			</div>
    		</div>
		  <div class="form-group row">
    			<label for="inputIsValid" class="form-control-label col-md-5">End Time</label>
    			<div class="col-md-7">
    				<input type="text" id="end_time" name="end_time" class="form-control end_time datetimepicker1" readonly value="{{$row->end_time}}" row="5"  >
    			</div>
    		</div>
    	
          
      </div>


        <div class="col-md-4">
           <div class="form-group row">
            <label for="active" class="form-control-label col-md-5">Schedule Type</label>
            <div class="col-md-6" style="pointer-events:none;">
              <select name='schedule_type' rows='5' class='form-control select2 schedule_type'  data-show-subtext="true" data-live-search="true"  required readonly>
                <option value="">--Please Select--</option>
                <option value="Manual" <?php if($row->schedule_type=='Manual'){ echo "selected"; } ?>>Manual</option>
                <option value="Request" <?php if($row->schedule_type=='Request'){echo "selected"; } ?>>Request</option>

              </select>
            </div>
          </div>
            <div class="form-group row">
                <label for="inputIsValid" class="form-control-label col-md-5">Remarks</label>
                <div class="col-md-7">
                  <input type="text" id="remarks" name="remarks" class="form-control remarks" value="{{$row->remarks}}" row="5" tabindex="2" readonly>
                </div>
            </div>
        </div>
      </div>
    </div>
      

    </div>
  </div>

      <!--*****************  End  *********** -->



<!--****************Linedata ********************-->
	<?php

if (count($linedata) >= 1) {  ?>
@foreach($linedata as $key=>$value)
<div class="row">
  <div class="col-md-12">
    <div id="preview-area" class="chandru">
        <table class="overflow-y preview schedule_table">
            <thead>
                <tr style="height:33px;">
                    <th>
                        <input type="hidden" name="bulk_exam_line_id[]" class="form-control input-sm bulk_exam_line_id" value="{{ $value->exam_line_id }}" >
                        <input type="hidden" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="{{ $key + 1 }}" readonly="readonly">
                     {{$key+1}}. &nbsp;
                        <input type="hidden" name="bulk_exam_questions_line_id[]" class="form-control input-sm bulk_exam_questions_line_id" value="{{ $value->exam_questions_line_id}}">
                        {{ $value->question}}
                    </th>
                    <th >&nbsp;</th>
                    <th >&nbsp;</th>
                    <th >&nbsp;</th>
                    <th >&nbsp;</th>
                    <th >&nbsp;</th>
                </tr>
            </thead>
            <tbody class="">
                <tr>
                    <td></td>
                    <td>
                    <input type="radio" name="answer[{{ $key }}]" id="bulk_answer" class="form-control input-sm  bulk_answer{{ $key }}" value="{{ $value->option1}}">{{ $value->option1}}    
                    </td>
                    <td>
                        <input type="radio" name="answer[{{ $key }}]" id="bulk_answer" class="form-control input-sm  bulk_answer{{ $key }}" value="{{ $value->option2}}">{{ $value->option2}}
                    </td>
                    <td>
                        <input type="radio" name="answer[{{ $key }}]" id="bulk_answer" class="form-control input-sm  bulk_answer{{ $key }}" value="{{ $value->option3}}">{{ $value->option3}}
                    </td>
                    <td>
                        <input type="radio" name="answer[{{ $key }}]" id="bulk_answer" class="form-control input-sm  bulk_answer{{ $key }}" value="{{ $value->option4}}">{{ $value->option4}}
                    </td>
                
                
                
                    <!--<select  name="bulk_employee_id[]" class="select2 input-sm bulk_employee_id"  required >-->
                    
                    <!--</select>-->
                    </td>
                    <td style="width:33px;">
                    <!--<a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>-->
                    <input type="hidden" name="counter[]">
                    </td>
                    </tr>
                    <tr>&nbsp;</tr>
                </tbody>
            </table>
            <input type="hidden" name="enable-masterdetail" value="true">
        </div>
    </div>
</div>

@endforeach
<?php }?>
<!--****************Linedata End********************-->


	<div class="row">
		<div class="col-lg-12 col-md-12">
			<div class="form-group text-center">
				<button type="button" class="btn save saveform" value ="save" >Save</button>
				<button type="button" class="btn save saveform"   value ="savenew" >Save and New</button>
				<button type="button" class='btn cancel' onclick='location.href="{{ url($pageMethod) }}"'>Cancel</button>
			</div>
		</div>
	</div>
	</div>
</form>

</div>
</div>



<script>


$(document).ready(function(){
 $('.datetimepicker1').datetimepicker({ weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
		format: "yyyy-mm-dd hh:ii:ss",
		});
		
		/**********Up/down/left/right arrow navigation start*******/
		$('input').keyup(function (e) {
	        if (e.which == 39) { // right arrow
	          $(this).closest('td').next().find('input').focus();
	 
	        } else if (e.which == 37) { // left arrow
	          $(this).closest('td').prev().find('input').focus();
	 
	        } else if (e.which == 40) { // down arrow
	          $(this).closest('tr').next().find('td:eq(' + $(this).closest('td').index() + ')').find('input').focus();
	 
	        } else if (e.which == 38) { // up arrow
	          $(this).closest('tr').prev().find('td:eq(' + $(this).closest('td').index() + ')').find('input').focus();
	        }
      	});
        
	
/******************** add row to line table **********************/
var index = $('.clone').closest('tr').index();
changeclassfields();
 var data = "{{\Session::get('j_date_format')}}";
 
 	$(".add_row").on('click',function(){
		
  var form = $('#scheduleexam');
   form.parsley().destroy();
});
        $(".add_row").relCopy(data);

        $('.add_row').click(function() {
            changeclassfields();
		});
	
/* -- Start Save function -- */
	$('#save_status').val('');

 
/* -- Start Save,draft Button function -- */
	$(document).on('click','.saveform',function()
    {
        var btnval		= $(this).val();
    	var url			="{{ URL::to('examsave') }}";
        var red_url		="{{ url('exam') }}";
        var create_url	="{{ url('examcreate') }}/0";
        var form = $('#scheduleexam');
        if(btnval != 'applychanges')
        {
  	       var form = $('#scheduleexam');
	       	validationrule('scheduleexam');
              form.parsley().validate();

            if (form.parsley().isValid())
            {
                 change_date();
                 var formdata	= $('#exam').serialize();
    	 var form_data = new FormData(document.getElementById('exam')); 
    	
               $(".ajaxLoading").show();
            	$.post(url,formdata,function(data)
                {
                	   var status = data.status;
                    var msg    = data.message;
                    var id     = data.id;

                    if(btnval !='save')
                    {
                        notyMsg(status,msg);
						window.location.href=create_url;
   }
                    else{
                    	   notyMsg(status,msg);
							window.location.href=red_url;
                    }
                });


            }
        }
         });
         	$('.trainertext').hide();
	$(".trainer_type").change(function(){
	    var type=$(this).val();
	    if(type=="Internal"){
	        	$('.trainertext').hide();
	        		$('.trainer_name').attr('required',true);
	        			$('.trainer_name1').attr('required',false);
	        				$('.trainer_mail').attr('required',false);
			$(".trainer_name").jCombo("{{ URL::to('jcomboform?table=hr_employee_t:employee_id:first_name|last_name')}}",
		{selected_value:""});
	    }else{
	        	$('.trainersel').hide();
	        		$('.trainertext').show();
	        		$('.trainer_name1').attr('required',true);
	        		$('.trainer_mail').attr('required',true);
	        		
	    }	
	});
	



	$(document).on('change', '.bulk_employee_id', function () {
		var index=$(this).closest('tr').index();
		var emp_id=$(this).val();
		var count = 0;
                            var count = empcheck(emp_id,index);
                            if(count <= 0){
                            }else{
                                 notyMsgs('info','Employee Already Selected');
                                $(".bulk_employee_id" + index).select2('val',['']);
                               
                            }
                             
	});


/* Start Remove Class Function */
	$(document).on('click','.remove',function()
	{
		var index = $(this).closest('tr').index();
		var rowCount=$('.schedule_table tbody tr').length;
		if(rowCount > 1)
		{
			// alert();
			$(this).closest("tr").remove();
			removeClass('bulk_line_no');
			removeClass('bulk_schedule_exam_line_id');
			removeClass('bulk_question');
			removeClass('bulk_answer');
				}
		else
		{
			notyMsg("info","You can't Delete Atleast one row should be there");
		}
	});
/* End Remove Class Function */

});

/* Start Change Class name Function */
function changeclassfields()
{
	changeClassName('bulk_line_no');
	changeClassName('bulk_schedule_exam_line_id');
	changeClassName('bulk_question');
	changeClassName('bulk_answer');
	}

function changeClassName(className)
{
$('.' + className).each(function (index)
{
$(this).removeClass(className + '0');
$(this).addClass(className + index);
// if(className == "bulk_answer"){
//     var line = $('bulk_line_no').val();
//     $(this).removeClass(className +index);
//     $(this).addClass(className +line);
// }
	if (className == "bulk_line_no") {
		$(this).val(index + 1).attr("readonly", 1);
}
});
}
/* End Change Class name Function */

/* Start Remove Class name Function */
function removeClass(className)
{
	var rowCount = $('.schedule_table tbody tr').length;
	for (var i = 0; i <= rowCount; i++)
	{
		$('.schedule_table tbody tr').find('.' + className).removeClass(className + i);
	}
	$('.' + className).each(function (index)
	{
		$(this).addClass(className + index);
	});

}
/* End Remove Class name Function */
</script>
@include('layouts.php_js_validation')
@endsection
