@extends('layouts.header')
@section('content')



                                <h4 class="heads"> Account Codes</h4>
   

<div class="panel panel-visible" id="spy1">

<div class="panel-title ">
  <div class="row">
  <div class="col-md-12">
      <!--<button id="adddata" class="btn add create adddata" >Add Account Code</button>-->
      <!--<button id="viewdata" class="btn vie viewdata" >View</button>-->
        <?php include('toolbar.php'); ?>
</div>
</div>
</div>

<div class="row">
<div class="col-md-12">
<hr class="xlg">
</div>
</div>


<div class="row">
<div class="col-md-12">
<table id="accountclassgrid"></table>
</div>
</div>


<div class="row">
<div class="col-md-12">
<hr class="xlg">
</div>
</div>

</div>




<!-- OUR CONTENT STARTS HERE -->
<script type="text/javascript">
jQuery(document).ready(function() {
     /*Karthigaa Purpose For Displaying data in JQgrid*/
    $("#accountclassgrid").jqGrid({
        url: "{{URL::to('getAccountcodesnewData')}}",
        mtype: 'GET',
        datatype: 'json',
        colModel: [
            { name: "account_class_id", align: "center", hidden: true},
//            { name: "account_codes_hdr_id", align: "center", hidden: true},
            { name: "account_class_name", align: "center", label: "Account Class Name"},
            { name: "main_account_code", align: "center", label: "Main Account Code"},
            { name: "description", align: "center", label: "Description"},
            {name: "active", align: "center", label: "Active"},

        ],
        rowNum: 10,
        rowList: [10,50,100,250,500,1000],
        sortorder: "asc",
        viewrecords: true,
        gridview: true,
        rownumbers: true,
       
        pager: "#accountclassgrid",
        autowidth: true,
        viewrecords: true,
                searching: {
                    defaultSearch: "cn"
                }
    });
    jQuery("#accountclassgrid").jqGrid('filterToolbar', {stringResult: true, searchOnEnter: false});
    $("#accountclassgrid").jqGrid("setLabel", "rn", "S.No");
    
    $(document).on('click',".exportexcel",function() {
    $("#accountclassgrid").jqGrid("exportToExcel",{
                                            includeLabels : true,
                                            includeGroupHeader : true,
                                            includeFooter: true,
                                            fileName : "Account Codes.xlsx"

                                    })		 
	 
	 
	
});  

    $(document).on('click',".exportpdf",function() {
               $("#accountclassgrid").jqGrid('exportToPdf', {
         title: null,
         orientation: 'portrait',
         pageSize: 'A4',
         description: null,
         onBeforeExport: null,
         download: 'download',
         includeLabels : true,
         includeGroupHeader : true,
         includeFooter: true,
         fileName : "Account Codes.pdf",
         mimetype : "application/pdf"  
       });
       });
    /*Karthigaa Purpose for Add Account Code*/
$("#adddata").click(function(){
	var gr = $("#accountclassgrid").jqGrid('getGridParam','selrow');
        var accid = $("#accountclassgrid").jqGrid ('getCell', gr, 'account_class_id');
//        var id = $("#accountclassgrid").jqGrid ('getCell', gr, 'account_codes_hdr_id');
//	alert(id);
	if( gr )
	{
		window.location.replace('accountcodesnewcreate/' +accid+'/'+'0');
	}
	else
	{
	  notyMsg("info","Please Select Row");
	}
});
 showcolumn('accountclassgrid'); 
/***** Karthigaa Purpose For CLEAR search ********/
	$("#clearsearch").click(function() {
		var grid = $("#accountclassgrid");
		grid.jqGrid('setGridParam',{search:false});

		var postData = grid.jqGrid('getGridParam','postData');
		$.extend(postData,{filters:""});
		grid.trigger("reloadGrid",[{page:1}]);
                 $('input[id*="gs_"]').val("");
	});
/*End*/
//$("#adddata").click(function(){
//	var gr = $("#accountclassgrid").jqGrid('getGridParam','selrow');
//        var accid = $("#accountclassgrid").jqGrid ('getCell', gr, 'account_class_id');
////        var id = $("#accountclassgrid").jqGrid ('getCell', gr, 'account_codes_hdr_id');
////	alert(id);
//	if( accid != false )
//	{
//		window.location.replace('accountcodesnewcreate/' +accid);
//	}
//	else
//	{
//	  notyMsg("info","Please Select Account Class");
//	}
//});

 /*Karthigaa Purpose for View Function*/
$("#viewdata").click(function(){
	var gr = jQuery("#accountclassgrid").jqGrid('getGridParam','selrow');
	var cellValue = jQuery("#accountclassgrid").jqGrid ('getCell', gr, 'account_class_id');
        var account_code = jQuery("#accountclassgrid").jqGrid ('getCell', gr, 'account_code');


	if( gr ){
		window.location.replace('accountcodesnewview/' +cellValue);
	}

	else
	{
	notyMsg("info","Please Select Row");
	}
});


});
</script>
<style>
.ui-jqgrid.ui-jqgrid-bootstrap {
border: 1px solid #003380;
}
.ui-jqgrid.ui-jqgrid-bootstrap .ui-jqgrid-caption {
background-color: #e6f0ff;
}
.ui-jqgrid.ui-jqgrid-bootstrap .ui-jqgrid-hdiv {
background-color: #cce0ff;
}
</style>
<!-- OUR CONTENT ENDS HERE -->
@endsection
