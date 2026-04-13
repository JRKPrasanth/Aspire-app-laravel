@extends('layouts.header')
@section('content')
<h2 class="heads">Purchase Dc</h2>
<div class="panel panel-visible" id="spy1"><div class="panel-title"><div class="row">
<div class="col-md-12" ><?php include('toolbar.php'); ?></div></div></div>
 <div class="row"><div class="col-md-12" >
<table id="dcgrid" ></table>
</div></div></div>
<script type="text/javascript">
$( document ).ready(function() {
/*Karthigaa Purpose For JQGrid*/
var date_format="{{\Session::get('p_date_format')}}";
$("#dcgrid").jqGrid({
      url: "purchasedcData",
      datatype: "json",
      mtype: "GET",
	 colNames: ["","","DC Number","DC Date","DC Status","Grn Number","Supplier Name"],
        colModel: [
            { name: "dc_hdr_id",align: "center",hidden:true},
            { name: "supplier_id",align: "center",hidden:true},
            { name: "dc_number",align: "center"},
            { name: "dc_date", label: "DC Date",editable:true, editrules:{date:true},formatter: 'date', formatoptions: { srcformat: 'Y-m-d', newformat: date_format}},
            { name: "dc_status",align: "center"},
            { name: "grn_number", align: "center" },
            { name: "supplier_name", align: "center" }
             ],
	iconSet: "fontAwesome",
	rownumbers: true,
	sortorder: "desc",
        threeStateSort: true,
	sortIconsBeforeText: true,
	headertitles: true,
	pager: "#dcgrid",
	rowList: [10,20,50,100,250,500,1000],
	viewrecords: true,
});
jQuery("#dcgrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
$("#dcgrid").jqGrid("setLabel", "rn", "S.No");
showcolumn('dcgrid');
$('#gs_return_date').attr('placeholder','Eg:2018-01-31');
/*Karthigaa Purpose For CREATE Function*/
$("#create").click(function(){
    var url="{{ url('grntablefordc') }}";
    window.location.replace(url);

});
/*Karthigaa Purpose For PDF Download Function*/
$(document).on('click',".exportpdf",function() {
   	$("#dcgrid").jqGrid('exportToPdf', {
  title: null,
  orientation: 'portrait',
  pageSize: 'A4',
  description: null,
  onBeforeExport: null,
  download: 'download',
  includeLabels : true,
  includeGroupHeader : true,
  includeFooter: true,
  fileName : "Purchase DC.pdf",
  mimetype : "application/pdf"
});

});
/*Karthigaa Purpose For EXCEL Download Function*/  	
$(document).on('click',".exportexcel",function() {
    $("#dcgrid").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "Purchase DC.xlsx"

				})
});
/*Karthigaa Purpose For Edit Function*/
$("#edit").click(function(){
        var index = $("#dcgrid").jqGrid('getGridParam','selrow');
	var dc_hdr_id = $("#dcgrid").jqGrid ('getCell', index, 'dc_hdr_id');
        var dc_status = $("#dcgrid").jqGrid ('getCell', index, 'dc_status');
       if(index) {
            if(dc_status!="INITIATED"){
        	window.location.replace('purchasereturnedit/'+dc_hdr_id);
            }else{
                 notyMsg("info","Unable to edit Purchase Dc");
            }
	}
	else
	{
		 notyMsg("info","Please Select a Row");
	}
});
/*Karthigaa Purpose For Print Function*/
 $(".print").click(function(){
 	var index = $("#dcgrid").jqGrid('getGridParam','selrow');
        var rehdrid = $("#dcgrid").jqGrid ('getCell', index, 'dc_hdr_id');
    if( index)
    {
          window.open('purchasedcprint/'+rehdrid);
    }else{
        notyMsg("info","Please Select a Row");
    }
 });  
 /*Karthigaa Purpose For View Function*/
       $("#view").click(function(){
	var index = $("#dcgrid").jqGrid('getGridParam','selrow');
	var dc_hdr_id = $("#dcgrid").jqGrid ('getCell', index, 'dc_hdr_id');
        var url = "{{$pageMethod}}";
	if( index )
	{
		window.location.replace('purchasedcview/' +dc_hdr_id+'?return='+url);
	}
	else
	{
			 notyMsg("info","Please Select a Row");
	}
});
/***** Karthigaa Purpose For CLEAR search ********/
$("#clearsearch").click(function() {
		var grid = $("#dcgrid");
		grid.jqGrid('setGridParam',{search:false});

		var postData = grid.jqGrid('getGridParam','postData');
		$.extend(postData,{filters:""});
		grid.trigger("reloadGrid",[{page:1}]);
                $('input[id*="gs_"]').val("");
	});
/*End*/
});
    </script>
@endsection
