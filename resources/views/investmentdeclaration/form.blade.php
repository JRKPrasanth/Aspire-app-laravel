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
 <div class="form-group row">
                        <label for="organization_id" class="form-control-label col-md-4">Age</label>
                            <div class="col-md-6">
								<!-- <div class="input-group form_date " data-date="" data-link-format="yyyy-mm-dd"> -->
                                    <input type="text" id="age" name="age" class="form-control age" value="" >
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
						 <div class="form-group row">
                        <label for="organization_id" class="form-control-label col-md-4">Declaration Type</label>
                            <div class="col-md-6">
								<select  id="type" name="type" class="select2 form-control type" >
                                    <option value='OLD' selected>OLD</option>
                                    <option value='NEW'>NEW</option>
                                </select>
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
                                    <select  id="rent_location" name="rent_location" class=" select2 form-control rent_location" >
<option value='Metro'>Metro</option>
<option value='Non Metro' selected>Non Metro</option>
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
		
		<th>Taxability & Calculation as per old and New Tax Regime</th>
			<th class="olddiv">Old</th>
			<th class="newdiv">New</th>
		</tr>
		</thead>
		<tbody class="table_lines1">
		    <?php foreach($set1 as $sk=>$sv){?>
		    	<tr>
    				<td>
    				</td>
    				<td>
    					<input type="text" id="{!! $sv->title_class !!}" name='title[]' class="form-control {!! $sv->title_class !!}" value="{!! $sv->inv_name !!}" readonly="true">	
    				</td>
    				<td class="olddiv">
    				      <input type="text" id="{!! $sv->old_class !!}" name="old[]" class="form-control {!! $sv->old_class !!}" value="" >	
    				</td>
    				<td class="newdiv">
    				      <input type="text" id="{!! $sv->new_class !!}" name="new[]" class="form-control {!! $sv->new_class !!}" value="" >	
    				</td>
    			</tr>
		    <?php }?>
					
			
		</tbody>
	</table>
</div>

<div class="col-md-12">
	<table class="preview table2">

<thead>
		<tr>
		
		<th>Investments U/S 80C & 80CCC	(Max Limit 150000)</th>
			<th class="olddiv"></th>
			<th class="newdiv"></th>
		</tr>
		</thead>
		<tbody class="table_lines2">
		    
		    <?php  foreach($set2 as $sk=>$sv){?>
		    	<tr>
    				<td>
    				</td>
    				<td>
    					<input type="text" id="{!! $sv->title_class !!}" name='title[]' class="form-control {!! $sv->title_class !!}" value="{!! $sv->inv_name !!}" readonly="true">	
    				</td>
    				<td class="olddiv">
    				      <input type="text" id="{!! $sv->old_class !!}" name="old[]" class="form-control {!! $sv->old_class !!}  <?php if($sv->inv_id!=26){echo 'cc80';}else{echo 'cc80_total';}?>" value="" >	
    				</td>
    				<td class="newdiv">
    				      <input type="text" id="{!! $sv->new_class !!}" name="new[]" class="form-control {!! $sv->new_class !!} <?php if($sv->inv_id!=26){echo 'cc80n';}else{echo 'cc80n_total';}?>" value="" >	
    				</td>
    			</tr>
		    <?php }?>
		</tbody>
	</table>
</div>

<div class="col-md-12">
	<table class=" preview table3">
        <thead>
		    <tr>
		        <th>Investments U/S 80C & 80CCC	Deduction</th>
			    <th class="olddiv"></th>
				<th class="newdiv"></th>
			</tr>
		</thead>
		<tbody class="table_lines3">
		    <?php foreach($set3 as $sk=>$sv){?>
		    	<tr>
    				<td>
    				</td>
    				<td>
    					<input type="text" id="{!! $sv->title_class !!}" name='title[]' class="form-control gasi {!! $sv->title_class !!}" value="{!! $sv->inv_name !!}" readonly="true">	
    				</td>
    				<td class="olddiv">
    				      <input type="text" id="{!! $sv->old_class !!}" name="old[]" class="form-control {!! $sv->old_class !!}  <?php if($sv->inv_id!=39){echo 'deduction_80c';}else{echo 'deduction_80c_total';}?>" value="" >	
    				</td>
    				<td class="newdiv">
    				      <input type="text" id="{!! $sv->new_class !!}" name="new[]" class="form-control {!! $sv->new_class !!} <?php if($sv->inv_id!=39){echo 'deduction_80cn';}else{echo 'deduction_80cn_total';}?>" value=""  readonly="true">	
    				</td>
    			</tr>
		    <?php }?>
		</tbody>
	</table>
