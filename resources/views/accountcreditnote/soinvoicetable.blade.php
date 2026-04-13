@extends('layouts.header')
@section('content')

<h2 class="heads">So Invoice Table</h2>
  
  <div class="panel panel-visible" id="spy1">


<div class="panel-title ">
  <div class="row">
  <div class="col-md-12" >
<a> <button type="button" class="btn add create_credit" >CREATE</button></a>
<a class='btn cancel' onclick="location.href = '{{url('accountcreditnote')}}'">Cancel</a>
<a id="clearsearch"><button type="button" class="btn search">Clear Search</button></a>
<button type='button' id="showcolumn" value="1" class='btn showcolumn vie '> Show column </button>
	   <button type="button" class="btn download  export">Export</button>
</div>
</div>
</div>
  <div class="row">
  <div class="col-md-12" >

    <table id="invoicegrid"></table>
  </div>
  </div>


  </div>

  



<script type="text/javascript">
$( document ).ready(function() {

        var cusopt="{{$cusnameopt}}";
        var invoicenoopt="{{$invoicenoopt}}";
$("#invoicegrid").jqGrid({
      url: "salesinvoiceData",
      datatype: "json",
      mtype: "GET",
	loadonce: true,
         colModel: [
            { name: "invoice_hdr_id",  align: "center",hidden:true},
            { name: "so_rma_hdr_id",  align: "center",hidden:true},
            { name: "rma_ref_no",  label:" RMA Ref No",align: "center" },
            { name: "return_date", label:"RMA Date",align: "center" },
            { name: "reference_source_id", label:"Invoice Number" ,editable:true,stype:'select', editoptions:{value:invoicenoopt}},
            { name: "return_status", label:"Invoice Status" ,align: "center" },
             { name: "ship_to_customer_id", label: "Customer Name",editable:true,stype:'select', editoptions:{value:cusopt}},
            { name: "remarks", label:"Remarks",align: "center" },

            ],
 rowNum:20,
		viewrecords: true,
		footerrow: true,
		userDataOnFooter: true, // use the userData parameter of the JSON response to display data on footer
		width: 780,
		height: 300,
		rowList: [10, 20, 50, 100,500,1000],
		pager: "#invoicegrid",
        sortorder: "desc"
});
jQuery("#invoicegrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});

showcolumn('invoicegrid');

 $(document).on('click',".export",function() {
   	$("#invoicegrid").jqGrid('exportToPdf', {
  title: null,
  orientation: 'portrait',
  pageSize: 'A4',
  description: null,
  onBeforeExport: null,
  download: 'download',
  includeLabels : true,
  includeGroupHeader : true,
  includeFooter: true,
  fileName : "Sales Invoice.pdf",
  mimetype : "application/pdf"  
});
			
$("#invoicegrid").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "Sales Invoice.xlsx"
    					
				})	
	
});
/*Karthigaa Purpose For CREATE Function*/
$(".create_credit").click(function(){
     var index = $("#invoicegrid").jqGrid('getGridParam','selrow');
     var rmahdrid = $("#invoicegrid").jqGrid ('getCell', index, 'so_rma_hdr_id');
        if(index){
		window.location.replace('accountcreditnotecreate/' +rmahdrid);
	}
	else
	{
		 notyMsg("info","Please Select Row");
	}

});
  /*Karthigaa Purpose For cancel*/
      $("#cancel").click(function(){
	var url="{{  URL::to('accountcreditnote')}}";
	window.location.replace(url);

	});

/***** Karthigaa Purpose For CLEAR search ********/
	$("#clearsearch").click(function() {
		var grid = $("#invoicegrid");
		grid.jqGrid('setGridParam',{search:false});

		var postData = grid.jqGrid('getGridParam','postData');
		$.extend(postData,{filters:""});
		grid.trigger("reloadGrid",[{page:1}]);
	});
/*End*/
});
    </script>
@endsection
