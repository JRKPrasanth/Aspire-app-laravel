@extends('layouts.header')
@section('content')

<h2 class="heads">GRN Table</h2>
  
  <div class="panel panel-visible" id="spy1">


<div class="panel-title ">
  <div class="row">
  <div class="col-md-12" >
<a> <button type="button" class="btn add create_dc" >CREATE</button></a>
<a class='btn cancel' onclick="location.href = '{{url('purchasedc')}}'">Cancel</a>
<a id="clearsearch"><button type="button" class="btn clearsearch">Clear Search</button></a>
<button type='button' id="showcolumn" value="1" class='btn showcolumn vie '> Show column </button>
</div>
</div>
</div>
  <div class="row">
  <div class="col-md-12" >

    <table id="grngrid"></table>
  </div>
  </div>


  </div>

 



<script type="text/javascript">
$( document ).ready(function() {
        var data="{{$result}}";
	var data=JSON.parse(data.replace(/&quot;/g,'"'));
  $("#grngrid").jqGrid({
        datatype: "local",
//      url: "grnData",
//      datatype: "json",
//      mtype: "GET",
	 colNames: ["","QC Number","Supplier Name","GRN Number","DC Number"],
        colModel: [
            { name: "qc_header_id",align: "center",hidden:true},
	    { name: "qc_number",align: "center"},
            { name: "supplier_name", align: "center" },
            { name: "grn_number",align: "center"},
            { name: "dc_number",align: "center" },
             ],
	iconSet: "fontAwesome",
	rownumbers: true,
	sortorder: "desc",
        threeStateSort: true,
        data:data,
	sortIconsBeforeText: true,
	headertitles: true,
        pager: "#grngrid",
	rowList: [10,20,50,100,250,500,1000],
	rowNum: 10,
	viewrecords: true,
         caption: "" ,
        
});
jQuery("#grngrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
$("#grngrid").jqGrid("setLabel", "rn", "S.No");
jQuery("#gs_grngrid_supplier_id").select2();
showcolumn('grngrid');
/*Karthigaa Purpose For Create Function*/
$(".create_dc").click(function(){
     var index = $("#grngrid").jqGrid('getGridParam','selrow');
     var qc_header_id = $("#grngrid").jqGrid ('getCell', index, 'qc_header_id');

       if( index ){
		window.location.replace('purchasedccreate/' +qc_header_id);
	}
	else
	{
		 notyMsg("info","Please Select a Row");
	}

});
  /*Karthigaa Purpose For cancel*/
      $("#cancel").click(function(){
	var url="{{  URL::to('purchasedc')}}";
	window.location.replace(url);

	});
  

/***** Karthigaa Purpose For CLEAR search ********/
	$("#clearsearch").click(function() {
		var grid = $("#grngrid");
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
