@extends('layouts.header')
@section('content')



<div id="accordion">
<div class="panel-heading" role="tab" id="headingOne">
    <h4 class="panel-title">
    <a role="button">Payments</a>
    </h4>
</div>
</div>



<div class="panel panel-visible" id="spy1">
	<div class="row">
	<div class="col-md-12">
<?php include('toolbar.php'); ?>

</div>
</div>


<div class="row">
<div class="col-md-12">
<hr class="xlg">
</div>
</div>

<div class="row">
<div class="col-md-12">
<table id="paymentsgrid"></table>
</div>
</div>

<div class="row">
<div class="col-md-12">
<hr class="xlg">
</div>
</div>
    
    <div class="modal fade" id="myLRModal">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">LR Number Update</h4>
        </div>
        <div class="modal-body">
			<div><span style="float:left"><b>Invoice Number:</b><span class="inv_no"></span></span><span style="float:right"><b>Invoice Date: </b><span class="inv_date"></span></span><br></div><br>
        <div class="col-md-12"> 
			<div class="col-md-6">
				LR No.:<input type="text" class="form-control lr_number" value=""></div>
     <div class="col-md-6">    LR Date.:<input type="text" class="form-control datepicker lr_date" value="">    <input type="hidden" class="form-control inv_id">
			</div>
			</div>
			<div class="col-md-12">  <div class="col-md-6"> LR Status: <select type="text" name="lr_status" id="lr_status" value="" class="select2 lr_status" >
                            
                            </select>
        </div>
        </div>
        </div>
        <div class="modal-footer" align="center">
          <button type="button" class="btn save lr_save" id="updateClose" >Update</button>
          <button type="button" class="btn cancel" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>


</div>



<script type="text/javascript">
$( document ).ready(function() {

$("#paymentsgrid").jqGrid({
url: "getPaymentsData",
datatype: "json",
mtype: "GET",
	 colModel: [
	{ name: "payment_hdr_id", label: "id",hidden:true },
	{ name: "payment_number", label: "Payment Number" },
        { name: "payment_date", label: "Payment Date"},
        { name: "payment_batches_hdr_id",  align: "center",label: "Batch Name"},
        { name: "payment_status", label: "Payment Status"},
        { name: "batch_total_amount", label: "Batch Total Amount"},
       ],
       rowNum:10,
		 		viewrecords: true,
		footerrow: true,
		sortorder: "desc",
		userDataOnFooter: true, // use the userData parameter of the JSON response to display data on footer
		width: 780,
		height: 300,
		  rownumbers: true ,
		rowList: [10, 20, 50,100,250,500,1000],
		pager: "#paymentsgrid"
      });
jQuery("#paymentsgrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
jQuery("#gs_paymentsgrid_payment_batches_hdr_id").select2();
showcolumn('paymentsgrid');
		$("#paymentsgrid").jqGrid("setLabel", "rn", "S.No");
 $(document).on('click',".export",function() {
   	$("#paymentsgrid").jqGrid('exportToPdf', {
  title: null,
  orientation: 'portrait',
  pageSize: 'A4',
  description: null,
  onBeforeExport: null,
  download: 'download',
  includeLabels : true,
  includeGroupHeader : true,
  includeFooter: true,
  fileName : "Payments.pdf",
  mimetype : "application/pdf"  
});
			
$("#paymentsgrid").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "Payments.xlsx"
    					
				})		 
	});
	
  /*Karthigaa Purpose For cancel*/
      $("#create").click(function(){
	var url="{{  URL::to('paymentscreate')}}";
	window.location.replace(url);
	});
/* LR No. Update */
 $("#payrefupdate").click(function()
            {
                var gr = jQuery("#salesinvoicegrid").jqGrid('getGridParam','selrow');
                var invoice_hdr_id = jQuery("#salesinvoicegrid").jqGrid ('getCell', gr, 'invoice_hdr_id');
                var invoice_number = jQuery("#salesinvoicegrid").jqGrid ('getCell', gr, 'invoice_number');
                var invoice_date = jQuery("#salesinvoicegrid").jqGrid ('getCell', gr, 'invoice_date');
                var invoice_status = jQuery("#salesinvoicegrid").jqGrid ('getCell', gr, 'invoice_status');
                if(gr)
                {
					if(invoice_status=='APPROVED'){

                    $(".inv_no").html(invoice_number);
                     $(".inv_date").html(invoice_date);
                     $(".inv_id").val(invoice_hdr_id);
                  $("#myLRModal").modal('show'); 
					var url="{{URL::to('lredit')}}?id="+invoice_hdr_id;
					$.get(url,function(data){
						
					$('.lr_number').val(data.lr_no);
					$('.lr_date').val(data.lr_date);
					$('.lr_status').select2('val',[data.lr_status]);
						if(data.update!="create"){
							$('.lr_number').attr("readonly",true);
						$('.lr_date').css("pointer-events","none");
						}else{
							$('.lr_number').attr("readonly",false);
						$('.lr_date').css("pointer-events","auto");
						}
						
					});
                }
                else
                {
                    notyMsg("info","Please Select Approved Invoice");
                }
 } else
                {
                    notyMsg("info","Please Select a row");
                }
            });
            
$("#edit").click(function(){
	var gr = jQuery("#paymentsgrid").jqGrid('getGridParam','selrow');
	var cellValue = jQuery("#paymentsgrid").jqGrid ('getCell', gr, 'payment_hdr_id');
        var batchstatus = jQuery("#paymentsgrid").jqGrid ('getCell', gr, 'payment_status');
	if( cellValue != false )
	{
            if( batchstatus != "INITIATED" ){
		var url = "{{ url('paymentscreate') }}";
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

$("#delete").click(function(){
	var gr = jQuery("#paymentsgrid").jqGrid('getGridParam','selrow');
	var id = jQuery("#paymentsgrid").jqGrid ('getCell', gr, 'payment_hdr_id');
	if(id != false ){
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
  var gr=$('#paymentsgrid').jqGrid('getGridParam','selrow');
  var cellValue = $("#paymentsgrid").jqGrid ('getCell', gr, 'payment_hdr_id');
  if(cellValue != false){
     var url="paymentsview";
     var viewurl = url+'/'+cellValue+'/view';
     window.location.replace('paymentsview/' +cellValue);
  }
  else
  {
  notyMsg("info","Please Select a Row");
  }
});

/*Karthigaa purpose:clear search the jqgrid*/
	$(".clearsearch").click(function(){
            var grid = $("#paymentsgrid");
            grid.jqGrid('setGridParam',{search:false});

            var postData = grid.jqGrid('getGridParam','postData');
            $.extend(postData,{filters:""});
            grid.trigger("reloadGrid",[{page:1}]);
		$('input[id*="gs_"]').val("");
		$('select[id*="gs_"]').select2('val',['']);
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
