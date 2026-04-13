@extends('layouts.header')
@section('content')



<div id="accordion">
<div class="panel-heading" role="tab" id="headingOne">
    <h4 class="panel-title">
    <a role="button">Aging Summary</a>
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
<table id="soagingsummarygrid"></table>
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
var customeropt="{{$customeropt}}";
$("#soagingsummarygrid").jqGrid({
url: "getsoagingsummaryData",
datatype: "json",
mtype: "GET",
	 colModel: [
	{ name: "invoice_hdr_id", label: "id",hidden:true },
	{ name: "ship_to_customer_id",  align: "center",label: "Customer Name",stype:'select', editoptions:{value:customeropt}},
	{ name: "invoice_grand_total", label: "Current", formatter:'number'},
        { name: "15days", label: "1-15 Days", formatter:'number'},
        { name: "30days", label: "16-30 Days", formatter:'number'},
        { name: "45days", label: "31-45 Days", formatter:'number'},
        { name: "above45days", label: ">45 Days", formatter:'number'},
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
jQuery("#soagingsummarygrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
//jQuery("#gs_soagingsummarygrid_supplier_id").select2();
showcolumn('soagingsummarygrid');


/*Karthigaa purpose:clear search the jqgrid*/
	$(".clearsearch").click(function(){
            var grid = $("#soagingsummarygrid");
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
