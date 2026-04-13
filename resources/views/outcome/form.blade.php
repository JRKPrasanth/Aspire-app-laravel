@extends('layouts.header')
@section('content')



<h2 class="heads">OUTCOME</h2>

	<div class="card">


<div class="card-body">

	  <div class="row">
		   <form id="prdpacksave" method="post"  action="" data-parsley-validate>
			   <input type="hidden" value="" name="savestatus" id="savestatus" />
			      <div class="card-body card-block">
					   <input type="hidden" name="edit_id" value="{{$row->outcome_id}}" id="edit_id" />
					    {{ csrf_field()}}
					      <div class="row">
							  
					         <div class="col-md-6">
								  <div class="form-group row">
								<label for="inputIsValid" class="form-control-label col-md-5"> <span style="font-style:20px;color:red;">*</span>Outcome Name</label>
									   <div class="col-md-7">
										    <input type="text" name="outcome_name" id="outcome_name" value="" class="form-control outcomename" required tabindex="1">
										    <span class="btn btn-danger dup_name" style="display:none;"></span>
									   </div>
									   <div class="col-md-2">
			                            </div>
								   </div>  
							</div>	

							<div class="col-md-6">
								  
								   <div class="form-group row">
								<label for="inputIsValid" class="form-control-label col-md-5"> Description</label>
									   <div class="col-md-7">
										    <input type="text" name="description" id="description" value="" class="form-control description" tabindex="4" >
									   </div>
									   <div class="col-md-2">
			                            </div>
								   </div>
							  </div>
							  
					      </div>	


					      

					      
							  <div class="col-md-6">
							<div class="form-group row">
            <label for="inputIsValid" class="form-control-label  col-md-5">Active</label>
                        <div class="col-md-7">
                            <select name="active" class="form-control select2 active" id="active" tabindex="3">
                                 <option <?php if($row->active=="Yes"){ echo "selected" ; }?> value="Yes" >Yes</option>
                                   <option <?php if($row->active=="No"){ echo "selected" ; }?> value="No" >No</option>
                            </select>
                        </div>
        </div> 
								  
						
		</div>					
	  
							   
					       <div class="row text-center">
                             <div class=" col-md-12">
			                    <button type="submit" id="save" class="btn  save saveform" value="SAVE">Save</button> 
								    
								 <?php include('toolbar.php'); ?>
                             
                               </div>
                           </div>
			      </div>
		   </form>
		    <div class="col-md-12">
                <table id="outcomenamegrid"></table>
              </div>
               </div>

</div>
	</div>
 
		  
<script>
	  /*Duplicate Validation*/
     var dup_chk = true;
        function duplicate_validate()
        {
            var outcome_name = $(".outcomename").val();
            var edit_id = $("#edit_id").val();
            var url = "{{URL::to('outcometypecheckname')}}"

            $.ajax({
                cache: false,
                url: url, /*this is your uri*/
                type: 'GET',
                dataType: 'json',
                async : false,
                data: {outcome_name : outcome_name,edit_id : edit_id},
                success: function(response)
                {
                    if(response == 1)
                    {
                        $('.dup_name').html('Outcome Name:'+outcome_name+' Already Exists ');
                        $('.dup_name').show();
                        $(".outcome_name").val('');
                        dup_chk = false;
                       }
                    else if(response == 0)
                    {
                       var html ="";
                       $('.dup_name').hide();
                       dup_chk = true;
                    }
                },
                error: function(xhr, resp, text)
                {
                    console.log(xhr, resp, text);
                }
            });
        }
/*End*/

		   $(document).ready(function(){



				 $("#outcomenamegrid").jqGrid({
					url:"{{URL::to('outcomegrid')}}",
					mtype:'GET',
					datatype:'json',
				 colModel: [
				 { name: "outcome_id", label: "id", width: 100,hidden:true },
				 { name: "outcome_name", label: "Outcome Name", width: 150},
         { name: "description", label: "Description", width: 250 ,editable:true, editrules:{date:true}},
         { name: "active", label: "Active", width: 150},
				 
			 ],
				   rowNum: 10,
					rowList: [10,20,50,100,250,500,1000],
					sortorder: "desc",
					viewrecords: true,
					gridview: true,
					rownumbers:true,
					 pager: "#outcomenamegrid",					 
			viewrecords: true,			
			});
/*Product Type Name to Uppercase Function*/
		$('.outcomename').on('keyup',function(){
		this.value = this.value.toUpperCase();
		});
/*End*/
 jQuery("#outcomenamegrid").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false}); 
