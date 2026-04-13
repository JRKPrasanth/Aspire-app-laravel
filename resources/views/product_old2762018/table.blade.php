@extends('layouts.header')
@section('content')
<div class="row">
<div class="col-md-12">
<div class="panel panel-visible" id="spy1">

<div class="panel-title">
	<div class="row">
	<div class="col-md-12">
<span class=""></span>
<a href='productcreate' class='btn add create'>  Create </a>
<a id="editdata"  class="btn sec"> Edit </a>
<a id="viewdata"  class="btn vie"> View </a>
<button type='button' href='' id="deletedata" class='btn del delete'>Delete </button>
<a id="assign"  class="btn sec"> Assign company</a>
<a id="price"  class="btn sec"> Assign pricelist</a>
<a id='pricecreate' class='btn sec'>  Create pricelist</a>
<a id="showcolumn"><button type="button" id="btnviewdetails" class="btn add showcolumn btnviewdetails " data-value="showcolumn">Show Column</button></a>


</div>
</div>
</div>
<div class="row">
<div class="col-md-12">
<table id="grid1"></table>
</div>
</div>
</div>
</div>
</div>


<!-- Maruthu purpose customer search jqgrid model-->


<div class="modal fade" id="customerModal">
  <div class="modal-dialog" style="width:80%;">
    <div class="modal-content">
		<!--Moda Header-->
      <div class="modal-header">
		  <h4 class="modal-title"> Price details</h4>
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
	  </div>
		<!-- Modal Body -->
	  <div class="modal-body">
	      <table id="pricegrid"></table>
	  </div>
		 <!-- Modal footer -->
      <div class="modal-footer">
      </div>

    </div>
  </div>
</div>
<!--end-->




<div class="row">
	<button type="button" class="btn btn-info btn-lg open" data-toggle="modal" data-target="#myModal" style="display:none">Open Modal</button>
	 <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
       <h4 class="modal-title">Company Details</h4>
        </div>
        <div class="modal-body companybody">

        </div>
        <div class="modal-footer">
          <button type="button" id="company_assign" class="btn btn-default">save</button>
          <button type="button" id='cancel' class="btn btn-default" data-dismiss="modal">cancel</button>
        </div>
      </div>

    </div>
  </div>


	<style>
		.mytable {
		background: transparent !important;
		}

	</style>

</div>




