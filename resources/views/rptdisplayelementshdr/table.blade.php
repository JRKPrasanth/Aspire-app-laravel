@extends('layouts.header')
@section('content')

<div id="accordion">
<div class="panel-heading" role="tab" id="headingOne">
   
                                <h4 class="panel-title">
                                    <a role="button">
                                       
                                       Report Elements
                                    </a>
                                </h4>

   
                            </div>

</div>



<div class="panel panel-visible" id="spy1">

	<div class="panel-title">
		<div class="row" >
  			<div class="col-md-12" >
<?php include('toolbar.php'); ?>

	<!--a id="clearsearch"><button type="button" class="btn clearsearch">Clear Search</button></a>
	<button type='button' id="showcolumn" value="1" class='btn showcolumn vie '> Show column </button-->
	</div>
	</div>


	<div class="row">
	<div class="col-md-12">
	<hr class="xlg">
	</div>
	</div>
	<div class="row" >
		<div class="col-md-12">
		<table id="rptdisplaygrid"></table>
		</div>
	</div>
	<div class="row">
	<div class="col-md-12">
	<hr class="xlg">
	</div>
	</div>




	</div>
		</div>

<script>
	$(document).ready(function(){

		$("#rptdisplaygrid").jqGrid({
        url:"getrptdispdata",
        datatype: "json",
        mtype: "GET",
	 colModel: [
		{ name: "rpt_displayelements_hdr_id", label: "Enq Id" ,hidden:true},
		{ name: "lookup_code", label: "Report Source"},
        { name: "set_name", label: "Set Name" },
        { name: "start_date", label: "Start Date" },
        { name: "end_date", label: "End Date" },
	    { name: "description", label: "Description"},
	],
	iconSet: "fontAwesome",
        rowNum: 10,
        rowList: [10,20,50,100,250,500,1000],
        sortorder: "desc",
        viewrecords: true,
        gridview: true,
        rownumbers:true,
          pager: "rptdisplaygrid",
        searching: {
            defaultSearch: "cn"
        }
      });
		
	$("#rptdisplaygrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
jQuery("#gs_rptdisplaygrid_lookup_code").select2();	
		
		showcolumn('rptdisplaygrid');
		
$("#rptdisplaygrid").jqGrid('hideCol',["description"]);
$("#description").click(function() {
  var btn = $(".description").val();
  if(btn =="1" ){
    $("#rptdisplaygrid").jqGrid('showCol',["description"]);
    $(".showcolumn").val('2');
  }else{
    $("#rptdisplaygrid").jqGrid('hideCol',["description"]);
    $(".showcolumn").val('1');
  }
});
		$(document).on('click',".editdata",function(){
			var index = $("#rptdisplaygrid").jqGrid('getGridParam','selrow');
			var prtid = $("#rptdisplaygrid").jqGrid ('getCell', index, 'rpt_displayelements_hdr_id');
				
			if( index  )
			{
		       window.location.replace('rptdisplayelementscreate/'+prtid);	
			}
			else
			{
				notyMsg("info","Please Select a Row");
			}

		});
		
		$(document).on('click','.create',function(){
			
	        var url="{{ url('rptdisplayelementscreate')}}/0";	
			window.location.replace(url);
		});
		
		
		
		
		$("#view").click(function(){
			var index = $("#rptdisplaygrid").jqGrid('getGridParam','selrow');
			var prtid = $("#rptdisplaygrid").jqGrid ('getCell', index, 'rpt_displayelements_hdr_id');
				
			if( index )
			{
		       window.location.replace('rptdisplayelementsview/'+prtid);	
			}
			else
			{
				notyMsg("info","Please Select a Row");
			}

		});
		$(document).on('click',".exportpdf",function() {
			//alert("h");
   	$("#rptdisplaygrid").jqGrid('exportToPdf', {
  title: null,
  orientation: 'portrait',
  pageSize: 'A4',
  description: null,
  onBeforeExport: null,
  download: 'download',
  includeLabels : true,
  includeGroupHeader : true,
  includeFooter: true,
  fileName : "Report Elements.pdf",
  mimetype : "application/pdf"  
});
			
$("#rptdisplaygrid").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "Report Elements.xlsx"
    					
				})		 
	});
$(document).on('click',".exportexcel",function() {
	$("#rptdisplaygrid").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "Report Elements.xlsx"
    					
				})		 
	});
		
$(".clearsearch").click(function() {
		var grid = $("#rptdisplaygrid");
		grid.jqGrid('setGridParam',{search:false});

		var postData = grid.jqGrid('getGridParam','postData');
		$.extend(postData,{filters:""});
		grid.trigger("reloadGrid",[{page:1}]);
	 $('input[id*="gs_"]').val("");
                $('select[id*="gs_"]').select2('val',['']);
	});		
		
	});
</script>
@endsection