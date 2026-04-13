@extends('layouts.header')
@section('content')

<style type="text/css">
	.select2-container {
		width: 150px !important;
	}
.batchname,.select2{


    margin-top: 1.5% !important;
    

}

input#choosefile {
	width: 18.5rem;
    
    background: #9baff1;
    color: #fff;
    padding: 0px;
    
}
input,select{
	height: auto;
 }


</style>

<h2 class="heads"> GSTR-3B Upload</h2>

<div class="panel panel-visible" id="spy1">

    <div class="panel-title">
        <form method="post" action="" id="empinsupload" enctype="multipart/form-data">
                {{ @csrf_field() }}


    	<div class="row ">
        	<div class="col-md-12">
		    	<input name="batchname" type="text" class=" batchname hide" />

		    	<a href="../uploads/GST- GSTR-3B.csv" class="col-md-2 lst btn download" download>Download Template</a>

		    	<span class=' col-md-2 lst btn'><input id="choosefile" name="choosefile"  type="file" /></span>
		    	
		    	<button type="button" id="upload" class="col-md-1 btn upload">Upload</button>

		    <!--	<select name='batchname' id='batchname' class='col-md-2 form-control batchname select2'></select> -->
			</div>
		</div>
    
	    <div class="row">

	        
	        	<div class="col-md-6 text-left">
 	             <!-- <a id="editdata" class="btn sec"> Edit </a> --> 
		          <button type='button' href='' class='btn clearsearch'>Clear Search </button>
                  <button type='button' href='' class='btn showcolumn'>Show Column </button> 
		            
	            </div>
	            <div class="col-md-6 text-right">

	        	</div>
	        

	    </div>

    	</form>
	</div>
	<div class="row">
	 	<div class="col-md-12" >
	 		<table id="empinsuploadgrid"></table>
	 	</div>
	</div>
</div>
    




<!--upload  Modal -->
<div id="myModal1" class="modal fade" role="dialog">
<div class="modal-dialog">
<!-- Modal content-->
<div class="modal-content" style='height:208px;'>
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal">&times;</button>
<h4 class="modal-title">Batch Name</h4>
</div>
<div class="modal-body form-group "><input type="hidden"  />


<div class="col-md-12 batch_data_div">

<div class='col-md-5' style="float:left">

<input type="text" name='batchname1'  id="batchname1" class="form-control"  readonly="true" value="BATCH-<?php echo date('Y-m-d');?>">


</div>

<div class="col-md-1">                -                </div>

<div class='mcontent4 form-group col-md-6'>
<input type="text" name='batchname2'  id="batchname2" class="form-control"  >

</div>

<div class="col-md-12  ">
<button type="button" class="index btn btn-success" data-val="modal">Go</button>
</div>
</div>
</div>
</div>
</div>
</div>
<!--end-->

<style>

	
span.lst {
    display: inline-block;
   
}
	a {
    color: #fff;
    text-decoration: none;
}
	

	/*#gview_pricelistuploadgrid{
	margin-top: -27px;
	}*/
.ui-th-column, 
.ui-jqgrid .ui-jqgrid-htable th.ui-th-column{
	white-space: normal;
}
.ui-jqgrid .ui-jqgrid-pager{
	white-space: normal;
}

</style>


