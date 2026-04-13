@extends('layouts.header')
@section('content')

  <div class="panel panel-visible" id="spy1">


<div class="panel-title ">
	<div class="row">
  <div class="col-md-12" >

<a> <button type="button" class="btn add create_qc" value="STANDARD">Quality Check</button></a>
<a class='btn cancel' onclick="location.href = '{{url('purchaseqc')}}'">Cancel</a>
<a id="clearsearch"><button type="button" class="btn search">Clear Search</button></a>
<button type='button' id="showcolumn" value="1" class='btn showcolumn vie '> Show column </button>
</div>
</div>
</div>
 <div class="row">
  <div class="col-md-12" style="padding:15px;">

    <table id="pogrid"></table>
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
$("#pogrid").jqGrid({
      url: "GrnData",
      datatype: "json",
      mtype: "GET",
	 colNames: ["","GRN Number","GRN Status","PO Number","PO Date","Supplier Name","Bill Number"],
        colModel: [
            { name: "grn_id",align: "center",hidden:true},
            { name: "grn_number",align: "center"},
            { name: "grn_status",align: "center"},
            { name: "po_number", align: "center" },
            { name: "po_date", align: "center" },
            { name: "supplier_id", align: "center" ,stype:'select', editoptions:{value:suppliername}},
            { name: "bill_number",align: "center" },
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
         caption: "Goods Reciept Note" ,
//         delOptions: { url: '/PurchaseorderController/delete' },
	searching: {
	defaultSearch: "cn"
	}
});
jQuery("#pogrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
jQuery("#gs_pogrid_supplier_id").select2();
 /*Karthigaa Purpose for Show Coloumn*/
showcolumn('pogrid');

/*Karthigaa Purpose For CREATE Function*/
$(document).on('click','.create_qc',function(){
 var index = $("#pogrid").jqGrid('getGridParam','selrow');
var grn_id = $("#pogrid").jqGrid ('getCell', index, 'grn_id');
 if( grn_id != false )
		 {
var url="{{ URL::to('qualitychecking') }}/"+grn_id;

window.location.replace(url);
  }
                else
		{
		  notyMsg("info","Please Select Row");
		}
});


  /*Karthigaa Purpose For cancel*/
      $("#cancel").click(function(){ 
	var url="{{  URL::to('purchaseqc')}}";
	window.location.replace(url);
	
	});  

    /*Karthigaa Purpose For View Function*/
       $("#view").click(function(){
	var index = $("#pogrid").jqGrid('getGridParam','selrow');
	var pohdrid = $("#pogrid").jqGrid ('getCell', index, 'po_hdr_id');
	if( pohdrid != false )
	{
		window.location.replace('purchaseorderview/' +pohdrid);
	}
	else
	{
			 notyMsg("info","Please Select Row");
	}
});
    /*Karthigaa Purpose For Delete Function*/
       $("#delete").click(function(){
		var gr = jQuery("#pogrid").jqGrid('getGridParam','selrow');
		var pohdrid = jQuery("#pogrid").jqGrid ('getCell', gr, 'po_hdr_id');
		if( pohdrid != false ){
		window.location.replace('purchaseorderdelete/' +pohdrid);
		}
		else{
		 notyMsg("info","Please Select Row");
		}
	});
/***** Delete Row ********/
/***** Karthigaa Purpose For CLEAR search ********/
	$("#clearsearch").click(function() {
		var grid = $("#pogrid");
		grid.jqGrid('setGridParam',{search:false});

		var postData = grid.jqGrid('getGridParam','postData');
		$.extend(postData,{filters:""});
		grid.trigger("reloadGrid",[{page:1}]);
	});
/*End*/
});
    </script>
@endsection
