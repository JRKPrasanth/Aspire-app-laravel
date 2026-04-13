@extends('layouts.header')
@section('content')




<div class="panel panel-visible" id="spy1">

<div class="panel-title ">
  <div class="row">
  <div class="col-md-12">
        <?php include('toolbar.php'); ?>
</div>
</div>
</div>
<div class="row">
<div class="col-md-12">
<table id="accountclassgrid"></table>
</div>
</div>

</div>




<!-- OUR CONTENT STARTS HERE -->
<script type="text/javascript">
jQuery(document).ready(function() {
     /*Karthigaa Purpose For Displaying data in JQgrid*/
    $("#accountclassgrid").jqGrid({
        url: "{{URL::to('getAccountcodesData')}}",
        mtype: 'GET',
        datatype: 'json',
        colModel: [
            { name: "account_class_id", align: "center", hidden: true},
            { name: "account_codes_hdr_id", align: "center", hidden: true},
            { name: "account_class_name", align: "center", label: "Account Class Name"},
            { name: "main_account_code", align: "center", label: "Main Account Code"},
            { name: "description", align: "center", label: "Description"},
            {name: "code_startwith", align: "center", label: "Code Start With"},

        ],
        rowNum: 10,
        rowList: [10, 20, 50, 100],
        sortorder: "asc",
        viewrecords: true,
        gridview: true,
        rownumbers: true,
        caption: "",
        pager: true,
        autowidth: true,
        viewrecords: true,
                searching: {
                    defaultSearch: "cn"
                }
    });
    jQuery("#accountclassgrid").jqGrid('filterToolbar', {stringResult: true, searchOnEnter: false});
    /*Karthigaa Purpose for Add Account Code*/
$("#adddata").click(function(){
	var gr = $("#accountclassgrid").jqGrid('getGridParam','selrow');
	var cellValue = $("#accountclassgrid").jqGrid ('getCell', gr, 'account_class_id');
        var accid = $("#accountclassgrid").jqGrid ('getCell', gr, 'account_codes_hdr_id');

	if( cellValue != false )
	{
		window.location.replace('accountcodescreate/' +cellValue+'/'+accid);
	}
	else
	{
	  notyMsg("info","Please Select Account Class");
	}
});
 /*Karthigaa Purpose for Edit Function*/
$("#editdata").click(function()
{
	var gr = jQuery("#accountclassgrid").jqGrid('getGridParam','selrow');
	var cellValue = jQuery("#accountclassgrid").jqGrid ('getCell', gr, 'account_class_id');
	if( cellValue != false )
	{
		window.location.replace('accountcodesedit/' +cellValue);
	}
	else
	{
	notyMsg("info","Please Select Row");
	}
});
 /*Karthigaa Purpose for View Function*/
$("#viewdata").click(function(){
	var gr = jQuery("#accountclassgrid").jqGrid('getGridParam','selrow');
	var cellValue = jQuery("#accountclassgrid").jqGrid ('getCell', gr, 'account_class_id');
        var account_code = jQuery("#accountclassgrid").jqGrid ('getCell', gr, 'account_code');


	if( cellValue != false ){
		window.location.replace('accountcodesview/' +cellValue);
	}
//         else if (account_code!= false){
//            notyMsg("error","Account Code not Created");
//        }
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
