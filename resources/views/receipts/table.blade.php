@extends('layouts.header')
@section('content')


<div id="accordion">
<div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a role="button">
                                       Receipts
                                    </a>
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
<table id="receiptsgrid"></table>
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
    

var batchopt="{{$batchopt}}";

$("#receiptsgrid").jqGrid({
url: "getReceiptsData",
datatype: "json",
mtype: "GET",
	
	 colModel: [
	{ name: "receipt_hdr_id", label: "id",hidden:true },
	{ name: "receipt_number", label: "Receipt Number" },
        { name: "receipt_date", label: "Receipt Date"},
        { name: "receipt_batch_hdr_id",  align: "center",label: "Batch Name",stype:'select', editoptions:{value:batchopt}},
        { name: "receipt_status", label: "Receipt Status"},
        { name: "batch_total_amount", label: "Batch Total Amount"},
       ],
        rowNum:10,
		viewrecords: true,
		footerrow: true,
		userDataOnFooter: true, // use the userData parameter of the JSON response to display data on footer
		width: 780,
		height: 300,
		rowList: [10, 20, 50, 100,250,500,1000],
		pager: "#receiptsgrid",
        sortorder: "desc"
      });
jQuery("#receiptsgrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
$("#gs_receipt_date").attr("placeholder","Eg:2018-10-31");  
showcolumn('receiptsgrid');
	
	$(document).on('click',".export",function() {
   	$("#receiptsgrid").jqGrid('exportToPdf', {
  title: null,
  orientation: 'portrait',
  pageSize: 'A4',
  description: null,
  onBeforeExport: null,
  download: 'download',
  includeLabels : true,
  includeGroupHeader : true,
  includeFooter: true,
  fileName : "Receipt.pdf",
  mimetype : "application/pdf"  
});
			
$("#receiptsgrid").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "Receipt.xlsx"
    					
				})		 
});
  /*Karthigaa Purpose For cancel*/
      $("#create").click(function(){ 
	var url="{{  URL::to('receiptscreate')}}";
	window.location.replace(url);
	}); 
        
$("#edit").click(function(){
	var gr = jQuery("#receiptsgrid").jqGrid('getGridParam','selrow');
	var cellValue = jQuery("#receiptsgrid").jqGrid ('getCell', gr, 'receipt_hdr_id');
        var batchstatus = jQuery("#receiptsgrid").jqGrid ('getCell', gr, 'receipt_status');
	if(gr)
	{
            if( batchstatus != "INITIATED" ){
		var url = "{{ url('receiptscreate') }}";
                var editUrl = url + '/' + cellValue;
		window.location.replace(editUrl);
	}
            else{
               notyMsg('error',"<i class='fa fa-exclamation-circle' style='font-size:16px'></i> Submitted BATCHES Cannot Be Edit!!!");
            }
            }
	else
	{
	notyMsg("info","Please Select Row");
	}
});

$("#delete").click(function(){
	var gr = jQuery("#receiptsgrid").jqGrid('getGridParam','selrow');
	var id = jQuery("#receiptsgrid").jqGrid ('getCell', gr, 'receipt_hdr_id');
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
				var url ="{{ url('receiptsdelete') }}/" +id;
				$.get(url,function(data)
				{
					var data = $.trim(data);
					var red_url ="{{ url('receipts') }}";
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

$('#view').click(function(){    
  var gr=$('#receiptsgrid').jqGrid('getGridParam','selrow');
  var cellValue = $("#receiptsgrid").jqGrid ('getCell', gr, 'receipt_hdr_id');
  if(gr){
     var url="receiptsview";
     var viewurl = url+'/'+cellValue+'/view';
     window.location.replace('receiptsview/' +cellValue);
  }
  else
  {
  notyMsg("info","Please Select Row");
  }
});

/*Karthigaa purpose:clear search the jqgrid*/
	$(".clearsearch").click(function(){
            var grid = $("#receiptsgrid");
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