</div>

<div class="col-md-12">
	<table class=" preview table4">

<thead>
		<tr>
		
		<th>Taxable Income</th>
			<th class="olddiv"></th>
				<th class="newdiv"></th>
			</tr>
		</thead>
		<tbody class="table_lines4">
			<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Taxable Income" readonly="true">	
				</td>
				<td class="olddiv">
				      <input type="text" id="old_tottincome" name="old_tottincome" class="form-control old_tottincome" value="" >	
				</td>
				<td class="newdiv">
				      <input type="text" id="new_tottincome" name="new_dus80" class="form-control new_dus80" value="" >	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Income Tax" readonly="true">	
				</td>
				<td class="olddiv">
				      <input type="text" id="old_intax" name="old_intax" class="form-control old_intax" value="" >	
				</td>
				<td class="newdiv">
				      <input type="text" id="new_intax" name="new_intax" class="form-control new_intax" value="" >	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Less: Rebate 87A" readonly="true">	
				</td>
				<td class="olddiv">
				      <input type="text" id="old_rebate87a" name="old_rebate87a" class="form-control old_rebate87a" value="" >	
				</td>
				<td class="newdiv">
				      <input type="text" id="new_rebate87a" name="new_rebate87a" class="form-control new_rebate87a" value="" >	
				</td>
			</tr>



<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Balance Tax Liability" readonly="true">	
				</td>
				<td class="olddiv">
				      <input type="text" id="old_btlib" name="old_btlib" class="form-control old_btlib" value="" >	
				</td>
				<td class="newdiv">
				      <input type="text" id="new_btlib" name="new_btlib" class="form-control new_btlib" value="" >	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Add: Surcharge" readonly="true">	
				</td>
				<td class="olddiv">
				      <input type="text" id="old_adsur" name="old_adsur" class="form-control old_adsur" value="" >	
				</td>
				<td class="newdiv">
				      <input type="text" id="new_adsur" name="new_adsur" class="form-control new_adsur" value="" >	
				</td>
			</tr>						


<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Total Tax" readonly="true">	
				</td>
				<td class="olddiv">
				      <input type="text" id="old_totaltax" name="old_totaltax" class="form-control old_totaltax" value="" >	
				</td>
				<td class="newdiv">
				      <input type="text" id="new_totaltax" name="new_totaltax" class="form-control new_totaltax" value="" >	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Add: Edu. Health Cess" readonly="true">	
				</td>
				<td class="olddiv">
				      <input type="text" id="old_addeduhc" name="old_addeduhc" class="form-control old_addeduhc" value="" >	
				</td>
				<td class="newdiv">
				      <input type="text" id="new_addeduhc" name="new_addeduhc" class="form-control new_addeduhc" value="" >	
				</td>
			</tr>
<tr>
				<td>
				</td>
				<td>
					<input type="text" id="gasi" class="form-control gasi" value="Net Annual Tax" readonly="true">	
				</td>
				<td class="olddiv">
				      <input type="text" id="old_netannualtax" name="old_netannualtax" class="form-control old_netannualtax" value="" >	
				</td>
				<td class="newdiv">
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
	    //$('.save').prop('disabled',true);
