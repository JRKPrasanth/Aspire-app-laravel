@extends('layouts.header')
@section('content')

<h2 class="heads">Employee Document Check And Print</h2>

<div class="panel panel-visible" id="spy1">

<div class="panel-title ">
	<div class="row">
            <div class="col-md-12">
             <?php include('toolbar.php'); ?>
              <!--  <button class="btn sec download" id="" disabled>Download and Print</button> -->
            </div>
        </div>
</div>
<div class="row">
<div class="col-md-12" style="padding: 15px;">
<!-- OUR CONTENT STARTS HERE -->

<table id="grid1"></table>

<!-- OUR CONTENT ENDS HERE -->


</div>
</div>
</div>
<script type="text/javascript">
$(document).ready(function()
{
	$('.document_check').attr('disabled',true);
$("#grid1").jqGrid(
{
url: "employeedocumentgriddata",
datatype: "json",
mtype: "GET",
colModel: [
{ name: "id", label: "id", width: 250, hidden: true },
{ name: "employee_number", label: "Employee Number", width: 250},
{ name: "first_name", label: "Employee Name", width: 250},
{ name: "work_telephone_number", label: "Contact Number", width: 250},
{ name: "email", label: "E-mail", width: 250},
 
],

iconSet: "fontAwesome",
rowNum: 10,
rowList: [10,20,50,100,1000,2000],
sortname:"id",
sortorder: "desc",
viewrecords: true,
gridview: true,
rownumbers:true,
pager: "#grid1",
multiselect:false,
multipageselection:true,
searching: {
defaultSearch: "cn",
},
});



        jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
        
        $('#grid1').on('click', function (event) 
        {
            var index = $("#grid1").jqGrid('getGridParam','selrow');
            var id = $("#grid1").jqGrid ('getCell', index, 'id');
            $('.document_check,.download').attr('id',id);
            $('.document_check,.download').prop('disabled',false);
        });
        
        $(document).on('click','.document_check',function()
        {
            var id = $('.document_check').attr('id');
            var document_check="{{ URL::to('documentcreate') }}/"+id;
             window.location.href=document_check;
        });
        
	 $(document).on('click',".exportpdf",function() {
   	$("#grid1").jqGrid('exportToPdf', {
  title: null,
  orientation: 'portrait',
  pageSize: 'A4',
  description: null,
  onBeforeExport: null,
  download: 'download',
  includeLabels : true,
  includeGroupHeader : true,
  includeFooter: true,
  fileName : "Employee Document Check.pdf",
  mimetype : "application/pdf"  
});
	 });
	 $(document).on('click',".exportexcel",function() {
$("#grid1").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "Employee Document Check.xlsx"
    					
				})	
});
	
	$(".view").click(function()
        {
            var index = $("#grid1").jqGrid('getGridParam','selrow');
            var sales_hdr_id = $("#grid1").jqGrid ('getCell', index, 'sales_hdr_id');
            var selRows= $('#grid1 tbody .ui-state-highlight').length;
            if( sales_hdr_id != false )
            {
                    if(selRows > 1)
                    {
                            notyMsgs('info','Please Select One Row.....');
                    }
                    else
                    {
                            window.location.replace('soorderview/' +sales_hdr_id);
                    }
            }
            else
            {
                alert("Please Select Row");
            }
        });
	$(".clearsearch").click(function()
	{	

	    var grid = $("#grid1");
	    grid.jqGrid('setGridParam',{search:false});

	    var postData = grid.jqGrid('getGridParam','postData');
	    $.extend(postData,{filters:""});
	    grid.trigger("reloadGrid",[{page:1}]);
	    $('input[id*="gs_"]').val("");
	   
	});
});
</script>

@endsection
