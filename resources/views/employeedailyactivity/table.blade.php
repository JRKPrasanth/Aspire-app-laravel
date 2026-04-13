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

<?php //dd($pageMethod); ?>
<div id="accordion">
<div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a role="button">

                                    Daily Activity
                                    </a>
                                </h4>
                            </div>

</div>




  <div class="panel panel-visible" id="spy1">

<div class="panel-title">
  <div class="row">
    <div class="col-md-12" >
		<?php include('toolbar.php'); ?>
</div>
</div>
</div>
  <div class="row">
    <div class="col-md-12" >
   <table id="dailyactivitygrid"></table>
  </div>
  </div>
  </div>


<script type="text/javascript">

$( document ).ready(function() {
      
$("#dailyactivitygrid").jqGrid({
      url: "getEmployeedailydata",
      datatype: "json",
      mtype: "GET",
        colModel: [
            { name: "emp_daily_activity_id",align: "center",hidden:true},
		    { name: "empname", align: "center",label: "Employee Name" },
            { name: "activity_date", align: "center",label: "Activity Date" },
            { name: "description", align: "center",label: "Description" },
            { name: "hour",  align: "center",label: "Hour"},
            { name: "remarks",align: "center",label: "Remarks" },
             ],
	   rowNum:10,
		
		viewrecords: true,
		footerrow: true,
		sortorder: "desc",
		userDataOnFooter: true, // use the userData parameter of the JSON response to display data on footer
		width: 780,
		height: 300,
		  rownumbers: true ,
		rowList: [10, 20, 50, 100,500,1000],
		pager: "#dailyactivitygrid"
});
jQuery("#dailyactivitygrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
 
		$("#dailyactivitygrid").jqGrid("setLabel", "rn", "S.No");
 $(document).on('click',".export",function() {
   	$("#dailyactivitygrid").jqGrid('exportToPdf', {
  title: null,
  orientation: 'portrait',
  pageSize: 'A4',
  description: null,
  onBeforeExport: null,
  download: 'download',
  includeLabels : true,
  includeGroupHeader : true,
  includeFooter: true,
  fileName : "Daily Activity.pdf",
  mimetype : "application/pdf"  
});
			
$("#dailyactivitygrid").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "Daily Activity.xlsx"
    					
				})		 
	});
/*Karthigaa Purpose for Show Coloumn*/
showcolumn('dailyactivitygrid');
$("#dailyactivitygrid").jqGrid('hideCol',["remarks"]);
$("#showcolumn").click(function() {
  var btn = $(".showcolumn").val();
  if(btn =="1" ){
    $("#dailyactivitygrid").jqGrid('showCol',["remarks"]);
    $(".showcolumn").val('2');
  }else{
    $("#dailyactivitygrid").jqGrid('hideCol',["remarks"]);
    $(".showcolumn").val('1');
  }
});


$(document).on('click','.create',function(){
var url="{{ url('dailyactivitycreate') }}/0";
var red_url="{{ url('employeedailyactivity') }}";
window.location.replace(url);
});


$("#edit").click(function(){
        var index = $("#dailyactivitygrid").jqGrid('getGridParam','selrow');

	var id = $("#dailyactivitygrid").jqGrid ('getCell', index, 'emp_daily_activity_id');
       if( index )
       {
           window.location.replace('dailyactivitycreate/' +id);
        }
      else
      {
        notyMsg("info","Please Select Row");
      }

});




  
    /*Karthigaa Purpose For View Function*/
       $("#view").click(function(){
	var index = $("#dailyactivitygrid").jqGrid('getGridParam','selrow');
	var emp_daily_activity_id = $("#dailyactivitygrid").jqGrid ('getCell', index, 'emp_daily_activity_id');
	if( index )
	{
		window.location.replace('dailyactivityview/' +emp_daily_activity_id);
	}
	else
	{
			notyMsg("info","Please Select Row");
	}
   });
   
/***** Karthigaa Purpose For CLEAR search ********/
	$("#clearsearch").click(function() {
		var grid = $("#dailyactivitygrid");
		grid.jqGrid('setGridParam',{search:false});

		var postData = grid.jqGrid('getGridParam','postData');
		$.extend(postData,{filters:""});
		grid.trigger("reloadGrid",[{page:1}]);
                 $('input[id*="gs_"]').val("");
                $('select[id*="gs_"]').select2('val',['']);
	});
/*End*/
});



    </script>
@include('layouts.php_js_validation')
@endsection
