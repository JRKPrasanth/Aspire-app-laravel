@extends('layouts.header')
@section('content')

@if (session('status'))
        <div class="alert alert-success">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> {{ session('status') }}
        </div>
    @endif


<h2 class="heads">Missed Call Report</h2>

<div class="panel panel-visible" id="spy1">

<div class="panel-title ">
  <div class="row" style="text-align:left;">
  <div class="col-md-12">


  <?php include 'toolbar.php'; ?>
    <button type='button' id="clearsearch" class='btn search btn-search'> clearsearch </button>
    <button type='button' id="showcolumn" value="1" class='btn showcolumn vie reveal '> Show column </button>
  </div>
</div>
</div>
<div class="row">

<div class="col-md-12" style="padding:15px;">
<h2 class="myheaders">Missed Call Report</h2>
<table id="grid1"></table>

</div>
</div>
</div>




<script type="text/javascript">
$( document ).ready(function() {

    
      $("#grid1").jqGrid({
      url: "getmcrData",
      datatype: "json",
      mtype: "GET",
    colModel: [
    { name: "missedcall_rpt_id", label: "id" ,hidden:true},
{ name: "date", label: "date" ,editable:true, editrules:{date:true}},
{ name: "area", label: "area" ,editable:true, editrules:{date:true}},
{ name: "actual_doctor", label: "actual doctor",editable:true, editrules:{date:true}},
 ],
 iconSet: "fontAwesome",
          rowNum: 10,
              rowList: [10,20,50,100],
              sortorder: "desc",
              viewrecords: true,
              gridview: true,
              rownumbers:true,
              caption: "Missed Call Report",
                pager: true,
                autowidth: true,
      viewrecords: true,
             searching: {
                 defaultSearch: "cn"
             }
           });

$("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false,edit:true,add:true,del:true,search:true,cloneToTop:true,refresh:false});

showcolumn('grid1');

$(document).ready(function(){


 $("a[href='" + window.location.hash + "']").parent(".reveal").click();
});

// $(".create").click(function(){

// var url="{{ URL::to('missedcallreportcreate/0')}}";
// window.location.replace(url);

// });
	


// $("#edit").click(function()
// {
// 	var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
// 	var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'missedcall_rpt_id');
//   var type = "<?php echo $urlname ?>";

// 	if( cellValue != false )
// 	{
//     var url = "{{ url('missedcallreportedit') }}/";
		
// 		  window.location.replace(url +cellValue);
// 	}
// 	else
// 	{
// 	notyMsg("info","Please Select Row");
// 	}
// });




// $("#view").click(function()
// {
// 	var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
// 	var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'missedcall_rpt_id');
//   var type = "<?php echo $urlname ?>";
// 	if( cellValue != false )
// 	{
//     var url = "{{ url('missedcallreportview') }}/";
//       window.location.replace(url +cellValue);


// 	}
// 	else
// 	{
// 	notyMsg("info","Please Select Row");
// 	}
// });


$(".jqgrow td input").each(function () {
    jQuery(this).click(function () {
        $("#grid").jqGrid('setSelection', $(this).parents('tr').attr('id'));
    });
});


// $("#delete").click(function(){

//   var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
//   var cellValue = jQuery("#grid1").jqGrid ('getCell', gr,'missedcall_rpt_id');
//   if( cellValue != false )
//   {
//     swal({
//       title: "Are you sure?",
//       text: "You want to delete!",
//       type: "warning",
//       showCancelButton: !0,
//       confirmButtonColor: "#DD6B55",
//       confirmButtonText: "Yes",
//       cancelButtonText: "No",
//     }, function(e) {
//     if(e == true)
//       {
//         var url ="{{ URL::to('missedcallreportdelete') }}/" +cellValue;
//         $.get(url,function(data)
//         {
//           var data = $.trim(data);
//           var red_url ="{{ URL::to('missedcallreport') }}";

//           if(data =='0')
//           {
//             notyMsg('success','Deleted Successfully!!!');
//             setTimeout(function(){
//             $("#grid1")[0].triggerToolbar();
//             }, 1500);
//           }
//           if(data=='2')
//           {
//             notyMsg('error',"You Cant't delete , Doctor DCR Used in SomeWhere!!!");
//             setTimeout(function(){
//               $("#grid1")[0].triggerToolbar();
//             }, 1500);
//           }
//         });
//       }
//       else
//       {
//         $('.apply').css('display','none');
//         swal("Cancelled");
//       }
//     });
//   $('.apply').css('display','none');
//   }
//   else
//   {
//   notyMsg("info","Please Select Row");
//   }
// });




/*********** CLEAR search **************************/

	$("#clearsearch").click(function() {
		var grid = $("#grid1");
		grid.jqGrid('setGridParam',{search:false});

		var postData = grid.jqGrid('getGridParam','postData');
		$.extend(postData,{filters:""});
		grid.trigger("reloadGrid",[{page:1}]);
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
