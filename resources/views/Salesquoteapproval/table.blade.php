@extends('layouts.header')
@section('content')
<div class="container-fluid tray tray-center">

  <div class="row">
  <div class="col-md-12">
  <div class="panel panel-visible" id="spy1">
  <div class="panel-heading">

<div class="panel-title hidden-xs">
<a id="edit"><button type="button" class="btn sec">Approve Sales Quote</button></a>

<a id="clearsearch"><button type="button" class="btn search">Clear Search</button></a>
</div>
  </div>
  </div>
  </div>
  </div>

  <div class="row">
  <div class="col-md-12">

    <table id="soquoteapprovalgrid"></table>
  </div>
  </div>
  </div>

<script type="text/javascript">
$( document ).ready(function() {
	$(".select2").select2();
$(".select2").css('width','100%');
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
$("#soquoteapprovalgrid").jqGrid({
url: "soquoteapprovalgriddata",
datatype: "json",
mtype: "GET",
colNames: ["","Quote Number","Quote Date","Quote Name","Quote Type","Quote Status","Customer Name","Remarks"],
colModel: [
{ name: "quote_hdr_id",align: "center",hidden:true},
{ name: "quote_no", align: "center" },
{ name: "quote_date",align: "center", sorttype: "date", frozen: true,
            formatter: "date", formatoptions: { newformat: "Y-M-d" }, datefmt: "Y-M-d",
            editoptions: { dataInit: initDateEdit },
            searchoptions: { sopt: ["eq", "ne", "lt", "le", "gt", "ge"], dataInit: initDateSearch }
            },
{ name: "quote_name", align: "center" },
{ name: "quote_type", align: "center" },
{ name: "quote_status", align: "center" },
{ name: "customer_id", align: "center",stype:'select', editoptions:{value:opt} },
{ name: "remarks",align: "center" },
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
	rownumWidth:50,
caption: "Sales Quote Approval" ,
searching: {
defaultSearch: "cn"
}
});
jQuery("#soquoteapprovalgrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
$('#gs_soquoteapprovalgrid_customer_id').select2();
/*Karthigaa Purpose For Edit Function*/
$("#edit").click(function(){
        var index = $("#soquoteapprovalgrid").jqGrid('getGridParam','selrow');
	var quotehdrid = $("#soquoteapprovalgrid").jqGrid ('getCell', index, 'quote_hdr_id');
        var quotetype = $("#soquoteapprovalgrid").jqGrid ('getCell', index, 'quote_type');
        var status = $("#soquoteapprovalgrid").jqGrid ('getCell', index, 'status');
	

       if( quotehdrid != false ) {
if(status!="Approved" && status !="Rejected" ){

		window.location.replace('salesquoteapprovalview/' +quotehdrid+'/'+quotetype);
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
		var grid = $("#soquoteapprovalgrid");
		grid.jqGrid('setGridParam',{search:false});

		var postData = grid.jqGrid('getGridParam','postData');
		$.extend(postData,{filters:""});
		grid.trigger("reloadGrid",[{page:1}]);
	});
/*End*/
});
    </script>
@endsection
