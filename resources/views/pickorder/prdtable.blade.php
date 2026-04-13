@extends('layouts.header')
@section('content')
<h2 class="heads">Pick Order</h2>
<div class="card">
	<div class="card-header">	
		<div class="card-body card-block">	
		    <div class="row">
		        <div class="col-md-12 text-left">
		       		<button id="pickprdorder" type="button" class=" btn tips pickprdorder pickorders" data-value="orderdata">ORDER</button>
				    <button type="button" id="showcolumn" class="tips btn btn-large showcolumn  vie dttoagel_button">Show Column</button>
					 <button type="button" class="btn download  export">Export</button>

		        </div>
		    </div>	
		    <div class="col-md-12">
		    	<table id="prodqohwise"></table>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
$( document ).ready(function() {
	$("#prodqohwise").jqGrid(
	{
		url: "sopickprodqohdata",
		datatype: "json",
		mtype: "GET",
		colModel: [
			{ name: "product_id", label: "product_id",hidden:true},
			{ name: "pricelist_id", label: "pricelist_id",hidden:true},
			{ name: "ship_to_customer_id", label: "ship_to_customer_id",hidden:true},
			{ name: "bill_to_address_id", label: "bill_to_address_id",hidden:true},
			{ name: "sales_hdr_id", label: "sales_hdr_id",hidden:true},
			{ name: "sales_order_no", label: "SO no"},
			{ name: "customer_name", label: "Customer Name"},
			{ name: "pricelist_name", label: "Pricelist Name"},
			{ name: "concatenated_product", label: "Product Name"},
			{ name: "qty", label: "Qoh",search:false},
		],
		  rowNum:10,
		viewrecords: true,
		footerrow: true,
		userDataOnFooter: true, // use the userData parameter of the JSON response to display data on footer
		width: 780,
		height: 300,
		rowList: [10, 20, 50, 100,500,1000],
		pager: "#prodqohwise",
	 rownumbers: true ,
        sortorder: "desc",
  multiselect:true,
		onSelectRow: function(id){
			var gr = jQuery("#prodqohwise").jqGrid('getGridParam','selrow');
			var cellValue = jQuery("#prodqohwise").jqGrid ('getCell', gr, 'sales_hdr_id');
			var $grid = $("#prodqohwise"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,
	    	sovalue = [];
	    	var checkcus='';
			var checkbillto='';
			var checkprice='';
			var j=0;
			var j1=0;
			var j2=0;

			for (i = 0, n = selIds.length; i < n; i++) {
				var check_customer= $grid.jqGrid("getCell", selIds[i], "ship_to_customer_id");
				var check_billto= $grid.jqGrid("getCell", selIds[i], "bill_to_address_id");
				var check_pricelist= $grid.jqGrid("getCell", selIds[i], "pricelist_id");
				if(check_customer!=false){
					if(j==0)
						checkcus=check_customer;
					if(checkcus!=check_customer)
						checkcus='0';
					j=1;
				}else{
					j=0;
				}
				if(checkcus=='0'){
					var status="Error";
					var msg="Same Customer Should be allow to Create";
					notyMsg(status,msg);
					$("#prodqohwise")[0].triggerToolbar();
				}
				if(check_billto!=false)
				{
					if(j1==0)
						checkbillto=check_billto;
					if(checkbillto!=check_billto)
						checkbillto='0';
					j1=1;
				}
				else
				{
					j1=0;
				}
				if(checkbillto=='0'){
					var status="Error";
					var msg="Same Billto address Should be allow to Create";
					notyMsg(status,msg);
					$("#prodqohwise")[0].triggerToolbar();
				}
				if(check_pricelist!=false)
				{
					if(j2==0)
						checkprice=check_pricelist;
					if(checkprice!=check_pricelist)
						checkprice='0';
					j2=1;
				}
				else
				{
					j2=0;
				}
				if(checkprice=='0'){
					var status="Error";
					var msg="Selected order has Different Pricelist";
					notyMsg(status,msg);
					$("#prodqohwise")[0].triggerToolbar();
				}
			}
		},
	});
	jQuery("#prodqohwise").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
	jQuery("#prodqohwise").jqGrid('hideCol','cb');
	$("#prodqohwise").jqGrid("setLabel", "rn", "S.No");
	showcolumn('prodqohwise');	
	 $(document).on('click',".export",function() {
   	$("#prodqohwise").jqGrid('exportToPdf', {
  title: null,
  orientation: 'portrait',
  pageSize: 'A4',
  description: null,
  onBeforeExport: null,
  download: 'download',
  includeLabels : true,
  includeGroupHeader : true,
  includeFooter: true,
  fileName : "Pick Order.pdf",
  mimetype : "application/pdf"  
});
$("#prodqohwise").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "Pick Order.xlsx"
				})	
});
	$('.pickprdorder').click(function(){
		var gr = jQuery("#prodqohwise").jqGrid('getGridParam','selrow');
		var cellValue = jQuery("#prodqohwise").jqGrid ('getCell', gr, 'sales_hdr_id');
		var $grid = $("#prodqohwise"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n, prdnos = [];
		for (i = 0, n = selIds.length; i < n; i++) {
			var product_ids= $grid.jqGrid("getCell", selIds[i], "sales_hdr_id");
			if(product_ids!=false)
				prdnos.push(product_ids);
		}
		if(gr){
			window.location.replace('pickordercreate/'+prdnos);			
		}else{
			notyMsg('info','Please select a row');
		}
	});
});
  </script>
@endsection