// employee load
		$("#employee_name").jCombo("{{ URL::to('jcomboform?table=hr_employee_t:employee_id:employee_number|first_name') }}&order_by=first_name asc",
		{selected_value:employee_id});
		// get investment type
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
				
				$('#date_of_joining').val(data['doj']).prop('readonly',true);
				if(data['dob']!='0000-00-00' && data['dob']!=''){
				    $('#date_of_birth').val(data['dob']).prop('readonly',true);
    				dob = new Date(data['dob']);
    				var today = new Date();
    				var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
    				$('#age').val(age).prop('readonly',true);
                }else{
                    $('#date_of_birth').val('').prop('readonly',true);
                    $('#age').val('').prop('readonly',true);
                }
          $(".old_gasi").val(data['gross_pay']*12);
          $(".new_gasi").val(data['gross_pay']*12);
          $(".old_toe").val(data['pt_amount']*2);
          $(".old_sd").val('50000');


			});
		}
		});
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
$(document).on('change','#type',function(){
    // console.log($(this).val());
    // alert('hh');
   if($(this).val()=='OLD'){
       $('.olddiv').show();
       $('.newdiv').hide();
   }else{
       $('.olddiv').hide();
       $('.newdiv').show();
   } 
});
$('#type').trigger('change');
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



  $(document).on('change','.Monthly_House_Rent_Paid',function()
			   {
				    var rent=$(this).val();
				    var basics=parseFloat($("#allowance_name_1").val())+parseFloat($("#allowance_name_3").val());
				    //alert(basics);
				    var hra=$("#allowance_name_2").val()*12;
				    var rent_location=$(".rent_location").val();
				    if(rent_location=='Metro')
				    	var basic=((basics*50)/100)*12;
				    else
				    	var basic=((basics*40)/100)*12;

				   var  basic_rent=(rent*12)-(((basics*10)/100)*12);



    if (basic_rent <= basic && basic_rent <= hra)
    {
       $(".old_hrae").val(basic_rent);
    }
     else if (basic <= basic_rent && basic <= hra)
    {
        $(".old_hrae").val(basic);
    }
     else
    {
        $(".old_hrae").val(hra);
    }

$(".old_hrae").trigger('change');

    });

 $(document).on('change','.old_hrae',function()
			   {
			   	var val=$(this).val();

          var gross=$(".old_gasi").val();
          //$(".new_gasi").val();
          var pt=$(".old_toe").val();
          var st=$(".old_sd").val();

          var toatl=gross-pt-st-val;
          $(".old_ins").val(toatl);
          $(".new_ins").val(gross);

          $(".ex_in").trigger('change');
 });




$(document).on('change','.old_lhp',function()
{
    var val=parseFloat($(this).val());
    if(val>200000)
    {
    notyMsg('error','Maximum (200000) Limit Exceed');
	    //$(".cc80_total").val(ex_in-val);
	$(this).val(200000);	
    }
    $(".ex_in").trigger('change');
});

$(document).on('change','.ex_in,.ex_in_n',function()
{
    var ex_in=0;
    var ex_in_n=0;
    
    $(".ex_in").each(function(index,val){
        var eVal = parseFloat($(this).val()) || 0;
        ex_in=ex_in+eVal;
    });
    $(".ex_in_n").each(function(index,val){
        var eVal = parseFloat($(this).val()) || 0;
        ex_in_n=ex_in_n+eVal;
    });
	var old_ins= parseFloat($(".old_ins").val());
	var de=parseFloat($(".old_lhp").val()) || 0;
    var new_ins= parseFloat($(".new_ins").val());
    $(".old_gti").val(parseFloat(old_ins+ex_in-de).toFixed(2));
    $(".new_gti").val(parseFloat(new_ins+ex_in_n-de).toFixed(2));
    $(".deduction_80c").trigger('change');
    $(".cc80").trigger('change');
    //old_gti
});


$(document).on('change','.cc80',function()
{
    var max_limt=150000;
    var ex_in=0;
    var val=parseFloat($(this).val());
    $(".cc80").each(function(index,val){
        var eVal = parseFloat($(this).val()) || 0;
        ex_in=ex_in+eVal;
    });
    if(ex_in>max_limt)
    {
    	notyMsg('error','Maximum 80cc Limit Exceed');
    	$(".cc80_total").val(ex_in-val);
    	$(this).val(0);
    }else {
    	$(".cc80_total").val(ex_in);
    }		   	
    $(".deduction_80c").trigger('change');
});

$(document).on('change','.cc80n',function()
{
    var max_limt=0;
    var ex_in=0;
    var val=parseFloat($(this).val());
    $(".cc80n").each(function(index,val){
        var eVal = parseFloat($(this).val()) || 0;
        ex_in=ex_in+eVal;
    });
    if(ex_in>max_limt)
    {
    	notyMsg('error','Maximum 80cc Limit Exceed');
    	$(".cc80_total").val(ex_in-val);
    	$(this).val(0);
    }
    else {
	    $(".cc80n_total").val(ex_in);
    }		   	
});
$(document).on('change','.rent_location',function()
{
    $(".Monthly_House_Rent_Paid").trigger('change');
});