/*Save Function*/
		var form=$("#prdpacksave");
		        form.parsley();
		        form.submit(function(){
				 $('input[name="_token"]').val("{{csrf_token()}}");
						var data = form.serialize();
            form.parsley().validate();
					var url="{{ URL::to('outcomenamesave') }}";
					//alert("fjg");
					 if(form.parsley().isValid()){
					$.post(url, data, function(data1)
                 {
					       var status = data1.status;
                 var msg    = data1.message;
						   notyMsg('success',"<i class='' style='font-size:16px'></i>"+msg+" !!!");
						   // var sgrid = $("#outcomenamegrid")[0];
         //                   sgrid.triggerToolbar();

// jQuery("#outcomenamegrid").jqGrid('filterToolbar',options);
 jQuery("#outcomenamegrid").jqGrid("filterToolbar");  
						  $('.outcomename').val('');
						  $('.description').val('');
						  $('.active').val('');
						  $('.clearsearch').trigger('click');
						  $('#edit_id').val('');
					  });
				 }
					return false;
				});


				/*End*/
/*Reset Function For Clearing Data in Forms*/ 
			$('.reset').click(function(){
         var form=$("#prdpacksave");
				$(':input','#prdpacksave')
			  	.not(':button, :submit, :reset')
			  	.val('')
			  	.prop('checked', false);
			  	$('.active').select2().val('Yes').change();
				   });
/*End*/
showcolumn('outcomenamegrid');

/*purpose:clear search the jqgrid*/
  $(".clearsearch").click(function()
  {    
    var grid = $("#outcomenamegrid");
    grid.jqGrid('setGridParam',{search:false});
    var postData = grid.jqGrid('getGridParam','postData');
    $.extend(postData,{filters:""});
    grid.trigger("reloadGrid",[{page:1}]);
    $('input[id*="gs_"]').val("");
    $('select[id*="gs_"]').select2('val',['']);
  });
  /*end*/


/*Grid For Displaying Product Type Datas in Table*/

								});




/*Edit Function*/
		$('.Edit').on('click',function(){
      var form=$("#prdpacksave");
      //alert("rohi");
   form.parsley().destroy();
			var gr = jQuery("#outcomenamegrid").jqGrid('getGridParam','selrow');
			var id = jQuery("#outcomenamegrid").jqGrid ('getCell', gr, 'outcome_id');
			var outcome_name = jQuery("#outcomenamegrid").jqGrid ('getCell', gr, 'outcome_name');
			var description = jQuery("#outcomenamegrid").jqGrid ('getCell', gr, 'description');
            var active = jQuery("#outcomenamegrid").jqGrid ('getCell', gr, 'active');
                       
			  if( gr )
			  {
				  var url="{{URL::to('outcomeeditchk')}}/"+id;
          $.get(url,form,function(data)
          {
            if(data==1)
					{
					notyMsg("info","Outcome Name Already Used In Some Where.Unable to Edit");
					}else
					 {
					 $('#outcome_name').val(outcome_name);
					 $('#description').val(description);
           $('#active').val(active).select2();
					 $('#edit_id').val(id);  
					  }
				   });
			  }else
        {
         notyMsg("info","Please Select Row");
          }
		});


 $(document).on('click',".exportpdf",function() {
   	$("#outcomenamegrid").jqGrid('exportToPdf', {
  title: null,
  orientation: 'portrait',
  pageSize: 'A4',
  description: null,
  onBeforeExport: null,
  download: 'download',
  includeLabels : true,
  includeGroupHeader : true,
  includeFooter: true,
  fileName : "Product Type.pdf",
  mimetype : "application/pdf"  
});
   });
  $(document).on('click',".exportexcel",function() {
				$("#outcomenamegrid").jqGrid("exportToExcel",{
					includeLabels : true,
    					includeGroupHeader : true,
    					includeFooter: true,
    					fileName : "Product Type.xlsx"
    					
				})
	
});    

		/*Delete Function*/
		 $("#delete").click(function(){
			 var gr = jQuery("#outcomenamegrid").jqGrid('getGridParam','selrow');
			var id = jQuery("#outcomenamegrid").jqGrid ('getCell', gr, 'outcome_id');
			if(gr )
			{
          swal({
                title: "Are you sure?",
                text: "You want to delete!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnCancel: !1
            },function(e)
               {
                 if(e == true)
			              {
                      var url ="{{ URL::to('outcomedelete') }}/" +id;
                      $.get(url,function(data)
                      {
                         var data = $.trim(data);
                         
                        if(data =='0')
                          {
                            notyMsg('success','Deleted Successfully!!!');
                            $("#outcomenamegrid")[0].triggerToolbar();
                            $('.clearsearch').trigger('click');
                            $('#outcome_name,#description').val('');
                            $(".reset").trigger('click');
                            $('.active').select2().val('Yes').change();
                          }
                          if(data =='1')
		                  {
			                  notyMsg('Info',"You Can't Delete,Outcome Name Is Used in SomeWhere!!!");
			                  $('.clearsearch').trigger('click');
		                  }
                      });
                    }
                    else
                    {
                      $('.apply').css('display','none');
                      swal("Cancelled");
                      $('.clearsearch').trigger('click');
                    }
               })
               $('.apply').css('display','none');
        }else
      	{
	        notyMsg("info","Please Select Row");
      	}
		 });
/*End*/
				</script>
 @endsection 