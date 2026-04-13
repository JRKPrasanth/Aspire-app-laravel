@extends('layouts.header')
@section('content')


<h2 class="heads">Job Card Filling</h2>



<div class="panel panel-visible" id="spy1">
<div class="panel-heading">
<?php include("toolbar.php");?>

<button type='button' href='' class='btn add filling'>Create Filling </button>
<a id="showcolumn"><button type="button" class="btn add showcolumn" value="1">Show Column</button></a>
<button type='button' href='' class='btn search clearsearch'>Clear Search </button>

</div>


<div class="row">
<div class="col-md-12">
<table id="grid1"></table>
</div>
</div>

</div>



<script type="text/javascript">
$( document ).ready(function() {
/* set select2*/
$(".select2").select2();
$(".select2").css('width','100%');
/*end*/
	
//alert(status);
	var uomopt="{{ $uomopt }}";
	var prdnameopt="{{ $prdnameopt }}";
	$("#grid1").jqGrid({
	url: "getfillinggriddata",
	datatype: "json",
	mtype: "GET",
		colModel: [
			{ name: "workorder_hdr_id", hidden: true, label: "Workorder Id", width: 40 },
			{ name: "workorder_line_id", hidden: true, label: "Workorder Line Id", width: 40},
			{ name: "product_sub_assembly_id", hidden: true, label: "Product Sub Assembly Id", width: 40},
			{ name: "workorder_no", label: "Workorder No", width: 40},
			{ name: "workorder_date", label: "Workorder Date"},
			{ name: "product_id", label: "Product",stype: 'select', editoptions:{value:prdnameopt}},
			{ name: "uom_code_id", label: "Uom Code",stype: 'select', editoptions:{value:uomopt}},
			{ name: "qty", label: "Workorder Qty" },
			{ name: "start_date", label: "Start Date"},
			{ name: "end_date", label: "End Date" },
		],
	        iconSet: "fontAwesome",
	        rowNum: 10,
	        rowList: [10,20,50,100,250,500,1000],
	        sortorder: "desc",
	        viewrecords: true,
	        gridview: true,
	        rownumbers:true,
	        caption: "",
		    autowidth:true,
	        pager: true,
	        multiselect:true,
	        multipageselection:true,
	        searching: {
	            defaultSearch: "cn"
	        },

        onSelectRow: function(id){
  			var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
			var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'product_sub_assembly_id');
        	var $grid = $("#grid1"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,
	        sovalue = [];
	        var checkprd='';
	        var j=0;
	        var j1=0;
	        var j2=0;
	        for (i = 0, n = selIds.length; i < n; i++)
	        {
                var check_product= $grid.jqGrid("getCell", selIds[i], "product_sub_assembly_id");
    			if(check_product!=false)
			    {
				    if(j==0)
      					checkprd=check_product;
    				if(checkprd!=check_product)
      					checkprd='0';
      				j=1;
			    }
			    else
			    {
			      j=0;
			    }
        		if(checkprd=='0')
				{
				  	var status="Error";
				  	var msg="Same Product Should be allow to Create Job Card";
    				notyMsg(status,msg);
  					$("#grid1")[0].triggerToolbar();
				}
    		}
  		}
   
  	});

	jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
	jQuery("#gs_grid1_product_id,#gs_grid1_uom_code_id").select2();
	$("#grid1").jqGrid('hideCol','cb');
	
	showcolumn('grid1');
	
	$(document).on('click','.filling',function(){

		
		var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
		var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'workorder_line_id');
		var prdsub_id = jQuery("#grid1").jqGrid ('getCell', gr, 'product_sub_assembly_id');
		var $grid = $("#grid1"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,cellvalues = [];
		var j=0;
	    for (i = 0, n = selIds.length; i < n; i++) {
			var v=	$grid.jqGrid("getCell", selIds[i], "workorder_line_id");
			console.log(v);
			if(v!=false)
				cellvalues.push(v);
		}
		if( cellvalues != false )
		{
			var url="{{ URL::to('fillingcreate') }}/"+cellvalues+"?prdsub_id="+prdsub_id;
			window.location.replace(url);	
		}else{
			notyMsg("info","Please Select Workorder");	
		}
	});

	$(document).on('click','.create',function()
	{
		var url="{{ URL::to('productionplancreate') }}/0";
		var red_url="{{ URL::to('productionplan') }}";
		window.location.replace(url);
	});

	/*deepika purpose:clear search the jqgrid*/
	$(".clearsearch").click(function()
	{
		var grid = $("#grid1");
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
