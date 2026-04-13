
@extends('layouts.header')
@section('content')

<div id="accordion">
<div class="panel-heading" role="tab" id="headingOne">
    <h4 class="panel-title">
    <a role="button">Pending Payments Details</a>
    </h4>
</div>
</div>



<div class="panel panel-visible" id="spy1">
	<div class="row">
	<div class="col-md-12">
<?php include('toolbar.php'); ?>
<button type='button' id="payablereport"  class='btn payablereport vie'> Pending Report </button>
<button type='button' href='' class='btn clearsearch'>Clear Search </button>
<button type='button' id="showcolumn" value="1" class='btn showcolumn showcolumns'> Showcolumn </button>
</div>
</div>

<div class="row">
<div class="col-md-12">
<hr class="xlg">
</div>
</div>

<div class="row">
<div class="col-md-12">
<table id="paymentsgrid"></table>
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
$("#paymentsgrid").jqGrid({

datatype: "local",
mtype: "GET",
	 colModel: [
	{ name: "po_invoice_id", label: "id",hidden:true },
	{ name: "bill_number", label: "Invoice Number" },
        { name: "invoice_date", label: "Invoice Date"},
        { name: "po_number", label: "PO Number" },
        { name: "supplier_id",  align: "center",label: "Supplier Name",stype:'select', editoptions:{value:supplieropt}},
	{ name: "invoice_grand_total", label: "Invoice Amount"},
        { name: "paid_amount", label: "Paid Amount"},
		{ name: "balance_amount", label: "Balance Amount"},
                { name: "paid_status", label: "Payment Status",formatter:fontColorFormat},
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
        searching: {
            defaultSearch: "cn"
        }
      });
	
	var mydata='{{$result}}';
	mydata=JSON.parse(mydata.replace(/&quot;/g,'"'));
	for(var i=0;i<=mydata.length;i++)
	jQuery("#paymentsgrid").jqGrid('addRowData',i+1,mydata[i]);
	
	
	
jQuery("#paymentsgrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
jQuery("#paymentsgrid").jqGrid('hideCol','cb');
//$("#paymentsgrid").jqGrid('setCell',rowId,'paid_status',{color:'red'});
//jQuery("#gs_paymentsgrid_supplier_id").select2();
//jQuery("#gs_paymentsgrid_po_invoice_id").select2();
showcolumn('paymentsgrid');
/*Karthigaa Purpose For Report*/
$("#payablereport").click(function(){
    var gr=$('#paymentsgrid').jqGrid('getGridParam','selrow');
  var cellValue = $("#paymentsgrid").jqGrid ('getCell', gr, 'payment_id');
  if(cellValue != false){
     window.location.replace('payablereport/' +cellValue);
  }
  else
  {
  notyMsg("info","Please Select Row");
  }  
});


 
$('#view').click(function(){
  var gr=$('#paymentsgrid').jqGrid('getGridParam','selrow');
  var cellValue = $("#paymentsgrid").jqGrid ('getCell', gr, 'payment_id');
  if(cellValue != false){
     var url="paymentsview";
     var viewurl = url+'/'+cellValue+'/view';
     window.location.replace('paymentsview/' +cellValue);
  }
  else
  {
  notyMsg("info","Please Select Row");
  }
});

/*Karthigaa purpose:clear search the jqgrid*/
	$(".clearsearch").click(function(){
            var grid = $("#paymentsgrid");
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

