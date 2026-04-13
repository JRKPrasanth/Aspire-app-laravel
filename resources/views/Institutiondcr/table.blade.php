@extends('layouts.header')
@section('content')

<style type="text/css">

</style>

<div id="accordion">
<div class="panel-heading" role="tab" id="headingOne">
  
                                <h4 class="panel-title">
                                    <a role="button">
                                       
                                        Institution DCR
                                    </a>
                                </h4>

                             
                            </div>

</div>



  <div class="panel panel-visible" id="spy1">


<div class="panel-title">
  <div class="row">
  <div class="col-md-12">
	  <?php include('toolbar.php'); ?>
<!--a> <button type="button" class="btn add Delete" >Delete</button></a-->

<!-- <a id="clearsearch"><button type="button" class="btn clearsearch">Clear Search</button></a> -->
</div>
</div>
</div>


<div class="row">
<div class="col-md-12">
<hr class="xlg">
</div>
</div>

<div class="row">
<div class="col-md-12">

  <table id="grid1"></table>
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


$("#grid1").jqGrid({
url:"{{URL::to('institutiondcrData')}}",
mtype:'GET',
datatype:'json',

colModel: [
{ name: "institution_dcr_id", label: "S.NO",hidden:true },
{ name: "name", label: "Institution" },
{ name: "keycontact1", label: "Key Contact"},
{ name: "timing", label: "Timing",editable:true, editrules:{date:true}},
{ name: "area_name", label: "Area",editable:true, editrules:{date:true}},

],
iconSet: "fontAwesome",
   rowNum: 10,
    rowList: [10,20,50,100,250,500,1000],
    sortorder: "desc",
    viewrecords: true,
    gridview: true,
    rownumbers:true,
      pager: "#grid1",
      autowidth: true,
viewrecords: true,
searching: {
defaultSearch: "cn"
}
});
        jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false, defaultSearch:'cn'});
$("#grid1").jqGrid("setLabel", "rn", "S.No");
	
$(".edit").click(function(){
    var index = $("#grid1").jqGrid('getGridParam','selrow');
	var edit_id = $("#grid1").jqGrid ('getCell', index, 'institution_dcr_id');
       if( edit_id){
		window.location.replace('institutiondcredit/'+edit_id);
	}
	else
	{
		 notyMsgs("info","Please Select a Row");
	}

});
$(document).on('click','.create',function()
{
var inquirytype = $(this).val();
var url="{{ url('createinstitutiondcr') }}";

window.location.replace(url);
});
showcolumn('grid1');

       $(".view").click(function(){
	var index = $("#grid1").jqGrid('getGridParam','selrow');
	var id = $("#grid1").jqGrid ('getCell', index, 'id');
	if(id)
	{
		window.location.replace('userview/'+id );
	}
	else
	{
			 notyMsgs("info","Please Select a Row");
	}
});
       $(".delete").click(function(){
		var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
		var pohdrid = jQuery("#grid1").jqGrid ('getCell', gr, 'id');
		if( pohdrid != false ){
		window.location.replace('userdelete/' +pohdrid);
		}
		else{
		 notyMsg("info","Please Select a Row");
		}
	});
/***** Delete Row ********/

	$("#clearsearch").click(function() {
		var grid = $("#grid1");
		grid.jqGrid('setGridParam',{search:false});

		var postData = grid.jqGrid('getGridParam','postData');
		$.extend(postData,{filters:""});
		grid.trigger("reloadGrid",[{page:1}]);
		 $('input[id*="gs_"]').val("");
         $('select[id*="gs_"]').select2('val',['']);
	});
/*End*/

	$("#exportpdf").on("click", function(){
		
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
  fileName : "User.pdf",
  mimetype : "application/pdf"  
});
	});	
		
		$("#exportexcel").on("click", function(){
				$("#grid1").jqGrid("exportToExcel",{
					includeLabels : true,
					includeGroupHeader : true,
					includeFooter: true,
					fileName : "User.xlsx",
					maxlength : 40000 
				})
		
		
			})

});
    </script>
@endsection
