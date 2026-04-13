<?php  //dd($status);?>
@extends('layouts.header')
@section('content')

<h2 class="heads">Pick Order</h2>

<div class="card">
<div class="card-header">
	
<div class="card-body card-block">

<?php
	
	if($url=="pickorder"){  ?>
    <div class="row">

      <div class="col-md-12 text-left">

       <button id="pickorder" type="button" class=" btn tips pickorder pickorders" data-value="orderwisedata">PICK ORDER</button>
		   <button type="button" id="showcolumn" class="tips btn btn-large showcolumn  vie dttoagel_button">Show Column</button>
            <ul class="nav nav-tabs">
              <li class="active ordwise"><a data-toggle="tab" class="order" data-class="order" href="#order" value="orderwisedata">Order wise</a></li>
              <li class="prdwise"><a data-toggle="tab" class="product" data-class="product" href="#product" value="productwisedata">Product wise</a></li>
              <input type="hidden" clas="buttonvalue" value="" />
            </ul>


           
       </div>

    </div>
	<? } else if($url=="invoicefrompickorder") { ?>
	<h2 class="myheaders">Invoice from pickorder</h2>
	<div class="row">
    		<div class="col-md-12 text-left">
    			<button type="button" class="tips btn add invoice">Convert Invoice</button>
    			<button type="button" class="tips btn vie  dttoagel_button">Show Column</button>
    		</div>
    	</div>
    <?	} else {?>
<h2 class="myheaders">dispatch from pickorder</h2>


    	<div class="row">
    		<div class="col-md-12 text-left">
    			<button type="button" class="tips btn add dispatch">Dispatch</button>
    			<button type="button" class="tips btn vie  dttoagel_button">Show Column</button>
    		</div>
    	</div>
        <?php } ?>
	
            <?php if($url=="dispatchfrmpickorder" ){   ?>
                <div class="col-md-12">
                    <table id="pickorderdatas"></table>
                </div>
	<?php } else if($url=="invoicefrompickorder") { ?>
	<div class="col-md-12">
                    <table id="pickorderdatas"></table>
                </div>
	
                <?php }else{ ?>
                    <div class="col-md-12">
                        <div class="tab-content ">
                            <div id="order" class="tab-pane fade in active">
                                <table id="salesorderwise"></table>
                            </div>
                            <div id="product" class="tab-pane fade in prdwise">
                                <table id="productwise"></table>
                            </div>
                        </div>
                    </div>
                    <?php } ?>


</div>
</div>
</div>



<script type="text/javascript">

