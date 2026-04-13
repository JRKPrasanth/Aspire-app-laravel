@extends('layouts.header')
@section('content')

 <?php include('tools_menu.php'); ?> <h3 class="heads">Daily Activity
<span class="ui_close_btn"><a class="collapse-close pull-right btn-danger" onclick='location.href="{{ url($return_url) }}"'></a></span>
</h3>


<?php //dd($this->data['pageMethod']); ?>



<form method="post" action="" id="dailyactivityform" class="dailyactivityform" data-parsley-validate>
{{ csrf_field() }}
<div class="card">
<div class="card-body card-block">


	<!------------------------------------- Body content start here ---------------------------->

				<div class="row">
                    <div class="row">



				<div class="col-md-12">
      
<input type="hidden" name="emp_daily_activity_id" class="emp_daily_activity_id" value="{{$row->emp_daily_activity_id}}">
      <div class="col-md-6 form-group row">
        <label for="inputIsValid" class="form-control-label col-md-4"><span style="color:red;" >&#42;</span>Employee Name</label>
        <div class="col-md-6 supplier_div">
            <select name='employee_id' rows='5' class='form-control employee_id select2' data-show-subtext="true" data-live-search="true" required>
                {!! $employee_id !!}
            </select>
        </div>
        <div class="col-md-2 showinline ichide">
            <span class="showspan"><i class="fa fa-refresh jcr_employee_id"></i></span>
        </div>
    </div>
<div class="form-group col-md-6 ">
                        <label for="activity_date" class="form-control-label col-md-4">Activity Date</label>
        <div class="col-md-6 ">
            <input type='text' name="activity_date" id="activity_date" rows='5' class='form-control activity_date datepicker' data-link-format="yyyy-mm-dd" value="{{$row->activity_date}}">
        </div>
        <div class="col-md-2">
        </div>
                    </div>
   <div class="col-md-6 form-group row">
        <label for="description" class="form-control-label col-md-4">Description</label>
        <div class="col-md-6">
            <textarea name="description" id="description" rows='5' class='form-control description'>{{ $row->description }}</textarea>
        </div>
        <div class="col-md-2">
        </div>
    </div>
       <div class="col-md-6 form-group">  
        <label for="inputIsValid" class="form-control-label col-md-4"><span class="reqstar" style="color:red;" >&#42;</span>Hour</label>
        <div class="col-md-6">
            <input type="text" value="{{ $row->hour }}" name="hour" class="form-control hour">
        </div>
        <div class="col-md-2">
           
        </div>
    </div> 
       <div class="col-md-6 form-group">  
        <label for="inputIsValid" class="form-control-label col-md-4"> Remarks</label>
        <div class="col-md-6">
            <input type="text" value="{{$row->remarks}}" name="remarks" class="form-control remarks">
        </div>
        <div class="col-md-2">
           
        </div>
    </div> 
    </div>
</div>

</div>





<div class="row">
	<div class="col-lg-12 col-md-12">
		<div class="form-group text-center actionbtn">
            <button name="submit" type="button" class="btn save saveform" value="SAVENEW">Submit and New</button>
            <button name="submit" type="button" class="btn save saveform" value="SAVE">Submit</button>
			<a class='btn cancel' onclick='location.href="{{ url($return_url) }}"'>Cancel</a>
</div>
</div>
</div>
</div>

</div>

  	</form>
	<script>

$(document).ready(function(){
	
$(document).on('click','.jcr_employee_id',function(){
$(".employee_id").jCombo("{{ URL::to('jcomboform?table=hr_employee_t:employee_id:first_name|last_name') }}&order_by=first_name asc",
{selected_value:""});
});

	/*deepika purpose:hour no validation*/
		$(document).on('keypress','.hour', function(ev){
			var regex = new RegExp("^[0-9.]+$");
					var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
					if (regex.test(str)) {
						return true;
					}
					ev.preventDefault();
					return false;
		});
	/*end*/
 $(document).on('click','.saveform',function()
    {   
	        var btnval=$(this).val();
	        var url		= "{{ url('dailyactivitysave') }}";
            var red_url		="{{ url('empdailyactivity') }}";
            var create_url	="{{ url('dailyactivitycreate') }}/0";
            validationrule('dailyactivityform');

		var form = $('#dailyactivityform');
		form.parsley().validate();

            
               form.parsley().validate();
					var form = $('#dailyactivityform');
					form.parsley().validate();

					if (form.parsley().isValid())
					{

                    change_date();                     
                     var formdata	= $('#dailyactivityform').serialize();


                    $.post(url,formdata,function(data)
                    {
                        var status      = data.status;
                        var msg     = data.message;
                        var id          = data.id;
                        var edit_url	= "{{ url('dailyactivitycreate') }}/"+id;
                        if(btnval !='SAVE')
                        {

                            notyMsg(status,msg);
                            setTimeout(function(){
                            window.location.href=create_url;
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
