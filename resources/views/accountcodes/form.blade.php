@extends('layouts.header')
@section('content')


<style type="text/css">
@media  only screen and (min-width: 1500px) {
    .bulk_line_no{width: 350px !important;}
    .bulk_account_code{width: 350px !important;}
    .bulk_account_code_meaning {width: 350px !important;}
    .bulk_description{width: 400px !important;}
    .bulk_active{width: 400px !important;}   
}

.bulk_line_no{width:200px;}
.bulk_account_code{width:200px;}
.bulk_account_code_meaning {width:200px;}
.bulk_description{width:200px;}
.bulk_active{width:200px;} 
</style>

	

<div class="ajaxLoading"></div>


	<form action="" method="post" class="acc_code_form" id="acc_code_form" data-parsley-validate>
	<input type="hidden" value="" name="savestatus" id="savestatus" />
{{ csrf_field()}}

<div class="card">
<div class="card-header">
<h2> Account Codes</h2> 
<span class="ui_close_btn"><a href="{{URL::to('accountcodes')}}" class="collapse-close pull-right btn-danger"></a></span>
</div>
	
<div class="card-body card-block">



	<!------------------------------------- Body content start here ---------------------------->
		<div class="row">
			<div class="col-md-12">
				

				
				<div class="ziehharmonika">
			<h3>Section One</h3>
			<div>
				<div class="row">
					
	<div class="col-md-12">
		<div class="form-group  col-md-4 row">
			<label for="inputIsValid" class="form-control-label col-md-5">Account Class Name</label>
			<div class="col-md-7">
                            <input class="form-control account_codes_hdr_id" id="account_codes_hdr_id" name="account_codes_hdr_id" size="16" type="hidden" value="{{$account_codes_hdr_id}}">
                            <select name='account_class_id' rows='5' class='form-control account_class_id' style="pointer-events:none;">
                             {!! $account_class_id !!}
                        </select>
			</div>
			
		</div>
            <div class="form-group  col-md-4 row">
                            <label for="inputIsValid" class="form-control-label col-md-5">Main Account Code</label>
                            <div class="col-md-7">
                                <input type="text" id="main_account_code" name="main_account_code" class="form-control 	main_account_code" required value="{{$main_account_code}}" readonly>
                            </div>
                        </div>  
            <div class="form-group  col-md-4 row">
                            <label for="inputIsValid" class="form-control-label col-md-5">Active</label>
                            <div class="col-md-7">
                                <select name="active" class="form-control active">
                                    <option value="Yes">Yes</option>
                                    <option value="No" >No</option>
                                </select>
                            </div>
                        </div>

	</div>	
				</div>
			</div>
			
		</div>
				
			</div>
		</div>
	<!------------------------------------------------------------------------------------------>

