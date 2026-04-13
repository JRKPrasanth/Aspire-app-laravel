@extends('layouts.header')
@section('content')


<h2 class="heads">Toolsmenu Config</h2>

<div class="panel panel-visible" id="spy1">

<div class="panel-title">
  <div class="row">
  <div class="col-md-12">
  	<?php include('toolbar.php'); ?>
</div>
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

$("#grid1").jqGrid({
url:"getToolData",
datatype: "json",
mtype: "GET",
	height: 300,
	width: 1250,
	 colModel: [
	{ name: "m_toolmenus_listing_hdr_id", label: "id" ,hidden:true},
	{ name: "pagemodule", label: "Page Module" ,editable:true, editrules:{date:true}},
        { name: "description", label: "Description" ,editable:true, editrules:{date:true}},

	],
	 /*data:result,*/
	iconSet: "fontAwesome",
        rowNum: 10,
        rowList: [10,20,50,100],
        sortorder: "desc",
        viewrecords: true,
        gridview: true,
        rownumbers:true,
        autowidth: true,
        
		autowidth: true,
		viewrecords: true,
          pager:"#grid1",
        searching: {
            defaultSearch: "cn"
        }
      });
jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
$("#grid1").jqGrid("setLabel", "rn", "S.No");

$(document).on('click','.create',function()
{

//var inquirytype = $(this).val();
var url="{{ url('toolsmenuconfighdrcreate') }}/0";
var red_url="{{ url('toolsmenuconfig') }}";


window.location.replace(url);
});




/*********** CLEAR search **************************/

	 /***** Purpose For CLEAR search ********/
	$("#clearsearch").click(function() {
		var grid = $("#grid1");
		grid.jqGrid('setGridParam',{search:false});

		var postData = grid.jqGrid('getGridParam','postData');
		$.extend(postData,{filters:""});
		grid.trigger("reloadGrid",[{page:1}]);
        $('input[id*="gs_"]').val("");
               
	});
/*End*/

jQuery("#grid1").jqGrid('hideCol',["remarks"]);

	/*
jQuery("#showcolumn").click(function() {

  var btn = $(".showcolumn").val();

  if(btn =="1" )
  {
    jQuery("#grid1").jqGrid('showCol',["remarks"]);
    $(".showcolumn").val('2');
  }else{
    jQuery("#grid1").jqGrid('hideCol',["remarks"]);
    $(".showcolumn").val('1');
  }

});
*/


$(document).ready(function(){

$("button#showcolumn").click(function(){
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



$("#editdata").click(function()
{
	var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
	var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'm_toolmenus_listing_hdr_id');
	if( gr )
	{
		window.location.replace('toolsmenuconfighdredit/' +cellValue);
	}
	else
	{
	notyMsgs('info',"Please Select Row");
	}
});

$('#viewdata').click(function(){
  //alert('hhhh');
  var gr=$('#grid1').jqGrid('getGridParam','selrow');
  var cellValue = $("#grid1").jqGrid ('getCell', gr, 'm_toolmenus_listing_hdr_id');  //alert(cellValue);

  if(gr)
  {
     var url="ardiscountshdrview";
     var viewurl = url+'/'+cellValue+'/view';
     window.location.replace('toolsmenuconfighdrview/' +cellValue);
  }
  else
  {
     notyMsgs('info',"Please Select Row");
  }
});
	
	$(document).on('click',".exportexcel",function() {
$("#grid1").jqGrid("exportToExcel",{
          includeLabels : true,
              includeGroupHeader : true,
              includeFooter: true,
              fileName : "Tools Menu.xlsx"
              
        })  
});
	
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
  fileName : "Tools Menu.pdf",
  mimetype : "application/pdf"  
});
   });	
/********** Delete ***************************/


$(document).on('click','#delete',function(e){
e.preventDefault();
var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'm_toolmenus_listing_hdr_id');

if(gr){
		swal({
                title: "Are you sure?",
                text: "You want to delete!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
            },



						 function(e) {
			if(e == true)
			{
				$(document).on('click','.confirm',function(e){
			       // $.get('paymentmethodsdelete?del_id='+cellValue, function(data,status)
				var url ="{{ url('toolsmenuconfighdrdelete') }}/" +cellValue;
				$.get(url,function(data)
				{

					var data = $.trim(data);
//dd(data);
					var red_url ="{{ url('toolsmenuconfig') }}";
					var data = $.trim(data);
					if(data =='0')
					{
						notyMsg('success','Deleted Successfully!!!');
						setTimeout(function(){
						$("#grid1")[0].triggerToolbar();
						}, 1500);
					}
					if(data =='2')
					{
						notyMsg('error',"You Cant't delete  Used in SomeWhere!!!");
						setTimeout(function(){
						$("#grid1")[0].triggerToolbar();
						}, 1500);
					}

				});});
			}

			else
			{
			$('.apply').css('display','none');
			swal("Cancelled");
			}



            })
		$('.apply').css('display','none');

//	window.location.replace('paymentmethodsdelete/' +id);
	}
	else
	{
	notyMsgs('info',"Please Select Row");
	}
});




});

  </script>
@endsection
