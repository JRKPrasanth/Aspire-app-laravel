@extends('layouts.header')
@section('content')

<h2 class="heads">CREDIT NOTE</h2>
  
  <div class="panel panel-visible" id="spy1">


<div class="panel-title ">
  <div class="row">
   <div class="col-md-12" >
<?php include('toolbar.php'); ?>
 <button type="button" class="btn download  export">Export</button>
</div>
</div>
</div>

 <div class="row">
  <div class="col-md-12" >

    <table id="creditgrid" ></table>
  </div>
  </div>



  </div>

  



<script type="text/javascript">
$( document ).ready(function() {
$("#creditgrid").jqGrid({

      url: "getaccountcreditData",
      datatype: "json",
      mtype: "GET",
		 colNames: [""," Credit Number","Credit Date","Credit Status","Customer Name","Invoice Number","Invoice Date"],
        colModel: [
            { name: "creditnote_hdr_id",align: "center",hidden:true},
            { name: "credit_number",align: "center"},
            { name: "credit_date",align: "center"},
            { name: "credit_status",align: "center"},
            { name: "customerid", align: "center"},
            { name: "invoice_number", align: "center"},
            { name: "invoice_date", align: "center" }
             ],
 rowNum:10,
		viewrecords: true,
		footerrow: true,
		userDataOnFooter: true, // use the userData parameter of the JSON response to display data on footer
		width: 780,
		height: 300,
		rowList: [10, 20, 50, 100,500,1000],
		pager: "#creditgrid",
        sortorder: "desc"
});
jQuery("#creditgrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
showcolumn('creditgrid');

	
	 $(document).on('click',".export",function() {
   	$("#creditgrid").jqGrid('exportToPdf', {
  title: null,
  orientation: 'portrait',
  pageSize: 'A4',
  description: null,
  onBeforeExport: null,
  download: 'download',
  includeLabels : true,
  includeGroupHeader : true,
  includeFooter: true,
  fileName : "Credit Note.pdf",
  mimetype : "application/pdf"  
});
			
$("#creditgrid").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "Credit Note.xlsx"
    					
				})	
	
});

/*Karthigaa Purpose For CREATE Function*/
    $("#create").click(function(){
        var url="{{ url('soinvoicetable') }}";
        window.location.replace(url);
    });

/*Karthigaa Purpose For Edit Function*/
    $("#edit").click(function(){
            var index = $("#creditgrid").jqGrid('getGridParam','selrow');
            var id = $("#creditgrid").jqGrid ('getCell', index, 'creditnote_hdr_id');
            var credit_status = $("#creditgrid").jqGrid ('getCell', index, 'credit_status');
           if(index) {
               if(credit_status!= 'INITIATED'){
                    window.location.replace('accountcreditnoteedit/'+id);
                }else{
                     notyMsg("info","Unable to edit Credit Note");
                }
            }
            else
            {
                     notyMsg("info","Please Select Row");
            }
    });

    /*Karthigaa Purpose For View Function*/
       $("#view").click(function(){
	var index = $("#creditgrid").jqGrid('getGridParam','selrow');
	var id = $("#creditgrid").jqGrid ('getCell', index, 'creditnote_hdr_id');
	if(index)
	{
		window.location.replace('accountcreditnoteview/' +id);
	}
	else
	{
			 notyMsg("info","Please Select Row");
	}
    });

/***** Karthigaa Purpose For CLEAR search ********/
	$("#clearsearch").click(function() {
		var grid = $("#creditgrid");
		grid.jqGrid('setGridParam',{search:false});

		var postData = grid.jqGrid('getGridParam','postData');
		$.extend(postData,{filters:""});
		grid.trigger("reloadGrid",[{page:1}]);
	});
/*End*/
});
    </script>
@endsection
