@extends('layouts.header')
@section('content')

<div id="accordion">
<div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a role="button">
                                       
                                        Customer Types
                                    </a>
                                </h4>
                            </div>

</div>

<div class="panel panel-visible" id="spy1">
	
	<form method="post" action="{{ URL::to('showcoloumnsave') }}" id="showcoloumnpermission" data-parsley-validate>
{{ csrf_field() }}
<div class="panel-heading">

<?php include('toolbar.php'); ?>
<input type='hidden' class="type" name='type' value="salesinquiry" />

</div>
</form>


<div class="row">
<div class="col-md-12">
<hr class="xlg">
</div>
</div>

<div class="row">
	<div class="col-md-12">
	<table id="receiptbatchgrid"></table>
	</div>
</div>

<div class="row">
<div class="col-md-12">
<hr class="xlg">
</div>
</div>

</div>


	 <!--Rajalakshmi purpose show column settings-->
<div id="config_modal" class="modal fade" role="dialog">
<div class="modal-dialog">
<!-- Modal content-->
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal">&times;</button>
<h4 class="modal-title">Configuration</h4>
</div>
<div class="modal-body ">
<div id="form_body">
</div>
</div>
    <div align="center">
        <button type="button" class="btn save ok">OK</button>
    </div>
</div>
 </div>
</div>





<script type="text/javascript">
$( document ).ready(function() {
	showcolumn('receiptbatchgrid');
/* set select2*/
$(".select2").select2();
$(".select2").css('width','100%');
/*end*/
var cusopt="{{ $cusnameopt }}";
$("#receiptbatchgrid").jqGrid({
url: "getBatchData",
datatype: "json",
mtype: "GET",
	
	 colModel: [
	{ name: "s_receipt_batch_hdr_id", label: "id",hidden:true },
	{ name: "receipt_batch_name", label: "Batch Name" ,editable:true, editrules:{date:true},},
	{ name: "receipt_batch_status", label: "Batch Status",editable:true, editrules:{date:true}},
    { name: "customerid", label: "Customer Name",editable:true},
		],
         rowNum:10,
		viewrecords: true,
		footerrow: true,
		userDataOnFooter: true, // use the userData parameter of the JSON response to display data on footer
		width: 780,
		height: 300,
		rowList: [10, 20, 50, 100,250,500,1000],
		pager: "#receiptbatchgrid",
        sortorder: "desc"
      });
jQuery("#receiptbatchgrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});

/*deepika purpose: show column*/
showcolumn('receiptbatchgrid');
jQuery("#receiptbatchgrid").jqGrid('hideCol',["organization_id"]);
 $(document).on('click',".export",function() {
   	$("#receiptbatchgrid").jqGrid('exportToPdf', {
  title: null,
  orientation: 'portrait',
  pageSize: 'A4',
  description: null,
  onBeforeExport: null,
  download: 'download',
  includeLabels : true,
  includeGroupHeader : true,
  includeFooter: true,
  fileName : "Receipt Batches.pdf",
  mimetype : "application/pdf"  
});
			
$("#receiptbatchgrid").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "Receipt Batches.xlsx"
    					
				})		 
	 
	 
	
});


/*end*/
$(document).on('click','.create',function()
{
var inquirytype = $(this).val();
var url="{{ url('receiptbatchcreate') }}/0";
window.location.replace(url);
});

$("#editdata").click(function()
{
	var gr = jQuery("#receiptbatchgrid").jqGrid('getGridParam','selrow');
	var cellValue = jQuery("#receiptbatchgrid").jqGrid ('getCell', gr, 's_receipt_batch_hdr_id');
	var rcptbatchstatus = jQuery("#receiptbatchgrid").jqGrid ('getCell', gr, 'receipt_batch_status');
	if(gr)
	{
	  if(rcptbatchstatus !="INITIATED")
	  {
		var url = "{{ url('receiptbatchcreate') }}";
        var editUrl = url + '/' + cellValue;
		window.location.replace(editUrl);
	  }
		else
		{
			notyMsg('error',"<i class='fa fa-exclamation-circle' style='font-size:16px'></i> Approved or Submitted Receipt Batch Cannot Be Edit!!!");
		}
	}
	else
	{
	 notyMsg("info","Please Select Row");
	}
});

/*Karthigaa Purpose for View Function*/
$('#view').click(function(){    
  var gr=$('#receiptbatchgrid').jqGrid('getGridParam','selrow');
  var cellValue = $("#receiptbatchgrid").jqGrid ('getCell', gr, 's_receipt_batch_hdr_id');
  if(gr){
     var url="receiptbatchesview";
     var viewurl = url+'/'+cellValue+'/view';
     window.location.replace('receiptbatchesview/' +cellValue);
  }
  else
  {
  notyMsg("info","Please Select Row");
  }
});

$("#delete").click(function(){
	var gr = jQuery("#receiptbatchgrid").jqGrid('getGridParam','selrow');
	var id = jQuery("#receiptbatchgrid").jqGrid ('getCell', gr, 's_receipt_batch_hdr_id');
	if(gr){
		swal({
                title: "Are you sure?",
                text: "You want to delete!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: !1,
                //timer: 2e3,
                closeOnCancel: !1
            }, function(e) {

			if(e == true)
			{
				var url ="{{ url('receiptbatchesdelete') }}/" +id;
				$.get(url,function(data)
				{
					var data = $.trim(data);
					var red_url ="{{ url('receiptbatches') }}";
					var data = $.trim(data);
					if(data =='0')
					{
						notyMsg('success','Deleted Successfully!!!',red_url);
						setTimeout(function(){
						window.location.href=red_url;
						}, 1500);
					}
					if(data =='2')
					{
						notyMsg('error',"You Cant't delete , Receipt Batch Used in SomeWhere!!!",red_url);
					}

				});
			}
			else
			{
                            $('.apply').css('display','none');
                            swal("Cancelled");
			}
            })
		$('.apply').css('display','none');
	}
	else
	{
	notyMsg("info","Please Select Row");
	}
});

var cols = jQuery("#receiptbatchgrid").jqGrid ('getGridParam', 'colModel');
var colarr =[];
for (var columnModelIndex in cols) {
	var columnModel = cols[columnModelIndex];
	if (! columnModel.hidden) {
			colarr.push(columnModel.name);
	}
}
$('.gridcolumns').val(colarr);


/*deepika purpose:clear search the jqgrid*/
	$(".clearsearch").click(function()
{
var grid = $("#receiptbatchgrid");
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
