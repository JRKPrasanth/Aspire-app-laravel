@extends('layouts.header')
@section('content')

@if (session('status'))
        <div class="alert alert-success">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{ session('status') }}
        </div>
    @endif


<h2 class="heads">Exam List
<span class="ui_close_btn"><a href="{{URL::to('generateresult')}}" class="collapse-close pull-right btn-danger" ></a></span></h2>
<div class="panel panel-visible" id="spy1">
<div class="ajaxLoading"></div>
<div class="panel-title ">
  <div class="row" style="text-align:left;">
  <div class="col-md-12">
<button class="btn edit" id="edit">Validate Results</button>
<button class="btn view" id="view">Publish Results</button>
<?php //include('toolbar.php'); ?>
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
      url: "{{ url('examlistgriddata')}}/<?php echo $exam_id; ?>",
      datatype: "json",
      mtype: "GET",
           colModel: [
{ name: "schedule_exam_line_id", label: "schedule_exam_line_id" ,hidden:true},
{ name: "exam_hdr_id", label: "exam_hdr_id" ,hidden:true},
{ name: "exam_attend", label: "exam_attend" ,hidden:true},
{ name: "result_generated", label: "result_generated" ,hidden:true},
{ name: "first_name", label: "Employee Name" ,editable:true, editrules:{date:true}},
{ name: "exam_date", label: "Exam Date" ,editable:true, editrules:{date:true}},
{ name: "exam_start_time", label: "Start Time" ,editable:true, editrules:{date:true}},
{ name: "exam_end_time", label: "End Time" ,editable:true, editrules:{date:true}},
{ name: "duration", label: "Duration" ,editable:true, editrules:{date:true}},
{ name: "attend_status", label: "Is Attend?"},
{ name: "generate_status", label: "Result Status"},
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
  fileName : "Schedule Training.pdf",
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
    					fileName : "Schedule Training.xlsx"
				})		
});	
/* -- End Export to excel Format -- */
jQuery("#grid1").jqGrid('hideCol',["remarks"]);

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

/* -- Start Create Function -- */
$(".create").click(function(){
    
var url="{{ URL::to('scheduleexamcreate/0')}}";
window.location.replace(url);
});
/* -- End Create Function -- */


/* -- Start Edit Data Function -- */
$("#edit").click(function()
{
	var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
// 	var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'schedule_exam_line_id');
	var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'exam_hdr_id');
	var att_status = jQuery("#grid1").jqGrid ('getCell', gr, 'exam_attend');
	var result_status = jQuery("#grid1").jqGrid ('getCell', gr, 'result_generated');
// 	alert(result_status);
  var type = "<?php echo $urlname ?>";
    
	if(gr)
	{ 
	    var url ="{{ URL::to('validateexam') }}/" +cellValue;
	    if(att_status==1){
	        if(result_status==1){
	            notyMsg('Info','Result Already Generated');
	        }else{
	            window.location.href=url;    
	        }
	        
	    }else{
	        notyMsg('Info','This Employee Not Attended the Exam');
	    }
	    
    //          var url ="{{ URL::to('resultgenerate') }}/" +cellValue;
    //          var red_url ="{{ URL::to('generateresult') }}";
    //           $(".ajaxLoading").show();
    //     $.get(url,function(data)
    //     { 
    //          var status = data.status;
    //         var msg    = data.message;
    //         var id     = data.id;
    //         notyMsg(status,msg);
		  //  window.location.href=red_url;
    //     });
        }       
	else
	{
	notyMsg("info","Please Select a Row");
	}
});
/* -- End Edit Data Function -- */

/* -- Start View Data Function -- */
$("#view").click(function()
{
	var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
	var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'schedule_exam_hdr_id');
  var type = "<?php echo $urlname ?>";
  var exam_id = <?php echo $exam_id; ?>;
  	if(exam_id !='')
	{
	    
             var url ="{{ URL::to('resultgenerate') }}/" +exam_id;
             var red_url ="{{ URL::to('generateresult') }}";
              $(".ajaxLoading").show();
        $.get(url,function(data)
        { 
             var status = data.status;
            var msg    = data.message;
            notyMsg(status,msg);
		    window.location.href=red_url;
        });

	}
	else
	{
	notyMsg("Error","Something Went Wrong");
	}
});
/* -- End View Data Function -- */

/* -- Start Select Column Data Function -- */
$(".jqgrow td input").each(function ()
{
    jQuery(this).click(function () {
        $("#grid").jqGrid('setSelection', $(this).parents('tr').attr('id'));
    });
});
/* -- End Select Column Data Function -- */

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

/***********Start Create *****************/
$(document).on('click','.create_old',function()
{

  var url="{{ url('freightcarriershdrcreate') }}/0";
  var pur_url="{{ url('purchasefreightcarriershdrcreate') }}/0";
  var red_url="{{ url('freightcarriershdr') }}";
  var type = "<?php echo $urlname ?>";

  if(type == "freightcarriershdr"){
    window.location.href =  "{{ url('freightcarriershdrcreate') }}/0";
  }else{
    window.location.href = "{{ url('purchasefreightcarriershdrcreate') }}/0";
  }

});
/***********End Create *****************/

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