<script type="text/javascript">
$( document ).ready(function() {
  	var group="{{$group}}";
  	var cat="{{$cat}}";
  	var uom="{{$uom}}";
        $("#grid1").jqGrid({

			url: "ProductgridData",

datatype: "json",
mtype: "GET",

	 colModel: [


 { name: "product_id", label: "id", width: 100,hidden:true},

{ name: "product_code", label: "product code", width: 250 ,editable:true, editrules:{date:true}},
{ name: "product_group_id", label: "product Group", width: 325,editable:true,stype:'select', editoptions:{value:group}},
 { name: "product_category_id", label: "product category", width: 325,editable:true,stype:'select', editoptions:{value:cat}},

		{ name: "product_type_id", label: "Product type", width: 250,editable:true, editrules:{date:true}},
		{ name: "product_variant_id", label: "Product variant", width: 250,editable:true, editrules:{date:true}},
		{ name: "product_pack_id", label: "Product pack", width: 250,editable:true, editrules:{date:true}},

{ name: "concatenated_product", label: "concatenated product", width: 250,editable:true, editrules:{date:true}},
//{ name: "active", label: "active", width: 250,editable:true, editrules:{date:true}},
//{ name: "organization_name", label: "organization", width: 250,editable:true, editrules:{date:true}},
{ name: "primary_uom_id", label: "primary uom", width: 250,editable:true, stype:'select', editoptions:{value:uom}},
{ name: "trx_uom_id", label: "Trx uom", width: 250,editable:true, stype:'select', editoptions:{value:uom}},
//{ name: "primary_uom_id", label: "primary uom", width: 250,editable:true, editrules:{date:true}},
//{ name: "trx_uom_id", label: "Trx uom", width: 250,editable:true, editrules:{date:true}},
//{ name: "min_order_qty", label: "minorder qty", width: 250,editable:true, editrules:{date:true}},
//{ name: "max_order_qty", label: "maxorder qty", width: 250,editable:true, editrules:{date:true}},
//{ name: "re_order_level", label: "reorder qty", width: 250,editable:true, editrules:{date:true}},
{ name: "hsn_code", label: "hsn code", width: 250,editable:true, editrules:{date:true}},
{ name: "product_alternate_name", label: "Product Alternate Name", width: 250,editable:true, editrules:{date:true}},
//{ name: "subinventory_id", label: "subinventory", width: 250,editable:true, editrules:{date:true}},
//{ name: "locator_id", label: "locator", width: 250,editable:true, editrules:{date:true}},
//{ name: "serial_control", label: "locator", width: 250,editable:true, editrules:{date:true}},
		// { name: "serial_prefix", label: "locator", width: 250,editable:true, editrules:{date:true}},



  ],
            iconSet: "fontAwesome",
        rowNum: 10,
        rowList: [10,20,50,100],
        sortorder: "desc",
        viewrecords: true,
        gridview: true,
        rownumbers:true,
		multiselect: true,
		multiPageSelection:true,
			autowidth:true,
		rownumWidth:50,
        caption: "",
         pager: true,
        searching: {
            defaultSearch: "cn"
        }
      });


			$("#gs_grid1_product_group_id").select2();
			$("#gs_grid1_product_category_id").select2();
		//$("#gs_productcatgorygrid_product_group_id").addClass('select2');
		jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
		$("#gs_grid1_product_group_id").select2();
		$("#gs_grid1_product_group_id").width(185).select2();
		$("#gs_grid1_product_category_id").select2();
		$("#gs_grid1_product_category_id").width(185).select2();


 jQuery("#grid1").jqGrid('hideCol','cb');

     jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
	jQuery("#grid1").jqGrid('hideCol',["trx_uom_id","organization_id"]);
	jQuery("#hc").click( function() {
	jQuery("#grid1").jqGrid('showCol',["trx_uom_id","organization_id"]);
});

showcolumn('grid1');

$("#editdata").click(function()
{


	var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
	var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'product_id');

	if( cellValue != false )
	{

		window.location.replace('productedit/' +cellValue);
	}
	else
	{
		notyMsg('info',"Please select a row");
	}


});





	$("#assign").click (function()

						{

		var $grid = $("#grid1"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,
    cellValues = [];
for (i = 0, n = selIds.length; i < n; i++) {
    cellValues.push($grid.jqGrid("getCell", selIds[i], "product_id"));
}
	console.log(cellValues);

		if( cellValues != false){
		var html="";
			 $.get('product/companydetails',function(data)
                {

			html+='<table class="table"> <thead><tr><th></th><th></th></tr></thead><tbody>';

         $.each(data, function( key, value ) {

           html+= '<tr class="mytable"><td><input type="checkbox" name="company_id" class="company_id" value="'+value.company_id +'"></td><td>&nbsp;</td><td>'+value.company_name+'</td></tr>';

         });
   html+='</tbody></table>';


        //	html+='<table class="table"> <thead><tr><th></th><th></th></tr></thead><tbody>'+$.each(data, function( key, value ) {
      //  +'<tr class="mytable"><td><input type="checkbox" name="company_id"></td><td>&nbsp;</td><td>'+value.company_name+'</td></tr>'+ });+' </tbody></table>';
            console.log(html);
				$('.companybody').html(html);


                 $('.open').trigger('click');


                });


		}

		else
		{

		notyMsg('info',"Please select a row");
		}

	});

	$("#price").click(function()
	{
		//alert();
		var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
		var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'product_id');
	    var $grid = $("#grid1"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,
	    groupname = [];
		var check='';
		var j=0;
	    for (i = 0, n = selIds.length; i < n; i++) {
	  		var check_group= $grid.jqGrid("getCell", selIds[i], "product_group_id");
			console.log(check_group);
			if(check_group!=false)
			{

			if(j==0)
				check=check_group;
			if(check!=check_group)
				check='1';
				j=1;
			}
			else
			{
				j=0;
			}
	    }
		if(check=='1')
		{
			notyMsg('info',"Select Only Sales Or Only Purchase Product");
			 $("#grid1")[0].triggerToolbar();
		}
		else
		{
			if(check_group=="RAW MATERIALS")
				check_group="Purchase";
			else
				check_group="Sales";

			$("#gs_pricegrid_price_list_type").val(check_group);

	 		$("#pricegrid")[0].triggerToolbar();
			if( cellValue != false )
			{
				$('#customerModal').modal('show');
			 	$('#customerModal').width("100%");
			}
			else
			{
				notyMsg('info',"Please select a row");
			}
		}

	});


	var mygrid = $("#pricegrid"),
    pagerSelector = "#pager",
    myAddButton = function(options) {
        mygrid.jqGrid('navButtonAdd',pagerSelector,options);
        mygrid.jqGrid('navButtonAdd','#'+mygrid[0].id+"_toppager",options);
    };
	mygrid.jqGrid({
		url: "{{ URL::to('getPurchasepricelistData/null') }}",
		datatype: "json",
		mtype: "GET",
		height: 320,
		width: 1000,
		colModel: [
			{ name: "pricelist_hdr_id", label: "id",hidden:true, width:55},
		    { name: "pricelist_name", label: "Pricelist Name", width:55},
				{ name: "price_list_type", label: "Pricelist Type", width:55},
	 	    { name: "start_date", label: "Statr Date", width:55},
	        { name: "end_date", label: "End Date", width:55},

	    ],

		iconSet: "fontAwesome",
		rowNum: 10,
		rowList: [10,20,100,1000],
		sortorder: "asc",
		viewrecords: true,
		gridview: true,
		rownumbers:true,
		caption: "Price",
		pager: pagerSelector,
		toppager:true,
		searching: {
		defaultSearch: "cn"
		}
   	});

	jQuery(mygrid).jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
	mygrid.jqGrid('navGrid',pagerSelector,
	{cloneToTop:true,edit:false,add:false,del:false,search:true});
	myAddButton ({
		caption:"Select Price",
		title:"Price",
		buttonicon :'ui-icon-plus',
		onClickButton:function()
		{

			var gr = jQuery(mygrid).jqGrid('getGridParam','selrow');
			var price = jQuery(mygrid).jqGrid ('getCell', gr, 'pricelist_hdr_id');
			var type = jQuery(mygrid).jqGrid ('getCell', gr, 'price_list_type');
			if(type=="Sales")
			{
				var url = "salespricelistedit";
			}
			else
			{
				var url = "purchasepricelistedit";
			}

			var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
			var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'product_id');
		    var $grid = $("#grid1"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,
	     	cellvalues = [];
			var check='';
			var j=0;
    		for (i = 0, n = selIds.length; i < n; i++) {
				var v=	$grid.jqGrid("getCell", selIds[i], "product_id");
				if(v!=false)
				cellvalues.push(v);
			}
    		console.log(v);

			if( price != false )
			{
				var a=1;
				var editUrl = url + '/' + price+'?products='+cellvalues+'&sr='+a;
				window.location.replace(editUrl);
				$('#customerModal').modal('hide');
			}
			else
			{
				notyMsg('info',"Please select a row");
			}
		}
});


	$("#pricecreate").click(function()
    {

		var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
		var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'product_id');
	    var $grid = $("#grid1"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,
	    groupname = [];
		var check='';
		var j=0;
    	for (i = 0, n = selIds.length; i < n; i++) {
 			var check_group= $grid.jqGrid("getCell", selIds[i], "product_group_id");

			if(check_group!=false)
			{

				if(j==0)
					check=check_group;
				if(check!=check_group)
					check='1';
					j=1;
			}
			else
			{
				j=0;
			}
       	}
		if(check=='1')
		{
			notyMsg('info',"Select Only sales Or Only Purchase product");
			 $("#grid1")[0].triggerToolbar();
		}
		else
		{
			if(check_group=="RAW MATERIALS")
				check_group="purchasepricelistcreate";
			else
				check_group="salespricelistcreate";

			var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
			var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'product_id');
		    var $grid = $("#grid1"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,
	     	cellvalues = [];
			var check='';
			var j=0;
    		for (i = 0, n = selIds.length; i < n; i++) {
				var v=	$grid.jqGrid("getCell", selIds[i], "product_id");
				if(v!=false)
				cellvalues.push(v);
			}

			if( cellvalues != false )
			{
				var a=1;
				window.location.replace(check_group+'?products='+cellvalues+'&sr='+a);
			}
			else
			{
				notyMsg('info',"Please select a row");
			}
		}


		});






$('#viewdata').click(function(){
//alert('hhhh');
var gr=$('#grid1').jqGrid('getGridParam','selrow');
var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'product_id');
//alert(cellValue);
if(cellValue != false)
{

window.location.replace('productview/' +cellValue);
}
else
{
notyMsg('info',"Please Select a Row");
}
});

/*$("#deletedata").click(function()
{ //alert("g");
	var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
	var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'product_id');

	if( cellValue != false )
	{
		var url = "customersdelete";
  //  alert(cellValue);
        var editUrl = url + '/' + cellValue + '/delete';
		window.location.replace('productdelete/' +cellValue);
	}
	else
	{
	alert("Please Select Row");
	}
}); */

		$("#deletedata").click(function(){
		  var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
	      var id = jQuery("#grid1").jqGrid ('getCell', gr, 'product_id');
			if(id != false )
			{
			   swal({
				    title: "Are you sure?",
					text: "You want to delete!",
					type: "warning",
					 showCancelButton: !0,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Yes",
					cancelButtonText: "No"
				  },function(e)
					{
			         if(e == true)
			              {
						     var url ="{{ URL::to('productdelete') }}/" +id;
							   $.get(url,function(data)
							   {
								   var data = $.trim(data);
								    var red_url ="{{ URL::to('product') }}";
								    var data = $.trim(data);
								   if(data =='0')
								  {
									notyMsg('success','Deleted Successfully!!!',red_url);
									setTimeout(function(){
									$("#grid1")[0].triggerToolbar();
									}, 1500);
								  }
								   if(data =='1')
								  {
									  notyMsg('error',"You Cant't delete , Enquiry Used in SomeWhere!!!",red_url);
									  setTimeout(function(){
									$("#grid1")[0].triggerToolbar();
									}, 1500);
								  }
							   });
						  }
				          else
						  {
						     $('.apply').css('display','none');
                             swal("Cancelled");
						  }
			     });
				$('.apply').css('display','none');
			}
			else
			  {
				 notyMsg('info',"Please Select a Row");
			  }
		});

  $("#company_assign").click(function()
     {

	       		var $grid = $("#grid1"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,
    cellValues = [];
for (i = 0, n = selIds.length; i < n; i++) {
    cellValues.push($grid.jqGrid("getCell", selIds[i], "product_id"));
}

	   var company = [];
            $.each($("input[name='company_id']:checked"), function(){
                company.push($(this).val());
            });

     $.get('product/companyassign/'+cellValues+'/'+company,function(data)
                {
       if(data==1){

         	$('#cancel').trigger('click');
         	notyMsg("success","Company assigned Successfully");

       } else{
       		notyMsg("error","Company not assigned");
       }

     });
  });



/*********** CLEAR search **************************/

	$("#clearsearch").click(function() {
		var grid = $("#grid1");
		grid.jqGrid('setGridParam',{search:false});

		var postData = grid.jqGrid('getGridParam','postData');
		$.extend(postData,{filters:""});
		grid.trigger("reloadGrid",[{page:1}]);
	});


});
    </script>
@endsection
