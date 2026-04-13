@extends('layouts.header')
@section('content')
<style type="text/css">
	.panel-success>.panel-heading {
		
    color: #fff;
    background-color: #11256f;
    border-color: #d6e9c6;
}
.panel-success{
	border: none;
	margin-bottom: 0 !important;
}
</style>
<div class="ajaxLoading"></div>
<span class="ui_close_btn"></span>

<h2 class="heads">Investment Declarations</h2>
<div class="card">

            <form  action=""  id="save" >
                <div class="card-body card-block">
                 
                {{ csrf_field()}}
                <div class="row">
					
					<div class="col-md-6">

						<div class="form-group row">
							<label for="start_date" class="form-control-label col-md-4">Employee Name</label>
							<div class="col-md-6">
							  <select type="text" id="employee_name" name="employee_name" class="form-control select2">
							  </select>
							</div>
</div>

						 <div class="form-group row">
							<label for="start_date" class="form-control-label col-md-4">Date of Joining</label>
							<div class="col-md-6">
								<!-- <div class="input-group form_date " data-date="" data-link-format="yyyy-mm-dd"> -->
							  <input type="text" id="date_of_joining" name="date_of_joining" class="form-control date_of_joining" value="" required="">
									<!-- <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> -->
								<!-- </div> -->
							</div>
</div>

					</div>

            		<div class="col-md-6">
                    <div class="form-group row">
                        <label for="organization_id" class="form-control-label col-md-4">Pan Number</label>
                            <div class="col-md-6">
                                    <input type="text" id="pan_number" name="pan_number" class="form-control pan_number" value="" required="">
                            </div>
                    </div>
						
						 <div class="form-group row">
                        <label for="organization_id" class="form-control-label col-md-4">Date of Birth</label>
                            <div class="col-md-6">
								<!-- <div class="input-group form_date " data-date="" data-link-format="yyyy-mm-dd"> -->
                                    <input type="text" id="date_of_birth" name="date_of_birth" class="form-control date_of_birth" value="" required="">
									<!-- <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> -->
								<!-- </div> -->
                            </div>
                    </div>
            </div>
					
					<div class="row" id="formm">                    
					</div>	
					
					
                <div class="col-md-12 text-center ">
				
				
				<div class="row">
				<h5>Do You Want to Confirm Save ?</h5>
					Yes <input type="radio" name="confirm"  class="confirm" value="1" />
					No <input type="radio" name="confirm" class="confirm" value="0" />
				</div>
				<h4>After saving the details you can't edit.</h4>
                    <button type="button"  class="btn save savebut">Save</button> 
                    <button type="button"  class="btn save draftbut">Draft</button>
                   
                </div>
              </div>
					
        </div> 
        
</form>

</div>






	<script>
	$(document).ready(function(){
		
		
		var employee_id = '{{Session::get('emp_id')}}';
       //button readonly
	    $('.save').prop('disabled',true);
// employee load
		$("#employee_name").jCombo("{{ URL::to('jcomboform?table=hr_employee_t:employee_id:employee_number|first_name') }}&order_by=first_name asc",
		{selected_value:employee_id});
		// get investment type
		if(employee_id != '')
		{
			var url = "{{URL::to('getDeclaration')}}/"+employee_id;
			
			$.get(url,function(data)
			{
				$('#formm').html(data['table']);
				$('#pan_number').val(data['pan_no']).prop('readonly',true);
				$('#date_of_birth').val(data['dob']).prop('readonly',true);
				$('#date_of_joining').val(data['doj']).prop('readonly',true);
			});
		}
	// confirm click 	
		$(document).on('click','.confirm',function()
		{
			var action = $(this).val();
			 if(action == 1)
			 {
				 $('.savebut').show();
				 $('.draftbut').hide();
                                 $('.save').prop('disabled',false);
			 }
			 else
			 {
			         $('.savebut').hide();
				 $('.draftbut').show();
                                 $('.save').prop('disabled',false);
			 }
		});


//save
            $(document).on('click','.save',function(e){
$('.ajaxLoading').show();
                e.preventDefault();
                var data;
                data = $("#save").serialize();
                
              
                $.post('investmentsave', data, function(data)
                {
                     
                    if(data == 1)
                    {
                        notyMsg('success','Saved Successfully');
                       setTimeout(function(){
                           window.location.reload();
                       },300);
                      
                    }
                   
                });
               

            }); 
                
       
		
		
		// numbers only
			   $(document).on('keypress','.investment',function(e)
			   {
				    if(e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
					  	$("#errmsg").html("Digits Only").show().fadeOut("slow");
							 return false;
				     }
     			});



          

	});
	</script>

@endsection
