@extends('layouts.header')
@section('content')

<div id="accordion">
<div class="panel-heading" role="tab" id="headingOne">
    <h4 class="panel-title">
    <a role="button">Poinvoice Table</a>
    </h4>
</div>
</div>


  <div class="panel panel-visible" id="spy1">


<div class="panel-title ">
  <div class="row">
  <div class="col-md-12" >
<a> <button type="button" class="btn add create_debit" >CREATE</button></a>
<a class='btn cancel' onclick="location.href = '{{url('accountdebitnote')}}'">Cancel</a>
<a id="clearsearch"><button type="button" class="btn clearsearch">Clear Search</button></a>
<button type='button' id="showcolumn" value="1" class='btn showcolumn vie '> Show column </button>
</div>
</div>
</div>

<div class="row">
<div class="col-md-12">
<hr class="xlg">
</div>
</div>


  <div class="row">
  <div class="col-md-12" >

    <table id="invoicegrid"></table>
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

        var suppliername = "{{$suppliername}}";
        var ponumber = "{{$ponumber}}";
$("#invoicegrid").jqGrid({
      url: "poinvoiceData",
      datatype: "json",
      mtype: "GET",
	 colNames: ["","Invoice Number","Invoice Date","Supplier Name","PO Number","PO Date"],
        colModel: [
            { name: "po_invoice_id",align: "center",hidden:true},
	    { name: "bill_number",align: "center" },
            { name: "invoice_date",align: "center" },
	    { name: "supplier_id", align: "center" ,stype:'select', editoptions:{value:suppliername}},
            { name: "po_number", align: "center" ,stype:'select', editoptions:{value:ponumber}},
            { name: "po_date", align: "center" },
             ],
	iconSet: "fontAwesome",
	rownumbers: true,
	sortorder: "desc",
        threeStateSort: true,
	sortIconsBeforeText: true,
	headertitles: true,
	pager: true,
	rowNum: 10,
	viewrecords: true,
         caption: "PURCHASE INVOICE" ,
	searching: {
	defaultSearch: "cn"
	}
});
jQuery("#invoicegrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
jQuery("#gs_invoicegrid_supplier_id").select2();
jQuery("#gs_invoicegrid_po_number").select2();

showcolumn('invoicegrid');


/*Karthigaa Purpose For CREATE Function*/
$(".create_debit").click(function(){
     var index = $("#invoicegrid").jqGrid('getGridParam','selrow');
     var invoicehdrid = $("#invoicegrid").jqGrid ('getCell', index, 'po_invoice_id');
        if( invoicehdrid != false ){
		window.location.replace('accountdebitnotecreate/' +invoicehdrid);
	}
	else
	{
		 notyMsg("info","Please Select Row");
	}

});
  /*Karthigaa Purpose For cancel*/
      $("#cancel").click(function(){
	var url="{{  URL::to('accountdebitnote')}}";
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
