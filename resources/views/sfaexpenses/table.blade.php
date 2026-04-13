@extends('layouts.header')
@section('content')

@if (session('status'))
        <div class="alert alert-success">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{ session('status') }}
        </div>
    @endif

<style type="text/css">
.ui-jqgrid .ui-jqgrid-bdiv {
    position: relative;
    margin: 0;
    padding: 0;
    height: auto !important;
    min-width: 100%;
    overflow: unset !important;
    overflow-x: unset;
    text-align: left;
}
</style>
<h2 class="heads">Expenses</h2>

<div class="panel panel-visible" id="spy1">

<div class="panel-title ">
  <div class="row" style="text-align:left;">
  <div class="col-md-12">


  <?php include 'toolbar.php'; ?>
  </div>
</div>
</div>
<div class="row">

<div class="col-md-12" style="padding:15px;">
<!-- <h2 class="myheaders">Doctor </h2> -->
<table id="grid1"></table>

</div>
</div>
</div>








<script type="text/javascript">
$( document ).ready(function() {

      $("#grid1").jqGrid({
      url: "getsfaexpensesData",
      datatype: "json",
      mtype: "GET",
    colModel: [
    { name: "sfaexpenses_id", label: "sfaexpenses_id" ,hidden:true},
{ name: "tour_date", label: "Tour Date" ,editable:true, editrules:{date:true}},
{ name: "city_name", label: "Town" ,editable:true, editrules:{date:true}},
{ name: "doctor_count", label: "Doctor Count" ,editable:true, editrules:{date:true}},
{ name: "chemist_count", label: "Chemist Count",editable:true, editrules:{date:true}},
{ name: "distance", label: "Distance",editable:true, editrules:{date:true}},
  { name: "fare", label: "Fare", editable:true, editrules:{date:true}},
  { name: "daily_allow", label: "Daily Allowance", editable:true, editrules:{date:true}},
  { name: "post_tele", label: "Post Telegrams", editable:true, editrules:{date:true}},
  { name: "line_total", label: "Total", editable:true, editrules:{date:true}},
    { name: "created_by", label: "Created By",hidden:true},
 ],
        iconSet: "fontAwesome",
          rowNum: 10,
              rowList: [10,20,50,100,250,500,1000],
              sortorder: "desc",
              viewrecords: true,
              gridview: true,
              rownumbers:true,              
                pager: "#grid1",
                autowidth: true,
              viewrecords: true,
             searching: {
                 defaultSearch: "cn"
             }
           });

$("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false,edit:true,add:true,del:true,search:true,cloneToTop:true,refresh:false});
$("#grid1").jqGrid("setLabel", "rn", "S.No");
 
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
            fileName : "Expenses.pdf",
            mimetype : "application/pdf"  
        });
   });
   $(document).on('click',".exportexcel",function() {
        $("#grid1").jqGrid("exportToExcel",{
          includeLabels : true,
              includeGroupHeader : true,
              includeFooter: true,
              fileName : "Expenses.xlsx"
              
        })  
    });

showcolumn('grid1');

$(document).ready(function(){


 $("a[href='" + window.location.hash + "']").parent(".reveal").click();
});

$(".create").click(function(){

var url="{{ URL::to('sfaexpensescreate')}}";
window.location.replace(url);

});
	


// $("#edit").click(function()
// {
// 	var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
// 	var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'sfaexpenses_id');
//   var type = "<?php echo $urlname ?>";

// 	if(cellValue)
// 	{
//     var url = "{{ url('sfaexpensesedit') }}/";
		
// 		  window.location.replace(url +cellValue);
// 	}
// 	else
// 	{
// 	notyMsg("info","Please Select Row");
// 	}
// });




$("#viewdata").click(function()
{
	var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
	var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'sfaexpenses_id');
  
	if(cellValue)
	{
    var url = "{{ url('sfaexpensesview') }}/"+cellValue;
      window.location.replace(url);
	}
	else
	{
	    notyMsg("info","Please Select Row");
	}
});


$(".jqgrow td input").each(function () {
    jQuery(this).click(function () {
        $("#grid").jqGrid('setSelection', $(this).parents('tr').attr('id'));
    });
});


$("#delete").click(function(){

  var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
  var cellValue = jQuery("#grid1").jqGrid ('getCell', gr,'sfaexpenses_id');
  if( cellValue )
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
    }, function(e) {
    if(e == true)
      {
        var url ="{{ URL::to('sfaexpensesdelete') }}/" +cellValue;
        $.get(url,function(data)
        {
          var data = $.trim(data);
          var red_url ="{{ URL::to('sfaexpenses') }}";

          if(data =='0')
          {
            notyMsg('success','Deleted Successfully!!!');
            setTimeout(function(){
            $("#grid1")[0].triggerToolbar();
            }, 1500);
          }
          if(data=='2')
          {
            notyMsg('error',"You Can't delete , Doctor Used in SomeWhere!!!");
            setTimeout(function(){
              $("#grid1")[0].triggerToolbar();
            }, 1500);
          }
        });
      }
      else
      {
        $('.apply').css('display','none');
        swal("Cancelled");
      }
    });
  $('.apply').css('display','none');
  }
  else
  {
  notyMsg("info","Please Select Row");
  }
});




/*********** CLEAR search **************************/

	$("#clearsearch").click(function() {
		  var grid = $("#grid1");
      grid.jqGrid('setGridParam',{search:false});

      var postData = grid.jqGrid('getGridParam','postData');
      $.extend(postData,{filters:""});
      grid.trigger("reloadGrid",[{page:1}]);
      $('input[id*="gs_"]').val("");

	});




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
