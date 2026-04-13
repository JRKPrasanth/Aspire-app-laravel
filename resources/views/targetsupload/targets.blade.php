@extends('layouts.header')
@section('content')
<style>
    input[type="file"] {
    display: none;
}
.custom-file-upload 
{
    border: 1px solid #ccc;
    display: inline-block;
    padding: 6px 12px;
    cursor: pointer;
}
.btnic 
{
    text-align: center;
    background-color: DodgerBlue;
    border: none;
    color: white;
    padding: 12px 30px;
    cursor: pointer;
    font-size: 12px;
}
.panel, .card {
    margin-bottom: 240px;
}
</style>


<span class="ui_close_btn"></span>


<h2 class="heads">{{$title}}</h2>

            <div class="card">
                    

                            <div class="card-body card-block">
                              <div class="row">
                                
                               <div class=" col-md-6">
                                


                            <form action="{{ url('targetuploadsave')}}" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="edit_id" value="" id="edit_id" />
                                  <input type="hidden" name="source" value="{{$title}}" id="source" />
                                {{ csrf_field()}}

                                
                                     
                                        <div class="form-group row">
                                          
                                            <label for="inputIsValid" class="form-control-label col-md-2">File:</label>
                                            <div class="col-md-5"> 
                                            <label for="file-upload" class="custom-file-upload file_choose">
                                                 File Upload
                                            </label>

                                            <input type="file" id="file_upload" name="file_upload" class="form-control file_upload" value="" required>
                                            </div>
                                              </div>
                                              
                                            <!-- <div class="row"> -->
                                              <div class="col-md-offset-2 col-md-10" style="margin-left: 15.666667%;">
                                          <button type="submit" id="save" class="btn upload  upload-image">Upload</button>
                                          <a href="{{url('/Uploads/branch_upload.csv')}}"  class="lst btn download" download>Download Template</a>
                                          </div>  
                                       <!--  </div> -->

                                
                            </form>
                            
                          </div>

                            <div class=" col-md-6">
                                
                                    <form id="validate_form" action="" >
                                        {{ csrf_field()}}
                                    <div class="form-group col-md-12">

                                        <label for="inputIsValid" class="form-control-label col-md-4">Batch Number:</label>
                                        <div class="col-md-6">
                                            <select id="batch_number" name="batch_number" class="select2 form-control batch_number" required="">
                                                {!!$batch_no!!}
                                            </select>
                                        </div>
                                            </div>
                                            
                                              <div class="col-md-offset-3 col-md-9" style="margin-left:32.4%;">
                                                <button type="button" id="save" class="btn  search search_btn ">Search</button> 
                                                <button type="button"  class="btn  verify validate validate_btn" >Validate</button>
                                                <button type="button"  class="btn  vie load loadd load_btn" >Load</button>
                                              </div>
                                            
                                    
                                    </form>
                                


                            </div>

                            
                                    

                                            
                                              <div class="row">
                                                  <div class=" col-md-12">
                                            <a id="editdata"  class="btn sec"> Edit</a>
                                         
                                          </div>
                                        </div>
                                      
                                    
                                
                        <div class="row">
                        <div class="col-md-12">
                        <table id="grid1"></table>
                        </div>
                        </div>
                      
                    </div>
                    </div>
            </div>
  
	<script>
	$(document).ready(function(){

<?php if($pageMethod=="awdtargets"){ ?>
    /*$("#batch_number").jCombo("{{ URL::to('jcomboformlogin?table=target_upload_tbl:batch_no:batch_no') }}&group_by= group by batch_no",
	{   });*/
      <?php } ?> 
        $("#grid1").jqGrid({
	url: "targetuploaddata?source={{$title}}",
	datatype: "json",
	mtype: "GET",
        colModel: [
            { name: "targets_id", label: "id", width:100,hidden:true },
            { name: "batch_no", label: "Batch No.", width: 200,editable:true, editrules:{date:true}},
            { name: "batch_status", label: "Batch Status", width: 200,editable:true, editrules:{date:true}},
            { name: "batch_comments", label: "Batch Comments", width: 200,editable:true, editrules:{date:true}},
            { name: "stockist_name", label: "Branch Name", width: 200,editable:true, editrules:{date:true}},
            { name: "state_name", label: "State", width:80, editable:true, editrules:{date:true}},
            { name: "town_name", label: "Town", width:250, editable:true, editrules:{date:true}},
            { name: "product_name", label: "Product Name", width:200,editable:true, editrules:{date:true}},
            { name: "target_qty", label: "Target Qty", width:350,editable:true, editrules:{date:true}},
            ],
           iconSet: "fontAwesome",
           rownumbers: true,
           sortname: "targets_id",
           sortorder: "asc",
           threeStateSort: true,
           sortIconsBeforeText: true,
           headertitles: true,
           pager: "#grid1",
           autowidth:true,
           rowNum: 10,
           viewrecords: true,
searching: {
    defaultSearch: "cn"
}
});
jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
$("#grid1").jqGrid("setLabel", "rn", "S.No");
/**** jqgrid employee upload end*****/
/**** file choose start*****/

            $(document).on('click','.file_choose',function(e)
            {
                $("#file_upload").trigger( "click" );
            });
            /**** file choose end*****/
                    /****edit function  start*****/
            $("#editdata").click(function()
            {
                var gr=$('#grid1').jqGrid('getGridParam','selrow');
                var cellValue = $("#grid1").jqGrid ('getCell', gr, 'targets_id');  //alert(cellValue);

                if(cellValue != false)
                {
                   
                   window.location.replace('awduploadedit/' +cellValue);
                }
                else
                {
                   notyMsg("info","Please Select Row");
                    setTimeout(function(){
                    window.location.replace(newUrl);
                  }, 2000);
                }
            });
                    /****edit function  end*****/
       

		
		/*** verify batch number start **/
		$('.verify').click(function()
                {

		var batchname=$('.batch_number option:selected').val();
		var parm='';
		var verify='verify';
		if(batchname!=''){
			var parm="?batchname="+batchname+'&type=verify';
		}
		else
		{
			notyMsg("info","Please select batchnumber");
		}

		var newUrl = refineUrl();//fetch new url
		var url="{{URL::to('getawdvalidate')}}";

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
		
		    /*** verify batch number end **/
        /*** load data in employee start **/
		$('.load').click(function(){

		var batchname=$('#batch_number option:selected').val();
		var parm='';
		var load='load';
		if(batchname!=''){
			// alert(batchname);
			var parm="?batchname="+batchname+'&type=load';
		}
		else
		{
			notyMsg("info","Please select batchnumber");
		}
				var newUrl = refineUrl();//fetch new url
		var url="{{URL::to('getawdvalidate')}}";

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
		        /*** load data in employee end **/
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
 /*** load data in employee start **/
            $(document).on('click','.load_btn',function()
            {
                var batch_number = $('.batch_number').select2('val');

                if(batch_number != '')
                {
                        var form_data = new FormData(document.getElementById('validate_form'));
                        $.ajax({
                          url: "{{ url('employeeload')}}",
                          type: "POST",
                          data: form_data,
                          enctype: 'multipart/form-data',
                          processData: false,  // tell jQuery not to process the data
                          contentType: false,   // tell jQuery not to set contentType
                          async:true,
                          xhr: function(){
                              var xhr = $.ajaxSettings.xhr();
                            if (xhr.upload) {
                                xhr.upload.addEventListener('progress', function(event) {
                                }, true);
                                }
                                return xhr;
                        }
                        }).done(function(data,status)
                        {
                            if(data[1] == 1)
                            {
                                notyMsgs('info','Employee Details Saved Successfully');
                                setTimeout(function(){
                                location.reload();
                                }, 2000);
                            }
                            else
                            {
                                $(".alert-success").hide();
                                $(".alert-danger").fadeIn(800);

                            }
                        }).fail(function(data,status)
                        {

                                $(".alert-success").hide();
                                $(".alert-danger").fadeIn(800);

                        });

                }
                else
                {
                     notyMsgs('info','<i class="fa fa-warning"></i> Select Batch Number');
                }
            });
 /*** load data in employee end **/

//            $(document).on('click','.save',function(e){
//
//                e.preventDefault();
//                var data;
//                data = $("#save").serialize();
//                $.post('employeeposition/save', data, function(data)
//                {
//                    if(data == 1){
//                        alert('saved successfully');
//                        location.reload();
//                    }
//                    else if(data == 2)
//                    {
//                        alert('updated  successfully');
//                        location.reload();
//                    }
//                });
//            });
 /*** upload start **/
                //$("body").on("click",".upload-image",function(e){
                $(document).on('click','.upload-image',function(e){
                  // alert("dfgfgss");
                   $(this).parents("form").ajaxForm({
                     complete: function(response)
                     {
                       if($.isEmptyObject(response.responseJSON.image)){
                         $('.preview-uploaded-image').html('<img src="'+response.responseJSON.url+'">');
                       }else{
                         var msg=response.responseJSON.image;
                         $(".error-msg").find("ul").html('');
                         $(".error-msg").css('display','block');
                         $.each( msg, function( key, value ) {
                           $(".error-msg").find("ul").append('<li>'+value+'</li>');
                         });
                       }
                     }
                   });
                 });

/** upload end*****/
/** delete funcation start ***/
            $(document).on('click','.delete',function(e){
                e.preventDefault();
                var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
                var cellValue = jQuery("#grid1").jqGrid ('getCell', gr, 'position_id');
                if(cellValue != false)
                {
                    $.get('employeeposition/delete?del_id='+cellValue, function(data,status)
                    {
                        if(data == 0)
                        {
                            alert('deleted successfully');
                            location.reload();
                        }
                        else if(data == 2)
                        {
                            alert('deletion error');
                            location.reload();
                        }
                    });
                }
                else
                {
                 alert("Please Select Row");
                }


            });
/***** delete function end *****/
	});
	</script>

@endsection
