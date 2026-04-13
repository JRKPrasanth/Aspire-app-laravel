@extends('layouts.header')
@section('content')
<div class="container">

  <div class="row">
  <div class="col-md-12">
  <div class="panel panel-visible" id="spy1">
  <div class="panel-heading">

<div class="panel-title hidden-xs">
<a id="edit"><button type="button" class="btn sec">Approve Sales Invoice</button></a>

<a id="clearsearch"><button type="button" class="btn search">Clear Search</button></a> 
</div>
  </div>
  </div>
  </div>
  <div class="col-md-12">

    <table id="soinvoiceapprovalgrid"></table>
  </div>
  </div>
  </div>

<script type="text/javascript">

	$(".select2").select2();
$(".select2").css('width','100%');


$( document ).ready(function() {
        initDateEdit = function (elem) {
				$(elem).datepicker({
					dateFormat: "yy-M-dd",
//                                        dateFormat: "yy-mm-dd",
					autoSize: true,
					changeYear: true,
					changeMonth: true,
					showButtonPanel: true,
					showWeek: true
				});
			},
                        initDateSearch = function (elem) {
				setTimeout(function () {
					initDateEdit(elem);
				}, 100);
			};
	var opt="{{ $cusnameopt }}";
$("#soinvoiceapprovalgrid").jqGrid({
url: "getSalesinvoiceapproveData",
datatype: "json",
mtype: "GET",
colModel: [
            { name: "invoice_hdr_id",label:"Invoice Id",align: "center",hidden:true},
           { name: "invoice_date",align: "center", sorttype: "date", frozen: true,
            formatter: "date", formatoptions: { newformat: "Y-M-d" }, datefmt: "Y-M-d",
            editoptions: { dataInit: initDateEdit },
            searchoptions: { sopt: ["eq", "ne", "lt", "le", "gt", "ge"], dataInit: initDateSearch }
            },
            { name: "invoice_number",label:"Invoice Number", align: "center" },
            { name: "invoice_type",label:"Invoice Type", align: "center" },
            { name: "invoice_status",label:"Invoice Status", align: "center" },
            { name: "ship_to_customer_id",label:"Ship to Customer", align: "center" ,stype:'select', editoptions:{value:opt} },
            { name: "remarks",label:"Remarks",align: "center" },
],
//	 data:result,
iconSet: "fontAwesome",
rownumbers: true,
sortorder: "desc",
threeStateSort: true,
sortIconsBeforeText: true,
headertitles: true,
pager: true,
rowNum: 10,
viewrecords: true,
	autowidth:true,
caption: "Sales Invoice Approval" ,
searching: {
defaultSearch: "cn"
}
});
jQuery("#soinvoiceapprovalgrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
$('#gs_soinvoiceapprovalgrid_ship_to_customer_id').select2();

/*Karthigaa Purpose For Edit Function*/
$("#edit").click(function(){
        var index = $("#soinvoiceapprovalgrid").jqGrid('getGridParam','selrow');
	var ret = jQuery("#soinvoiceapprovalgrid").jqGrid('getRowData',index);
	console.log(ret);
	var invoicehdrid = ret.invoice_hdr_id;
	var invoicetype = ret.invoice_type;
	var status = ret.invoice_status;
        if( invoicehdrid != false ) {
if(status!="Approved" && status !="Rejected" ){

		window.location.replace('salesinvoiceapprovalview/' +invoicehdrid+'/'+invoicetype);
}else{
alert("Already"+status);
}
	}

	else
	{
		alert("Please Select Row");
	}

});
/***** Deepika Purpose For CLEAR search ********/
	$("#clearsearch").click(function() {
		var grid = $("#soinvoiceapprovalgrid");
		grid.jqGrid('setGridParam',{search:false});

		var postData = grid.jqGrid('getGridParam','postData');
		$.extend(postData,{filters:""});
		grid.trigger("reloadGrid",[{page:1}]);
	});
/*End*/
});
    </script>
@endsection
