@extends('layouts.header')
@section('content')
<span class="ui_close_btn"></span>


<form action="{{ url('empexpuploadsave') }}" method="post" id="empexpupload" data-parsley-validate>
{{ csrf_field() }}



		<h2 class="heads">Employee Expenses Upload

<span class="ui_close_btn"><a href="../empexpupload" class="collapse-close pull-right btn-danger" onclick="../empexpupload"></a></span>
		</h2>
		



<div class="card">
	

<div class="card-body card-block">
<div class="col-md-12">
    <div class="col-md-4">
		
	 	<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">User Name</label>
			<div class="col-md-6">
				<input class="form-control expupl_id" id="expupl_id" name="expupl_id" size="16" type="hidden" value="{{ $empexpuploaddata['expupl_id'] }}" readonly>
				<input type="text" id="UserName" name="UserName" class="form-control UserName" value="{{ $empexpuploaddata['UserName'] }}">
			</div>
			<div class="col-md-2 showline"></div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Zone</label>
			<div class="col-md-6">
				<input type="text" id="Zone" name="Zone" class="form-control Zone" value="{{ $empexpuploaddata['Zone'] }}">
			</div>
			<div class="col-md-2 showline"></div>
		</div>
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Employee Name </label>
			<div class="col-md-6">
				<input type="text" id="EmployeeName" name="EmployeeName" class="form-control EmployeeName" value="{{ $empexpuploaddata['EmployeeName'] }}">
			</div>
			<div class="col-md-2 showline"></div>
		</div>

		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Employee Number </label>
			<div class="col-md-6">
				<input type="text" id="EmployeeNumber" name="EmployeeNumber" class="form-control EmployeeNumber" value="{{ $empexpuploaddata['EmployeeNumber'] }}">
			</div>
			<div class="col-md-2 showline"></div>
		</div>

		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Designation</label>
			<div class="col-md-6">
				<input type="text" id="Designation" name="Designation" class="form-control Designation" value="{{ $empexpuploaddata['Designation'] }}">
			</div>
			<div class="col-md-2 showline"></div>
		</div>
	  	
	  	<div class="form-group row ">
			<label for="inputIsValid" class="form-control-label col-md-4">HQ Name </label>
			<div class="col-md-6">
				<input type="text" id="HQName" name="HQName" class="form-control HQName" value="{{ $empexpuploaddata['HQName'] }}">
			</div>
			<div class="col-md-2 showline"></div>
		</div>

		<div class="form-group row ">
			<label for="inputIsValid" class="form-control-label col-md-4">DCR Period From </label>
			<div class="col-md-6">
				<input type="text" id="DCRPeriodFrom" name="DCRPeriodFrom" class="form-control DCRPeriodFrom" value="{{ $empexpuploaddata['DCRPeriodFrom'] }}">
			</div>
			<div class="col-md-2 showline"></div>
		</div>
		
		<div class="form-group row ">
			<label for="inputIsValid" class="form-control-label col-md-4">DCR Period To </label>
			<div class="col-md-6">
				<input type="text" id="DCRPeriodTo" name="DCRPeriodTo" class="form-control DCRPeriodTo" value="{{ $empexpuploaddata['DCRPeriodTo'] }}">
			</div>
			<div class="col-md-2 showline"></div>
		</div>
		
		<div class="form-group row ">
			<label for="inputIsValid" class="form-control-label col-md-4">Courier and Postage Cost </label>
			<div class="col-md-6">
				<input type="Number" id="CourierandPostageCost" name="CourierandPostageCost" class="form-control CourierandPostageCost" value="{{ $empexpuploaddata['CourierandPostageCost'] }}">
			</div>
			<div class="col-md-2 showline"></div>
		</div>
		
		<div class="form-group row fdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Daily Joint Work Allowance </label>
			<div class="col-md-6">
				<input type="Number" id="DailyJointWorkAllowance" name="DailyJointWorkAllowance" class="form-control DailyJointWorkAllowance" value="{{ $empexpuploaddata['DailyJointWorkAllowance'] }}">
			</div>
			<div class="col-md-2 showline"></div>
		</div>

	    <div class="form-group row fdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Doctor Gift Cost</label>
			<div class="col-md-6">
				<input type="Number" id="DoctorGiftCost" name="DoctorGiftCost" class="form-control DoctorGiftCost" value="{{ $empexpuploaddata['DoctorGiftCost'] }}">
			</div>
			<div class="col-md-2"></div>
		</div>
	  	
	  	<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Hill Station Allowances</label>
			<div class="col-md-6">
			  	<input type="text" id="HillStationAllowances" name="HillStationAllowances" class="form-control HillStationAllowances" value="{{ $empexpuploaddata['HillStationAllowances'] }}">
			</div>
			<div class="col-md-2"></div>
		</div>

		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Internet and Mobile</label>
			<div class="col-md-6">
				<input type="text" id="InternetandMobile" name="InternetandMobile" class="form-control InternetandMobile" value="{{ $emmpexpuploaddata['InternetandMobile'] }}">
			</div>
			<div class="col-md-2 showline"></div>
		</div>

		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Joint Work</label>
			<div class="col-md-6">
				<input type="text" id="JointWork" name="JointWork" class="form-control JointWork" value="{{ $empexpuploaddata['JointWork'] }}">
			</div>
			<div class="col-md-2 showline"></div>
		</div>
		
	    
  	<div class="col-md-4" >
		
		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Others</label>
			<div class="col-md-6">
			<input type="text" id="Others" name="Others" class="form-control Others" value="{{ $empexpuploaddata['Others'] }}">
			</div>
			<div class="col-md-2 showline"></div>
		</div>

		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Metro</label>
			<div class="col-md-6">
				<input type="text" id="Metro" name="Metro" class="form-control Metro" value="{{ $empexpuploaddata['Metro'] }}">
			</div>
			<div class="col-md-2"></div>
		</div>
		
		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Rail Bus Pass Allowance</label>
			<div class="col-md-6">
				<input type="text" id="RailBusPassAllowance" name="RailBusPassAllowance" class="form-control RailBusPassAllowance" value="{{ $empexpuploaddata['RailBusPassAllowance'] }}">
			</div>
			<div class="col-md-2"></div>
		</div>

		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Retail Gift Cost</label>
			<div class="col-md-6">
				<input type="text" id="RetailGiftCost" name="RetailGiftCost" class="form-control RetailGiftCost" value="{{ $empexpuploaddata['RetailGiftCost'] }}">
			</div>
			<div class="col-md-2"></div>
		</div>

		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Travel Actuals</label>
			<div class="col-md-6">
				<input type="text" id="TravelActuals" name="TravelActuals" class="form-control TravelActuals" value="{{ $empexpuploaddata['TravelActuals'] }}">
			</div>
			<div class="col-md-2"></div>
		</div>

		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Travel Allowance</label>
			<div class="col-md-6">
				<input type="text" id="TravelAllowance" name="TravelAllowance" class="form-control TravelAllowance" value="{{ $empexpuploaddata['TravelAllowance'] }}">
			</div>
			<div class="col-md-2"></div>
		</div>

		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Vehicle Local travel Allowance</label>
			<div class="col-md-6">
				<input type="text" id="VehicleLocaltravelAllowance" name="VehicleLocaltravelAllowance" class="form-control VehicleLocaltravelAllowance" value="{{ $empexpuploaddata['VehicleLocaltravelAllowance'] }}">
			</div>
			<div class="col-md-2 showline"></div>
		</div>

		

		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Transit</label>
			<div class="col-md-6">
				<input type="text" id="Transit" name="Transit" class="form-control Transit" value="{{ $empexpuploaddata['Transit'] }}">
			</div>
			<div class="col-md-2 showline"></div>
		</div>


		<div class="form-group row sublocate stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">OS Meeting With Stay</label>
			<div class="col-md-6">
			<input type="text" id="OSMeetingWithStay" name="OSMeetingWithStay" class="form-control OSMeetingWithStay" value="{{ $empexpuploaddata['OSMeetingWithStay'] }}">
			</div>
			<div class="col-md-2 showline"></div>
		</div>
		
		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Ex Hq</label>
			<div class="col-md-6">
				<input type="text" id="ExHq" name="ExHq" class="form-control ExHq" value="{{ $empexpuploaddata['ExHq'] }}">
			</div>
			<div class="col-md-2"></div>
		</div>

		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Os Ex</label>
			<div class="col-md-6">
				<input type="text" id="OsEx" name="OsEx" class="form-control OsEx" value="{{ $empexpuploaddata['OsEx'] }}">
			</div>
			<div class="col-md-2"></div>
		</div>

		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">HQ</label>
			<div class="col-md-6">
				<input type="text" id="HQ" name="HQ" class="form-control HQ" value="{{ $empexpuploaddata['HQ'] }}">
			</div>
			<div class="col-md-2"></div>
		</div>

		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">OS</label>
			<div class="col-md-6">
				<input type="text" id="OS" name="OS" class="form-control OS" value="{{ $empexpuploaddata['OS'] }}">
			</div>
			<div class="col-md-2"></div>
		</div>

		
	</div>

  	<div class="col-md-4">

  		
		<div class="form-group row stdiv">
			<label for="inputIsValid" class="form-control-label col-md-4">Total</label>
			<div class="col-md-6">
				<input type="text" id="Total" name="Total" class="form-control Total" value="{{ $empexpuploaddata['Total'] }}">
			</div>
			<div class="col-md-2"></div>
		</div>

		
		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Batch Name</label>
			<div class="col-md-6">
				<input type="text" name="batchname" id="batchname" value="{{ $empexpuploaddata['batchname'] }}" class="form-control batchname" readonly>
			</div>
			<div class="col-md-2 showline">

				</div>
		</div>

		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Batch Date</label>
			<div class="col-md-6">
				<input type="text" name="batchdate" id="batchdate" value="{{ $empexpuploaddata['batchdate'] }}" class="form-control batchdate" readonly>
			</div>
			<div class="col-md-2 showline"></div>
		</div>

		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Batch Status</label>
			<div class="col-md-6">
				<input type="text" name="batchstatus" id="batchstatus" value="{{ $empexpuploaddata['batchstatus'] }}" class="form-control batchstatus" readonly>
			</div>
			<div class="col-md-2 showline">

				</div>
		</div>

		<div class="form-group row">
			<label for="inputIsValid" class="form-control-label col-md-4">Batch Comments</label>
			<div class="col-md-6">
				<textarea  id="batchcomments" readonly name="batchcomments" class="form-control batchcomments">{{ $empexpuploaddata['batchcomments'] }}</textarea>
				
			</div>
			<div class="col-md-2 showline"></div>
		</div>

	</div>
