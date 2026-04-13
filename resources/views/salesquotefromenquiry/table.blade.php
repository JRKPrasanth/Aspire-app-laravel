@extends('layouts.header')
@section('content')
<div class="container">

  <div class="row">
  <div class="col-md-12">
  <div class="panel panel-visible" id="spy1">
  <div class="panel-heading">

<div class="panel-title hidden-xs">
<span class="glyphicon glyphicon-tasks"></span>
<a id=""><button type="button" class="btn add convert">Convert to Order</button></a>
</div>
  </div>
  </div>
  </div>
  <div class="col-md-12">

    <table id="grid1"></table>
  </div>
  </div>

  @extends('layouts.footer')

  </div>

<script type="text/javascript">
$( document ).ready(function() {
  var opt="{{$datas}}";

$("#grid1").jqGrid({
      url: "soquotefromenquiry",
      datatype: "json",
      mtype: "GET",
	 colNames: ["","Inquiry No","Inquiry Date","Customer Name","Inquiry Type"],
         colModel: [
            { name: "so_inquiry_hdr_id",label: "",align: "center",hidden:true},
            { name: "inquiry_no", label: "sales Order No",align: "center" },

            { name: "inquiry_date", align: "center" },
            { name: "customerid", align: "center" },
            { name: "inquiry_type", align: "center" },
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
   var orderid = $("#grid1").jqGrid ('getCell', index, 'so_inquiry_hdr_id');
  alert(orderid);
   var invoicetype= $("#grid1").jqGrid ('getCell', index, 'inquiry_type');
   alert(invoicetype);
   if(invoicetype=='STANDARD')
  var type=1;
   else
 var type=2;
   alert(invoicetype);
   if( orderid != false ){
//alert();
  window.location.replace('salesenquiryconvert/' +orderid+'/'+type);
    //window.location.replace('salesinvoiceconvert');
         }
   else
   {
             alert("Please Select Row");
         }
     });

	$(".convert").click(function()
{
	var index = $("#grid1").jqGrid('getGridParam','selrow');
	var quoteid = $("#grid1").jqGrid ('getCell', index, 'so_inquiry_hdr_id');
	if( quoteid != false )
	{
		window.location.replace('salesenquiryconvert/' +quoteid);
	}
	else
	{
		alert("Please Select Row");
	}
});




});
    </script>
@endsection
