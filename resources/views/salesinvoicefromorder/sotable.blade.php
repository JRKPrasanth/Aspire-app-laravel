@extends('layouts.header')
@section('content')

 
  
  <div class="panel panel-visible" id="spy1">
  <div class="panel-heading">

<div class="panel-title ">
<span class="glyphicon glyphicon-tasks"></span>
<!--<a href="salesinvoicecreatestandard/1" class='btn  btn-success'> <i class="fa fa-plus"> Create STANDARD</i></a>
<a href="salesinvoicecreatelabour/2" class='btn  btn-success'> <i class="fa fa-plus"> Create LABOUR </i></a>-->
<a id="convertinvoice"  class="btn  btn-primary"><i class="fa fa-edit"> Convert Invoice </i></a>
<a id="view"  class="btn  btn-info"><i class="fa fa-eye"> View </i></a>
<a id="delete" class='btn  btn-info btn-danger'><i class="fa fa-trash"> Delete </i></a>
<!--<a id="clearsearch" class='btn btn-info clearsearch'><i class="fa fa-trash"> Clear Search </i></a> -->
</div>
  </div>

<div class="row">
  <div class="col-md-12">

    <table id="grid1"></table>
  </div>
  </div>

  </div>
  

  
  
<script type="text/javascript">
$( document ).ready(function() {
  var opt="{{$datas}}";

$("#grid1").jqGrid({
      url: "getsalesinvoicefromorderData",
      datatype: "json",
      mtype: "GET",
	 colNames: ["","Sales Order No","Order Type","Sales Order Date","Customer Name"],
         colModel: [
            { name: "sales_hdr_id",label: "",align: "center",hidden:true},
            { name: "sales_order_no", label: "sales Order No",align: "center" },
            { name: "order_type_id", label: "Order Type",align: "center" },
            { name: "sales_order_date", align: "center" },
            { name: "customer_id", align: "center" },
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
        caption: "Sales Invoice From Order" ,
        delOptions: { url: '/SalesinvoicefromorderController/delete' },
	searching: {
	defaultSearch: "cn"
	}
});
$("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
//For Default Width
  $(window).bind('resize', function() {
        $("#grid1").setGridWidth($(window).width()*0.99);
    }).trigger('resize');


    $("#convertinvoice").click(function(){
   var index = $("#grid1").jqGrid('getGridParam','selrow');
   var orderid = $("#grid1").jqGrid ('getCell', index, 'sales_hdr_id');
  alert(orderid);
   var invoicetype= $("#grid1").jqGrid ('getCell', index, 'order_type_id');
   //alert(invoicetype);
   if(invoicetype=='STANDARD')
  var type=1;
   else
 var type=2;
   alert(invoicetype);
   if( orderid != false ){
//alert();
  window.location.replace('salesinvoiceconvert/' +orderid+'/'+type);
    //window.location.replace('salesinvoiceconvert');
         }
   else
   {
             alert("Please Select Row");
         }
     });

});
    </script>
@endsection
