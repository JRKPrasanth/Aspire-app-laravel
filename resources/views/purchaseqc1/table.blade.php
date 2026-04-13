@extends('layouts.header')
@section('content')

  
  <div class="panel panel-visible" id="spy1">


<div class="panel-title ">
  <div class="row">
  <div class="col-md-12">
   <?php include('toolbar.php'); ?>
	 <?php if($pageMethod=="qcapproval"){?>
	  <button type="button" class="btn add approval" id="" value="Approval">Approval</button>
	 <?php }?>
    <a id="clearsearch"><button type="button" class="btn search">Clear Search</button></a>
    <button type='button' id="showcolumn" value="1" class='btn showcolumn vie '> Show column </button>
</div>
</div>
</div>
<div class="row">
	<div class="col-md-12" >

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

      url: "getQCData?status={{$status}}",
      datatype: "json",
      mtype: "GET",
	 colNames: ["","QC Number","Qc Date","Qc Status","Grn Number","PO Number","Supplier Name","Bill Number"],
        colModel: [
            { name: "qc_header_id",align: "center",hidden:true},
	    { name: "qc_number", align: "center" },
            { name: "qc_date", align: "center" },
            { name: "qc_status", align: "center" },
            { name: "grn_number", align: "center" },
            { name: "po_number", align: "center" },
            { name: "supplier_id", align: "center" ,stype:'select', editoptions:{value:suppliername}},
            { name: "bill_number", align: "center" }
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
         caption: "QUALITY CHECK" ,
         delOptions: { url: '/PurchaseorderController/delete' },
	searching: {
	defaultSearch: "cn"
	}
});
jQuery("#pogrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
jQuery("#gs_pogrid_supplier_id").select2();
 /*Karthigaa Purpose for Show Coloumn*/
showcolumn('pogrid');

/*Karthigaa Purpose For CREATE Function*/
$("#create").click(function(){

var url="{{ url('grntable') }}";

window.location.replace(url);

});

/*Karthigaa Purpose For Edit Function*/
$("#edit").click(function(){
        var index = $("#pogrid").jqGrid('getGridParam','selrow');
	var qc_header_id = $("#pogrid").jqGrid ('getCell', index, 'qc_header_id');
        var qc_status = $("#pogrid").jqGrid ('getCell', index, 'qc_status');

       if( qc_header_id != false ) {

 if(qc_status!="INITIATED")
         {
		window.location.replace('purchaseqcedit/'+qc_header_id);

	}
else
         {
            notyMsg('error',"<i class='fa fa-exclamation-circle' style='font-size:16px'></i>Submitted QC Cannot Be Edit!!!");
         }
     }
	else
	{
		 notyMsg("info","Please Select Row");
	}

});

    /*Karthigaa Purpose For View Function*/
       $("#view").click(function(){
	var index = $("#pogrid").jqGrid('getGridParam','selrow');
	var qc_header_id = $("#pogrid").jqGrid ('getCell', index, 'qc_header_id');
	if( qc_header_id != false )
	{
		window.location.replace('purchaseqcview/' +qc_header_id+'/23');
	}
	else
	{
			 notyMsg("info","Please Select Row");
	}
});
/*deepika purpose:approval*/	
	$('.approval').click(function(){
    var gr=$('#pogrid').jqGrid('getGridParam','selrow');
    var id = $("#pogrid").jqGrid ('getCell', gr, 'qc_header_id');
  if(id != false)
  {
      window.location.replace('qcapprovalcreate/' +id+'?approve_status=approved');
  }
  else
  {
    notyMsg("info","Please Select Row");
  }
});
    /*Karthigaa Purpose For Delete Function*/

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
