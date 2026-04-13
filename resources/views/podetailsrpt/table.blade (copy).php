@extends('layouts.header')
@section('content')



<div id="accordion">
<div class="panel-heading" role="tab" id="headingOne">
    <h4 class="panel-title">
    <a role="button">Purchase Order Details</a>
    </h4>
</div>
</div>



<div class="panel panel-visible" id="spy1">
	<div class="row">
	<div class="col-md-12">
<?php include('toolbar.php'); ?>
<button type='button' href='' class='btn clearsearch'>Clear Search </button>
<button type='button' id="showcolumn" value="1" class='btn showcolumn showcolumns'> Show column </button>
</div>
</div>


<div class="row">
<div class="col-md-12">
<hr class="xlg">
</div>
</div>

<div class="row">
<div class="col-md-12">
<table id="podetailsgrid"></table>
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

function fontColorFormat(cellvalue, options, rowObject) {
 var color = "red";
 var cellHtml = "<span style='color:" + color + "' originalValue='" + cellvalue + "'>" + cellvalue + "</span>";
 return cellHtml;
 }
var supplieropt="{{$supplieropt}}";
$("#podetailsgrid").jqGrid({

datatype: "local",
mtype: "GET",
	 colModel: [
	{ name: "po_hdr_id", label: "id",hidden:true },
        { name: "po_status", label: "Status"},
        { name: "po_date", label: "PO Date"},
        { name: "delivery_date", label: "Delivery Date"},
        { name: "po_number", label: "PO Number"},
	{ name: "supplier_id",  align: "center",label: "Vendor Name",stype:'select', editoptions:{value:supplieropt}},
	{ name: "po_grand_total", label: "Amount"},
        ],
        iconSet: "fontAwesome",
        rowNum: 10,
        rowList: [10,20,50,100],
        sortorder: "desc",
        viewrecords: true,
        gridview: true,
        rownumbers:true,
        caption: "",
	autowidth:true,
        pager: true,
//         footerrow: true, 
        searching: {
            defaultSearch: "cn"
        }
      });
	var mydata='{{$result}}';
	mydata=JSON.parse(mydata.replace(/&quot;/g,'"'));
	for(var i=0;i<=mydata.length;i++)
	jQuery("#podetailsgrid").jqGrid('addRowData',i+1,mydata[i]);
jQuery("#podetailsgrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
//jQuery("#gs_podetailsgrid_supplier_id").select2();
showcolumn('podetailsgrid');


/*Karthigaa purpose:clear search the jqgrid*/
	$(".clearsearch").click(function(){
            var grid = $("#podetailsgrid");
            grid.jqGrid('setGridParam',{search:false});

            var postData = grid.jqGrid('getGridParam','postData');
            $.extend(postData,{filters:""});
            grid.trigger("reloadGrid",[{page:1}]);
    });
/*end*/
	$(window).scroll(function() {
if ($(this).scrollTop() >150){
    $('.header-sticky').addClass("sticky");
  }
  else{
    $('.header-sticky').removeClass("sticky");
  }
});

});
  </script>
@endsection