</div>	     
<br>
   
</div>
<div class="row">
	<div class="col-lg-12 col-md-12">
		<div class="form-group text-center">
			<button type="button" class="btn save">Submit</button>
			<a href="../empexpupload" class='btn cancel'  >Cancel</a>

		</div>
	</div>
</div>
	
</div>

	
	</form>

	

<script>
/*$(document).ready(function(){

	$(".subinventory_name").change(function(){
		var sub_name =$('.subinventory_name').select2('val');
		console.log(sub_name);
		var condition = ' subinventory_id='+sub_name; 	 
		$(".sublocator_name").jCombo("{{ URL::to('jcomboform?table=m_sublocators_t:sublocator_id:locator_code')}}&order_by=locator_code asc"+'&parent='+condition, {selected_value:""});

	});*/

	$(document).on('click','.save',function()
    {
    	var url		= "{{ URL::to('empexpuploadsave') }}";
    	var create_url = "{{ URL::to('empexpupload') }}";
    	var formdata	= $('#empexpupload').serialize();
    	var form = $('#empexpupload');
    	$.post(url,formdata,function(data)
        {

        	var status      = data.status;
			var msg			=data.message;
            notyMsg(status,msg);
            setTimeout(function(){
           		window.location.href=create_url;
            }, 1500);
        });
    });

/*});*/

</script>

@endsection