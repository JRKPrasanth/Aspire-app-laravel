@extends('layouts.header')
@section('content')



<style>
.company_provision .parsley-errors-list.filled{
display: none;
}
</style>

<span class="ui_close_btn"></span>
<h2 class="heads">INVESTMENT TYPE<span class="ui_close_btn"><a href="{{ url('investmenttype') }}" class="collapse-close pull-right btn-danger" ></a></span></h2>
<div class="card">

            <form   id="save" enctype="multipart/form-data" data-parsley-validate>
                <div class="card-body card-block">
                    <input type="hidden" name="edit_id" value="{{$inv_id}}" id="edit_id" />
                   
                {{ csrf_field()}}
                <div class="row">
                <div class="form-group col-md-5">
                                <label for="inputIsValid" class="form-control-label col-md-5"><span class="req">*</span>Name</label>
                                         <div class="col-md-7">
                                    <input type="text"  id="inv_name" name="inv_name" class="form-control inv_name" value="{{$inv_name}}" required>
                                </div>
                        </div>
                        <div class="form-group col-md-5">
                                <label for="inputIsValid" class="form-control-label col-md-5"><span class="req">*</span>Limit</label>
                                <div class="col-md-7">
                                    <input type="text"  id="inv_limit" name="inv_limit" class="form-control inv_limit" value="{{$inv_limit}}" required>
                                </div>
                        </div>
                 
                </div>
                
                        <div class="custom_div row">
                            <fieldset><legend></legend>   

                            <div class="col-md-12">

                                 <a href="javascript:void(0);" class="add_row additem newitem" rel=".rcopy">
                            <i class="fa fa-plus"></i> New Item</a>

<div id="preview-area" class="linesscroll">
<table class="overflow-y preview company_provision">

                               
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>&nbsp;</th>
                                      </tr>
                                </thead>
                                <tbody>
									
									@if(count($line_data)>0)
										@foreach($line_data as $key => $value)
									<tr class="clone clonedInput rcopy">
										<input type="hidden" name="inv_lines_id[]" class="inv_lines_id" id="inv_lines_id" value="{{$value->inv_lines_id}}" />
										<td>
											<input type="text" class="form-control name" id="name"  name="name[]" value="{{$value->name}}" required />
										</td>
										<td>
											<input type="text"  id="description" name="description[]" value="{{$value->description}}" class="form-control description" required />
										</td>
										
										<td >                        
											<a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
											<input type="hidden" name="counter[]">
										</td>  
									</tr>
									@endforeach
									@else
									<tr class="clone clonedInput rcopy">
										<input type="hidden" name="inv_lines_id[]" class="inv_lines_id" id="inv_lines_id" value="" />
										<td>
											<input type="text" class="form-control name" id="name"  name="name[]" value="" required/>
										</td>
										<td>
											<input type="text"  id="description" name="description[]" value="" class="form-control description" required/>
										</td>
									
										<td >                        
											<a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
                            <input type="hidden" name="counter[]">
										</td>  
									</tr>
									
									@endif
								</tbody>
                            </table>
								</div>
								
                           </div>
                    </fieldset>
                    

                    <style>
                        .icheckbox_square-green,
                    .iradio_square-green {
                        z-index:99999;
                    }
                   .dup_name{

}
                    </style>
                    </div>
                <br>
                       
                <div class="row">
                    <div class="col-md-12 text-center ">
                        <button type="button" class="btn  save">Save</button> 
                        <button type="button"  class="btn cancel" >Cancel</button>
                    </div>
                </div>
                </div>
            </form>

</div>







	<script>


  $(".clearsearch").click(function()
  {
    var grid = $("#grid1");
    grid.jqGrid('setGridParam',{search:false});
    var postData = grid.jqGrid('getGridParam','postData');
    $.extend(postData,{filters:""});
    grid.trigger("reloadGrid",[{page:1}]);
    $('input[id*="gs_"]').val("");
    
  });

  
	$(document).ready(function(){

            $('.reset').click(function()
            {
                $('#inv_limit').val('');
                $('#name').val('');
                $('#description').val('');
                $('#inv_id').val('');
                $('.dup_name').hide();
            });
            
            $(".add_row").relCopy();
     
        
  
/** Remove table tr add row  Start **/
    $(document).on('click','.remove',function()
{
    var index = $(this).closest('tr').index();
    var rowCount = $('.company_provision tbody tr').length;
    if(rowCount > 1)
    {
        $($(this).closest("tr")).remove();
       
        
    }
    else
    {
        notyMsg('info',"You Can't Delete Atleast One row should be there");
    }
});


function removeClass(className)
{
    var rowCount = $('.company_provision tbody tr').length;
    for(var i=0;i<=rowCount;i++)
    {
    $('.company_provision tbody tr').find('.'+className).removeClass(className+i);
    }
    $('.' + className).each(function (index)
    {
        if (className == "inv_lines_id")
        {
        $(this).val(index + 1).attr("readonly", 1);
            
        }
        $(this).addClass(className + index);
    });
}



 $(document).on('keypress', '#name,#inv_name', function(ev){
        var regex = new RegExp("^[a-z,A-Z.]+$");
                var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                if (regex.test(str)) {
                    return true;
                }
                ev.preventDefault();
                return false;
    });
/** Remove Class  End **/


   $(document).on('keypress', '.inv_limit', function(ev){
            var regex = new RegExp("^[0-9]+$");
                    var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                    if (regex.test(str)) {
                        return true;
                    }
                    ev.preventDefault();
                    return false;
        });




/** Save data Start **/
  $(document).on('click','.save',function(){
			
                var data;
                data = $("#save").serialize();
           
                var form = $('#save');
				form.parsley().validate();
                if (form.parsley().isValid())
				{
				$.post("{{ URL::to('investmenttypesave')}}", data, function(data)
                {
                    if(data == 1){
                        
						notyMsgs('success','Investment Type Saved Successfully');
						setTimeout(function(){
							var url = "{{URL::to('investmenttype')}}";
							window.location.href=url;
						}, 1300);
                    }
                    else if(data == 2)
                    {
                        notyMsgs('success','Investment Type Updated Successfully');
						 var url = "{{URL::to('investmenttype')}}";
						window.location.href=url;
                    }
                });
                }

            });
	/** Save data End **/
		
		
        $(document).on('click','.cancel',function()
        {
           
            var url="{{ URL::to('investmenttype') }}";
             window.location.href=url;
        });
            
           



	});
	</script>
@include('layouts.php_js_validation')
@endsection
