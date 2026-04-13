@extends('layouts.header')
@section('content')


<div id="accordion">
<div class="panel-heading" role="tab" id="headingOne">
    <h4 class="panel-title">
    <a role="button">Payment Batches</a>
    </h4>
</div>
</div>


<div class="panel panel-visible" id="spy1">
	<div class="row">
	<div class="col-md-12">
<?php include('toolbar.php'); ?>
<!-- <button type='button' href='' class='btn  clearsearch'>Clear Search </button>
<button type='button' id="showcolumn" value="1" class='btn showcolumn showcolumns'> Showcolumn </button> -->
</div>
</div>


<div class="row">
<div class="col-md-12">
<hr class="xlg">
</div>
</div>

<div class="row">
	<div class="col-md-12">
<table id="paymentbatchesgrid"></table>
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
$("#paymentbatchesgrid").jqGrid({
url: "getPaymentbatchesData",
datatype: "json",
mtype: "GET",
	 colModel: [
	{ name: "payment_batches_hdr_id", label: "id",hidden:true },
	{ name: "payment_batch_name", label: "Payment Batch Name" },
        { name: "batch_date", label: "Batch Date"},
	{ name: "payment_batch_status", label: "Payment Batch Status"},
        { name: "supplier_id",  align: "center",label: "Supplier Name"},

		],
       rowNum:20,
		 loadonce: true,
		viewrecords: true,
		footerrow: true,
		sortorder: "desc",
		userDataOnFooter: true, // use the userData parameter of the JSON response to display data on footer
		width: 780,
		height: 300,
		  rownumbers: true ,
		rowList: [10, 20, 50,100,250,500,1000],
		pager: "#paymentbatchesgrid"
      });
jQuery("#paymentbatchesgrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
jQuery("#gs_paymentbatchesgrid_supplier_id").select2();
showcolumn('paymentbatchesgrid');
		$("#paymentbatchesgrid").jqGrid("setLabel", "rn", "S.No");
 $(document).on('click',".export",function() {
   	$("#paymentbatchesgrid").jqGrid('exportToPdf', {
  title: null,
  orientation: 'portrait',
  pageSize: 'A4',
  description: null,
  onBeforeExport: null,
  download: 'download',
  includeLabels : true,
  includeGroupHeader : true,
  includeFooter: true,
  fileName : "Payment Batches.pdf",
  mimetype : "application/pdf"  
});
	 $("#paymentbatchesgrid").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "Payment Batches.xlsx"
    					
				})		 
	});
			
$("#edit").click(function(){
	var gr = jQuery("#paymentbatchesgrid").jqGrid('getGridParam','selrow');
	var cellValue = jQuery("#paymentbatchesgrid").jqGrid ('getCell', gr, 'payment_batches_hdr_id');
        var batchstatus = jQuery("#paymentbatchesgrid").jqGrid ('getCell', gr, 'payment_batch_status');
	if( gr )
	{
            if( batchstatus != "INITIATED" ){
		var url = "{{ url('paymentbatchescreate') }}";
                var editUrl = url + '/' + cellValue;
		window.location.replace(editUrl);
	}
            else{
               notyMsg('error',"<i class='fa fa-exclamation-circle' style='font-size:16px'></i> Submitted BATCHES Cannot Be Edit");
            }
            }
	else
	{
	notyMsg("info","Please Select a Row");
	}
});
  /*Karthigaa Purpose For cancel*/
      $("#create").click(function(){
	var url="{{  URL::to('paymentbatchescreate')}}";
	window.location.replace(url);

	});
$("#delete").click(function()
{
	var gr = jQuery("#paymentbatchesgrid").jqGrid('getGridParam','selrow');
	var id = jQuery("#paymentbatchesgrid").jqGrid ('getCell', gr, 'payment_batches_hdr_id');
	if( gr ){
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
				var url ="{{ url('paymentbatchesdelete') }}/" +id;
				$.get(url,function(data)
				{
					var data = $.trim(data);
					var red_url ="{{ url('paymentbatches') }}";
					var data = $.trim(data);
					if(data =='0')
					{
						notyMsg('success','Deleted Successfully',red_url);
						setTimeout(function(){
						window.location.href=red_url;
						}, 1500);
					}
					if(data =='2')
					{
						notyMsg('error',"You Cant't delete , Payment Batch Used in SomeWhere",red_url);
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
	notyMsg("info","Please Select a Row");
	}
});

$('#view').click(function(){
  var gr=$('#paymentbatchesgrid').jqGrid('getGridParam','selrow');
  var cellValue = $("#paymentbatchesgrid").jqGrid ('getCell', gr, 'payment_batches_hdr_id');
  if(gr){
     var url="paymentbatchesview";
     var viewurl = url+'/'+cellValue+'/view';
     window.location.replace('paymentbatchesview/' +cellValue);
  }
  else
  {
  notyMsg("info","Please Select a Row");
  }
});

/*Karthigaa purpose:clear search the jqgrid*/
	$(".clearsearch").click(function(){
            var grid = $("#paymentbatchesgrid");
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
