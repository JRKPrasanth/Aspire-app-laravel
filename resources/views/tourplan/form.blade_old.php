
@extends('layouts.header')
@section('content')


<?php  error_reporting(0); ?>

<form action="" method="post" id="priceform" data-parsley-validate>
	
{{ csrf_field() }}


<div class="card">

<?php if($urlname == "tourplancreate" ) { ?>
	<div class="ajaxLoading"></div>
    <h2 class="heads"> Tour Plan
    	<span class="ui_close_btn">
    		<a href="{{URL::to('tourplan')}}" class="collapse-close pull-right btn-danger"></a>
    	</span> 
	</h2> 
<?php }elseif($urlname == "tourapproval" ) { ?>
	<h2 class="heads"> Tour Plan Approval
    	<span class="ui_close_btn">
    		<a href="{{URL::to($return_url)}}" class="collapse-close pull-right btn-danger"></a>
    	</span> 
	</h2> 
<?php } ?>
	<!--********************- Body content start here ********************-->
	<div class="card-body card-block">
	<div class="row">
	<div class="col-md-12">
	<div class="row">
		<div class="col-md-6">
			<div class="form-group row area_div">
		        <label for="inputIsValid" class="form-control-label col-md-5">Choose Month</label>
		        <div class="col-md-7">
		          	<select name="month_id" id="month_id" class="form-control month_id select2" >
	          			{!! $month_id !!}
		          	</select> 
		        </div>
			</div>


	  		<div class="form-group row">
	  			<label for="inputIsValid" class="form-control-label col-md-5">Tour Type <span style="color: red;" >*</span> </label>
	  			<div class="col-md-7">
	  				<input class="form-control tour_id" id="tour_id" name="tourprogram_id" size="16" type="hidden" value="{{ $row->tourprogram_id }}" readonly>
	  				<input class="form-control tour_status" id="tour_status" name="status" size="16" type="hidden" value="{{ $row->status }}" readonly>
					<select name="tour_details" class=" form-control tour_type select2 " id="tour_type"  required style="width: 100%;">
						{!! $tour_type !!}
					</select>
	  			</div>
  			</div>
  			<div class="form-group row area_div">
		        <label for="inputIsValid" class="form-control-label col-md-5">Tour Area</label>
		        <div class="col-md-7">
		          	<select name="tour_area[]" id="tour_area" class="form-control tour_area select2" multiple>
	          			{!! $tour_area !!}
		          	</select> 
		        </div>
			</div>

			<div class="form-group row area_div">
		        <label for="inputIsValid" class="form-control-label col-md-5">Doctor Name</label>
		        <div class="col-md-7">
		        	<div class="doctor_d"></div>
		        </div>
			</div>

	        
        </div>
        <div class="col-md-6">
        	<!-- <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-5">Tour Date </label>
		        <div class="col-md-7">
			        <div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
				        <td><input type="text" name="tour_date" class="tour_date datepicker form-control" id="tour_date" value="{{ $row->tour_date }}" data-link-format="yyyy-mm-dd"  style="width: 100%;"></td>
		        	</div>
		        </div>
		    </div>
 -->
		    <div class="form-group row">
		        <label for="inputIsValid" class="form-control-label col-md-5">Remarks </label>
		        <div class="col-md-7">
			        <input type="text" name="remarks" class="remarks form-control" id="remarks" value="{{ $row->remarks }}" >		        	
		        </div>
		    </div>
		</div>

	</div>
	</div>
	</div>
	</div>
	<!--********************- Body content start here ********************-->



	<div class="row">
		<div class="col-lg-12 col-md-12">
			<div class="form-group text-center">
			<?php if($urlname == "tourplancreate" ) { ?>
				<button type="button" id="save" class="btn save saveform" value="SAVE">Save</button>
				<button type="button" id="save" class="btn save saveform" value="SAVENEW">Save New</button>
			   	<a class='btn cancel' onclick="location.href = '{{url::to('tourplan')}}'">Cancel</a>
			   	<?php } else{ ?>
			   		<button type="button" id="save" class="btn save saveform" value="APPROVED">APPROVED</button>
			   		<button type="button" id="save" class="btn save saveform" value="REJECTED">REJECTED</button>
			   		<a class='btn cancel' onclick="location.href = '{{url::to('tourplanapproval')}}'">Cancel</a>
			   	<?php } ?>
			</div>
		</div>
	</div>

	<input type="hidden" class="pdtindex" value="" >
</div>

</form>

<script>

$(document).ready(function(){

	
	$(document).on('change','.tour_type',function(){
		var type=$(".tour_type").select2('val');
		if(type==3){
            notyMsg('info','Day is holiday');
		} else {
                    
		}
	});

	setTimeout(function(){
		var doctor_name = "{!! $doctor_d !!}";

		$(".doctor_d").append(doctor_name);
	},750);	


	$(document).on('change','.tour_area',function(){
		var area=[];
		area = $(".tour_area").val();
		var url = "{{ URL::to('areadoctor') }}/"+area;
		var html='';
		$.get(url,function(data){
			$(".doctor_d").empty();
			$.each(data,function(k,v){
				html += $(".doctor_d").append("<input type='checkbox' required='true' name='doctor_id["+v['doctors_adr_id']+"]' data-index='"+v['doctors_adr_id']+"' class='doctor_id' id='doctor_id' value="+v['doctor_id']+">"+v['doctor_name']+"<br>");
			});
		});
		var tour_id=$("#tour_id").val();
		if(tour_id!=""){

			var	check_data=$.parseJSON(<?php print json_encode(json_encode($check_doctor)); ?>);

			$.each(check_data,function(key,value){
				setTimeout(function(){
					$('[data-index='+value+']').attr('checked',true);
				},300);
			});
		}

	});


	$(document).on('click','.saveform',function(){

	   	var btnval		= $(this).val();
	   	if(btnval == "SAVE" || btnval == "SAVENEW"){
	   		var status="Pending";
	   	}else if(btnval == "APPROVED"){
	   		var status="APPROVED";
	   	}else if(btnval == "REJECTED"){
	   		var status="REJECTED";
	   	}
	   	$(".tour_status").val(status);

		$('.submit_type').val("save");

		var url= "{{ URL::to('tourplansave') }}";
		validationrule('priceform');
		var form = $('#priceform');

	 	var red_url = "{{ url('tourplan') }}";
		 
		    form.parsley().validate();
	        var form = $('#priceform');
	        form.parsley().validate();

			if(form.parsley().isValid())
            {
            	$('.ajaxLoading').show();
            	change_date();
            	var formdata	= $('#priceform').serialize();

			    $.post(url,formdata,function(data)
				{
				    var status      = data.status;
					var msg         = data.message;
				    var id          = data.id;
					var edit_url	= "{{ url('tourplancreate') }}";
					if(btnval !='SAVE')
                  	{
                        notyMsg(status,msg);
                        setTimeout(function(){
                        	window.location.href=edit_url;
                        }, 1500);
                  	}
			       	else
                   	{
						notyMsg(status,msg);
						setTimeout(function(){
							window.location.href=red_url;
						}, 1500);
                   	}

				  });
			}
	  	
		

	});


});



</script>
@include('layouts.php_js_validation')
@endsection
