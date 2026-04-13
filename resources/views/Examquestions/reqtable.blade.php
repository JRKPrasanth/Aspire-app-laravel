@extends('layouts.header')
@section('content')

@if (session('status'))
        <div class="alert alert-success">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{ session('status') }}
        </div>
    @endif


<h2 class="heads">Schedule Training From Request</h2>

<div class="panel panel-visible" id="spy1">

<div class="panel-title ">
  <div class="row" style="text-align:left;">
  <div class="col-md-12">
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

<div class="col-md-12" style="padding:15px;">

<table id="grid1"></table>

</div>
</div>

<div class="row">
    <div class="col-md-12">
         <hr class="xlg">
    </div>
</div>

</div>
<script type="text/javascript">
/**********Jqgrid  Start*******/
$( document ).ready(function() {
var format="<?php echo (\Session::get('p_date_format')); ?>";
      $("#grid1").jqGrid({
      url: "getrequesttraininggriddata",
      datatype: "json",
      mtype: "GET",
           colModel: [
{ name: "training_request_id", label: "id" ,hidden:true},
{ name: "request_type", label: "Request Type" ,editable:true, editrules:{date:true}},
{ name: "topic_name", label: "Topic" ,editable:true, editrules:{date:true}},
{ name: "employee_name", label: "Employee Name"},
{ name: "remarks", label: "Remarks"}
 ],
 iconSet: "fontAwesome",
         rowNum:10,
		viewrecords: true,
		footerrow: true,
		userDataOnFooter: true, // use the userData parameter of the JSON response to display data on footer
		width: 780,
		height: 300,
		rowList: [10,20,50,100,250,500,1000],
		     rownumbers: true ,
		pager: "#grid1"
           });
$("#grid1").jqGrid("setLabel", "rn", "S.No");
$("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false,edit:true,add:true,del:true,search:true,cloneToTop:true,refresh:false});
/**********Jqgrid  End *******/

/* -- Start Export to pdf Format -- */
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
  fileName : "Request For Training.pdf",
  mimetype : "application/pdf"  
});
	
});
/* -- End Export to pdf Format -- */

/* -- Start Export to excel Format -- */
$(document).on('click',".exportexcel",function() {
$("#grid1").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "Request For Training.xlsx"
				})		
});	
/* -- End Export to excel Format -- */


/* -- Start Show Column Function -- */
showcolumn('grid1');
$(document).ready(function(){
$("button#showcolumn_old").click(function(){
        $(this).toggleClass("active").next().slideToggle("fast");
        var btn = $(".showcolumn").val();

        if ($.trim($(this).text()) === 'Show column') {
                $(this).text('Hide column');
                jQuery("#grid1").jqGrid('showCol',["remarks"]);
                $(".showcolumn").val('2');
        } else {
                $(this).text('Show column');
                jQuery("#grid1").jqGrid('hideCol',["remarks"]);
                $(".showcolumn").val('1');
        }

    return false;
});
 $("a[href='" + window.location.hash + "']").parent(".reveal").click();
});
/* -- End Show Column Function -- */


/* -- create schedule from request Function -- */
$(".create").click(function()
{
	var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
	var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'training_request_id');
	if(gr)
	{ 
             var url ="{{ URL::to('scheduletrainingcreate') }}/0?reqid="+cellValue;
            
            window.location.replace(url);
    
        }       
	else
	{
	notyMsg("info","Please Select a Row");
	}
});
/* -- End  -- */

/* -- Start Delete Jqgrid Data Function -- */
$(document).on('click','#delete',function(e){
        e.preventDefault();
        var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
        var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'ar_frieghtcarriers_hdr_id');
          var type = jQuery("#grid1").jqGrid ('getCell', gr, 'source_type_id');
        
        if(gr)
        {
            swal({
                       title: "Are you sure?",
                      text: "You want to delete!",
                      type: "warning",
                      showCancelButton: !0,
                      confirmButtonColor: "#DD6B55",
                      confirmButtonText: "Yes",
                      cancelButtonText: "No",
                      closeOnCancel:!1
                    },

            function(e) {
                 if(e == true)
                 {
                  $(document).on('click','.confirm',function(e){
                    $.get('freightcarriershdrdelete/'+cellValue+"/"+type, function(data,status)
                    {
                        var data = $.trim(data);
                        if(data =='0')
                        {
                            notyMsg('success','Deleted Successfully');
                           $(".reset").trigger('click');
                            $('.clearsearch').trigger('click');
                            setTimeout(function(){
                            $("#grid1")[0].triggerToolbar();
                            }, 1500);
                        }
                        if(data =='1')
                        {
                            notyMsg('info',"You Can't delete  Used in SomeWhere");
                             $('.clearsearch').trigger('click');
                            setTimeout(function(){
                            $("#grid1")[0].triggerToolbar();
                            }, 1500);
                        }
                    });
                  });
                }else{
                  $('.apply').css('display','none');
                   $('.clearsearch').trigger('click');
                  swal("Cancelled");
                }
            })
            $('.apply').css('display','none');
        }
        else
        {
          	  notyMsgs("info","Please Select a Row");
        }
   });
/* -- End Delete Jqgrid Data Function -- */

/***********Start CLEAR search *****************/

	$("#clearsearch").click(function() {
		var grid = $("#grid1");
		grid.jqGrid('setGridParam',{search:false});

		var postData = grid.jqGrid('getGridParam','postData');
		$.extend(postData,{filters:""});
		grid.trigger("reloadGrid",[{page:1}]);
                 $('input[id*="gs_"]').val("");
                
	});
  /***********End CLEAR search *****************/

/***********Start Scroll header function *****************/
$(window).scroll(function() {
if ($(this).scrollTop() >150){
    $('.header-sticky').addClass("sticky");
  }
  else{
    $('.header-sticky').removeClass("sticky");
  }
});
/***********End Scroll header function *****************/
});
  </script>
@endsection
