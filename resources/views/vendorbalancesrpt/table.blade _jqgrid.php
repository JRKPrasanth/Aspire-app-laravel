@extends('layouts.header')
@section('content')




    <h4 class="heads">
		<a role="button"><span class="header_part">Vendor Balances</span></a>    <span class="ui_close_btn"><a class="collapse-close pull-right btn-danger close_vendor"></a></span></h4>
		





<div class="panel panel-visible" id="spy1">
	<div class="row">
	<div class="col-md-12">
<?php include('toolbar.php'); ?>

</div>
</div>



<div class="row">
<div class="col-md-12 vendorbalancesrpt" style="padding: 12px;">
<table id="vendorbalancegrid"></table>
</div>
<div class="col-md-12 vendordetails"  id="vendordetails">

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

$("#vendorbalancegrid").jqGrid({
datatype: "local",
mtype: "GET",
	 colModel: [
	
	{ name: "supplier_ids", label: "id",hidden:true },
	{ name: "supplier_id",  align: "center",label: "Supplier Name",stype:'select', editoptions:{value:supplieropt}},
	{ name: "invoice_grand_total", label: "Bill Balance"},
	{ name: "paid_amount", label: "Paid Amount"},
	{ name: "balance_amount", label: "Balance Amount",formatter:fontColorFormat},
        
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



	$(".close_vendor").on('click',function(){
	var val=$(this).attr('data-value');
	var val1=$(this).attr('data-myvalue');
	$("."+val).hide();
	$("."+val1).show();	
	$(".header_part").html("Vendor Balances");	
	
	});
	 $('#vendorbalancegrid').on('click', function () 
            {
                var index = $("#vendorbalancegrid").jqGrid('getGridParam','selrow');
                var supplier_ids = $("#vendorbalancegrid").jqGrid ('getCell', index, 'supplier_ids'); 
		  var supplier_id = $("#vendorbalancegrid").jqGrid ('getCell', index, 'supplier_id');  
		 		var url="{{URL::to('pendingpurchasepayment')}}?supplier_id="+supplier_ids;
		 		$.get(url,function(data)
			    {
					$('#vendordetails').html(data);
					$('.close_vendor').attr('data-value','vendordetails');
					$('.close_vendor').attr('data-myvalue','vendorbalancegrid');
					$(".header_part").html("Vendor Balances For - "+supplier_id);
					$('.vendordetails').show();
					$('.vendorbalancegrid').hide();
				});
		 
            });
	var mydata='{{$result}}';
	mydata=JSON.parse(mydata.replace(/&quot;/g,'"'));
	for(var i=0;i<=mydata.length;i++)
	jQuery("#vendorbalancegrid").jqGrid('addRowData',i+1,mydata[i]);
	
	
jQuery("#vendorbalancegrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});

showcolumn('vendorbalancegrid');

/*Karthigaa purpose:clear search the jqgrid*/
	$(".clearsearch").click(function(){
            var grid = $("#vendorbalancegrid");
            grid.jqGrid('setGridParam',{search:false});

            var postData = grid.jqGrid('getGridParam','postData');
            $.extend(postData,{filters:""});
            grid.trigger("reloadGrid",[{page:1}]);
    });
/*end*/
	
		//$("#gs_vendorbalancegrid_supplier_id").select2();
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
