@extends('layouts.header')
@section('content')


<div id="accordion">
<div class="panel-heading" role="tab" id="headingOne">
    <h4 class="panel-title">
    <a role="button">Group Access</a>
    </h4>
</div>
</div>



  <div class="panel panel-visible" id="spy1">


  <div class="row">
   <div class="col-md-12" >
<?php include('toolbar.php'); ?>
<a id="clearsearch"><button type="button" class="btn clearsearch">Clear Search</button></a>
<button type='button' id="showcolumn" value="1" class='btn showcolumn showcolumns'> Show column </button>
</div>
</div>


<div class="row">
<div class="col-md-12">
<hr class="xlg">
</div>
</div>

 <div class="row">
  <div class="col-md-12" >

    <table id="debitgrid" ></table>
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
        initDateEdit = function (elem) {
				$(elem).datepicker({
					dateFormat: "yy-M-dd",
//                                        dateFormat: "yy-mm-dd",
					autoSize: true,
					changeYear: true,
					changeMonth: true,
					showButtonPanel: true,
					showWeek: true
				});
			},
                        initDateSearch = function (elem) {
				setTimeout(function () {
					initDateEdit(elem);
				}, 100);
			};
				var suppliername = "{{$suppliername}}";
                                var ponumber = "{{$ponumber}}";
                                var invoiceno = "{{$invoiceno}}";
$("#debitgrid").jqGrid({

      url: "getaccountdebitData",
      datatype: "json",
      mtype: "GET",
	 colNames: [""," Debit Number","Debit Date","Debit Status","PO Number","Supplier Name","Invoice Number"],
        colModel: [
            { name: "debitnote_hdr_id",align: "center",hidden:true},
            { name: "debit_number",align: "center"},
            { name: "debit_date",align: "center"},
            { name: "debit_status",align: "center"},
            { name: "po_number", align: "center" ,stype:'select', editoptions:{value:ponumber}},
            { name: "supplier_id", align: "center" ,stype:'select', editoptions:{value:suppliername}},
            { name: "invoice_number", align: "center",stype:'select', editoptions:{value:invoiceno}}
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
         caption: "DEBIT NOTE" ,
        searching: {
	defaultSearch: "cn"
	}
});
jQuery("#debitgrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
jQuery("#gs_debitgrid_supplier_id").select2();
jQuery("#gs_debitgrid_po_number").select2();
jQuery("#gs_debitgrid_invoice_number").select2();
showcolumn('debitgrid');

/*Karthigaa Purpose For CREATE Function*/
    $("#create").click(function(){
        var url="{{ url('poinvoicetable') }}";
        window.location.replace(url);
    });

/*Karthigaa Purpose For Edit Function*/
    $("#edit").click(function(){
            var index = $("#debitgrid").jqGrid('getGridParam','selrow');
            var id = $("#debitgrid").jqGrid ('getCell', index, 'debitnote_hdr_id');
            var debit_status = $("#debitgrid").jqGrid ('getCell', index, 'debit_status');
           if( id != false ) {
               if(debit_status!= 'INITIATED'){
                    window.location.replace('accountdebitnoteedit/'+id);
                }else{
                     notyMsg("info","Unable to edit Debit Note");
                }
            }
            else
            {
                     notyMsg("info","Please Select Row");
            }
    });

    /*Karthigaa Purpose For View Function*/
       $("#view").click(function(){
	var index = $("#debitgrid").jqGrid('getGridParam','selrow');
	var id = $("#debitgrid").jqGrid ('getCell', index, 'debitnote_hdr_id');
	if( id != false )
	{
		window.location.replace('accountdebitnoteview/' +id);
	}
	else
	{
			 notyMsg("info","Please Select Row");
	}
    });

/***** Karthigaa Purpose For CLEAR search ********/
	$("#clearsearch").click(function() {
		var grid = $("#debitgrid");
		grid.jqGrid('setGridParam',{search:false});

		var postData = grid.jqGrid('getGridParam','postData');
		$.extend(postData,{filters:""});
		grid.trigger("reloadGrid",[{page:1}]);
	});
/*End*/
});
    </script>
@endsection