var ages=parseInt($(".age").val()) || 0;
if(ages>=60){$('.old_dus80title').val('Deduction u/s 80D (Max Limit 50000)');}else{$('.old_dus80title').val('Deduction u/s 80D (Max Limit 25000)');}

$(document).on('change','.old_dus80',function()
{
    var age=parseInt($(".age").val()) || 0;
    var val=parseFloat($(this).val());
    if(age>=60)
    {
    	if(val>50000)
    	{
            notyMsg('error','Maximum (50000) Limit Exceed');
            $(this).val('');
    	}
    }
    else
    {
    	if(val>25000)
    	{
            notyMsg('error','Maximum (25000) Limit Exceed');
            $(this).val('');
    	}
    }
});
			   
var dmt=parseInt($(".dmt").val()) || 0;
if(dmt==1){$('.old_dus80dtitle').val('Deduction u/s 80DD (Max Limit 75000)');}else{$('.old_dus80dtitle').val('Deduction u/s 80DD (Max Limit 125000)');}

$(document).on('change','.old_dus80d',function()
{
    var age=parseInt($(".dmt").val()) || 0;
    var val=parseFloat($(this).val());
    if(age==1)
    {
    	if(val>75000)
    	{
            notyMsg('error','Maximum (75000) Limit Exceed');
            $(this).val('');
    	}
    }
    else
    {
    	if(val>125000)
    	{
            notyMsg('error','Maximum (125000) Limit Exceed');
            $(this).val('');
    	}
    }
});


if(ages>=60){$('.old_dus80ddbtitle').val('Deduction u/s 80DDB (Max Limit 100000)');}else{$('.old_dus80ddbtitle').val('Deduction u/s 80DDB (Max Limit 40000)');}

$(document).on('change','.old_dus80ddb',function()
{
    var age=parseInt($(".age").val()) || 0;
    var val=parseFloat($(this).val());
    if(age>=60)
    {
    	if(val>100000)
    	{
            notyMsg('error','Maximum (100000) Limit Exceed');
            $(this).val('');
    	}
    }
    else
    {
    	if(val>40000)
    	{
            notyMsg('error','Maximum (40000) Limit Exceed');
            $(this).val('');
    	}
    }
});
     
$(document).on('change','.old_dus80ee',function()
{
    var val=$(this).val();
	if(val>50000)
	{
        notyMsg('error','Maximum (50000) Limit Exceed');
        $(this).val('');
	}

});


$(document).on('change','.old_dus80eea',function()
{
    var val=$(this).val();
	if(val>150000)
	{
        notyMsg('error','Maximum (50000) Limit Exceed');
        $(this).val('');
	}

 });


$(document).on('change','.old_dus80eeb',function()
{
    var val=$(this).val();
	if(val>150000)
	{
        notyMsg('error','Maximum (50000) Limit Exceed');
        $(this).val('');
	}

 });


if(ages>=60){$('.old_dus80ttatitle').val('Deduction u/s 80TTA (Max Limit 0)');}else{$('.old_dus80ttatitle').val('Deduction u/s 80TTA (Max Limit 10000)');}


$(document).on('change','.old_dus80tta',function()
{
    var age=parseInt($(".age").val()) || 0;
    var val=parseFloat($(this).val());
    if(age>=60)
    {
    	if(val>0)
    	{
            // notyMsg('error','Maximum (100000) Limit Exceed');
            notyMsg('error','Maximum (0) Limit Exceed');
            $(this).val('');
    	}
    }
    else
    {
    	if(val>10000)
    	{
            // notyMsg('error','Maximum (40000) Limit Exceed');
            notyMsg('error','Maximum (10000) Limit Exceed');
            $(this).val('');
    	}
    }
});

var smt=parseInt($(".smt").val()) || 0;
if(smt==1){$('.old_dus80utitle').val('Deduction u/s 80U (Max Limit 75000)');}else{$('.old_dus80utitle').val('Deduction u/s 80U (Max Limit 125000)');}


