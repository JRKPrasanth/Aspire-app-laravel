@extends('layouts.header')
@section('content')
<style type="text/css">
	.panel>.panel-heading {
		padding: 4px !important;
		text-align: left !important;
    color: #fff;
    background-color: #11256f;
    border-color: #d6e9c6;
}
</style>
<div class="ajaxLoading"></div>
<span class="ui_close_btn"></span>

<h2 class="heads">Proof Submission</h2>
<div class="card">

            <form  action=""  id="save" >
                <div class="card-body card-block">
                    <input type="hidden" name="edit_id" value="" id="edit_id" class="edit_id" />
                {{ csrf_field()}}
                <div class="row">
					
					<div class="col-md-6">

						<div class="form-group row">
							<label for="start_date" class="form-control-label col-md-4">Employee Name</label>
							<div class="col-md-8">
							  <select type="text" id="employee_name" name="employee_name" class="form-control select2">
							  </select>
							</div>
</div>

						 <div class="form-group row">
							<label for="start_date" class="form-control-label col-md-4">Date of Joining</label>
							<div class="col-md-4">
								<div class="input-group form_date " data-date="" data-link-format="yyyy-mm-dd">
							  <input type="text" id="date_of_joining" name="date_of_joining" class="form-control date_of_joining" value="" required="">
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
								</div>
							</div>
</div>

					</div>

            		<div class="col-md-6">
                    <div class="form-group row">
                        <label for="organization_id" class="form-control-label col-md-4">Pan Number</label>
                            <div class="col-md-4">
                                    <input type="text" id="pan_number" name="pan_number" class="form-control pan_number" value="" required="">
                            </div>
                    </div>
						
						 <div class="form-group row">
                        <label for="organization_id" class="form-control-label col-md-4">Date of Birth</label>
                            <div class="col-md-4">
								<div class="input-group form_date " data-date="" data-link-format="yyyy-mm-dd">
                                    <input type="text" id="date_of_birth" name="date_of_birth" class="form-control date_of_birth" value="" required="">
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
								</div>
                            </div>
                    </div>
            </div>
					<div class="row" id="allowancediv">                    
					

					</div>			
<div class="col-md-6">
<div class="form-group row">
</div>	
</div>
	<div class="col-md-6">
                    <div class="form-group row">
                        <label for="organization_id" class="form-control-label col-md-4">Monthly House Rent Paid</label>
                            <div class="col-md-6">
                                    <input type="text" id="Monthly_House_Rent_Paid" name="Monthly_House_Rent_Paid" class="form-control Monthly_House_Rent_Paid" value="" required="">
                            </div>
                    </div>
						
						 <div class="form-group row">
                        <label for="organization_id" class="form-control-label col-md-4">Select Rent Location</label>
                            <div class="col-md-6">
								<!-- <div class="input-group form_date " data-date="" data-link-format="yyyy-mm-dd"> -->
                                    <select  id="rent_location" name="rent_location" class="form-control rent_location" >
<option value='Metro' selected>Metro</option>
<option value='Non Metro'>Non Metro</option>
                                    </select>
									<!-- <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> -->
								<!-- </div> -->
                            </div>
                    </div>
                    	 <div class="form-group row">
                        <label for="organization_id" class="form-control-label col-md-4">Dependent Medical Treatment</label>
                            <div class="col-md-6">
								<!-- <div class="input-group form_date " data-date="" data-link-format="yyyy-mm-dd"> -->
                                    <select  id="dmt" name="dmt" class="form-control dmt" >
<option value='1' selected>40%-79%</option>
<option value='2'>>=80%</option>
                                    </select>
									<!-- <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> -->
								<!-- </div> -->
                            </div>
                    </div>
                     <div class="form-group row">
                        <label for="organization_id" class="form-control-label col-md-4">Self Medical Treatment</label>
                            <div class="col-md-6">
								<!-- <div class="input-group form_date " data-date="" data-link-format="yyyy-mm-dd"> -->
                                    <select  id="dmt" name="smt" class="form-control smt" >
