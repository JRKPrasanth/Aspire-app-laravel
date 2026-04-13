@extends('layouts.header')
@section('content')
<div id="accordion">
<div class="panel-heading" role="tab" id="headingOne">
    <h4 class="panel-title">
    <a role="button">Receipt For Invoice</a>
    </h4>
</div>
</div>
  <div class="panel panel-visible" id="sreceiptinvpy1">
  <div class="row">
  <div class="col-md-12">
 <?php //include('toolbar.php'); ?>
 <button type='button' id="mreceiptinv"  class='btn mreceiptinv vie'> Create Receipt </button>
 <!--<a id="clearsearch"><button type="button" class="btn clearsearch">Clear Search</button></a>-->
<!--<button type='button' id="showcolumn" value="1" class='btn showcolumn showcolumns'> Show column </button>-->
	  		

</div>
</div>

<div class="row">
<div class="col-md-12">
<hr class="xlg">
</div>
</div>

<div class="row">
	<div class="col-md-12" >

    <table id="invoicegrid"></table>
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
var date_format="{{\Session::get('p_date_format')}}";
$("#invoicegrid").jqGrid({
      url: "getsfiData",
      datatype: "json",
      mtype: "GET",
       colModel: [
            { name: "invoice_hdr_id",  align: "center",hidden:true},
            { name: "ship_to_customer_id",  align: "center",hidden:true},
            { name: "invoice_number", label:"Invoice Number" ,align: "center" },
	        { name: "invoice_date", label:"Invoice Date" ,align: "center" ,editable:true, editrules:{date:true},formatter: 'date', formatoptions: { srcformat: 'Y-m-d', newformat: date_format}},
            { name: "invoice_type", label:"Invoice Type" ,align: "center" },
            { name: "invoice_grand_total", label:"Invoice Amount" ,align: "center" },
			{ name: "balance_amount", label:"Balance Amount" ,align: "center"},
            { name: "customer_id",  align: "center",hidden:true},
            { name: "sales_hdr_id",  align: "center",hidden:true},
            { name: "customer_name", label: "Customer Name"},
            { name: "invoice_status", label:"SO Invoice Status",align: "center" },
            { name: "remarks", label:"Remarks",align: "center" },

            ],
	iconSet: "fontAwesome",
	rowNum: 10,
	rowList: [10,20,50,100,250,500,1000],
	sortorder: "desc",
	viewrecords: true,
	gridview: true,
	rownumbers:true,
	rownumWidth:50,
	autowidth:true,
	caption: "" ,
	pager: "#invoicegrid",
	multiselect:true,
	multiPageSelection:true,


onSelectRow: function(id){
	var gr = jQuery("#invoicegrid").jqGrid('getGridParam','selrow');
	var cellValue = jQuery("#invoicegrid").jqGrid ('getCell', gr, 'invoice_hdr_id');

        var $grid = $("#invoicegrid"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,
	    sovalue = [];
		var checkcus='';
		var j=0;
		
    for (i = 0, n = selIds.length; i < n; i++) {
  var check_customer= $grid.jqGrid("getCell", selIds[i], "ship_to_customer_id");
  //alert(check_customer);
  	console.log(check_customer);
		if(check_customer!=false)
		{

		if(j==0)
			checkcus=check_customer;
		//alert(checkcus);
		if(checkcus!=check_customer)
			//alert(checkcus);
			checkcus='s';
			j=1;

		}
		else
		{
			j=0;
			//alert(checkcus);
		}
		if(checkcus=='s1')
{
	var status="Error";
	var msg="Can't Create Receipt For Different Customer";
		notyMsg(status,msg);
	$("#invoicegrid")[0].triggerToolbar();
}
      }
	},
});

jQuery("#invoicegrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
jQuery("#gs_invoicegrid_ship_to_customer_id").select2();
jQuery("#invoicegrid").jqGrid('hideCol','cb');
$("#invoicegrid").jqGrid("setLabel", "rn", "S.No");
 /*Karthigaa Purpose for Show Coloumn*/
showcolumn('invoicegrid');
$("#gs_invoice_date").attr("placeholder","Eg:2018-10-01");
 
 /*Karthigaa purpose for Multiple row Receipt*/
$("#mreceiptinv").click(function(){
	var gr = jQuery("#invoicegrid").jqGrid('getGridParam','selrow');
    var $grid = $("#invoicegrid"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,cellvalues = [];
      for (i = 0, n = selIds.length; i < n; i++) {
	var v=	$grid.jqGrid("getCell", selIds[i], "invoice_hdr_id");
		if(v!=false)
		cellvalues.push(v);
	}
        var $grid = $("#invoicegrid"), selIds = $grid.jqGrid("getGridParam", "selarrrow"), i, n,cellvaluess = [];
      for (i = 0, n = selIds.length; i < n; i++) {
	var v=	$grid.jqGrid("getCell", selIds[i], "sales_hdr_id");
		if(v!=false)
		cellvaluess.push(v);
	}
       if(cellvalues != false){
         window.location.replace('mreceiptforinvoicecrt/'+cellvalues);
          }
	else
	{
	notyMsg("info","Please Select Row");
	}
});


 $(document).on('click',".exportpdf",function() {
   	$("#invoicegrid").jqGrid('exportToPdf', {
  title: null,
  orientation: 'portrait',
  pageSize: 'A4',
  description: null,
  onBeforeExport: null,
  download: 'download',
  includeLabels : true,
  includeGroupHeader : true,
  includeFooter: true,
  fileName : "Receipt For Invoice.pdf",
  mimetype : "application/pdf"  
});
	 });
	 $(document).on('click',".exportexcel",function() {
			
$("#invoicegrid").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "Receipt For Invoice.xlsx"
    					
				})	
	
});

/***** Karthigaa Purpose For CLEAR search ********/
	$("#clearsearch").click(function() {
		var grid = $("#invoicegrid");
		grid.jqGrid('setGridParam',{search:false});

		var postData = grid.jqGrid('getGridParam','postData');
		$.extend(postData,{filters:""});
		grid.trigger("reloadGrid",[{page:1}]);
                $('input[id*="gs_"]').val("");
	});
/*End*/
});



	    $(".view").click(function()
            {
                var gr = jQuery("#invoicegrid").jqGrid('getGridParam','selrow');
                var cellValue = jQuery("#invoicegrid").jqGrid ('getCell', gr, 'invoice_hdr_id');
                var return1 = "{{$pageMethod}}";
                // alert(return1);
                if(gr)
                {
                    var url = "salesinvoiceview";
                    var editUrl = url + '/' + cellValue + '/show';
                    window.location.replace('salesinvoiceview/' +cellValue+'?return='+return1);
                }
                else
                {
                    notyMsg("info","Please Select Row");
                }
            });

    </script>
@endsection