<script type="text/javascript">

    $( document ).ready(function() {

	$("#empinsuploadgrid").jqGrid({
	url: "getgst3data",
	datatype: "json",
	mtype: "GET",
		colModel: [
		{ name: "id", label: "id",hidden:true },
		{ name: "gstin", label: "GSTIN" ,width:300,editable:true, editrules:{date:true}},
		{ name: "period", label: "Month",width:300,editable:true, editrules:{date:true}},
		{ name: "fn_yr", label: "Fn_Year",width:300,editable:true, editrules:{date:true}},
		{ name: "description", label: "Details",width:300,editable:true, editrules:{date:true}},
		{ name: "tax_val", label: "Taxable Value",width:300,editable:true, editrules:{date:true}},
		{ name: "igst", label: "IGST",width:300,editable:true, editrules:{date:true}},
		{ name: "cgst", label: "CGST",width:300,editable:true, editrules:{date:true}},
		{ name: "sgst", label: "SGST",width:300,editable:true, editrules:{date:true}},
		{ name: "cess", label: "CESS",width:300,editable:true, editrules:{date:true}},
		{ name: "uploaded_by", label: "By",width:300,editable:true, editrules:{date:true}},
		{ name: "uploaded_on", label: "Date",width:300,editable:true, editrules:{date:true}},
		{ name: "batch_status", label: "Batch Status",width:300,editable:true, editrules:{date:true}},
		{ name: "batch_name", label: "Batch Name",width:300,editable:true, editrules:{date:true}},
		

		],
		 	iconSet: "fontAwesome",
            rowNum: 10,
	        rowList: [10,20,50,100,250,500,1000],
	        sortorder: "asc",
	        viewrecords: true,
	        gridview: true,
	        rownumbers:true,
	        pager: "#empinsuploadgrid",
	        autowidth:true,
	        searching: {
	            defaultSearch: "cn"
	        }
  	});

	jQuery("#empinsuploadgrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
    $("#empinsuploadgrid").jqGrid("setLabel", "rn", "S.No");

    	$("#editdata").click(function()
	   {
		var gr = jQuery("#empinsuploadgrid").jqGrid('getGridParam','selrow');
		var cellValue = jQuery("#empinsuploadgrid").jqGrid ('getCell', gr, 'expins_id');
		var batchstatus = jQuery("#empinsuploadgrid").jqGrid ('getCell', gr, 'batchstatus');

		if( cellValue )
		{
			if(batchstatus=="ERROR"){
			window.location.replace('empinsuploadedit/' +cellValue);
		}
		else
		{
		notyMsg("info","Batch not allow to edit");
		}
		}
		else
		{
		notyMsg("info","Please Select Row");
		}
	});


showcolumn('empinsuploadgrid');

	/*********** CLEAR search **************************/

	$(".clearsearch").click(function() {
		var grid = $("#empinsuploadgrid");
		grid.jqGrid('setGridParam',{search:false});

		var postData = grid.jqGrid('getGridParam','postData');
		$.extend(postData,{filters:""});
		grid.trigger("reloadGrid",[{page:1}]);
		$('input[id*="gs_"]').val("");
    	$('select[id*="gs_"]').select2('val',['']);		
	});





	$(window).scroll(function() {
	if ($(this).scrollTop() >150){
	    $('.header-sticky').addClass("sticky");
	  }
  	else{
	    $('.header-sticky').removeClass("sticky");
	  }
	});

	showResponse('<?php echo $status; ?>',"<?php echo $message; ?>");

		
   var condition1='group by batchname';
            $("#batchname").jCombo("{{ URL::to('jcomboform1?table=f_empinsupload_t:batchname:batchname') }}&parent="+condition1+'&order_by=batchname asc',{selected_value:''});
    

	$('.upload').click(function(){
		var flname=$("#choosefile").val();
		if(flname != "" ){
			$('#myModal1').modal('show');
			$('.modal-dialog').width('40%');
			$("#myModal1").modal({backdrop: "static"});
		}
		else{
			notyMsg("info","Please choose file");
		}
	});

    	$('.docsupload').click(function(){
    	    var docname=$("#files").val();
    	    //alert(docname);
		    if(docname != "" ){
		      //alert(docname);  
                var form_data = new FormData(document.getElementById('empinsupload'));
	            $.ajax({
	                url: "{{URL::to('docsupload')}}",
	                data: form_data,
	                type: 'POST',
	                enctype: 'multipart/form-data',
	                contentType: false,
	                processData: false,
	                success: function (data)
	                {
	               		notyMsg("success",data['message']);
	                    $('.close').trigger('click');
	                    setTimeout(function(){
	                        location.reload();
	                	}, 2000);

	                },
	                error: function (xhr, status, error)
	                {
	                    //$('#preview_image').attr('src', '{{asset('images/noimage.jpg')}}');
	                }
	            });
	        }
		else{
			notyMsg("info","Please choose file");
		}
    	});

	$('#myModal1').on('shown.bs.modal', function()
	{
		$('.index').click(function()
		{
			var tmp1=$('#batchname1').val();
			var tmp2=$('#batchname2').val();
			if(tmp2 != ""){
				var temp=tmp1+tmp2;
				$('.batchname').val(temp);
			    var form_data = new FormData(document.getElementById('empinsupload'));
	            $.ajax({
	                url: "{{URL::to('gstrthreebdataupload')}}",
	                data: form_data,
	                type: 'POST',
	                enctype: 'multipart/form-data',
	                contentType: false,
	                processData: false,
	                success: function (data)
	                {
	               		notyMsg("success",data['message']);
	                    $('.close').trigger('click');
	                    setTimeout(function(){
	                        location.reload();
	                	}, 2000);

	                },
	                error: function (xhr, status, error)
	                {
	                    //$('#preview_image').attr('src', '{{asset('images/noimage.jpg')}}');
	                }
	            });
	        }else{
	        	notyMsg('info','Please batch name');
	        }
		});
	});

	$('.searchfile_cls').click(function(){
		var batchname=$("#batchname").val();
		$("#empinsuploadgrid").jqGrid('setGridParam', {
	        postData: {"batchname":batchname }
	 	}).trigger('reloadGrid');
	});




	$('.verify').click(function(){

		var batchname=$('#batchname option:selected').val();
		var parm='';
		var verify='verify';
		if(batchname!=''){
			var parm="?batchname="+batchname+'&type=verify';
		}
		else
		{
			notyMsg("info","Please select batchname");
		}

		var newUrl = refineUrl();//fetch new url
		var url="{{URL::to('getempinsvalidate')}}";

		$.get(url, {'batchname': encodeURIComponent(batchname), 'type': verify}, function (response) {
			var data=response.status;
			var message=response.message;
			if(data == 'success')
			{

			notyMsg("success",message);
				 setTimeout(function(){
			window.location.replace(newUrl);
			    }, 2000);

			}

			if(data=='info')
			{
				notyMsg("info",message);
				setTimeout(function(){
			window.location.replace(newUrl);
			    }, 2000);

			}
			if(data=='error') {
			notyMsg("error",message);
			 setTimeout(function(){
			window.location.replace(newUrl);
			    }, 2000);
			}
		});
	});

	$('.load').click(function(){

		var batchname=$('#batchname option:selected').val();
		var parm='';
		var load='load';
		if(batchname!=''){
			// alert(batchname);
			var parm="?batchname="+batchname+'&type=load';
		}
		else
		{
			notyMsg("info","Please select batchname");
		}
		var newUrl = refineUrl();//fetch new url
		var url="{{URL::to('getempinsvalidate')}}";

		$.get(url, {'batchname': encodeURIComponent(batchname), 'type': load}, function (response) {
			var data=response.status;
			var message=response.message;
			if(data == 'success')
			{

			notyMsg("success",message);
				 setTimeout(function(){
			window.location.replace(newUrl);
			    }, 2000);

			}

			if(data=='info')
			{
				notyMsg("info",message);
				setTimeout(function(){
			window.location.replace(newUrl);
			    }, 2000);

			}
			if(data=='error') {
			notyMsg("error",message);
			 setTimeout(function(){
			window.location.replace(newUrl);
			    }, 2000);
			}
		});

	});
	
    $('.lineload').click(function(){

		var batchname=$('#batchname option:selected').val();
		var parm='';
		var lineload='lineload';
		if(batchname!=''){
			// alert(batchname);
			var parm="?batchname="+batchname+'&type=lineload';
		}
		else
		{
			notyMsg("info","Please select batchname");
		}
				var newUrl = refineUrl();//fetch new url
		        var url="{{URL::to('getempinsvalidate')}}";

		$.get(url, {'batchname': encodeURIComponent(batchname), 'type': lineload}, function (response) {
			var data=response.status;
			var message=response.message;
			if(data == 'success')
			{

			notyMsg("success",message);
				 setTimeout(function(){
			window.location.replace(newUrl);
			    }, 2000);

			}

			if(data=='info')
			{
				notyMsg("info",message);
				setTimeout(function(){
			window.location.replace(newUrl);
			    }, 2000);

			}
			if(data=='error') {
			notyMsg("error",message);
			 setTimeout(function(){
			window.location.replace(newUrl);
			    }, 2000);
			}
		});

	});	

});

/*refine url with  params */
function refineUrl()
{
	//get full url
	var url = window.location.href;
	//get url after/
	//var value = url.substring(url.lastIndexOf('/') + 1);
	//get the part after before ?
	var value  = url.split("?")[0];
	// alert(value);
	return value;
}

function showResponse(data,message)
{

	if(data == 'success')
	{
		notyMsg("success",message);
		var url="{{ URL::to('empinsupload') }}";

	}

	if(data=='info')
	{
		notyMsg("info",message);
		var url="{{ URL::to('empinsupload') }}";

	}


	if(data=='error')
	{
		notyMsg("error",message);
		var url="{{ URL::to('empinsupload') }}";

	}


}

</script>
@endsection