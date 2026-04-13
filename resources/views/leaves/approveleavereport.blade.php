@extends('layouts.header')
@section('content')

<h2 class="heads"> Leave Approval Report</h2>
<div class="panel panel-visible" id="spy1">

    <div class="panel-title ">
        
            <div class="row">
               
                <div class="col-md-6">
                           <div class=" col-md-3">
              <div class="form-group">
                <label for="inputIsValid" class="form-control-label">From Date</label>
                     <input type="text" id="from_month" name="from_month" class="form-control timepicker123" value="" tabindex="2">
                </div>
            </div>
            </div>
             <div class="col-md-6">
            <div class=" col-md-3">
             <div class="form-group">
                <label for="inputIsValid" class="form-control-label">To Date</label>
                     <input type="text" id="to_month" name="to_month" class="form-control timepicker123" value="" tabindex="2">
                </div>
            </div>
                </div>
            </div>
            <div class="row" style="text-align: center;">
               <button type="button" id="save" class="btn search">Search</button>
            </div>
             <div class="col-md-6">
                    <button type="button" id="exportexcel" href="#" class="exportexcel">Export as Excel</button>
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
{           /******* Approval Leave grid Start  *******/


 $(document).on('click',".search",function() {

    var from_month = $("#from_month").val();
      var to_month = $('#to_month').val();
    // if (from_month != "" && to_month != "")
    // {
       url = '{{URL::to("gridleavesapprovereportdata")}}?from_month='+from_month+'&to_month='+to_month;
    // $.getJSON(url, function(data)
    // {
    
    var date_format="{{\Session::get('p_date_format')}}";
    //       var data1 = data.result1;

//	var data=JSON.parse(data1.replace(/&quot;/g,'"'));
                
                $("#grid1").jqGrid({
                url: url,
                datatype: "json",
                mtype: "GET",

// 			$("#grid1").jqGrid(
//             {
//               datatype: "local",
                colModel: [
                    { name: "leave_id", label: "Leaves ID", width: 250, hidden: true },
                    { name: "employee_id", label: "Employee Id", width: 250, hidden: true },
                    { name: "forwarded_id", label: "Forwarded Id", width: 250, hidden: true },
                    { name: "employee_number", label: "Employee Number", width: 250},
                    { name: "first_name", label: "Employee Name", width: 250},
                    { name: "forwarded_name", label: "Reporting Name", width: 250},
                    { name: "start_date", label: "Start Date", width: 250 ,editable:true, formatter: 'date',editrules:{date:true}, formatoptions: {  newformat: date_format}},
                    { name: "end_date", label: "End Date", width: 250 ,editable:true, formatter: 'date',editrules:{date:true}, formatoptions: {  newformat: date_format}},
                    { name: "no_of_days", label: "No of Days", width: 250},
                   // { name: "alloted_days", label: "Alloted Days", width: 250},
                    { name: "leave_status", label: "Leave Status", width: 250,hidden: true},
                 //   { name: "approval_reason", label: "Approve Reason", width: 250},
                   // { name: "approvel_comments", label: "Approve Comments", width: 250},
                    { name: "leave_status", label: "Approve Status", width: 250},
                    { name: "created_at", label: "Leave Apply Date", width: 250},
                    { name: "app_name", label: "Approved By", width: 250},
                    { name: "updated_at", label: "Approved Date", width: 250},
                    { name: "leave_type", label: "Leave Type", width: 250},
                    { name: "organization_id", label: "Organization", width: 250,hidden: true},
                    { name: "od_start_date", label: "OD start date", width: 250,hidden: true},
                    { name: "od_end_date", label: "OD end date", width: 250,hidden: true},
                    { name: "od_no_of_days", label: "Od No of days", width: 250,hidden: true},
                    { name: "od_alloted_days", label: "od alloted days", width: 250,hidden: true},
                    { name: "leave_mode", label: "Leave Mode", width: 250,hidden: true},
                    { name: "leave_reason", label: "Leave Reason", width: 250,hidden: true}
                ],

                iconSet: "fontAwesome",
                rowNum: 10,
                rowList: [10,20,100,1000,2000],
                sortname:"leave_id",
                sortorder: "desc",
                viewrecords: true,
                gridview: true,
                rownumbers:true,
                pager: "#grid1",
                data:data,
                multiselect:false,
                multipageselection:true,
                searching: {
                defaultSearch: "cn",
                },
            });
      /***show column ****/
      showcolumn('grid1');
      /*** show colum ***/

      jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
		$("#gs_start_date").attr("placeholder","Eg:2018-10-31");
		$("#gs_end_date").attr("placeholder","Eg:2018-10-31");
		$("#grid1").jqGrid("setLabel", "rn", "S.No");
      
      
   //   console.log(data1);
    // });
    // }else{
    //     notyMsg("info","Please fil the details");
    // }
     });


  

		/******* Approval Leave grid Start  *******/
                
                /******* Clear Search Grid Start  *******/
        $(".clearsearch").click(function()
	{	

	    var grid = $("#grid1");
	    grid.jqGrid('setGridParam',{search:false});

	    var postData = grid.jqGrid('getGridParam','postData');
	    $.extend(postData,{filters:""});
	    grid.trigger("reloadGrid",[{page:1}]);
	    $('input[id*="gs_"]').val("");
	   
	});
              /******* Clear Search Grid End  *******/
                    /******* export To Pdf  Start  *******/
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
  fileName : "Leave Approve.pdf",
  mimetype : "application/pdf"  
});
});	
// $(".datepicker2").datetimepicker( {
//     format: "mm-yyyy",
//     viewMode: "months", 
//     minViewMode: "months"
// });
// $('.datepicker2').datetimepicker({
//     format      :   "YYYY",
//     viewMode    :   "months", 
// });
    $('.timepicker123').datetimepicker({
  
        format: 'yyyy-mm',
            startView: 'decade',
    //minView: 'decade',
    viewSelect: 'decade',
    minView: 3,
      autoclose: true,
 
  });
	   /******* export To Pdf  End  *******/ 
           /******* export To Excel  Start  *******/
        $(document).on('click',".exportexcel",function() {
$("#grid1").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "Leave Approve.xlsx"
    					
				})	
});
      
});

</script>

@endsection
