@extends('layouts.header_exam')
@section('content')
<style type="text/css"> 
::-webkit-scrollbar {
  width: 10px;
}

/* Track */
::-webkit-scrollbar-track {
  box-shadow: inset 0 0 5px grey; 
  border-radius: 10px;
}
 
/* Handle */
::-webkit-scrollbar-thumb {
  background: #30a1ea; 
  border-radius: 10px;
}

/* Handle on hover */
/*::-webkit-scrollbar-thumb:hover {*/
/*  background: #b30000; */
/*}*/
    .invoice-box table td {
   padding: 10px;
   
}
.invoice-box .table>thead:first-child>tr:first-child>th, .invoice-box .table-bordered>tbody>tr>td{
    font-size:18px;
}
/*.table>caption+thead>tr:first-child>td, .table>caption+thead>tr:first-child>th, .table>colgroup+thead>tr:first-child>td, .table>colgroup+thead>tr:first-child>th, .table>thead:first-child>tr:first-child>td, .table>thead:first-child>tr:first-child>th{*/
/*    background:#30a1ea !important;*/
/*}*/
.table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th{
    border:0px;
}
.form-check{
    display: flex;
    margin: 10px;
}
.radio{
    margin:0px 100px;
}
#demo{ text-align: center; font-size: 30px; margin-top: 0px; }
.countdown{ background:#05234e; vertical-align: middle !important; }
.countdown p{ color:#fff !important;}
.next{background:#30a1ea;color:#fff;float:right;}.previous{background:#30a1ea;color:#fff;float:left;}
.swal-wide{
    width:850px !important;
}
.swal2-popup {
  font-size: 2.6rem !important;
}
.question_tab{width: 9%; background: #ccc; color: #fff; display: inline-block; height: 35px; padding: 7px 24px; margin: 10px;cursor:pointer;}
.answered{background:green;}
.active{box-shadow: 5px 10px #888888;}
.end{background:#30a1ea;color:#fff;}
.swal-text{ max-height: 6em; overflow-y: scroll; width: 100%; }
.swal2-html-container{max-height:300px;overflow-y:scroll;}

.box:before{ content: ""; background: darkgrey; position: absolute; z-index: 10; top: 21px; left: 0; width: -webkit-fill-available; height: 2px; border-bottom: 1px solid #fff; }
.box{
   font-family:'Verdana, Geneva, sans-serif' !important;
  margin:0 3px;
  padding: 5px;
  position: relative;
    width: 30px;
    height: 46px;
    font-size: 30px;
    line-height: 46px;
    border-radius: 2px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.3);
  color:#555555;
background: rgb(235,235,235); /* Old browsers */
background: -moz-linear-gradient(top, rgba(235,235,235,1) 0%, rgba(235,235,235,1) 50%, rgba(255,255,255,1) 51%, rgba(248,248,248,1) 100%); /* FF3.6-15 */
background: -webkit-linear-gradient(top, rgba(235,235,235,1) 0%,rgba(235,235,235,1) 50%,rgba(255,255,255,1) 51%,rgba(248,248,248,1) 100%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(to bottom, rgba(235,235,235,1) 0%,rgba(235,235,235,1) 50%,rgba(255,255,255,1) 51%,rgba(248,248,248,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ebebeb', endColorstr='#f8f8f8',GradientType=0 ); /* IE6-9 */
}
.text_area_field {
        background-color: #fff;
    border: 1px solid #375a80;
    width: 100%;
    border-radius: 5px;
    box-shadow: none;
    color: #000;
    /* height: 30px; */
    padding: 7px 12px;
    transition: all 300ms linear 0s;
}
.pop_td{
    max-width: 47px;
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
}
small{ display: block; clear: both; font-size: 12px; line-height: 12px; font-weight: 400; text-transform: uppercase; text-align: center; padding-top: 13px; }
#Days, #Hours, #Minutes, #Seconds{ float:left;margin:0px;background:#05234e; border-radius: 10px; }
.time-content { width: 40%; margin-left:40px;color:#fff;}
.time-content p { width: calc(40%/4);}
#dateEnd, #action{ display:block; margin:0 auto;}
/*body{background:url(../images/bg10.jpeg);}*/
</style>
<form method="post" action="" id="exam" class="exam"  enctype="multipart/form-data">
	<input type="hidden" value="" name="save_status" id="save_status" />
	{{ csrf_field() }}
	
	<div class="card">
		<div class="card-header">
		    
		    <div class="row">
		        
		        <div class="col-md-9 time-content">
                    <p id="Days"></p>
                    <p id="Hours"></p>
                    <p id="Minutes"></p>
                    <p id="Seconds"></p>
                    
                </div>
                <div class="expired col-md-3"><h2 id="demo"></h2></div>
                <p id="demo" style="width:fit-content"></p>
		    
				    <li class="btn pull-right end exam_content saveform" value="0">End Now</li>
			<span class="ui_close_btn"><a href="../exam" class="collapse-close pull-right btn-danger" ></a></span>
			</div>
		</div><div class="ajaxLoading"></div>
	<div class="card-body card-block">
	   <div class="row">
                <div class="col-md-12">
                    <h2 class="heads1">Exam - {{$row->topic_name }}</h2>
                    <div class="invoice-box" id="section-to-print" class="exam_content">

                        <table cellpadding="0" cellspacing="0">
                            <tbody>
                                <input type="hidden" name="schedule_exam_line_id" id="schedule_exam_line_id" value="{{$row->schedule_exam_line_id}}">
                                <input type="hidden" name="exam_date" id="exam_date" value="{{$row->schedule_date}}">
                                <input type="hidden" name="employee_id" id="employee_id" value="{{$row->employee_id}}">
                                <input type="hidden" name="topic_id" id="topic_id" value="{{$row->topic_id}}">
                                <input type="hidden" name="total_questions" id="total_questions" value="{{$row->total_questions}}">
                                <input type="hidden" name="start_time" id="start_time" value="{{$row->start_time}}">
                                <input type="hidden" name="end_time" id="end_time" value="{{$row->end_time}}">
                                <input type="hidden" name="exam_start_time" id="exam_start_time" value="{{$row->exam_start_time}}">
                                <input type="hidden" name="exam_end_time" id="exam_end_time" value="">
                                <input type="hidden" name="start_timer" id="start_timer" value="1">
                                <input type="hidden" name="source" id="source" value="">
                                
                                <tr>
                                    @foreach($linedata as $key=>$value)
                                        <div class="question_tab qtab{{$key+1}}" onclick="inc('{{$key+1}}')">
                                            Q{{$key+1}}
                                        </div>
                                    @endforeach
                                </tr>
                                
                                
                                 <tr>
                                    <td colspan="6" class="ref">
                                        <h4 class="head-style-1">Questions</h4></td>
                                </tr>
                                <?php //dd($vlinesdata); ?>
                                                @foreach($linedata as $key=>$value)
                                <tr class="heading">
                                    <table class="table table-bordered table-hover questionsdiv{{$key+1}}"<?php if($key!=0){ echo "style='display:none;'";}?>>
                                        <thead style="height:33px;">
                                            <tr>
                                                <th class="question{{$key}}"style="display:flex">
                                                    {{$key+1}}. &nbsp;
                                                    <!--<input type="hidden" name="bulk_exam_line_id[]" class="form-control input-sm bulk_exam_line_id" value="{{ $value->exam_line_id }}" >-->
                                                <input type="hidden" name="exam_questions_line_id[]" class="form-control input-sm bulk_exam_questions_line_id" value="{{ $value->exam_questions_line_id}}">
                                                <label for="question{{$key}}" >{{ $value->question}}</label>
                                                <input type="hidden" name="exam_question_type[{{$key}}]" id="exam_question_type{{$key}}" class="form-control input-sm bulk_exam_question_type" value="{{ $value->qtype}}">
                                                </th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                                <tr>
                                                    <td>
                                                        <?php if($value->qtype=='text'){?>
                                                        <div class="form-check">
                                                            <div class="radio">
                                                                <textarea name="answer[{{$key}}]" id="bulk_answer[]" rows="5" cols="100" onchange="answered_color1('{{$key+1}}');" class="text_area_field form-check-input input-sm  bulk_answer{{ $key }}">
                                                                    
                                                                </textarea>
                                                            </div>
                                                        </div>
                                                        <?php }else{?>
                                                        <div class="form-check">
                                                            <div class="radio">
                                                                <input type="radio" oninput="answered_color('{{$key+1}}');" name="answer[{{ $key }}]" id="bulk_answer1" class="form-control form-check-input input-sm  bulk_answer{{ $key }}" value="{{ $value->option1}}">    
                                                                <label class="form-check-label" for="bulk_answer">
                                                                    {{ $value->option1}}
                                                                </label>
                                                            </div>
                                                          <div class="radio">
                                                              <input type="radio" oninput="answered_color('{{$key+1}}');" name="answer[{{ $key }}]" id="bulk_answer2" class="form-control form-check-input input-sm  bulk_answer{{ $key }}" value="{{ $value->option2}}">    
                                                              <label class="form-check-label" for="bulk_answer2">
                                                                    {{ $value->option2}}
                                                              </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-check">
                                                          <div class="radio">
                                                              <input type="radio" oninput="answered_color('{{$key+1}}');" name="answer[{{ $key }}]" id="bulk_answer3" class="form-control form-check-input input-sm  bulk_answer{{ $key }}" value="{{ $value->option3}}">    
                                                              <label class="form-check-label" for="bulk_answer3">
                                                                    {{ $value->option3}}
                                                              </label>
                                                          </div>
                                                          <div class="radio">
                                                              <input type="radio" oninput="answered_color('{{$key+1}}');" name="answer[{{ $key }}]" id="bulk_answer4" class="form-control form-check-input input-sm  bulk_answer{{ $key }}" value="{{ $value->option4}}">    
                                                              <label class="form-check-label" for="bulk_answer4">
                                                                    {{ $value->option4}}
                                                              </label>
                                                            </div>
                                                        </div>
                                                        <?php }?>
                                                      </td>
                                                  
                                                </tr>
                                               
                                        </tbody>
                                    </table>
                                </tr>
                            @endforeach
                            <input type="hidden" name="cnt" id="cnt" value="1">
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                    			<div class="form-group buttons" style="display:none">
                    				<button type="button" class='btn previous exam_content' onclick="inc('sub')">< Previous</button>
                    			</div>
                    			<div class="form-group buttons" style="display:none">
                    				<button type="button" class="btn next exam_content" onclick="inc('add')">Next ></button>
                    			</div>
                    		</div>
                        </div>
                    </div>
                	<div class="row save_btn">
                		<div class="col-lg-12 col-md-12">
                			<div class="form-group text-center buttons">
                				<button type="button" class="btn save saveform" value ="SUBMITTED" >Submit</button>
                				
                			</div>
                		</div>
                	</div>
                </div>
            </div>
        </div>
	</div>
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
var date = document.getElementById('end_time').value;
var start_timer = document.getElementById('start_timer').value;
var countDownDate = new Date(date).getTime();
 $('.buttons').show();
$('.exam_content').show();
$('.ui_close_btn').hide();
var x = setInterval(function() {

  var now = new Date().getTime();
    
  var distance = countDownDate - now;
    
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
//   document.getElementById("demo").innerHTML = days + "d " + hours + "h "+ minutes + "m " + seconds + "s ";
  document.getElementById("Days").innerHTML = "<span class='box'>"+days+"</span><small>Days</small>";
  document.getElementById("Hours").innerHTML = "<span class='box'>"+hours+ "</span><small>Hours</small>";
  document.getElementById("Minutes").innerHTML = "<span class='box'>"+minutes+"</span><small>Minutes</small>";
  document.getElementById("Seconds").innerHTML = "<span class='box'>"+ seconds + "</span><small>Seconds</small>";
    
  if (distance < 0) {
      if(start_timer==1){
        clearInterval(x);
        document.getElementById("demo").innerHTML = "EXPIRED";
        $('.buttons').hide();
        $('.exam_content').hide();
        $('.ui_close_btn').show();
      }else{
        document.getElementById("demo").innerHTML = "Time Out";
        document.getElementById('timer').value= 0;  
      }
  }
}, 1000);

$(document).ready(function(){

    question_change();
//   function preventBack(){window.history.forward();}
//     setTimeout("preventBack()", 0);
//     window.onunload=function(){null};
});


	$(document).on('click','.saveform',function()
    {
        var btnval		= $(this).val();
    	var url			="{{ URL::to('examsave') }}";
        var red_url		="{{ url('exam') }}";
        var create_url	="{{ url('examcreate') }}/0";
        var form = $('#exam');
        
        if(btnval != 'Applychanges')
        {
  	       var form = $('#exam');
	       	validationrule('exam');
              form.parsley().validate();
        var form_data = new FormData(document.getElementById('exam'));
        var st = '';
        var dia_log="";
        var count = $('#total_questions').val();
        dia_log +="<table class='table table-bordered table-hover' style='width:100%;height:100px;'><thead style='height:33px;'><tr><th>Sno.</th><th>Questions</th><th>Answers</th></tr></thead><tbody>";
        for(var i=0;i<count;i++){
            var ques_name = "question"+i;
            var ans_name = "answer["+i+"]";
            var qtype = $('#exam_question_type'+i).val();
            // alert(qtype);
            var question = $('label[for ="'+ques_name+'"]').text();
            if(qtype=='text'){
                var answer = $('.bulk_answer'+i).val();
                // alert('.bulk_answer'+i);
            }else{
                var answer = $('input[name="'+ans_name+'"]:checked').val();
            }
            dia_log +='<tr><td>'+(i+1)+'.</td><td> '+question  +' </td><td class="pop_td">'+ answer+"</td></tr>";
        
            dia_log = dia_log.replace('undefined', '');
        }
        dia_log += "</tbody></table>" 
        // dia_log = dia_log.replace('undefined', '');
var swaltitle = "Are you sure to submit?";
// alert(btnval);
if(btnval == 0){swaltitle = "Are you sure to END EXAM?";$('#source').val('ENDED');}else{$('#source').val(btnval);}

            if (form.parsley().isValid())
            {
                 change_date();
   
                    Swal.fire({
                      title: swaltitle,
                      html:dia_log,
                      showCancelButton: true,
                      focusConfirm: false,
                      confirmButtonText:'Yes',
                      cancelButtonText:'No',
                      closeOnCancel:!1
                    }).then((result) => {
                      if (result.isConfirmed) {
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
                        						window.location.href=red_url;
                                            }
                                            else{
                                            	   notyMsg(status,msg);
                        							window.location.href=red_url;
                                            }
                                        });
                      }
                    })
                   

        /*         var formdata	= $('#exam').serialize();
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
*/

            }
        }
         });
   
    function question_change(){
       var cnt = $('#cnt').val();
       var total = $('#total_questions').val();
            $('.question_tab').removeClass('active');
       for(i=1;i<=total;i++){
            $('.questionsdiv'+i).hide();   
            
       }
       var org=cnt-1;
        $('.qtab'+cnt).addClass('active');
       $('.questionsdiv'+cnt).show();
       $('.next').show();
        $('.previous').show();
        $('.save_btn').hide();
        $('.end').show();
       if(cnt==1){
           $('.previous').hide();
       }
       if(cnt==total){
           $('.next').hide();
           $('.save_btn').show();
           $('.end').hide();
       }
       
    }
    function inc(val){
        var cnt = $('#cnt').val();
        
        if(val=='add'){
            cnt = ++cnt;
            $('#cnt').val(cnt);
        }else if(val=='sub'){
            cnt = --cnt;
            $('#cnt').val(cnt);
        }else{
            
            $('#cnt').val(val);
        }
       question_change();
    }
    function answered_color(key){
        $('.qtab'+key).addClass('answered');
    }
     function answered_color1(key){
         var ans = $('.bulk_answer'+key).val();
         if(ans==''){
             $('.qtab'+key).removeClass('answered');
         }else{
            $('.qtab'+key).addClass('answered');
         }
    }
</script>
@include('layouts.php_js_validation')
@endsection
