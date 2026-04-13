@extends('layouts.header')
@section('content')

<?php //dd($pageMethod); ?>

<h2 class="heads">Workorder From Sales Order</h2>

<div class="panel panel-visible" id="spy1">


<div class="panel-title ">
	<div class="row">
	<div class="col-md-12">
<?php include('toolbar.php'); ?>
</div>
</div>
</div>
<div class="row">
<div class="col-md-12" style="padding: 15px;">
<!-- OUR CONTENT STARTS HERE -->

<table id="grid1"></table>

<!-- OUR CONTENT ENDS HERE -->


</div>
</div>
</div>




<script type="text/javascript">
$(document).ready(function()
{
	/*deepika purpose: function to load data using jqgrid*/
 var format="<?php echo (\Session::get('p_date_format')); ?>";
$("#grid1").jqGrid(
{
url: "sogriddata",
datatype: "json",
mtype: "GET",
colModel: [
{ name: "sales_hdr_id", label: "sales_hdr_id",hidden: true },
{ name: "sales_line_id", label: "sales_line_id",hidden: true },
{ name: "product_id", label: "product_id",hidden: true },
{ name: "product_code", label: "Product Code"},
{ name: "concatenated_product", label: "Product Name"},
{ name: "qty", label: "Qty"},
{ name: "sales_order_no", label: "Sales Order No"},
{ name: "sales_order_date", label: "Sales Order Date", editrules:{date:true},formatter: 'date', formatoptions: { newformat: format}},
{ name: "delivery_date", label: "Delivery Date", editrules:{date:true},formatter: 'date', formatoptions: { newformat: format}},
{ name: "customer_name", label: "Customer Name"},
 { name: "username", label: "Created By"}
],

         rowNum:10,
		viewrecords: true,
		footerrow: true,
		sortorder: "desc",
		userDataOnFooter: true, // use the userData parameter of the JSON response to display data on footer
		width: 780,
		height: 300,
		rownumbers: true ,
	multiselect:true,
		rowList: [10, 20, 50, 100,250,500,1000],
		pager: "#grid1",
onSelectRow: function(id){
	var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
	var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'sales_hdr_id');
    var $grid = $("#grid1"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,
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
		if(gr)
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
	$("#grid1")[0].triggerToolbar();
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
	   $("#grid1")[0].triggerToolbar();
}
			if(gr)
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
	var msg="Selected order has Different Pricelist";
		notyMsg(status,msg);

}

      }
	},


});
/*end*/
/*deepika purpose:redirect to create function*/
	$(".workorder").click(function()
{

	var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
	var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'sales_hdr_id');
	var cellValue1 = jQuery("#grid1").jqGrid ('getCell', gr, 'product_id');
    var $grid = $("#grid1"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,
	     cellvalues1 = [];
		    var $grid1 = $("#grid1"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,
	     cellvalues = [];
		 var $grid1 = $("#grid1"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,
	     cellvalues2 = [];
		var j=0;
    for (i = 0, n = selIds.length; i < n; i++) {
		
	var v=	$grid1.jqGrid("getCell", selIds[i], "sales_hdr_id");
	var v2=	$grid1.jqGrid("getCell", selIds[i], "sales_line_id");
	var v1=	$grid.jqGrid("getCell", selIds[i], "product_id");
	console.log(v);
		
		if(v!=false)
		cellvalues.push(v);
		cellvalues1.push(v1);
		cellvalues2.push(v2);
	}
	if(gr)
	{
	window.location.replace('workordercreate/'+cellvalues+'?source=SALESORDER&prdid='+cellvalues1+'&slid='+cellvalues2);
	}
	else
	{
	notyMsg('info',"Please Select Row");
	}

});
/*deepika purpose:Hide & Show column & set label for serial no*/
jQuery("#grid1").jqGrid("hideCol","cb");
	showcolumn('grid1');
	$("#grid1").jqGrid("setLabel", "rn", "S.No");
/*end*/
	/*deepika purpose: export function*/
	$(document).on('click',".exportpdf",function() {
   	$("#grid1").jqGrid('exportToPdf', {
  title: null,
  orientation: 'portrait',
  pageSize: 'A4',
  description: null,
  onBeforeExport: null,
  download: 'download',
  includeLabels : true,
  includeGroupHeader : true,
  includeFooter: true,
  fileName : "Salesorder.pdf",
  mimetype : "application/pdf"  
});
			
});

	$(document).on('click',".exportexcel",function() {	
	$("#grid1").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "Salesorder.xlsx"
    					
				});
		});

	/*end*/
jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
/*deepika purpose:to set date*/
$("#gs_sales_order_date").attr("placeholder","Eg:2018-10-01");
$("#gs_delivery_date").attr("placeholder","Eg:2018-10-01");
/*end*/
//For Default Width
$(window).bind('resize', function()
{
$("#grid1").setGridWidth($(window).width()*0.99);
}).trigger('resize');
/*Maruthu Purpose For View Function*/
        $(".view").click(function()
        {
            var index = $("#grid1").jqGrid('getGridParam','selrow');
            var sales_hdr_id = $("#grid1").jqGrid ('getCell', index, 'sales_hdr_id');
			var selRows= $('#grid1 tbody .ui-state-highlight').length;
			var pagemethod='<?php echo $pageMethod;?>';
            if( index )
            {
				if(selRows > 1)
				{
					notyMsgs('info','Please Select Row.....');
				}
				else
				{
                window.location.replace('soorderview/' +sales_hdr_id+"?return="+pagemethod);
				}
            }
            else
            {
               notyMsg('info',"Please Select Row");
            }
        });
/*end*/
	/***** Karthigaa Purpose For CLEAR search ********/
	$(".clearsearch").click(function()
	{
		var grid = $("#grid1");
		grid.jqGrid('setGridParam',{search:false});
	var postData = grid.jqGrid('getGridParam','postData');
		$.extend(postData,{filters:""});
		grid.trigger("reloadGrid",[{page:1}]);
		$('input[id*="gs_"]').val("");
	});
	/*End*/
});
</script>

@endsection