<option value='1' selected>40%-79%</option>
<option value='2'>>=80%</option>
                                    </select>
									<!-- <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> -->
								<!-- </div> -->
                            </div>
                    </div>
            </div>
           
            				<!-- <div class="row" id="formm">                    
					</div>	
					 -->
	
<div class="col-md-12" >
	<table class=" preview table1">

<thead>
		<tr>
		
		<th style="width: 40%;">Taxability & Calculation as per old and New Tax Regime</th>
			<th style="width: 10%;">Provisional Old</th>
			<th style="width: 10%;">Provisional New</th>
			<th style="width: 10%;">Actual Old</th>
			<th style="width: 10%;">Actual New</th>
			<th style="width: 9%;">Document</th>
			</tr>
		</thead>
		<tbody class="table_lines1">
			

		</tbody>
	</table>
</div>

<div class="col-md-12">
	<table class=" preview table4">

<thead>
		<tr>
		
		<th>Taxable Income</th>
			<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody class="table_lines4">
			<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Taxable Income" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_tottincome" name="old_tottincome" class="form-control old_tottincome" value="" >	
				</td>
				<td>
				      <input type="text" id="new_tottincome" name="new_dus80" class="form-control new_dus80" value="" >	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Income Tax" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_intax" name="old_intax" class="form-control old_intax" value="" >	
				</td>
				<td>
				      <input type="text" id="new_intax" name="new_intax" class="form-control new_intax" value="" >	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Less: Rebate 87A" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_rebate87a" name="old_rebate87a" class="form-control old_rebate87a" value="" >	
				</td>
				<td>
				      <input type="text" id="new_rebate87a" name="new_rebate87a" class="form-control new_rebate87a" value="" >	
				</td>
			</tr>



<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Balance Tax Liability" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_btlib" name="old_btlib" class="form-control old_btlib" value="" >	
				</td>
				<td>
				      <input type="text" id="new_btlib" name="new_btlib" class="form-control new_btlib" value="" >	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Add: Surcharge" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_adsur" name="old_adsur" class="form-control old_adsur" value="" >	
				</td>
				<td>
				      <input type="text" id="new_adsur" name="new_adsur" class="form-control new_adsur" value="" >	
				</td>
			</tr>						


<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Total Tax" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_totaltax" name="old_totaltax" class="form-control old_totaltax" value="" >	
				</td>
				<td>
				      <input type="text" id="new_totaltax" name="new_totaltax" class="form-control new_totaltax" value="" >	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Add: Edu. Health Cess" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_addeduhc" name="old_addeduhc" class="form-control old_addeduhc" value="" >	
				</td>
				<td>
				      <input type="text" id="new_addeduhc" name="new_addeduhc" class="form-control new_addeduhc" value="" >	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Net Annual Tax" readonly="true">	
				</td>
				<td>
				      <input type="text" id="old_netannualtax" name="old_netannualtax" class="form-control old_netannualtax" value="" >	
				</td>
				<td>
				      <input type="text" id="new_netannualtax" name="new_netannualtax" class="form-control new_netannualtax" value="" >	
				</td>
			</tr>
		
		</tbody>
	</table>
</div>

	
                <div class="col-md-12 text-center ">
				
				
				<div class="row">
				<h5>Do You Want to Confirm Save ?</h5>
					Yes <input type="radio" name="confirm"  class="confirm" value="1" />
					No <input type="radio" name="confirm" class="confirm" value="0" />
				</div>
				<h4>After saving the details you can't edit.</h4>
                    <button type="button"  class="btn save savebut">Save</button> &nbsp;&nbsp;&nbsp;
                    <button type="button"  class="btn save draftbut">Draft</button> &nbsp;&nbsp;&nbsp;
                    <button type="button"  class="btn cancel" >Clear</button>
                </div>
              </div>
					
        </div> 
        
</form>

