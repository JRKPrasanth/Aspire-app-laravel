@extends('layouts.header')
@section('content')
<style>
.panel, .card {
    margin-bottom: 300px;
}
</style>

<h2 class="heads">Indent Material Issue</h2>

<div class="panel panel-visible" id="spy1">

	<div class="row">
	<div class="col-md-12">
		<?php include("toolbar.php"); ?>

<input type='hidden' class="columnhide" name='columnhide[]' value="">
<input type='hidden' class="gridcolumns" name='gridcolumns[]' value="">
</div>
</div>
<div class="row">
<div class="col-md-12" style="padding: 15px;">
<table id="grid1"></table>
</div>
</div>
</div>

<script type="text/javascript">
$( document ).ready(function() {
/* set select2*/
$(".select2").select2();
$(".select2").css('width','100%');
/*end*/
$("#grid1").jqGrid({
url: "indentmaterialissuedata",
datatype: "json",
mtype: "GET",
	 colModel: [
	{ name: "quality_indent_hdr_id",width:100, label: "id",hidden:true },
    { name: "indent_name",width:120, label: "Indent Name"},
	{ name: "indent_date",width:50, label: "Indent Date "},
    { name: "remarks",width:100, label: "Remarks"},
	 ],
        iconSet: "fontAwesome",
        rowNum: 10,
        rowList: [10,20,50,100,250,500,1000],
        sortorder: "desc",
        viewrecords: true,
        gridview: true,
        rownumbers:true,
        caption: "",
	    
        pager: "#grid1",
        searching: {
            defaultSearch: "cn"
        }
      });
jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
$("#grid1").jqGrid("setLabel", "rn", "S.No");
showcolumn('grid1');


$(".qualityindentcreate").click(function()
{
	var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
	var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'quality_indent_hdr_id');
	if(gr)
	{
		var url = "{{ URL::to('indentmaterialissuecreate') }}";
                var editUrl = url + '/' + cellValue+'?status=indentmaterialissue';
		window.location.replace(editUrl);
	}
	else
	{
	notyMsg('info',"Please select a row");
	}
});


$(".qualityedit").click(function()
{
	var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
  // alert(gr);
	var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'quality_indent_hdr_id');
  var page = "indentmaterialissue";
	if(gr)
	{
		var url = "{{ URL::to('qualityindentcreate') }}";
                var editUrl = url + '/' + cellValue+'?pageMethod='+page;
		window.location.replace(editUrl);
	}
	else
	{
	notyMsg('info',"Please select a row");
	}
});

$('#view').click(function()
{
  var gr=$('#grid1').jqGrid('getGridParam','selrow');
  var cellValue = $("#grid1").jqGrid ('getCell', gr, 'quality_indent_hdr_id');
  if(cellValue != false)
  {
     var url="{{URL::to('indentmaterialissueview')}}/"+cellValue;
     window.location.replace(url);
  }
  else
  {
     notyMsg('info',"Please select a row");
  }
});


/*deepika purpose:clear search the jqgrid*/
	$(".clearsearch").click(function()
{
var grid = $("#grid1");
grid.jqGrid('setGridParam',{search:false});

var postData = grid.jqGrid('getGridParam','postData');
$.extend(postData,{filters:""});
grid.trigger("reloadGrid",[{page:1}]);
		$('input[id*="gs_"]').val("");
    $('select[id*="gs_"]').select2('val',['']);
});

/*Export to Pdf & Export to Excel*/
 $(document).on('click',".exportpdf",function() {
	
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
  fileName : "Indentmaterial.pdf",
  mimetype : "application/pdf"  
});
 });
		 $(document).on('click',".exportexcel",function() {
$("#grid1").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "Indentmaterial.xlsx"
    					
				})	
	
});

     /*End*/

	
});



  </script>
@endsection
