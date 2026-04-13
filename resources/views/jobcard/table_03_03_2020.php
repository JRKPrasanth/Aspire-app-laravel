@extends('layouts.header')
@section('content')



<h2 class="heads">Jobcard Status</h2>

<div class="panel panel-visible" id="spy1">
<div class="row">
<div class="col-md-12">
<?php include("toolbar.php");?>
</div>
</div>



<div class="row">
<div class="col-md-12">
<table id="grid1"></table>
</div>
</div>
</div>
<script type="text/javascript">
$( document ).ready(function() {
/*deepika purpose: function to load data using jqgrid*/
	var status='<?php echo $status; ?>';
	var type='<?php echo $type; ?>';
	var jobtype='<?php echo $jobtype; ?>';
	
$("#grid1").jqGrid({
url: "getjobcardData?status="+status+"&type="+type,
datatype: "json",
mtype: "GET",
	 colModel: [
{ name: "w_jobs_hdr_id", label: "id", width: 100,hidden:true},
{ name: "qoh_detail_id", label: "id", width: 100,hidden:true},
{ name: "quality_spec_trx_hdr_id", label: "id", width: 100,hidden:true},
{ name: "qa_submitstage_trx_hdr_id", label: "id", width: 100,hidden:true},
{ name: "job_no", label: "Job No", width: 250 ,editable:true},
{ name: "plan_no", label: "Plan No", width: 250 ,editable:true},
{ name: "job_date", label: "Job Date", width: 250 ,editable:true, editrules:{date:true}},
{ name: "job_completion_date", label: "Job Completion Date", width: 250 ,editable:true, editrules:{date:true}},
{ name: "product_code", label: "Product Code", width: 250 },
{ name: "concatenated_product", label: "Product", width: 250 },
{ name: "batch_no", label: "Batch Number", width: 250 },
{ name: "uom_code", label: "Uom Code", width: 250},
{ name: "job_qty", label: "Job Qty", width: 250 ,editable:true},
{ name: "job_adjusted_qty", label: "Job Adjusted Qty", width: 250 ,editable:true},
{ name: "production_qty", label: "Job Completion Qty", width: 250 ,editable:true},
{ name: "balancejob_qty", label: "Job Balance Qty", width: 250 ,editable:true},
{ name: "job_status", label: "Job status", width: 250 ,editable:true},
{ name: "job_process", label: "Process Name", width: 250 ,editable:true},
{ name: "first_name", label: "Job Created by", width: 250 },
{ name: "remarks", label: "Remarks", width: 250 ,editable:true},
		],
        rowNum:10,
		viewrecords: true,
		footerrow: true,
		sortorder: "desc",
		userDataOnFooter: true, // use the userData parameter of the JSON response to display data on footer
		width: 780,
		height: 300,
		rownumbers: true ,
		rowList: [10, 20, 50, 100,250,500,1000],
		pager: "#grid1",
      });
jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
/*end*/
/*deepika purpose:set date*/
$("#gs_job_date").attr("placeholder","Eg:2018-10-01");
$("#gs_job_completion_date").attr("placeholder","Eg:2018-10-01");
/*deepika purpose: show column*/
jQuery("#grid1").jqGrid('hideCol',["first_name","remarks"]);
showcolumn('grid1');
/*end*/
	$("#grid1").jqGrid("setLabel", "rn", "S.No");
	/*deepika purpose: show column*/
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
  fileName : "Jobcard.pdf",
  mimetype : "application/pdf"  
});
});
$(document).on('click',".exportexcel",function() {		

	$("#grid1").jqGrid("exportToExcel",{
		includeLabels : true,
	    includeGroupHeader : true,
	    includeFooter: true,
	    fileName : "Jobcard.xlsx"					
	});		 
		 
	 
	
});
	/*end*/
/*deepika purpose:redirect to create function*/
$(document).on('click','.create',function()
{
	
var url="{{ URL::to('jobcardcreate') }}/0";
var red_url="{{ URL::to('jobcard') }}";
window.location.replace(url);
});
	/*end*/
	/*deepika purpose: function :redirect to  update the created record*/