<div class="row">
    <div class="col-md-12">
        <!------------------------- clone row start -------------------------------->
        <div id="add-del-buttons" style="display:none">
            <input type="button" id="btnAdd" class="onclickrel1" value="[ + ] add to this form" rel=".rcopy">
            <input type="button" id="btnDel" value="[ - ] remove the section above">
        </div>
        <a href="javascript:void(0);" class="add_row additem"><i class="fa fa-plus"></i> New Item</a>
        <a style="display:none" href="javascript:void(0);" id="btnAdd" class="onclickrel add_row additem" rel=".rcopy"><i class="fa fa-plus"></i> Relcopy</a>

        <!------------------------- clone row End-------------------------------->

        <!-------------------------Linedata -------------------------------->
        

                <div id="preview-area" class="chandru">
                 <table class="overflow-y preview acc_codes_table">

                <thead>
                    <tr>
                        <th></th>
                        <th>Line No</th>
                        <th>Account Code</th>
                        <th>Account Code Meaning</th>
                        <th>Description</th>
                        <th>Active</th>
                        <th></th>
                    </tr>

                </thead>
                
                <tbody class="acc_codes_lines_body">
                    <?php if(count($linedata)>=1) { ?>
                        @foreach($linedata as $key=>$value)

                        <tr class="rcopy clone">
                            <td>
                                <input type="hidden" name="bulk_account_codes_line_id[]" class="form-control input-sm bulk_account_codes_line_id" value="{{ $value->account_codes_line_id }}">
                            </td>
                            <td></td>
                            <td>
                                <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no"  value="" readonly="readonly">
                            </td>
                            <td >
                                <input type="text" name="bulk_account_code[]" class="form-control input-sm bulk_account_code"  value="{{ $value->account_code }}" required>
                            </td>
                           <td >
                                <input type="text" name="bulk_account_code_meaning[]" class="form-control input-sm bulk_account_code_meaning "  value="{{ $value->account_code_meaning }}" required>
                            </td>
                            <td >
                                <input type="text" name="bulk_description[]" class="form-control input-sm bulk_description"  value="{{ $value->description }}">
                            </td>
                            <td >
                                <select name="bulk_active[]" class="select2 bulk_active " >
                                    <option value="">-- please select --</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </td>      
                            
                            <td>
                                <!--<a class="remove btn-xs btn-danger">-</a>-->
                                 <a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
                                <input type="hidden" name="counter[]">
                            </td>
                        </tr>
                        @endforeach
                       
                        <?php } if(count($linedata) < 1 ) { ?>
                            <tr class="rcopy clone">
                                <td>
                                    <input type="hidden" name="bulk_account_codes_line_id[]" class="form-control input-sm bulk_account_codes_line_id" value="">
                                </td>
                                 <td>
                                    <input type="hidden" name="bulk_account_codes_hdr_id[]" class="form-control input-sm bulk_account_codes_hdr_id" value="{{$account_codes_hdr_id}}">
                                </td>
                                <td >
                                    <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="">
                                </td>
                                <td >
                                    <input type="text" name="bulk_account_code[]" class="form-control input-sm bulk_account_code" value="" required>
                                </td>
                                <td >
                                    <input type="text" name="bulk_account_code_meaning[]" class="form-control input-sm bulk_account_code_meaning " value="" required>
                                </td>
                                <td >
                                    <input type="text" name="bulk_description[]" class="form-control input-sm bulk_description" value="">
                                </td>
                                <td >
                                    <select name="bulk_active[]" class="select2 bulk_active ">
                                        <option value="">-- please select --</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>

                                    </select>
                                </td>
                                
                                <td>
                                    <a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
                                    <input type="hidden" name="counter[]">
                                </td>
                            </tr>
                            <?php } ?>
                </tbody>
            </table>
            <input type="hidden" name="enable-masterdetail" value="true">
            <div class="col-lg-12 col-md-12">
                <div class="form-group text-center">
                    <input type="hidden" name="submit_type" class="submit_type" id="submit_type">
                    <!--<button type="button"  name="apply" class='btn applychanges saveform' value="APPLYCHANGES" >Apply Changes</button>-->
                    <button type="button" id="save" class="btn save saveform" value="SAVE">Save</button>
                    <a class='btn cancel' onclick="location.href ='{{url('accountcodes')}}'">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</div>


	
			

<!-------------------------Linedata End-------------------------------->	
	
    </div>
	


</div>

	</form>
