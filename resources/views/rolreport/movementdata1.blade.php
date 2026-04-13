@extends('layouts.header')
@section('content')

<style type="text/css">
  .panel,.card{
  margin-bottom: 370px !important;
}
</style>
<h3 class="heads">Movement Data</h3>


  <div class="panel panel-visible" id="spy1">
<div class="row">

<div class="col-md-4">
      
        <div class="form-group " >
            <label for="Enquiry Number" class=" control-label col-md-4 text-left">Product</label>
                    <div class="col-md-7">
                        <select name='product_id' rows='5' id='product_id' class='form-control select2 product_id ' required>
                
                     

                        </select>
                    </div>
        </div>
    
          
        </div>
	<div class="col-md-4">
	  <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4">From Date</label>
            <div class="col-md-6">
                            <div class="input-group form_date " 	 data-date=""   data-link-format="yyyy-mm-dd">
                                            <input class="form-control start_date  " id="start_date" name="start_date"  required type="text" value="" style="border-radius: 5px;">
                                             
                            </div>
            </div>
        </div>
		   </div>
	 <div class="col-md-4">
        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4">To Date</label>
            <div class="col-md-6">
                    <div class="input-group form_date " 	 data-date=""   data-link-format="yyyy-mm-dd">
                                    <input class="form-control end_date  " id="end_date" name="end_date"  required type="text" value="" style="border-radius: 5px;">
                                     <!-- <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span> -->
                    </div>
            </div>
        </div>
    </div>
    	


    

	

    <div class="col-md-offset-5 col-md-10">
		<div class="col-md-8">
            <fieldset>
                <div class="form-group">
                    <div class="col-md-7">
                        <button  id="view" type="button" class=" btn  tips search quote_compare">Search</button>
                    </div>
                </div>
            </fieldset>
        </div>
</div>

<div class='col-md-12'>
        
        <h4>Data in Details</h4>
        <div class='data_div'></div>
    </div> 


  <style type="text/css">
  body{
    overflow-x:hidden;
  }
 .info-box {
    display: block;
    min-height: 90px;
    background: #fff;
    width: 100%;
    box-shadow: 0 1px 1px rgba(0,0,0,0.1);
    border-radius: 2px;
    margin-bottom: 15px;
} 
.bg-aqua, .callout.callout-info, .alert-info, .label-info, .modal-info .modal-body {
    background-color: #00c0ef !important;
}
.info-box-content {
    padding: 5px 10px;
    margin-left: 90px;
}
.info-box-text {
    text-transform: uppercase;
}
.info-box-number {
    display: block;
    font-weight: bold;
    font-size: 18px;
}
.table-responsive {
    display: block;
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    -ms-overflow-style: -ms-autohiding-scrollbar;
}
.table-responsive > .table-bordered {
        text-align: center;
    border: 0;
}

.table {
    width: 100%;
    max-width: 100%;
    margin-bottom: 1rem;
    background-color: transparent;
}

.table.table-bordered thead {
    border: 1px solid #f2f2f2;
    border-bottom: none;
}

.table-responsive tbody tr > td:first-child {
    display: table-cell;
}
.card{
    border-radius: 5px;
    margin-bottom: 40px;
}
.card-body {
    padding: 14px 10px;
}
table tbody tr:nth-child(1) {
    background: none;
}
tbody{
  text-align: center;
    vertical-align: middle;
    font-size: 13px;
    line-height: 1;
    white-space: nowrap;
}
table-bordered td {
    border: 1px solid #f2f2f2;
}
.table th, .table td {
    padding: 18px 30px;
    
}
.table>thead:first-child>tr:first-child>th {
   color: #fff;
    font-weight: bolder;
    text-align: center;
    background: #345a7f;
    padding: 10px 0px 10px 0px;
}
.table .progress{
    text-align: center;
    height: 8px;
    border-radius: 3px;
    width: 75%;
}

.table.table-bordered thead tr th {
    border-left: none;
    border-right: none;
}
.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #f2f2f2;
}
.table-bordered th, .table-bordered td {
    border: 1px solid #f2f2f2;
}
.table>tbody>tr>td{
    padding: 15px;

    vertical-align: middle;
    border-top: 1px solid #f2f2f2;
}


.select2-selection__rendered {
 
  font-size: 11px !important;
}


.select2-container{
  height: auto;
}

.select2-container--default .select2-selection--multiple{
  border:none !important ;
}
.select2-container .select2-selection--multiple {
    box-sizing: border-box !important ;
    cursor: pointer !important ;
    display: block !important ;
    min-height: 25px !important ;
    user-select: none !important;
    -webkit-user-select: none !important ;
}
.select2-container--default.select2-container--focus .select2-selection--multiple{
   border: none !important;

}

</style>
<script>

$( document ).ready(function() {



$(".product_id").jCombo("{{ URL::to('jcomboformallchecknew?table= m_products_t:product_id:product_code|concatenated_product')}}&condition=yes",
{selected_value:""});








	//	$('.divhide').hide();
                
	    $(document).on('click','.search',function(){
                var product=$('.product_id').select2('val');
                $("#supplier_id").select2();
                var startdate=$('.start_date').val();
                var enddate=$('.end_date').val();
         
        if(product!=''  && startdate!="" && enddate!=""){
                var url="{{URL::to('movementdata')}}/?product="+product+"&startdate="+startdate+"&enddate="+enddate;
	  $.get(url , function(data){
                  $('.data_div').html(data.result);
                    $('.result').text(data.result);
                    
                  
		  $('.divhide').show();
			});  
                    }
                    else{
                        notyMsg('error','Please Select Product and period Details');
                    }
});

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


});


</script>
@endsection