$("#editdata").click(function()
{
	var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
	var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'w_jobs_hdr_id');
	var cellValue1 = jQuery("#grid1").jqGrid ('getCell', gr, 'quality_spec_trx_hdr_id');
	var job_status = jQuery("#grid1").jqGrid ('getCell', gr, 'job_status');
	var source="{{ $source }}";
 if(source=="JOBCARD"){
	   cellValue=cellValue;
	   }
	
	else if(source=="REWORK"){
	   cellValue=cellValue1;
	   }
	
	
	if(gr)
	{
		if(job_status=="OPEN"){
		var url = "{{ URL::to($editurl) }}";
		if(source !==""){
                var editUrl = url + '/' + cellValue+'?source='+source;
		   	window.location.replace(editUrl);
		   }
		
		
		else{
		    var editUrl = url + '/' + cellValue+'?jobtype='+jobtype;
		   	window.location.replace(editUrl);
		   }
		}else{
			notyMsg("info","Open Job Card Should be Allow to Edit");
		}
	}
	else
	{
	notyMsg("info","Please Select Row");
	}
});
/*end*/
	/*deepika purpose: function :redirect to rework jobcard*/	
	$("#rework").click(function()
{
	var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
	var cellValue1 = jQuery("#grid1").jqGrid ('getCell', gr, 'quality_spec_trx_hdr_id');
	var qaid = jQuery("#grid1").jqGrid ('getCell', gr, 'qa_submitstage_trx_hdr_id');
	var qohid = jQuery("#grid1").jqGrid ('getCell', gr, 'qoh_detail_id');
		if(cellValue1!=0){
	var source="{{ $source }}";
		   }else{
			   cellValue1=qohid;
		  	var source="Returnrework"; 
		   }
	if( gr )
	{
		var url = "{{ URL::to($editurl) }}";
		
           var editUrl = url + '/' + cellValue1+'?source='+source+'&qaid='+qaid;
		   	window.location.replace(editUrl);
	}
	else
	{
	notyMsg("info","Please Select Row");
	}
});
/*end*/
	/*deepika purpose: function :redirect to delete the created records*/
$("#delete").click(function()
{
	var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
	var id = jQuery("#grid1").jqGrid ('getCell', gr, 'w_jobs_hdr_id');
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
				var url ="{{ URL::to('workorderdelete') }}/" +id;
				$.get(url,function(data)
				{
					var data = $.trim(data);
					var red_url ="{{ URL::to('workorder') }}";
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
						notyMsg('error',"You Can't delete , Enquiry Used in SomeWhere",red_url);
					}

				});
			}
			else
			{
                            $('.apply').css('display','none');
                            swal("Cancelled");
			}
				/*
                e ? swal("Deleted!", "Your imaginary file has been deleted.", "success") : swal("Cancelled", "Your imaginary file is safe :)", "error")*/
            })
		$('.apply').css('display','none');

	//window.location.replace('salesinquirydelete/' +id);
	}
	else
	{
notyMsg("info","Please Select Row");
	}
});
/*end*/
	/*deepika purpose: function :redirect to show the created records*/
$('#view').click(function()
{
  var gr=$('#grid1').jqGrid('getGridParam','selrow');
  var cellValue = $("#grid1").jqGrid ('getCell', gr, 'w_jobs_hdr_id');
  var pageurl="<?php echo $pageurl; ?>";
  if(gr)
  {
     var url="{{URL::to('jobcardview')}}/"+cellValue+"?pageurl="+pageurl;
     window.location.replace(url);
  }
  else
  {
     notyMsg("info","Please Select Row");
  }
});
/*end*/
/*deepika purpose:clear search the jqgrid*/
	$(".clearsearch").click(function()
{
var grid = $("#grid1");
		grid.jqGrid('setGridParam',{search:false});
	var postData = grid.jqGrid('getGridParam','postData');
		$.extend(postData,{filters:""});
		grid.trigger("reloadGrid",[{page:1}]);
		$('input[id*="gs_"]').val("");
});
/*end*/
});
  </script>
@endsection
