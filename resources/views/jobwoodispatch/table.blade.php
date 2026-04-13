@extends('layouts.header')
@section('content')



<h2 class="heads">Job Work Out order Dispatch</h2>

<div class="panel panel-visible" id="spy1">

	<div class="row">
	<div class="col-md-12">
<?php include("toolbar.php");?>
<!--<button type="button" class="btn download  export">Export</button>-->  
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

$("#grid1").jqGrid({
url: "getjobworkoutorderData",
datatype: "json",
mtype: "GET",
	 colModel: [
	{ name: "jobworkoutorder_hdr_id", label: "id",hidden:true },
	{ name: "joboutorder_no", label: "Jobworkoutorder No" ,editable:true, editrules:{date:true}},
	{ name: "subcontract_name", label: "Subcontract Supplier"},
	{ name: "return_date", label: "Return Date",editable:true, editrules:{date:true}},
    { name: "remarks", label: "Remarks",editable:true, editrules:{date:true}},
    { name: "username", label: "Created By"},
		],
         rowNum:10,
		viewrecords: true,
		footerrow: true,
		sortorder: "desc",
		userDataOnFooter: true, // use the userData parameter of the JSON response to display data on footer
		width: 780,
		height: 300,
		rownumbers: true ,
		rowList: [10, 20, 50, 100,250,500,1000],
		pager: "#grid1",
      });
     
jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
	$("#grid1").jqGrid("setLabel", "rn", "S.No");
	/*deepika purpose: show column*/
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
  fileName : "Jobworkoutorder.pdf",
  mimetype : "application/pdf"  
});
   	});
		$(document).on('click',".exportexcel",function() {	
$("#grid1").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "Jobworkoutorder.xlsx"
    					
				})		 
	 
	 
	
});
	
	
/*$('.ui-pg-selbox').css('display','none');
setTimeout(function(){ 
$('.ui-pg-selbox').val('10').trigger('change');
 }, 300);*/
	/*end*/
/*deepika purpose: show column*/

showcolumn('grid1');
/*end*/
$(document).on('click','.create',function()
{
var url="{{ URL::to('jobworkoutordercreate') }}/0";
var red_url="{{ URL::to('jobworkoutorder') }}";
window.location.replace(url);
});
    /*Dispatch */
$(".dispatch").click(function()
{
	var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
	var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'jobworkoutorder_hdr_id');
       
	if( gr )
	{
		var url = "{{ URL::to('jobdispatchcreate') }}";
                var editUrl = url + '/' + cellValue;
		window.location.replace(editUrl);
	}
	else
	{
	notyMsg("info","Please Select Row");
	}
});
/*End*/

/*Edit Function*/
$(".edit").click(function()
{
	var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
	var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'jobworkoutorder_hdr_id');
	if( gr )
	{
		var url = "{{ URL::to('jobworkoutorderedit') }}";
                var editUrl = url + '/' + cellValue;
		window.location.replace(editUrl);
	}
	else
	{
	notyMsg("info","Please Select Row");
	}
});
/*End*/

/*Delete Function*/
$("#delete").click(function()
{
	var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
	var id = jQuery("#grid1").jqGrid ('getCell', gr, 'jobworkoutorder_hdr_id');
	if(gr){
		swal({
                title: "Are you sure?",
                text: "You want to delete!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: !1,
                //timer: 2e3,
                closeOnCancel: !1
            }, function(e) {

			if(e == true)
			{
				var url ="{{ URL::to('jobworkoutorderdelete') }}/" +id;
				$.get(url,function(data)
				{
					var data = $.trim(data);
					var red_url ="{{ URL::to('jobworkoutorder') }}";
					var data = $.trim(data);
					if(data =='0')
					{
						notyMsg('success','Deleted Successfully!!!',red_url);
						setTimeout(function(){
						window.location.href=red_url;
						}, 1500);
					}
					if(data =='2')
					{
						notyMsg('error',"You Cant't delete , Jobworkoutorder Used in SomeWhere!!!",red_url);
					}

				});
			}
			else
			{
                            $('.apply').css('display','none');
                            swal("Cancelled");
			}
				/*
                e ? swal("Deleted!", "Your imaginary file has been deleted.", "success") : swal("Cancelled", "Your imaginary file is safe :)", "error")*/
            })
		$('.apply').css('display','none');
	}
	else
	{
	notyMsg("info","Please Select Row");
	}
});
/*End*/

/*View Function*/
$('#view').click(function()
{
  var gr=$('#grid1').jqGrid('getGridParam','selrow');
  var cellValue = $("#grid1").jqGrid ('getCell', gr, 'jobworkoutorder_hdr_id');
  
  if(gr)
  {
     var url="{{URL::to('jobworkoutorderview')}}/"+cellValue;
     window.location.replace(url);
  }
  else
  {
     notyMsg("info","Please Select Row");
  }
});
/*End*/



/*deepika purpose:clear search the jqgrid*/
	$(".clearsearch").click(function()
{
var grid = $("#grid1");
		grid.jqGrid('setGridParam',{search:false});
	var postData = grid.jqGrid('getGridParam','postData');
		$.extend(postData,{filters:""});
		grid.trigger("reloadGrid",[{page:1}]);
		$('input[id*="gs_"]').val("");
});


});
  </script>
@endsection