$(document).on('change','.old_dus80u',function()
{
    var age=parseInt($(".smt").val()) || 0;
    var val=parseFloat($(this).val());
    if(age==1)
    {
    	if(val>75000)
    	{
            notyMsg('error','Maximum (75000) Limit Exceed');
            $(this).val('');
    	}
    }
    else
    {
    	if(val>125000)
    	{
            notyMsg('error','Maximum (125000) Limit Exceed');
            $(this).val('');
    	}
    }
});



$(document).on('change','.deduction_80c',function()
{
    var ex_in=0;
	$(".deduction_80c").each(function(index,val){
        var eVal = parseFloat($(this).val()) || 0;
        ex_in=ex_in+eVal;
    });

var cc_dedu=parseFloat($(".cc80_total").val()) || 0;
var ccn_dedu=parseFloat($(".cc80n_total").val()) || 0;
var old_gti=parseFloat($(".old_gti").val()) || 0;
var new_gti=parseFloat($(".new_gti").val()) || 0;

$(".deduction_80c_total").val(ex_in);

$(".old_tottincome").val(old_gti-ex_in-cc_dedu);
$("#new_tottincome").val(new_gti-ccn_dedu);


//Old Tax Rule

var old_total=total=old_gti-ex_in-cc_dedu;

var old_tax=$.parseJSON("{{$old}}".replace(/&quot;/g,'"'));
//console.log(old_tax);

var remain_amou=total;
var old_tax_amount=0;

$.each(old_tax,function(index,val){

if(total>=val.from_value  && remain_amou>0)
{
	var a=val.to_value-val.from_value;
    
    var re=remain_amou-a;

    if(re<0)
    {

     old_tax_amount=old_tax_amount+((remain_amou*(val.precentage))/100);	
     remain_amou=0;
    }
    else
    {
    	old_tax_amount=old_tax_amount+((a*(val.precentage))/100);
    	remain_amou=remain_amou-a;
    }

}


});

$(".old_intax").val(Math.round(old_tax_amount));



//New Tax Rule

var new_total=total=new_gti-ccn_dedu;

var new_tax=$.parseJSON("{{$new}}".replace(/&quot;/g,'"'));
//console.log(old_tax);

var remain_amou=total;
var new_tax_amount=0;

$.each(new_tax,function(index,val){

if(total>=val.from_value  && remain_amou>0)
{
	//console.log("fdfd");
	var a=val.to_value-val.from_value;
    
    var re1=parseFloat(remain_amou-a);
//console.log(re1);
    if(re1<=0)
    {
//console.log(remain_amou);
     new_tax_amount=new_tax_amount+((remain_amou*(val.precentage))/100);	
     remain_amou=0;
    }
    else
    {
    	new_tax_amount=new_tax_amount+((a*(val.precentage))/100);
    	remain_amou=remain_amou-a;
    }

}


});
new_tax_amount=parseFloat(new_tax_amount).toFixed(2);
$(".new_intax").val(new_tax_amount);
old_tax_amount=Math.round(old_tax_amount);

if(new_total<=500000)
{
$(".new_rebate87a").val(new_tax_amount);
$(".new_btlib").val(0);
$(".new_adsur").val(0);
$(".new_totaltax").val(0);
$(".new_addeduhc").val(0);
$(".new_netannualtax").val(0);	
}
else
{
$(".new_rebate87a").val(0);
$(".new_btlib").val(new_tax_amount);
$(".new_adsur").val(0);
$(".new_totaltax").val(new_tax_amount);
var cess=Math.round((new_tax_amount*4)/100);
$(".new_addeduhc").val(cess);

console.log(cess+new_tax_amount);

$(".new_netannualtax").val(parseFloat(parseFloat(cess)+parseFloat(new_tax_amount)).toFixed(2));	
    
}
if(old_total<=500000)
{
$(".old_rebate87a").val(old_tax_amount);
$(".old_btlib").val(0);
$(".old_adsur").val(0);
$(".old_totaltax").val(0);
$(".old_addeduhc").val(0);
$(".old_netannualtax").val(0);	
}
else
{
$(".old_rebate87a").val(0);
$(".old_btlib").val(old_tax_amount);
$(".old_adsur").val(0);
$(".old_totaltax").val(old_tax_amount);
var cess=Math.round((old_tax_amount*4)/100);
$(".old_addeduhc").val(cess);
$(".old_netannualtax").val((cess+old_tax_amount));		
}


			   });






//deduction_80c_total



	});








	</script>

@endsection

