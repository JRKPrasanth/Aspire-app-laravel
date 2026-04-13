@extends('layouts.header')
@section('content')



<style>
.company_provision .parsley-errors-list.filled{
display: none;
}
.allowance_id{
  width:400px;
}
.account_structure_id{
    width:400px; 
}
</style>

<span class="ui_close_btn"></span>
<h2 class="heads">HRMS ALLOWANCE ACCOUNT SETTINGS<span class="ui_close_btn"><a href="{{ url('hrmsallowancesettings') }}" class="collapse-close pull-right btn-danger" ></a></span></h2>
<div class="card">

            <form   id="save" enctype="multipart/form-data" data-parsley-validate>
                <div class="card-body card-block">
                    <input type="hidden" name="edit_id" value="{{$account_allowance_setting_id}}" id="edit_id" />
                   
                {{ csrf_field()}}
                <div class="row">
                <div class="form-group col-md-5">
                                <label for="inputIsValid" class="form-control-label col-md-5"><span class="req">*</span>Department Name</label>
                                         <div class="col-md-7">
                                    <select id="department_id" name="department_id" class="select2 department_id"  required>
                                    </select>
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
                                        <th>Allowance</th>
                                        <th>Account Structure</th>
                                        <th>&nbsp;</th>
                                      </tr>
                                </thead>
                                <tbody>
									
									@if(count($line_data)>0)
										@foreach($line_data as $key => $value)
									<tr class="clone clonedInput rcopy">
										<input type="hidden" name="account_allowance_setting_line_id[]" class="account_allowance_setting_line_id" id="account_allowance_setting_line_id" value="{{$value->account_allowance_setting_line_id}}" />
										<td>
											<select  class="select2 allowance_id" id="allowance_id"  name="allowance_id[]"  required />
                                                                                        {!!$value->allowance_id!!}
                                                                                        </select>
										</td>
										<td>
											<select  id="account_structure_id" name="account_structure_id[]"  class="select2 account_structure_id" required />
                                                                                        {!!$value->account_structure_id!!}
                                                                                        </select>
										</td>
										
										<td >                        
											<a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
											<input type="hidden" name="counter[]">
										</td>  
									</tr>
									@endforeach
									@else
									<tr class="clone clonedInput rcopy">
										<input type="hidden" name="account_allowance_setting_line_id[]" class="account_allowance_setting_line_id" id="account_allowance_setting_line_id" value="" />
										<td>
											<select  class="select2 allowance_id" id="allowance_id"  name="allowance_id[]"  required />
                                                                                        {!!$allowance_id!!}
                                                                                        </select>
										</td>
										<td>
											<select  id="account_structure_id" name="account_structure_id[]"  class="select2 account_structure_id" required />
                                                                                        {!!$account_structure_id!!}
                                                                                        </select>
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
                var condition2="  parent_class_id=0";
		// company jcombo and for session company select
		$("#department_id").jCombo("{{ URL::to('jcomboform?table=m_department_lines_t:department_line_id:sub_department_code|sub_department_name') }}&order_by=department_line_id asc"+'&parent='+condition2,
                {selected_value:'{{$department_id}}'});
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
				$.post("{{ URL::to('hrmsallowancesettingssave')}}", data, function(data)
                {
                    if(data == 1){
                        
						notyMsgs('success','HRMS Allowance Settings Saved Successfully');
						setTimeout(function(){
							var url = "{{URL::to('hrmsallowancesettings')}}";
							window.location.href=url;
						}, 1300);
                    }
                    else if(data == 2)
                    {
                        notyMsgs('success','HRMS Allowance Settings Updated Successfully');
						 var url = "{{URL::to('hrmsallowancesettings')}}";
						window.location.href=url;
                    }
                });
                }

            });
	/** Save data End **/
		
		
        $(document).on('click','.cancel',function()
        {
           
            var url="{{ URL::to('hrmsallowancesettings') }}";
             window.location.href=url;
        });
            
           



	});
	</script>
@include('layouts.php_js_validation')
@endsection
