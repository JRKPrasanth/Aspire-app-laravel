@extends('layouts.header')
@section('content')


<span class="ui_close_btn"></span>

<h2 class="heads">PARTIAL DAY</h2>
<div class="card">

            
                <div class="card-body card-block">
                    <form  action=""  id="partial" data-parsley-validate >
					 <?php $data=\Session::get('data'); if(isset($data[$pageMethod]['save'])) { ?>

                  <input type="hidden" name="edit_id" value="" id="edit_id" />
                {{ csrf_field()}}
                <div class="row">
                  
                
                <div class="form-group col-md-4 ">
                    <label for="start_date" class="form-control-label col-md-5"><span class="req">*</span>Partial Date</label>
                    <div class="col-md-6" >
                        <!-- <div class="input-group form_date " data-date=""   data-link-format="yyyy-mm-dd"> -->
                     <input class="form-control partial_date datepicker" id="partial_date" name="partial_date"  required type="text" value="" >
                                                                                        
									<!-- </div> -->
									</div>
                </div>
					<div class="form-group col-md-4">
							<label for="inputIsValid" class="form-control-label col-md-5"><span class="req">*</span>Start Time</label>
							<div class="col-md-6">
									<!-- <div class="input-group form_date " data-date=""   data-link-format="yyyy-mm-dd"> -->
											<input class="form-control start_time" id="start_time" name="start_time"  required type="time" value="" >
                                                                                        
									<!-- </div> -->
							</div>
					</div>

					<div class="form-group col-md-4">
							<label for="inputIsValid" class="form-control-label col-md-5"><span class="req">*</span>End Time</label>
                                                                <div class="col-md-6">
                                                                        <!-- <div class="input-group form_date " data-date=""   data-link-format="yyyy-mm-dd"> -->
                                                                            <input class="form-control end_time" id="end_time" name="end_time"  required type="time" value="" >
                                                                            
                                                                        <!-- </div> -->
                                                                </div>
					</div>
				
			
				
                    <div class=" form-group col-md-4">
                        <label for="fob_point_name" class="form-control-label col-md-5">Description</label>
                            <div class="col-md-6">
                                <input type="text" id="description" class="form-control" name="description" >
                            </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="organization_id" class="form-control-label col-md-5">Active</label>
                            <div class="col-md-6 pointer">
                                   
								<select name='active' rows='5'  id="active" class='select2' >							
                <option  value="Yes">Yes</option>
                <option  value="No">No</option>
              
                      </select>
                            </div>
                    </div>
            </div>
                
                
                <div class="row">
                <div class="col-md-12 text-center ">
                <button type="button" id="save" class="btn save  save_form">Save</button> 
          <?php include('toolbar.php'); ?>
                </div>
              </div>
              <div class="row">
                  <div class=" col-md-12">
                     
                  </div>
              </div>
              
 			      
<?php } else { ?>
	   <div class="row text-center">
        <?php  include('toolbar.php'); ?>
   
    </div>
	<?php } ?>       
</form>
<div class="row">
              <div class="col-md-12">
                <table id="grid1"></table>
              </div>
              </div>
</div>
		
		</div>
    
    

