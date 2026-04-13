@extends('layouts.header')
@section('content')
<h3 class="text-danger">Attendance Monthly Report</h3>
@include('layouts.breadcrumb')



<div class="card">
<div class="card-body card-block">

        <form  action="" id="advance" data-parsley-validate >
        <input type="hidden" name="edit_id" value="" id="edit_id" />
            {{ csrf_field()}}
        <div class="row">
           <div class="col-md-6">
                
                <div class="form-group row">
                    <label for="start_date" class="form-control-label col-md-5"><span >*</span>Employee</label>
                    <div class="col-md-6" >
                      <select name='employee_id' rows='5' id="employee_id" class='select2' data-show-subtext="true" data-live-search="true" required>
						  
                      </select>
                    </div>
                </div>
                
                <div class="form-group row">
                        <label for="inputIsValid" class="form-control-label col-md-5">Year</label>
                        <div class="col-md-4">
							<div class="input-group form_date" data-link-format="yyyy-mm-dd">
								<select name="year" id="year" class="select2">
										
									</select>
							</div>
                        </div>
                </div>
				
				<div class="form-group row">
                        <label for="inputIsValid" class="form-control-label col-md-5">Month</label>
                        <div class="col-md-4">
							<div class="input-group form_date" data-link-format="yyyy-mm-dd">
									<select name="month" id="month"  class="select2">
										
									</select>
							</div>
                        </div>
                </div>
                </div>
              <div class="col-md-6">
                
                <div class="form-group row">
                   <div class="w3-container">
					  
					    <h4 class="heads"><i class="fa fa-info-circle" aria-hidden="true"></i>  Attendance Days Report</h4>
							<table>
							  <tr>
								<th><i class="fa fa-calendar" aria-hidden="true"></i> Attendance Details</th><th>No of Days</th>
								<th>Leaves</th><th>No of Days</th>
							  </tr>
								<tr>
								<th >Attendance Days</th><th id="at_days"></th>
								<th>CL</th><th id="cl"> </th>
							  </tr>
							  <tr>
								<th >Present Days</th><th id="ps_days"></th>
								<th>SL</th><th id="sl"> </th>
							  </tr>
							 <tr>
								<th >Absent Days</th><th id="abs_days"> </th>
								<th>EL</th><th id="el"> </th>
							  </tr>
							  <tr>
								<th >Holidays Days</th><th id="holi_days"> </th>
								<th></th><th> </th>
							  </tr>
							  <tr>
								<th >Sunday Days</th><th id="sun_days"> </th>
								<th></th><th> </th>
							  </tr>
							</table>
					  
					   
						
					</div>
                </div>
                
            
                </div>
        </div>
		
        <div class="row text-center">
            <button type="button" class="btn save save_form">Search</button> &nbsp;&nbsp;&nbsp;
            <button type='button' class='btn del clear' id="delete">Clear</button>
        </div>
    </form>

   


</div>
</div>

<div class="card block">
<div class="report ">
  
         

</div>
</div>

<script>
	$(document).ready(function()
        {
		
				// for year load and selected
        var min = 1900,
					max = new Date().getFullYear(),
					select = document.getElementById('year');

				for (var i = max; i>=min; i--){
					var opt = document.createElement('option');
					opt.value = i;
					opt.innerHTML = i;
					select.appendChild(opt);
					
				}
		
		
		$('.block').hide();
		$("#employee_id").jCombo("{{ URL::to('jcomboform?table=hr_employee_t:employee_id:first_name') }}");
		$("#month").jCombo("{{ URL::to('jcomboformlogin?table=month:id:month_name') }}");

		 var myDate = $.trim($(this).val());
 if(myDate!='0000-00-00')
 {
var parsedDate = $.datepicker.parseDate("yy-mm-dd", myDate);

$(this).val($.datepicker.formatDate("{{\Session::get('j_date_format')}}", parsedDate));
 }
 else {
   $(this).val('');
 }
		
		$(document).on('click','.save_form',function()
	    {
			  var form = $('#advance');
			  form.parsley().validate();
			  var form = $('#advance');
			  form.parsley().validate();
			
			  var emp_id = $('#employee_id').select2('val');
			  var year = $("#year").select2('val') != '' ? $("#year").select2('val') : '';
			  var month = $("#month").select2('val') != '' ? $("#month").select2('val') : ''; 
				
			  if(form.parsley().isValid())
			  {
				var url = "{{URL::to('attendancemonthly')}}?emp_id="+emp_id+"&year="+year+"&month="+month;
				$.get(url,function(data)
				{
						$('.block').show();
						$('.report').html(data['data']);
						$('#at_days').html(data['days'].total_days);
						$('#ps_days').html(data['days'].present_days);
						$('#abs_days').html(data['days'].absent_days);
						$('#holi_days').html(data['days'].holidays);
						$('#sun_days').html(data['days'].sundays);
						$('#cl').html(data['days'].casual);
						$('#sl').html(data['days'].sick);
						$('#el').html(data['days'].earn);
				});
			  }
			});
	});
	
	</script>
@include('layouts.php_js_validation')
@endsection
