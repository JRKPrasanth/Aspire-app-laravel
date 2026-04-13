@extends('layouts.header')
@section('content')

<?php //dd($pageMethod); ?>

<h2 class="heads">View Uploaded Documents</h2>

<div class="panel panel-visible" id="spy1">
<button id="viewdata" class="btn vie viewdata" >View</button>

<div class="panel-title ">
	<div class="row">
	<div class="col-md-12">
       
     
</div>
</div>
</div>
<div class="row">
<div class="col-md-12" style="padding: 15px;">
<!-- OUR CONTENT STARTS HERE -->
 <div class="img_location" align="right">
        <img src="{{asset('/images/Clear.png')}}" class="clear" height="30px;" width="30px" >
        <img src="{{asset('/images/excel.png')}}" class="exportexcel" height="30px;" width="30px" >
        <img src="{{asset('/images/pdf.png')}}" class="exportpdf" height="30px;" width="30px">
    </div>
<table id="grid1"></table>

<!-- OUR CONTENT ENDS HERE -->


</div>
</div>
</div>
<style type="text/css">
	.panel,.card{
		margin-bottom: 300px !important;
	}
</style>
<script type="text/javascript">
$(document).ready(function()
{

$("#grid1").jqGrid(
{
url: "proofviewgriddata",
datatype: "json",
mtype: "GET",
colModel: [
{ name: "id", label: "id", width: 250, hidden: true },
{ name: "first_name", label: "Employee Name", width: 250},
{ name: "inv_amount", label: "Invsetment Amount", width: 250},
{ name: "act_amount", label: "Actual Amount", width: 300},
{ name: "file_upload", label: "File", width: 260} ,
{ name: "year_from", label: "Year", width: 260} ,
],

       rowNum:10,
		viewrecords: true,
		footerrow: true,
		rownumbers: true ,
		userDataOnFooter: true, // use the userData parameter of the JSON response to display data on footer
		width: 780,
		height: 300,
		rowList: [10, 20, 50, 100,500,1000],
		pager: "#grid1",
        sortorder: "asc",
 


});
	 	jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});

$("#gs_sales_order_date").attr("placeholder","Eg:2018-10-31");	
	jQuery("#grid1").jqGrid("hideCol","cb");
		$("#grid1").jqGrid("setLabel", "rn", "S.No");
	showcolumn('grid1');


    /***** Grid Search Clear Start  ****/

        $(".clear").click(function()
	{	
	    var grid = $("#grid1");
	    grid.jqGrid('setGridParam',{search:false});

	    var postData = grid.jqGrid('getGridParam','postData');
	    $.extend(postData,{filters:""});
	   location.reload();
	    $('input[id*="gs_"]').val("");
	   
	});
        $("#viewdata").click(function(){
  var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
  var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'id');

  if( gr ){
    window.location.replace('proofviewshow/' +cellValue);
  }

  else
  {
  notyMsg("info","Please Select Row");
  }
});

        /***** Grid Search Clear End  ****/

     $(document).on('click',".exportpdf",function() 
                {
                    $("#grid1").jqGrid('exportToPdf', {
                      title: null,
                      orientation: 'portrait',
                      pageSize: 'A4',
                      description: null,
                      onBeforeExport: null,
                      download: 'download',
                      includeLabels : true,
                      includeGroupHeader : true,
                      includeFooter: true,
                      fileName : "View Uploaded Documents.pdf",
                      mimetype : "application/pdf"  
                    });
                });
		
        /***** export to pdf END  ****/
		
        /***** export to Excel Start  ****/
		     $(document).on('click',".exportexcel",function() 
            {
                $("#grid1").jqGrid("exportToExcel",{
                        includeLabels : true,
                        includeGroupHeader : true,
                        includeFooter: true,
                        fileName : "View Uploaded Documents.xlsx"

                })	
            });
		
		
         /***** export to Excel End  ****/







});
</script>

@endsection