$( document ).ready(function() {
	
	$(".select2").select2({width:"90%"});
	$('.sales_order_date').datepicker({format: 'yyyy-mm-dd', autoClose: true});
$("#customer_id").jCombo("{{ URL::to('jcomboform?table=m_customers_t:customer_id:customer_name') }}&order_by=customer_name asc",
{selected_value:""});

$("#sales_hdr_id").jCombo("{{ URL::to('jcomboform?table=s_salesorder_hdr_t:sales_hdr_id:sales_order_no') }}&order_by=sales_order_no asc",
{selected_value:""});


//var status_type="";

	var status_type="<?php echo $status; ?>";
	var customer="{{$customer}}";
	var priceopt ="{{$priceopt}}";
	
$("#salesorderwise").jqGrid(
{
url: "{{ URL::to('soordergriddata') }}?status="+status_type,
datatype: "json",
mtype: "GET",

colNames: ["sales_hdr_id","SO.No", "SO Date","Order Status","Customer", "Billing Address", "Shipping Address","Price List"],
	
colModel: [
{ name: "sales_hdr_id", label: "sales_hdr_id",hidden:true},
{ name: "sales_order_no", label: "Sales Order. No."},
{ name: "sales_order_date", label: "SO Date"},
{ name: "order_status_id", label: "Order Status"},
{ name: "ship_to_customer_id", label: "Customer Name",stype:'select', editoptions:{value:customer}},
{ name: "billingaddress", label: "Billing Address",search:false},
{ name: "shippingaddress", label: "Shipping Address",search:false},
{ name: "pricelist_id", label: "Price List",stype:'select', editoptions:{value:priceopt}},

],

iconSet: "fontAwesome",
rowNum: 100,
rowList: [10,20,100,1000,2000],
sortorder: "desc",
viewrecords: true,
gridview: true,
rownumbers:true,
rownumWidth:50,
caption: "Sales Order",
pager: true,
multiselect:true,
multiPageSelection:true,
searching: {
defaultSearch: "cn"
},
onSelectRow: function(id){
	var gr = jQuery("#salesorderwise").jqGrid('getGridParam','selrow');
	var cellValue = jQuery("#salesorderwise").jqGrid ('getCell', gr, 'sales_hdr_id');

        var $grid = $("#salesorderwise"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,
	    sovalue = [];
		var checkcus='';
		var checkbillto='';
		var checkprice='';
		var j=0;
		var j1=0;
		var j2=0;
    for (i = 0, n = selIds.length; i < n; i++) {
  var check_customer= $grid.jqGrid("getCell", selIds[i], "customer_id");
  var check_billto= $grid.jqGrid("getCell", selIds[i], "bill_to_address_id");
  var check_pricelist= $grid.jqGrid("getCell", selIds[i], "sales_pricelist_id");
		console.log(check_customer);
		if(check_customer!=false)
		{

		if(j==0)
			checkcus=check_customer;
		if(checkcus!=check_customer)
			checkcus='1';
			j=1;

		}
		else
		{
			j=0;
		}
		if(checkcus=='1')
{
	var status="Error";
	var msg="Same Customer Should be allow to Create Pickorder";
		notyMsg(status,msg);
	$("#salesorderwise")[0].triggerToolbar();
}

		if(check_billto!=false)
		{

		if(j1==0)
			checkbillto=check_billto;
		if(checkbillto!=check_billto)
			checkbillto='1';
			j1=1;

		}
		else
		{
			j1=0;
		}
		if(checkbillto=='1')
{
	var status="Error";
	var msg="Same Billto address Should be allow to Create Pickorder";
		notyMsg(status,msg);
	   $("#salesorderwise")[0].triggerToolbar();
}
			if(check_pricelist!=false)
		{

		if(j2==0)
			check_pricelist=check_pricelist;
		if(check_pricelist!=check_pricelist)
			check_pricelist='1';
			j2=1;

		}
		else
		{
			j2=0;
		}
		if(check_pricelist=='1')
{
	var status="Error";
	var msg="Same Pricelist Should be allow to Create Pickorder";
		notyMsg(status,msg);
			 $("#salesorderwise")[0].triggerToolbar();
}

      }
	},

});
jQuery("#salesorderwise").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
jQuery("#salesorderwise").jqGrid('hideCol','cb');
	jQuery("#gs_salesorderwise_ship_to_customer_id").select2();
	jQuery("#gs_salesorderwise_pricelist_id").select2();
jQuery("#productwise").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
jQuery("#productwise").jqGrid('hideCol','cb');
	showcolumn('productwise');
	
$('.order').click(function(){
var val=	$(this).attr('value');
	$('.pickorder').attr('data-value',val);
});
	
$('.product').click(function(){

var val=	$(this).attr('value');

$('.pickorder').attr('data-value',val);
});
	
$("#pickorder").click(function()
{
        var datatype=$(this).attr('data-value');
        var index = $("#salesorderwise").jqGrid('getGridParam','selrow');
        var cellValue = jQuery("#salesorderwise").jqGrid ('getCell', index, 'sales_hdr_id');
if(cellValue != false)
{

	if(datatype=="orderwisedata")
        {
            var gr = jQuery("#salesorderwise").jqGrid('getGridParam','selrow');
            var cellValue = jQuery("#salesorderwise").jqGrid ('getCell', gr, 'sales_hdr_id');
            var $grid = $("#salesorderwise"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,
            cellvalues = [];
            var check='';
            var j=0;

            for (i = 0, n = selIds.length; i < n; i++)
            {
                var v =	$grid.jqGrid("getCell", selIds[i], "sales_hdr_id");
                if(v!=false)
                        cellvalues.push(v);
            }
	}
        else
        {

		var gr = jQuery("#productwise").jqGrid('getGridParam','selrow');
                var cellValue = jQuery("#productwise").jqGrid ('getCell', gr, 'sales_hdr_id');
                var $grid = $("#productwise"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,
                cellvalues = [];
                var pid = [];
                var slid = [];
                var check='';
		var j=0;
                for (i = 0, n = selIds.length; i < n; i++)
                {

                var v=	$grid.jqGrid("getCell", selIds[i], "sales_line_id");
                console.log(v);
                if(v!=false)
                        cellvalues.push(v);
                }

	}

        if(datatype == "orderwisedata")
        {
		
                var index = $("#salesorderwise").jqGrid('getGridParam','selrow');
                var cellValue = jQuery("#salesorderwise").jqGrid ('getCell', index, 'sales_hdr_id');
                var type="orderwisedata";
                var url	="{{ url('pickordercheck') }}/"+cellvalues;
                   $.get(url,function(data)
	            	 {
if($.trim(data)==1){
          notyMsgs('info','Sales qty is full picked!!!');
}
else{
	 window.location.replace('pickordercreate/'+cellvalues+'/'+type);
}
		             });
               
        }
        else
        {
                var index = $("#salesorderwise").jqGrid('getGridParam','selrow');
                var cellValue = jQuery("#salesorderwise").jqGrid ('getCell', index, 'sales_hdr_id');
                var type="productwisedata";

               window.location.replace('pickordercreate/'+cellvalues+'/'+type);
        }
}
	else
	{
		notyMsgs('info','Please Select One Row !!!');
	}



});
	
$(window).scroll(function()
{
    if ($(this).scrollTop() >150)
    {
        $('.header-sticky').addClass("sticky");
    }
    else
    {
        $('.header-sticky').removeClass("sticky");
    }
});

var url='<?php echo $url; ?>';
var prepareropt='<?php echo $prepareropt; ?>';
var cusopt='<?php echo $cusopt; ?>';
var priceopt='<?php echo $priceopt; ?>';
	
	if(url=="dispatchfrmpickorder" || url=="invoicefrompickorder"){
$("#pickorderdatas").jqGrid(
{
url: "pickorderdata",
datatype: "json",
mtype: "GET",

colModel: [
{ name: "so_pickrelease_hdr_id", label: "so_pickrelease_hdr_id",hidden:true},
{ name: "release_reference_no", label: "Release Reference No."},
{ name: "release_source", label: "Release Source"},
{ name: "release_date", label: "Release Date"},
{ name: "release_status", label: "Release Status"},
{ name: "preparer_id", label: "Preparer",stype:"select",editoptions:{value:prepareropt}},
{ name: "bill_to_customerid", label: "Customer",stype:"select",editoptions:{value:cusopt}},
{ name: "bill_to_address_id", label: "Billing Address",search:false},
{ name: "pricelist_id", label: "Price List",stype:"select",editoptions:{value:priceopt}},
],

iconSet: "fontAwesome",
rowNum: 100,
rowList: [10,20,100,1000,2000],
sortorder: "asc",
viewrecords: true,
gridview: true,
rownumbers:true,
rownumWidth:50,
caption: "Pick Order",
pager: true,
multiselect:true,
multiPageSelection:true,
searching: {
defaultSearch: "cn"
},
onSelectRow: function(id){
	var gr = jQuery("#pickorderdatas").jqGrid('getGridParam','selrow');
	var cellValue = jQuery("#pickorderdatas").jqGrid ('getCell', gr, 'so_pickrelease_hdr_id');
    var $grid = $("#pickorderdatas"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,
	    sovalue = [];
		var checkcus='';
		var checkbillto='';
		var checkprice='';
		var j=0;
		var j1=0;
		var j2=0;
    for (i = 0, n = selIds.length; i < n; i++) {
  var check_customer= $grid.jqGrid("getCell", selIds[i], "customer_id");
  var check_billto= $grid.jqGrid("getCell", selIds[i], "bill_to_address_id");
  var check_pricelist= $grid.jqGrid("getCell", selIds[i], "pricelist_id");
		console.log(check_customer);
		if(check_customer!=false)
		{

		if(j==0)
			checkcus=check_customer;
		if(checkcus!=check_customer)
			checkcus='1';
			j=1;

		}
		else
		{
			j=0;
		}
		if(checkcus=='1')
{
	var status="Error";
	var msg="Same Customer Should be allow to Create Dispatch";
		notyMsg(status,msg);
	$("#pickorderdatas")[0].triggerToolbar();
}

		if(check_billto!=false)
		{

		if(j1==0)
			checkbillto=check_billto;
		if(checkbillto!=check_billto)
			checkbillto='1';
			j1=1;

		}
		else
		{
			j1=0;
		}
		if(checkbillto=='1')
{
	var status="Error";
	var msg="Same Billto address Should be allow to Create Dispatch";
		notyMsg(status,msg);
	   $("#pickorderdatas")[0].triggerToolbar();
}
			if(check_pricelist!=false)
		{

		if(j2==0)
			check_pricelist=check_pricelist;
		if(check_pricelist!=check_pricelist)
			check_pricelist='1';
			j2=1;

		}
		else
		{
			j2=0;
		}
		if(check_pricelist=='1')
{
	var status="Error";
	var msg="Same Pricelist Should be allow to Create Dispatch";
		notyMsg(status,msg);
			 $("#pickorderdatas")[0].triggerToolbar();
}

      }
	},
});

jQuery("#pickorderdatas").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
jQuery("#pickorderdatas").jqGrid('hideCol','cb');
$("#gs_pickorderdatas_preparer_id").select2();
$("#gs_pickorderdatas_bill_to_customerid").select2();
$("#gs_pickorderdatas_pricelist_id").select2();
	}
	
	showcolumn('pickorderdatas');
	
	$(".dispatch").click(function()
{

	var gr = jQuery("#pickorderdatas").jqGrid('getGridParam','selrow');
	var cellValue = jQuery("#pickorderdatas").jqGrid ('getCell', gr, 'so_pickrelease_hdr_id');
    var $grid = $("#pickorderdatas"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,
	     cellvalues = [];
	    var pid = [];
	     var slid = [];
			var check='';
		var j=0;
    for (i = 0, n = selIds.length; i < n; i++) {
	var v=	$grid.jqGrid("getCell", selIds[i], "so_pickrelease_hdr_id");
	console.log(v);
		if(v!=false)
		cellvalues.push(v);
	}
	if( cellvalues != false )
	{
	window.location.replace('dispatchcreate/'+cellvalues+'?status=PICKORDER');
	}
	else
	{
	notyMsg('info',"Please Select Row");
	}

});
	
	$(".invoice").click(function()
	{

		var gr = jQuery("#pickorderdatas").jqGrid('getGridParam','selrow');
		var cellValue = jQuery("#pickorderdatas").jqGrid ('getCell', gr, 'so_pickrelease_hdr_id');
	    var $grid = $("#pickorderdatas"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,
	     cellvalues = [];
	    var pid = [];
	    var slid = [];
		var check='';
		var j=0;
    for (i = 0, n = selIds.length; i < n; i++) {
	var v=	$grid.jqGrid("getCell", selIds[i], "so_pickrelease_hdr_id");
	console.log(v);
		if(v!=false)
		cellvalues.push(v);
	}
	if( cellvalues != false )
	{
		console.log(cellvalues);

	window.location.replace('pickorderfrominvoicecreate/'+cellvalues);
	}
	else
	{
	notyMsg('info',"Please Select Row");
	}

});
	
	
	
	
	
	
	
	
});
  </script>
@endsection