</div>






	<script>
	$(document).ready(function(){
		
		
		var employee_id = '{{Session::get('emp_id')}}';
       // disable button
	    $('.save').prop('disabled',true);
// employee drop down
		$("#employee_name").jCombo("{{ URL::to('jcomboform?table=hr_employee_t:employee_id:employee_number|first_name') }}&order_by=first_name asc",
		{selected_value:employee_id});
		// get investment data
		if(employee_id != '')
		{
			var url = "{{URL::to('empview')}}/"+employee_id;
			
			$.get(url,function(data)
			{
				$('#formm').html(data['table']);
				$('#pan_number').val(data['pan_no']).prop('readonly',true);
				$('#date_of_birth').val(data['dob']).prop('readonly',true);
				$('#date_of_joining').val(data['doj']).prop('readonly',true);
			});
		}
			$('#employee_name').on('change',function(){
		if(employee_id != '')
		{
	var employee_id=$("#employee_name").val();	   
			var url = "{{URL::to('getDeclaration')}}/"+employee_id;
			
			$.get(url,function(data)
			{
				
				$('#formm').html(data['table']);
					$('#allowancediv').html(data['alltbl']);
				$('#pan_number').val(data['pan_no']).prop('readonly',true);
				$('#date_of_birth').val(data['dob']).prop('readonly',true);
				$('#date_of_joining').val(data['doj']).prop('readonly',true);
				dob = new Date(data['dob']);
				var today = new Date();
				var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
				$('#age').val(age).prop('readonly',true);
				
				console.log(data['inv_hdr']);
				$('.Monthly_House_Rent_Paid').val(data['inv_hdr']['Monthly_House_Rent_Paid']);
				$('.old_tottincome').val(data['inv_hdr']['old_tottincome']);
				$('.new_tottincome').val(data['inv_hdr']['new_tottincome']);
				$('.old_intax').val(data['inv_hdr']['old_intax']);
				$('.new_intax').val(data['inv_hdr']['new_intax']);
				$('.old_rebate87a').val(data['inv_hdr']['old_rebate87a']);
				$('.new_rebate87a').val(data['inv_hdr']['new_rebate87a']);	
				$('.old_btlib').val(data['inv_hdr']['old_btlib']);
				$('.new_btlib').val(data['inv_hdr']['new_btlib']);
				 $('.new_dus80').val(data['inv_hdr']['new_dus80']);
				$('.old_adsur').val(data['inv_hdr']['old_adsur']);
				$('.new_adsur').val(data['inv_hdr']['new_adsur']);
				$('.old_totaltax').val(data['inv_hdr']['old_totaltax']);	
				$('.new_totaltax').val(data['inv_hdr']['new_totaltax']);
				$('.old_addeduhc').val(data['inv_hdr']['old_addeduhc']);
				$('.new_addeduhc').val(data['inv_hdr']['new_addeduhc']);
				$('.old_netannualtax').val(data['inv_hdr']['old_netannualtax']);
				$('.new_netannualtax').val(data['inv_hdr']['new_netannualtax']);
				$('.edit_id').val(data['inv_hdr']['id']);
				$('.table_lines1').html(data['htmllins']);	
				
          // $(".old_gasi").val(data['gross_pay']*12);
          // $(".new_gasi").val(data['gross_pay']*12);
          // $(".old_toe").val(data['pt_amount']*12);
          // $(".old_sd").val('50000');


			});
		}
		});
		// confirm button
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
               
		var form_data = new FormData(document.getElementById('save'));              
                $.ajax({
                  url: "{{URL::to('proofsubmissionsave')}}",
                  type: "POST",
                  data: form_data,
                  enctype: 'multipart/form-data',
                  processData: false,  // tell jQuery not to process the data
                  contentType: false,   // tell jQuery not to set contentType
                  async:true,
                  xhr: function(){
                      var xhr = $.ajaxSettings.xhr();
                    if (xhr.upload) {
                        xhr.upload.addEventListener('progress', function(event) {
                                var percent = 0;
                                var position = event.loaded || event.position;
                                var total = event.total;
                                if (event.lengthComputable) {
                                        percent = Math.ceil(position / total * 100);
                                }
                                        //update progressbar

                                }, true);
                        }
          return xhr;

                }
                }).done(function(data)
		{
	
		var edit_url	="{{URL::to('proofsubmission')}}";
		
		notyMsg("success","Saved Successfully");
		  setTimeout(function(){
                           window.location.reload();
                       },300);
		
	

		});

            }); 
                
      
		
		
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
