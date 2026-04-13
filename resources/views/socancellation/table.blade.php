@extends('layouts.header')
@section('content')
<div class="container">

  <div class="row">
  <div class="col-md-12">
  <div class="panel panel-visible" id="spy1">
  <div class="panel-heading">

<div class="panel-title hidden-xs">
<a id="edit"><button type="button" class="btn sec">Cancel Sales Order</button></a>

<a id="clearsearch"><button type="button" class="btn search">Clear Search</button></a>
</div>
  </div>
  </div>
  </div>
  <div class="col-md-12">

    <table id="soorderapprovalgrid"></table>
  </div>
  </div>
  </div>

<script type="text/javascript">
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
$("#soorderapprovalgrid").jqGrid({
url: "socancellationdata",
datatype: "json",
mtype: "GET",
colModel: [
{ name: "sales_hdr_id", label: "sales_hdr_id", width: 250,hidden:true},
{ name: "sales_order_no", label: "SO No.", width: 250},
{ name: "salesperson_id", label: "Sales Pesrson", width: 250},
{ name: "sales_order_date",align: "center", sorttype: "date", frozen: true,
            formatter: "date", formatoptions: { newformat: "Y-M-d" }, datefmt: "Y-M-d",
            editoptions: { dataInit: initDateEdit },
            searchoptions: { sopt: ["eq", "ne", "lt", "le", "gt", "ge"], dataInit: initDateSearch }
            },
{ name: "order_type_id", label: "Order Type", width: 250},
{ name: "order_status_id", label: "Order Status", width: 250},
{ name: "ar_sales_hdr_id", label: "Quote Details", width: 100 },
{ name: "customer_id", label: "Customer", width: 250},
{ name: "contact_person", label: "Contact Person", width: 250},
{ name: "contact_number", label: "Contact Number", width: 250},

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
caption: "Sales Order Approval" ,
searching: {
defaultSearch: "cn"
}
});
jQuery("#soorderapprovalgrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});


$("#edit").click(function(){
        var index = $("#soorderapprovalgrid").jqGrid('getGridParam','selrow');
		var saleshdrid = $("#soorderapprovalgrid").jqGrid ('getCell', index, 'sales_hdr_id');
        var ordertype = $("#soorderapprovalgrid").jqGrid ('getCell', index, 'order_type_id');
        var status = $("#soorderapprovalgrid").jqGrid ('getCell', index, 'order_status_id');


       if( saleshdrid != false ) {
	//if(status!="Approved" && status !="Rejected" ){
		//alert(ordertype);
		window.location.replace('socancellation/' +saleshdrid+'/'+ordertype);
		//}


		   else{
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
		var grid = $("#soorderapprovalgrid");
		grid.jqGrid('setGridParam',{search:false});

		var postData = grid.jqGrid('getGridParam','postData');
		$.extend(postData,{filters:""});
		grid.trigger("reloadGrid",[{page:1}]);
	});
	
/*End*/
});
    
    </script>

@endsection
