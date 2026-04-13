@extends('layouts.header')
@section('content')


<div id="accordion">
<div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a role="button">
                                       Budgets
                                    </a>
        </h4>
  </div>
</div>



<div class="panel panel-visible" id="spy1">
	<div class="row">
	<div class="col-md-12">
<?php include('toolbar.php'); ?>

</div>
</div>


<div class="row">
<div class="col-md-12">
<hr class="xlg">
</div>
</div>

<div class="row">
<div class="col-md-12">
<table id="budgetsgrid"></table>
</div>
</div>

<div class="row">
<div class="col-md-12">
<hr class="xlg">
</div>
</div>


</div>





<script type="text/javascript">
$( document ).ready(function() {

$("#budgetsgrid").jqGrid({
url: "getBudgetsData",
datatype: "json",
mtype: "GET",
	 colModel: [
	{ name: "budget_hdr_id", label: "id",hidden:true },
	{ name: "budget_name", label: "Budget Name" },
        { name: "budget_date", label: "Budget Date"},
        { name: "budget_status", label: "Budget Status"},
        { name: "budget_year", label: "Budget Year"},
        { name: "budget_line_total", label: "Budget Line Total"},
       ],
       rowNum:10,
		viewrecords: true,
		footerrow: true,
		sortorder: "desc",
		userDataOnFooter: true, // use the userData parameter of the JSON response to display data on footer
		rownumbers: true ,
		rowList: [10, 20,50,100,250,500,1000],
		pager: "#budgetsgrid"
      });
jQuery("#budgetsgrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
	$("#budgetsgrid").jqGrid("setLabel", "rn", "S.No");
 $(document).on('click',".exportpdf",function() {
	 
   	$("#budgetsgrid").jqGrid('exportToPdf', {
  title: null,
  orientation: 'portrait',
  pageSize: 'A4',
  description: null,
  onBeforeExport: null,
  download: 'download',
  includeLabels : true,
  includeGroupHeader : true,
  includeFooter: true,
  fileName : "Create Budgets.pdf",
  mimetype : "application/pdf"  
});
});


  $(document).on('click',".exportexcel",function() {
			
$("#budgetsgrid").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "Create Budgets.xlsx"
    					
				})	
	 	});
	
showcolumn('budgetsgrid');
  /*Karthigaa Purpose For Create*/
      $("#create").click(function(){
	var url="{{  URL::to('budgetscreate')}}";
	window.location.replace(url);
	});
  /*Karthigaa Purpose For Detail Create*/
      $("#detailcreate").click(function(){
        var gr = jQuery("#budgetsgrid").jqGrid('getGridParam','selrow');
	var cellValue = jQuery("#budgetsgrid").jqGrid ('getCell', gr, 'budget_hdr_id');
        //var batchstatus = jQuery("#budgetsgrid").jqGrid ('getCell', gr, 'budget_status');
	if( gr )
	{
		var url = "{{ url('budgetsdetailcreate') }}";
                var detailUrl = url + '/' + cellValue;
		window.location.replace(detailUrl);
    }
	else
	{
	notyMsg("info","Please Select Row");
	}
        });
$("#edit").click(function(){
	var gr = jQuery("#budgetsgrid").jqGrid('getGridParam','selrow');
	var cellValue = jQuery("#budgetsgrid").jqGrid ('getCell', gr, 'budget_hdr_id');
        var batchstatus = jQuery("#budgetsgrid").jqGrid ('getCell', gr, 'budget_status');
	if( gr )
	{
            if( batchstatus != "INITIATED" && batchstatus != "APPROVED"){
		var url = "{{ url('budgetscreate') }}";
                var editUrl = url + '/' + cellValue;
		window.location.replace(editUrl);
	}
            else{
               notyMsg('error',"<i class='fa fa-exclamation-circle' style='font-size:16px'></i> Submitted or Approved BUDGETS Cannot Be Edit!!!");
            }
            }
	else
	{
	notyMsg("info","Please Select Row");
	}
});



$('#view').click(function(){
  var gr=$('#budgetsgrid').jqGrid('getGridParam','selrow');
  var cellValue = $("#budgetsgrid").jqGrid ('getCell', gr, 'payment_hdr_id');
  if(gr){
     var url="budgetsview";
     var viewurl = url+'/'+cellValue+'/view';
     window.location.replace('budgetsview/' +cellValue);
  }
  else
  {
  notyMsg("info","Please Select Row");
  }
});

/*Karthigaa purpose:clear search the jqgrid*/
	$(".clearsearch").click(function(){
            var grid = $("#budgetsgrid");
            grid.jqGrid('setGridParam',{search:false});

            var postData = grid.jqGrid('getGridParam','postData');
            $.extend(postData,{filters:""});
            grid.trigger("reloadGrid",[{page:1}]);
              $('input[id*="gs_"]').val("");
    });
/*end*/
	$(window).scroll(function() {
if ($(this).scrollTop() >150){
    $('.header-sticky').addClass("sticky");
  }
  else{
    $('.header-sticky').removeClass("sticky");
  }
});

});
  </script>
@endsection