<script>
	$(document).ready(function()
    {
        /******** Grid Partial Function Strat   *******/
        var date_format="{{\Session::get('p_date_format')}}";
        var data="{{$result}}";
	var data=JSON.parse(data.replace(/&quot;/g,'"'));
		$("#grid1").jqGrid(
            {
               datatype: "local",
                colModel: [
                    { name: "id", label: "ID", width: 250, hidden: true },
                    { name: "partial_date", label: "Partial Date", width: 250 ,editable:true, formatter: 'date',editrules:{date:true}, formatoptions: {  newformat: date_format}},
                    { name: "description", label: "Description", width: 250},
                    { name: "active", label: "Active", width: 250},
                    { name: "start_time", label: "", width: 250, hidden: true },
                    { name: "end_time", label: "", width: 250, hidden: true },
                    
                ],

                iconSet: "fontAwesome",
                rowNum: 10,
                rowList: [10,20,100,1000,2000],
                sortorder: "desc",
                sortname: "id",
                viewrecords: true,
                        sortname: "id",
                gridview: true,
                rownumbers:true,
                pager: "#grid1", data:data,
                multiselect:false,
                multipageselection:true,
                searching: {
                defaultSearch: "cn",
                },
            });
        /***showcolumn**/
        showcolumn('grid1');
        /***shocolumn**/
		
		jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
		$("#gs_start_date").attr("placeholder","Eg:2018-10-31");
		$("#gs_end_date").attr("placeholder","Eg:2018-10-31");
		$("#grid1").jqGrid("setLabel", "rn", "S.No");
                /******** Grid Partial Function End *******/
	/******** Export TO PDF Function Start *******/
		
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
  fileName : "Partial Day.pdf",
  mimetype : "application/pdf"  
});
	 });
     
         /******** Export TO PDF Function End *******/
         
        /******** Export To Excel Function Start *******/
	
	 $(document).on('click',".exportexcel",function() {
    $("#grid1").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "Partial Day.xlsx"
    					
				})	
                        });

       /******** Export To Excel Function End *******/
     
        /******** ClearSearch Function Start *******/
       
		$(".clearsearch").click(function()
	{	

	    var grid = $("#grid1");
	    grid.jqGrid('setGridParam',{search:false});

	    var postData = grid.jqGrid('getGridParam','postData');
	    $.extend(postData,{filters:""});
	    grid.trigger("reloadGrid",[{page:1}]);
	    $('input[id*="gs_"]').val("");
	   
	});
		    
  /******** ClearSearch Function End *******/
    /******** Reset Function Start *******/
       
                $(document).on('click','.reset',function()
                {
                    $('#edit_id').val('');
                    $('#partial_date').val('');
                    $('#start_time').val('');
                    $('#end_time').val('');
                    $('#description').val('');
                    $('#active').select2('val',['Yes']);
					  $("#grid1")[0].triggerToolbar();
                });
                /******** Reset Function End *******/
            /******** Edit Function Start *******/
        $(document).on('click','.edit',function()
                {
					
                    var index = $("#grid1").jqGrid('getGridParam','selrow');
                    var id = $("#grid1").jqGrid ('getCell', index, 'id');
                    var partial_date = $("#grid1").jqGrid ('getCell', index, 'partial_date');
                    var start_time = $("#grid1").jqGrid ('getCell', index, 'start_time');
                    var end_time = $("#grid1").jqGrid ('getCell', index, 'end_time');
                    var description = $("#grid1").jqGrid ('getCell', index, 'description');
                    var active = $("#grid1").jqGrid ('getCell', index, 'active');
                    
                    
                    if(index)
                    {
                            var dateAr = partial_date.split('-');
                            var newDate = dateAr[2] + '-' + dateAr[1] + '-' + dateAr[0]; 
                            $('#edit_id').val(id);
                            $('#start_time').val(start_time);
                            $('#partial_date').val(newDate);
                            $('#end_time').val(end_time);
                            $('#description').val(description);
                            $('#active').select2('val',[active]);
                           
                    }
                    else
                    {
                        
                        notyMsgs('info','Please Selet A Row');
                    }
                }); 
                
             
                
                /******** Edit Function Edit*******/



   $(document).on('click','.delete',function(e)
                {
                    e.preventDefault();

                    var index = jQuery("#grid1").jqGrid('getGridParam','selrow');
                    var id = $("#grid1").jqGrid ('getCell', index, 'id');
           
                    if(id)
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
                        $.get('partialday/delete?del_id='+id, function(data)
                        {
                           
                            if(data == 1)
                            {
                                setTimeout(function()
                                {
                                    notyMsgs('Info','Cannot Be Delete.Already Used in Some Where');
                                     location.reload();

                                }, 2000);
                              
                                                          }
                            else if(data == 2)
                            {
                                notyMsg('Info','Partial Details Deleted Successfully');
                        location.reload();
                            }
                        });
                     }
                        else
                        {
                               location.reload();
                          $('.apply').css('display','none');
                          swal("Cancelled");
                        }
                });
                $('.apply').css('display','none');
                }
                    else
                    {
                        notyMsgs('Info','Please Select a Row');
                    
                           
                    }


            });















              
        /******** Save Form Function Start*******/
        
                
                $(document).on('click','.save_form',function()
                {
                    var url	="{{URL::to('partialsave')}}";
                    var form = $('#partial');
                  form.parsley().validate();
                    if (form.parsley().isValid())
                    {	
			change_date();			                  
                       var data	= $('#partial').serialize();
                        $.post(url,data,function(status)
                        {
                            if(status == 1)
                            {
                                notyMsg('success','Partial day Saved Successfully');
                                             location.reload();
                                $("#grid1")[0].triggerToolbar();

                                $('.reset').trigger('click');
                            }
                            else
                            {
                                notyMsg('success','Partial day Updated Successfully'); 
                                             location.reload();
                                $("#grid1")[0].triggerToolbar();

                                $('.reset').trigger('click');
                            }
                        });
                    }
                });
                });
	

    /******** Save Form Function End*******/
$(".edit,.reset").click(function(){

   $("#partial").parsley().destroy();

 });
 

    
	</script>
@include('layouts.php_js_validation')
@endsection
