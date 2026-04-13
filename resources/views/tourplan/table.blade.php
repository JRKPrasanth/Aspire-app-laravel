@extends('layouts.header')
@section('content')

<?php //dd($pageMethod); ?>

<h2 class="heads">{{$emp_name}} Tour List</h2>

<div class="panel panel-visible" id="spy1">

<div class="panel-title text-left">
	<div class="row">
		<div class="col-md-12">

			<?php if($edit_type=="new"){ include('toolbar.php'); } else { ?>
			<button type='button' id="status_ap" class='btn status_ap search btn-search'> Approve/Reject </button>
			<?php } ?>
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

var edit_type="{{$edit_type}}"; 

if(edit_type=="new"){
	var urlname="gettourplanData";
} else if(edit_type=="approve"){ 
	var emp_id1="{{$emp_id1}}";
	
	var urlname="gettourapprovaldata?emp_id="+emp_id1;
	//var  urlname = url+'?emp_id='+emp_id;
	
}


$("#grid1").jqGrid({
url: urlname,
datatype: "json",
mtype: "GET",
	 colModel: [
	{ name: "tourprogram_id", label: "id",hidden: true },
	{ name: "tour_date", label: "Plan Date" ,editable:true, editrules:{date:true}},
    { name: "first_name",label: "Name", editrules:{date:true}},
	{ name: "work_type", label: "Type",editrules:{date:true}}
	],
    	rowNum: 10,
        rowList: [10,20,50,100],
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

jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});


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
          fileName : "Tourplan.pdf",
          mimetype : "application/pdf"  
      });
   });
   $(document).on('click',".exportexcel",function() {
        $("#grid1").jqGrid("exportToExcel",{
            includeLabels : true,
            includeGroupHeader : true,
            includeFooter: true,
            fileName : "Tourplan.xlsx"
              
        })  
    });

$("#clearsearch").click(function() {
     var grid = $("#grid1");
      grid.jqGrid('setGridParam',{search:false});

      var postData = grid.jqGrid('getGridParam','postData');
      $.extend(postData,{filters:""});
      grid.trigger("reloadGrid",[{page:1}]);
      $('input[id*="gs_"]').val("");
  });

showcolumn('grid1');

	
	
	$("#edit,#status_ap").click(function()
	{
		var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
		var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'tourprogram_id');

		if( cellValue )
		{
			var emp_id1 ='';
			if(edit_type=="approve"){ 
				var emp_id1="{{$emp_id1}}";
			}
			var url = 'tourplanedit';
			var editUrl = url + '/' + cellValue+"?emp_id="+emp_id1;
			window.location.replace(editUrl);
		}
		else
		{
		notyMsg("info","Please Select Row");
		}
	});

	$("#approve").click(function()
	{
		var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
		var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'tourprogram_id');

		if( cellValue  )
		{
			var url = 'tourplanapproval';
	        var editUrl = url + '/' + cellValue;
			window.location.replace(editUrl);
		}
		else
		{
		notyMsg("info","Please Select Row");
		}
	});

	

	$("#reject").click(function()
	{
		var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
		var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'tourprogram_id');

		if( cellValue  )
		{
			var url = 'tourplanapproval';
	        var editUrl = url + '/' + cellValue;
			window.location.replace(editUrl);
		}
		else
		{
		notyMsg("info","Please Select Row");
		}
	});

	$(".create").click(function(){
			
		var salesurl="{{  URL::to('tourplancreate')}}";
		window.location.replace(salesurl);
	
	});






	$('#view').click(function(){ 
	  	var gr=$('#grid1').jqGrid('getGridParam','selrow');
	  	var cellValue = $("#grid1").jqGrid ('getCell', gr, 'tourprogram_id');

	  	if(cellValue )
	  	{
	     	var url= 'tourplanview';
	     	var viewurl = url+'/'+cellValue;
	     	window.location.replace(viewurl);
	  	}
	  	else
	  	{
			notyMsg("info","Please Select Row");
	  	}
	});

	$(document).on('click','.del',function(e)
	{
		e.preventDefault();
		var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
		var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'tourprogram_id');
		if(cellValue )
	    {
			swal({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			type: 'warning',
			showCancelButton: !0,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes',
			cancelButtonText: "No",
			closeOnCancel: !1
			},function(e)
			{
		  	   	if(e == true)
		  	   	{
		  	   	var url ="{{ url('tourplandelete') }}/" +cellValue;
			  	   	$.get(url,function(data)
				   	{
				   		var data = $.trim(data);
				   		if(data =='0')
					  	{
							notyMsg('success','Deleted Successfully!!!');
							setTimeout(function(){
								$("#grid1")[0].triggerToolbar();
							}, 1500);
					  	}
					  	if(data =='1')
					  	{
						  	notyMsg('error',"You Cant't delete , Enquiry Used in SomeWhere!!!");
						  	setTimeout(function(){
								$("#grid1")[0].triggerToolbar();
							}, 1500);
					  	}	
				   	});
		  	   	}
		  	   	else{
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


});
  </script>
@endsection
