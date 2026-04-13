@extends('layouts.header')
@section('content')
<style type="text/css">


.divhide .card{
    border:1px solid #ccc;
}
.card-header {
    
    border-bottom: 1px solid #ccc;
}


</style>
<div id="accordion">
<div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a role="button">
                                      Accounting Transaction
                                    </a>
        </h4>
  </div>
</div>

<div class="card">
    <div class="card-body">
<div class="row">
<div class="col-md-12">   

			<div class="col-md-6">					
        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4">From Date</label>
            <div class="col-md-6">
                            <div class="input-group form_date " 	 data-date=""   data-link-format="yyyy-mm-dd">
                                            <input class="form-control start_date  " id="start_date" name="start_date"  required type="text" value="" style="border-radius: 5px;" autocomplete="off">
                                             
                            </div>
            </div>
        </div>
    </div>
	  
    <div class="col-md-6">
        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4">To Date</label>
            <div class="col-md-6">
                    <div class="input-group form_date " 	 data-date=""   data-link-format="yyyy-mm-dd">
                                    <input class="form-control end_date  " id="end_date" name="end_date"  required type="text" value="" style="border-radius: 5px;" autocomplete="off">
                                     <!-- <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> -->
                    </div>
            </div>
        </div>
    </div>
<div class="form-group row">
                                   <label for="inputIsValid" class="form-control-label col-md-offset-3 col-md-2"><span style="color:red; " > </span></label>
                                    <div class="col-md-1">
										<a><button type="button" class="btn add search" id="search" value="">Search</button></a>
                                    </div>
                                    <div class="col-md-2 showline">
                                    </div>
                                </div>
								
<div class="divhide">
    
  	
    </div>
</div>
</div>
</div>

</div>

	<script type="text/javascript">
$( document ).ready(function() {
		$('.divhide').hide();
	    $(document).on('click','.search',function()
{
			 var start_date = ($('.start_date').val() != '') ? $('.start_date').val() : '';
			 var end_date   =  ($('.end_date').val() != '') ? $('.end_date').val()  : ''; 
		 if(start_date!=''&&end_date!=''){
			var url="{{URL::to('getaccounttransaction')}}/?start_date="+start_date+"&end_date="+end_date;
	  $.get(url , function(data)
					 {
				$('.divhide').html(data);
		  $('.divhide').show();
			});  
			 }
                      else{
                          notyMsg("info","Please Select From & To Period");
                      }
});
	
		});
/*$(function()
{
  
  
  $('.start_date').datepicker({
    changeMonth: true,
      dateFormat: data,
      changeYear: true,   
      
  });
  $('.end_date').datepicker({
    changeMonth: true,
      dateFormat: data,
      changeYear: true,   
      
  });
});   */  
var data = "<?php echo \Session('j_date_format'); ?>";
var grid_min_date="{{\Session::get('js_griddate')}}";
  var grid_max_date="{{\Session::get('js_gridenddate')}}";
 						$('.start_date').datepicker({
    					changeMonth: true,
     					dateFormat: data,
     					changeYear: true,
      					minDate: grid_min_date,
      					maxDate: grid_max_date,
      					onClose: function( selectedDate ) {
        				jQuery( "#end_date" ).datepicker( "option", "minDate", selectedDate );
        }
      
  });
						$('.end_date').datepicker({
						changeMonth: true,
						dateFormat: data,
						changeYear: true,
						minDate: grid_min_date,
      					maxDate: grid_max_date,
						onClose: function( selectedDate ) {
						jQuery( "#start_date" ).datepicker( "option", "maxDate", selectedDate );
						}
						      
  });
	</script>
@endsection




