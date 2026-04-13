@extends('layouts.header')
@section('content')

<style type="text/css">
  .datepicker{

    z-index:1052 !important;}


.Menu {
    position: absolute;
    top: 77%;
    left: auto;
    z-index: 1000;
    display: none;
    float: left;
    min-width: 160px;
    padding: 5px 0;
    margin: 2px 0 0;
    font-size: 14px;
    text-align: left;
    list-style: none;
    background-color: #fff;
    -webkit-background-clip: padding-box;
    background-clip: padding-box;
    border: 1px solid #ccc;
    border: 1px solid rgba(0, 0, 0, .15);
    border-radius: 4px;
    -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, .175);
    box-shadow: 0 6px 12px rgba(0, 0, 0, .175);
}





</style>
<div id="accordion">
<div class="panel-heading" role="tab" id="headingOne">
    <h4 class="panel-title">
    <a role="button">Purchase Details Report</a>
    </h4>
</div>
</div>
  <div class="panel panel-visible" id="spy1">
	<div class="sbox-content"> 	
			
		<div class="container demo-1">

			<div class="main clearfix">
				<form id="nl-form" class="nl-form" align="center">
					<span style="font-size:15px;font-weight:700;">I Like to see 
				
						Purchase Order from </span>
					 <input type="text" value="" name='daterange' placeholder="any Date" class='daterange' id="daterange"/>
				
						<button class="btn search view1" type="button">Search PO</button>
				
					<div class="nl-overlay"></div>
				</form>
			</div>
		</div>
		
		
		<div class="table"></div>
		
        </div>
        </div>

<script type="text/javascript">

$( document ).ready(function() {
       
$('.view1').click(function(){
     $(".sbox").removeClass('hide');        
 
     var dat = $(".daterange").val();
     var arr = dat.split('-');
     var from_date=arr[0].trim().split(':');
     var from_date=from_date[2]+"-"+from_date[1]+"-"+from_date[0];
     var to_date=arr[1].trim().split(':');
     var to_date=to_date[2]+"-"+to_date[1]+"-"+to_date[0];
     var date="po_date:between:"+from_date+":"+to_date+"|"
    
  
    var url_val="{{URL::to('PodetailsreportData') }}?from_date="+from_date+"&to_date="+to_date;
    $.get(url_val,function(data){
        
            $(".table").html(data);
       
    });
    
    });
    $(document).on('click','.highcharts-credits',function(){
	
});	
 
});
 $(function() {

    var start = moment().subtract(29, 'days');
    var end = moment();
    console.log(end);
    $('.daterange').daterangepicker({
        maxDate: new Date(),
        startDate: start,
        endDate: end,
        locale:{format:'DD:MM:YYYY'},
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
        
        });

});
    </script>
@include('layouts.php_js_validation')
@endsection