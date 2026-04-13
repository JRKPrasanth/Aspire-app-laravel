@extends('layouts.header')
@section('content')
<style type="text/css"> 
    .invoice-box table td {
   padding: 10px;
   
}
.feedback{ width: 100%; max-width: 780px; margin: 0 auto; padding: 15px; }
.survey-hr{ margin:10px 0; border: .5px solid #ddd; }
.star-rating { margin: 25px 0 0px; font-size: 0; white-space: nowrap; display: inline-block; width: 175px; height: 35px; overflow: hidden; position: relative;
  background: url('data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB3aWR0aD0iMjBweCIgaGVpZ2h0PSIyMHB4IiB2aWV3Qm94PSIwIDAgMjAgMjAiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDIwIDIwIiB4bWw6c3BhY2U9InByZXNlcnZlIj48cG9seWdvbiBmaWxsPSIjREREREREIiBwb2ludHM9IjEwLDAgMTMuMDksNi41ODMgMjAsNy42MzkgMTUsMTIuNzY0IDE2LjE4LDIwIDEwLDE2LjU4MyAzLjgyLDIwIDUsMTIuNzY0IDAsNy42MzkgNi45MSw2LjU4MyAiLz48L3N2Zz4=');
  background-size: contain;
}
.star-rating i { opacity: 0; position: absolute; left: 0; top: 0; height: 100%; width: 20%; z-index: 1; 
  background: url('data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB3aWR0aD0iMjBweCIgaGVpZ2h0PSIyMHB4IiB2aWV3Qm94PSIwIDAgMjAgMjAiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDIwIDIwIiB4bWw6c3BhY2U9InByZXNlcnZlIj48cG9seWdvbiBmaWxsPSIjRkZERjg4IiBwb2ludHM9IjEwLDAgMTMuMDksNi41ODMgMjAsNy42MzkgMTUsMTIuNzY0IDE2LjE4LDIwIDEwLDE2LjU4MyAzLjgyLDIwIDUsMTIuNzY0IDAsNy42MzkgNi45MSw2LjU4MyAiLz48L3N2Zz4=');
  background-size: contain;
}
.star-rating input { -moz-appearance: none; -webkit-appearance: none; opacity: 0; display: inline-block; width: 20%; height: 100%; margin: 0; padding: 0; z-index: 2; position: relative; }
.star-rating input:hover + i,
.star-rating input:checked + i { opacity: 1;}
.star-rating i ~ i { width: 40%; }
.star-rating i ~ i ~ i { width: 60%; }
.star-rating i ~ i ~ i ~ i { width: 80%; }
.star-rating i ~ i ~ i ~ i ~ i { width: 100%; }
.choice { position: fixed; top: 0; left: 0; right: 0; text-align: center; padding: 20px; display: block; }
span.scale-rating{ margin: 5px 0 15px; display: inline-block; width: 100%; }
span.scale-rating>label { position:relative; -webkit-appearance: none; outline:0 !important; border: 1px solid grey; height:33px; margin: 0 5px 0 0; width: calc(10% - 7px); float: left; cursor:pointer; }
span.scale-rating label { position:relative; -webkit-appearance: none; outline:0 !important; height:33px; margin: 0 5px 0 0; width: calc(10% - 7px); float: left; cursor:pointer; }
span.scale-rating input[type=radio] { position:absolute; -webkit-appearance: none; opacity:0; outline:0 !important; height:33px; margin: 0 5px 0 0; width: 100%; float: left; cursor:pointer; z-index:3; }
span.scale-rating label:hover{ background:#fddf8d;}
span.scale-rating input[type=radio]:last-child{ border-right:0;}
span.scale-rating label input[type=radio]:checked ~ label{ -webkit-appearance: none; margin: 0; background:#fddf8d; }
span.scale-rating label:before{ content:attr(value); top: 7px; width: 100%; position: absolute; left: 0; right: 0; text-align: center; vertical-align: middle; z-index:2;}
</style>

    <form class="feedback" id="feedback">
	{{ csrf_field() }}
	
	<div class="card">
		<div class="card-header">
				<span class="ui_close_btn"><a href="../scheduletraining" class="collapse-close pull-right btn-danger" ></a></span>
			
		</div>
	<div class="card-body card-block">
	   <div class="row">
                <div class="col-md-12">
                    <div class="ajaxLoading"></div>
                    <div class="invoice-box" id="section-to-print">
                        
                        <input type="hidden" name="employee_id" id="employee_id" value="{{$vlinesdata->employee_id}}">
                        <input type="hidden" name="topic_id" id="topic_id" value="{{$vlinesdata->topic_id}}">
                        <input type="hidden" name="topic_name" id="topic_name" value="{{$vlinesdata->topic_name}}">
                        <input type="hidden" name="count" id="count" value="3">
                        <input type="hidden" name="feedback_id" id="feedback_id" value="">

                        <table cellpadding="0" cellspacing="0">
                            <tbody>

                                <h2 class="heads1">Feedback Form</h2>

                                <tr class="information">
                                    <td colspan="6">

                                       <table>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <p><b>Topic:</b> {{$vlinesdata->topic_name }}</p>
                                                        <br>
                                                        <p><b>Start Time:</b>{{$vlinesdata->start_time}}</p>
                                                        <br>
                                                        
                                                        
                                                    </td>
                                                    
                                                    <td >
                                                        <p><b>Trainer Name:</b> {{$vlinesdata->trainer_name }}</p>
                                                        <br>
                                                        
                                                        <p><b>End Time:</b>{{$vlinesdata->end_time}}</p>
                                                        <br>
                                                    </td>
													<td >
                                                       <p><b>Schedule Date:</b> {{$vlinesdata->schedule_date }}</p>
                                                        <br>
                                                       
                                                    </td>
                                                </tr>
                                                
                                               
                                                <tr>
                                                    <td colspan="6" class="ref">
                                                        <h4 class="head-style-1">Feedback</h4></td>
                                                </tr>


                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                               
                                    <tr>
                                        <table>
                                            <tr>
                                                <td>
                                                    <label>1. Your overall experience in this Training ?</label><br>
                                                        <input type="hidden" name="rate1" id="rate1" value="Your overall experience in this Training">
                                                        <span class="star-rating">
                                                          <input type="radio" name="rating1" value="1" required><i></i>
                                                          <input type="radio" name="rating1" value="2" required><i></i>
                                                          <input type="radio" name="rating1" value="3" required><i></i>
                                                          <input type="radio" name="rating1" value="4" required><i></i>
                                                          <input type="radio" name="rating1" value="5" required><i></i>
                                                        </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="rate2" id="rate2" value="How much usefull it is">
                                                    <label>2. How much usefull it is?</label><br>
                                                    <span class="star-rating">
                                                      <input type="radio" name="rating2" value="1" required><i></i>
                                                      <input type="radio" name="rating2" value="2" required><i></i>
                                                      <input type="radio" name="rating2" value="3" required><i></i>
                                                      <input type="radio" name="rating2" value="4" required><i></i>
                                                      <input type="radio" name="rating2" value="5" required><i></i>
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="rate3" id="rate3" value="Rate your trainer based on their knowledge & training skills">
                                                    <label>3. Rate your trainer based on their knowledge & training skills?</label><br><br/>
                                                      <div style="color:grey">
                                                        <span style="float:left">
                                                         POOR
                                                        </span>
                                                        <span style="float:right">
                                                          BEST
                                                        </span>
                                                        
                                                      </div>
                                                    <span class="scale-rating">
                                                      <label value="1">
                                                          <input type="radio" name="rating3" value="1" required>
                                                          <label style="width:100%;"></label>
                                                      </label>
                                                      <label value="2">
                                                          <input type="radio" name="rating3" value="2" required>
                                                          <label style="width:100%;"></label>
                                                      </label>
                                                      <label value="3">
                                                          <input type="radio" name="rating3" value="3" required>
                                                          <label style="width:100%;"></label>
                                                      </label>
                                                      <label value="4">
                                                          <input type="radio" name="rating3" value="4" required>
                                                          <label style="width:100%;"></label>
                                                      </label>
                                                      <label value="5">
                                                          <input type="radio" name="rating3" value="5" required>
                                                          <label style="width:100%;"></label>
                                                      </label>
                                                      <label value="6">
                                                          <input type="radio" name="rating3" value="6" required>
                                                          <label style="width:100%;"></label>
                                                      </label>
                                                      <label value="7">
                                                          <input type="radio" name="rating3" value="7" required>
                                                          <label style="width:100%;"></label>
                                                      </label>
                                                      <label value="8">
                                                          <input type="radio" name="rating3" value="8" required>
                                                          <label style="width:100%;"></label>
                                                      </label>
                                                      <label value="9">
                                                          <input type="radio" name="rating3" value="9" required>
                                                          <label style="width:100%;"></label>
                                                      </label>
                                                      <label value="10">
                                                          <input type="radio" name="rating3" value="10" required>
                                                          <label style="width:100%;"></label>
                                                      </label>
                                                    </span>
                                                    
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    
                                                    <label for="comment">4. Any Other suggestions:</label><br/><br/>
                                                    <textarea cols="75" name="comment"  id="comment" rows="5" style="100%"></textarea><br>
                                        
                                                </td>
                                            </tr>
                                        </table>
                                    
                                        <!--<input style="background:#43a7d5;color:#fff;padding:12px;border:0" type="submit" value="Submit your review">&nbsp;-->
                                        
                                    </tr>

                            </tbody>
                        </table>
                        <div class="row save_btn">
                		    <div class="col-lg-12 col-md-12">
                			    <div class="form-group text-center buttons">
                				    <button type="button" class="btn save saveform" value ="SUBMITTED" >Submit your review</button>
                				</div>
                		    </div>
                	    </div>
                    </div>

                </div>
            </div>

</div>

				</div>
			</form>
<script>
     	$(document).on('click','.saveform',function()
    {
        var btnval		= $(this).val();
    	var url			="{{ URL::to('feedbacksave') }}";
        var red_url		="{{ url('home') }}";
        var create_url	="{{ url('examcreate') }}/0";
        var form = $('#exam');
        
        if(btnval != 'Applychanges')
        {
            
  	       var form = $('#feedback');
	       	validationrule('feedback');
              form.parsley().validate();
        var form_data = new FormData(document.getElementById('feedback'));
        
                  if (form.parsley().isValid())
            {
                 change_date();
   
                              var formdata	= $('#feedback').serialize();
                            	 var form_data = new FormData(document.getElementById('feedback')); 
                            	
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