<button type="button" class="btn btn-info btn-lg open_modal" data-toggle="modal" style="display:none;" data-target="#myModal">Open Modal</button>	
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Account</h4>
        </div>
        <div class="modal-body">
           <div class="form-group  " > 
					<label for="Job Order No" class=" control-label col-md-4 text-left">
					Account Head
                                        </label>
					<div class="col-md-6">
                                            <input type="text" class="form-control account_head" readonly="true">
                                            <input type="hidden" class="parent_id" name="parent_id">
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div>
              <div class="form-group  " > 
					<label for="Job Order No" class=" control-label col-md-4 text-left">
					Account Name
                                        </label>
					<div class="col-md-6">
                                            <input type="text" name="accountclass_name" class="form-control accountclass_name">
					 </div> 
					 <div class="col-md-2">
					 	
					 </div>
				  </div>
        </div>
        <div class="modal-footer">
           <button type="button" class="btn btn-success add_account"  data-dismiss="modal">Add</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

	<script>		
	$(document).ready(function(){
$(document).on('click','.add_row',function(){
            $('.onclickrel').trigger('click');
             changeclassfields();
    });
        
            $(".account_group").click(function(){
            $(".parent_id").val();
            var id=$('.account_class_id').val();
            $(".account_head").val($(".acc_name"+id).text().trim().replace('ADD',''));
            $(".open_modal").trigger('click');
            });
        
        
    
              $('#savestatus').val(''); 
		$(document).on('click','.saveform',function(){
		   var btnval		= $(this).val();
			
			if(btnval == 'APPLYCHANGES')
                var savestatus = 'APPLY CHANGES';
			else(btnval == 'SAVE')
                var savestatus = 'SAVE';
			
			 $('#savestatus').val(savestatus);
			 $('.submit_type').val("save");
			
			 var url		= "{{ URL::to('accountcodessave') }}"; 
			 validationrule('acc_code_form');
			var red_url = "{{ URL::to('accountcodes') }}";
			 var formdata	= $('#acc_code_form').serialize();
			 var form = $('#acc_code_form');
			 if(btnval != 'APPLYCHANGES'){ 
			     form.parsley().validate();
		          var form = $('#acc_code_form');
		          form.parsley().validate();
				  if(form.parsley().isValid()){
                                      //   $('.ajaxLoading').show();

				    $.post(url,formdata,function(data)	
					  {
					      var status      = data.status;
						  var msg         = data.message;
						    var id          = data.id;
//						var edit_url	= "{{ URL::to('accountcodesedit') }}/"+id;
						                                         $('.ajaxLoading').show();

						if(btnval !='SAVE')
                              {                        
                                notyMsg(status,msg);
                                setTimeout(function(){ 
//                                window.location.href=edit_url;
                                }, 1500);
                              }
                            else{
                                notyMsg(status,msg);
                                setTimeout(function(){
                                window.location.href=red_url;
                                }, 1500);
                               }
					  });
				 }
			  }
			else
			{
			   $.post(url,formdata,function(data)
				{

						var status = data.status;
						var msg    = data.message;
						var id     = data.id;
						var edit_url	="{{ URL::to('accountcodescreate') }}/"+id;
								notyMsg(status,msg);
								setTimeout(function(){
								window.location.href=edit_url;
								}, 1500);

					});
				}
			
		});	
		
	



$(document).on('click','.remove',function(){
	var rowCount = $('.acc_codes_table tbody tr').length;
	if(rowCount > 1){
	$($(this).closest("tr")).remove();
	changeclassfields();
	}
	else{
		alert("You Can't Delete Atleast One row should be there");
	}
});
		
var index = $('.clone').closest('tr').index();
changeclassfields();
		
	});
function changeClassName(className)
{ 
$('.' + className).each(function (index)
{
if (className == "bulk_line_no")
{
$(this).val(index + 1).attr("readonly", 1);
}

$(this).removeClass(className + '0');
$(this).addClass(className + index);
});
}

function removeClass(className)
{
	var rowCount = $('.acc_codes_table tbody tr').length;
	for(var i=0;i<=rowCount;i++)
	{
	$('.acc_codes_table tbody tr').find('.'+className).removeClass(className+i);
	}
	$('.' + className).each(function (index)
	{
		if (className == "bulk_line_no")
		{
		$(this).val(index + 1).attr("readonly", 1);
		}
		$(this).addClass(className + index);
	});

}
function changeclassfields(){
changeClassName('bulk_account_codes_line_id');
changeClassName('bulk_line_no');
changeClassName('bulk_account_code');
changeClassName('bulk_account_code_meaning');
changeClassName('bulk_description');
changeClassName('bulk_active');
}
	</script>
@include('layouts.php_js_validation')
@endsection