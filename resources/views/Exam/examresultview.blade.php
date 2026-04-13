@extends('layouts.header')
@section('content')
<style type="text/css"> 
    .invoice-box table td {padding: 10px;}
    .anstd{word-wrap: break-word;max-width: 500px;}
</style>
	{{ csrf_field() }}
	<?php 
	    $url = 'examresult';
	    $schedule_exam_hdr_id = $values->schedule_exam_hdr_id;
	?>
	<div class="card">
		<div class="card-header">
				<span class="ui_close_btn"><a href="{{url($url)}}" class="collapse-close pull-right btn-danger" ></a></span>
			
		</div>
		<div class="ajaxLoading"></div>
	<div class="card-body card-block">
	   <div class="row">
                <div class="col-md-12">

                    <div class="invoice-box" id="section-to-print">

                        <table cellpadding="0" cellspacing="0">
                            <tbody>

                                <h2 class="heads1">Exam Schedule Details</h2>
                                    
                                    <input type="hidden" name="total_questions" id="total_questions" value="{{$values->total_questions}}">
                                    <input type="hidden" name="exam_hdr_id" id="exam_hdr_id" value="{{$values->exam_hdr_id}}">
                                    
                                <tr class="information">
                                    <td colspan="6">

                                       <table>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <p><b>Topic:</b> {{$values->topic_name }}</p>
                                                        <br>
                                                       
                                                         <p><b>Employee Name:</b> {{$values->first_name }}</p>
                                                        
                                                    </td>
                                                    
                                                    <td >
                                                        <p><b>Exam Attended Date:</b> {{$schedule_date }}</p>
                                                        <br>
                                                        
                                                        <p><b>Attended Time:</b> {{$started_at }} - {{$ended_at}}</p>
                                                    </td>
													<td >
													    <p><b>Exam Time:</b> {{$start_time }} - {{$end_time }}</p>
                                                       
                                                        <br>
                                                        <p><b>Duration Taken:</b>{{$duration_taken}}</p>
                                                    </td>
                                                    
                                                    <td>
                                                        
                                                        
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6" class="ref">
                                                        <h4 class="head-style-1">Exam Q&A</h4></td>
                                                </tr>

                                               

                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr class="heading">
                                    <table class="table table-bordered table-hover ">
                                        <thead>
                                            <tr>
                                                <th>Sno</th>
												<th>Question</th>
												<th>Answer Written</th>
												<th>Actual Answer</th>
												<th>Result</th>
												<th>Mark(Max 1 mark for each Correct Answers)</th>
											</tr>
                                        </thead>
                                        <tbody>
                                            <?php //dd($vlinesdata); ?>
                                                @foreach($vlinesdata as $key=>$value)
                                                <tr>
                                                   <td><?php echo $key+1; ?></td>
													<td>
													    <input type="hidden" name="exam_line_id[{{$key}}]" value="{{$value->exam_line_id}}">
													    {{$value->question}}
													</td>
													<td class="anstd">
													    {{$value->answer}}
													</td>    
													    <?php if($value->exam_question_type=='text'){?>
													        
													    <td class="anstd">{{$value->actual_text_answer}}</td>
													    
												       <?php if($value->status==1){?>
												                <td style="background-color:'green'"><h4>Correct</h4></td>
												                <td><h4 class='mark{{$key}}'>1</h4></td>
												          <?php }else{?>
												            <td style="background-color:'red'"><h4>Wrong</h4></td>
												            <td><h4 class='mark{{$key}}'>0</h4></td>
												           <?php } 
													        
													    }else{
													    $ans = $value->actual_answer;
													    ?>
													        <td class="anstd">{{$value->$ans}}</td>
													           
													           <input type="hidden" name="status[{{$key}}]" id="status" value="{{$value->status}}">
													           
													           <?php if($value->status==1){?>
													                <td style="background-color:'green'"><h4>Correct</h4></td>
													                <td><h4 class='mark{{$key}}'>1</h4></td>
													          <?php }else{?>
													            <td style="background-color:'red'"><h4>Wrong</h4></td>
													            <td><h4 class='mark{{$key}}'>0</h4></td>
													           <?php }?>
													            
													    <?php }?>
													    
													 
                                                </tr>
                                                @endforeach
                                        </tbody>
                                    </table>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

</div>

				</div>


<script>
    function mark(key,value){
        // alert(value);
        $('.mark'+key).html(value);
    }
    	$(document).on('click','.saveform',function()
    {
        var btnval		= $(this).val();
    	var url			="{{ URL::to('validateexamsave') }}";
        var red_url		="{{ url('examlist') }}/<?php echo $schedule_exam_hdr_id;?>";
        var create_url	="{{ url('examcreate') }}/0";
        var form = $('#exam');
        
        if(btnval != 'Applychanges')
        {
            
  	       var form = $('#exam_result');
	       	validationrule('exam_result');
              form.parsley().validate();
        var form_data = new FormData(document.getElementById('exam'));
        
                  if (form.parsley().isValid())
            {
                 change_date();
   
                              var formdata	= $('#exam_result').serialize();
                            	 var form_data = new FormData(document.getElementById('exam_result')); 
                            	
                                       $(".ajaxLoading").show();
                                    	$.post(url,formdata,function(data)
                                        {
                                        	   var status = data.status;
                                            var msg    = data.message;
                        
                                            if(btnval !='save')
                                            {
                                                notyMsg(status,msg);
                        						window.location.href=red_url;
                                            }
                                            else{
                                            	   notyMsg(status,msg);
                        							window.location.href=red_url;
                                            }
                                        });
                      
                   
            }
            
        }
    });
</script>

@include('layouts.php_js_validation')
@endsection
