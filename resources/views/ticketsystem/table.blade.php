@extends('layouts.header')
@section('content')

<style type="text/css">

</style>

<div id="accordion">
<div class="panel-heading" role="tab" id="headingOne">
  
                                <h4 class="panel-title">
                                    <a role="button">
                                       
                                        Ticket System
                                    
                                    </a>
                                </h4>

                             
                            </div>

</div>



  <div class="panel panel-visible" id="spy1">

 <?php include('toolbar.php'); ?>


<div class="row">
<div class="col-md-12">
<hr class="xlg">
</div>
</div>

<div class="row">
<div class="col-md-12">

  <table id="ticketsystem"></table>
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

var method="{{$pageMethod}}";
$("#ticketsystem").jqGrid({
url:"{{URL::to('ticketrequestData')}}?status="+method,
mtype:'GET',
datatype:'json',

colModel: [
{ name: "ticket_id", label: "S.NO",hidden:true },
{ name: "ticket_no", label: "Ticket Number" },
{ name: "ticket_group", label: "Ticket Group" },
{ name: "ticket_type", label: "Ticket Type" },
{ name: "ticket_date", label: "Ticket Date" },
{ name: "ticket_severity", label: "Ticket Severity"},
{ name: "request_remarks", label: "Causes",editable:true, editrules:{date:true}},
{ name: "ticket_status", label: "Ticket Status",editable:true, editrules:{date:true}},
{ name: "close_remarks", label: "Closed Remarks",editable:true, editrules:{date:true}},
{ name: "files", label: "Files",editable:true, editrules:{date:true}},

],
iconSet: "fontAwesome",
   rowNum: 10,
    rowList: [10,20,50,100,250,500,1000],
    sortorder: "desc",
    viewrecords: true,
    gridview: true,
    rownumbers:true,
      pager: "#ticketsystem",
      autowidth: true,
viewrecords: true,
searching: {
defaultSearch: "cn"
},onCellSelect: function (rowid) {
    var rowData = $(this).jqGrid("getRowData", rowid);
    console.log(rowData['files']);
    var file_path = 'upload/sop/'+rowData['files'];
    if(rowData['files']){
    	$("#sopdownload").attr("href",file_path);
/*var a = document.createElement('A');
a.href = file_path;
a.download = file_path.substr(file_path.lastIndexOf('/') + 1);
document.body.appendChild(a);
a.click();
document.body.removeChild(a);*/
}
    // now you can use rowData.name2, rowData.name3, rowData.name4 ,...
}
});

        jQuery("#ticketsystem").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false, defaultSearch:'cn'});
$("#ticketsystem").jqGrid("setLabel", "rn", "S.No");
	
$("#sopdownload").click(function(){
	var index = $("#ticketsystem").jqGrid('getGridParam','selrow');
	var id = $("#ticketsystem").jqGrid ('getCell', index, 'id');
	var files = $("#ticketsystem").jqGrid ('getCell', index, 'files');
	if(id)
	{
    var file_path = 'upload/sop/'+files;
    // alert(file_path);
    if(files){
    	// alert(files);
var a = document.createElement('A');
a.href = file_path;
a.download = file_path.substr(file_path.lastIndexOf('/') + 1);
document.body.appendChild(a);
a.click();
document.body.removeChild(a);
}else{
	notyMsgs("info","There is No file to Download");
}
 
	}
	else
	{
			 notyMsgs("info","Please Select a Row");
	}
});


$(".allocate").click(function(){
	var btnval="{{$pageMethod}}";
	//alert(method);
	//var btnval = $(this).val();
    var index = $("#ticketsystem").jqGrid('getGridParam','selrow');
	var edit_id = $("#ticketsystem").jqGrid ('getCell', index, 'id');
	//alert(edit_id);
       if(edit_id){
		window.location.replace('engineerallocate/'+edit_id+'?btnval='+btnval);
	}
	else
	{
		 notyMsgs("info","Please Select a Row");
	}

});

<?php if($pageMethod!="sopupload")
{?>
jQuery("#ticketsystem").jqGrid('hideCol',["error_code","files"]);
<?php } ?>
$(".upload").click(function(){
	var btnval="{{$pageMethod}}";
	//alert(method);
	//var btnval = $(this).val();
    var index = $("#ticketsystem").jqGrid('getGridParam','selrow');
	var edit_id = $("#ticketsystem").jqGrid ('getCell', index, 'id');
	//alert(edit_id);
       if(edit_id){
		window.location.replace('engineerallocate/'+edit_id+'?btnval='+"sopupload");
	}
	else
	{
		 notyMsgs("info","Please Select a Row");
	}

});
$(document).on('click','.create',function()
{
var inquirytype = $(this).val();
var url="{{ url('ticketrequestcreate') }}";

window.location.replace(url);
});
showcolumn('ticketsystem');

       $("#viewdata").click(function(){

	var index = $("#ticketsystem").jqGrid('getGridParam','selrow');
	var id = $("#ticketsystem").jqGrid ('getCell', index, 'id');
	if(id)
	{
		window.location.replace('userview/'+id +'?btnval='+method);
	}
	else
	{
			 notyMsgs("info","Please Select a Row");
	}
});

$("#sopview").click(function(){
	var index = $("#ticketsystem").jqGrid('getGridParam','selrow');
	var id = $("#ticketsystem").jqGrid ('getCell', index, 'id');
	if(id)
	{
		window.location.replace('sopview/'+id);
	}
	else
	{
			 notyMsgs("info","Please Select a Row");
	}
});
       $(".delete").click(function(){
		var gr = jQuery("#ticketsystem").jqGrid('getGridParam','selrow');
		var pohdrid = jQuery("#ticketsystem").jqGrid ('getCell', gr, 'id');
		if( pohdrid != false ){
		window.location.replace('userdelete/' +pohdrid);
		}
		else{
		 notyMsg("info","Please Select a Row");
		}
	});
/***** Delete Row ********/

	$("#clearsearch").click(function() {
		var grid = $("#ticketsystem");
		grid.jqGrid('setGridParam',{search:false});

		var postData = grid.jqGrid('getGridParam','postData');
		$.extend(postData,{filters:""});
		grid.trigger("reloadGrid",[{page:1}]);
		 $('input[id*="gs_"]').val("");
         $('select[id*="gs_"]').select2('val',['']);
	});
/*End*/

	$("#exportpdf").on("click", function(){
		
			$("#ticketsystem").jqGrid('exportToPdf', {
  title: null,
  orientation: 'portrait',
  pageSize: 'A4',
  description: null,
  onBeforeExport: null,
  download: 'download',
  includeLabels : true,
  includeGroupHeader : true,
  includeFooter: true,
  fileName : "Issue.pdf",
  mimetype : "application/pdf"  
});
	});	
		
		$("#exportexcel").on("click", function(){
				$("#ticketsystem").jqGrid("exportToExcel",{
					includeLabels : true,
					includeGroupHeader : true,
					includeFooter: true,
					fileName : "Issue.xlsx",
					maxlength : 40000 
				})
		
		
			})

});
    </script>
@endsection
