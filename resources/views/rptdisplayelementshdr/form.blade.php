@extends('layouts.header')
@section('content')
<style type="text/css">

@media  only screen and (min-width: 1500px) { 

.bulk_line_no {width: 500px !important;}
	.bulk_element_name{width:500px !important}	
	.bulk_element_content{width:500px !important}	
}

.bulk_line_no {width: 200px;}
.bulk_element_name {width: 450px;}
.bulk_element_content {width: 450px;}
</style>


<?php include('tools_menu.php'); ?>

  
 <h4 class="heads">
<a role="button">
  Report Display Elements
 </a>
	  <span class="ui_close_btn"><a class="collapse-close pull-right btn-danger" onclick='location.href="{{ URL::to('reportelements') }}"'></a></span>
  </h4>

   
  
<form method="post" action=" " id="rptelements_form" class="rptelements_form" data-parsley-validate>
	<input type="hidden" value="" name="savestatus" id="savestatus" />
	{{ csrf_field() }}
	
	<div class="card">
		  <div class="card-body card-block">
			 <div class="row">
				  <div class="col-md-12">
					   <div class="row">
						   <div class="col-md-6">
							   
							   <div class="form-group row">
                                 <label for="inputIsValid" class="form-control-label col-md-4"><span style="font-style:20px;color:red;">*</span>Report Source</label>
                                    <div class="col-md-6 sel2">
										<input type="hidden" name="rpt_displayelements_hdr_id" id="rpt_displayelements_hdr_id" value="{{ $row->rpt_displayelements_hdr_id }}"> 
                                        <select name='report_source' rows='5' class='form-control report_source select2' required>
                                           {!!$row->report_source!!}
                                         </select>
                                     </div>
									<div class="col-md-2 showinline">
										<span class="showspan"><i class="fa fa-refresh jcr_report_source"></i></span>
									</div>
                               </div>
							   
							    <div class="form-group row">
                                 <label for="inputIsValid" class="form-control-label col-md-4"><span style="font-style:20px;color:red;">*</span>Set Name</label>
                                    <div class="col-md-6">
                                        <input type="text" name="set_name" class=" form-control set_name" id="set_name" value=" {!!$row->set_name!!}" required>
                                    <span class="btn btn-danger dup_name" style="display:none;"></span>
									</div>
									<div class="col-md-2 showinline">
									</div>
                               </div>
							   
							    <div class="form-group row">
                                 <label for="inputIsValid" class="form-control-label col-md-4">Description</label>
                                    <div class="col-md-6">
                                        <input type="text" name="description" class=" form-control description" id="description"value=" {!!$row->description!!}">
                                     </div>
									<div class="col-md-2 showinline">
									</div>
                               </div>
							   
						   </div>
						   <div class="col-md-6">
							  
							<div class="form-group row">
                               <label for="start_date" class="form-control-label col-md-4">Start Date</label>
                                 <div class="col-md-6">
                                   <input name="start_date" type="text" id="start_date" rows='5' class='form-control start_date ' data-link-format="yyyy-mm-dd" value="{{$row->start_date}}">
                                </div>
                                 <div class="col-md-2">
								 </div>
                            </div>
							   
							   
							   
							<div class="form-group row">
                               <label for="end_date" class="form-control-label col-md-4">End Date</label>
                                 <div class="col-md-6">
                                   <input name="end_date" type="text" id="end_date" rows='5' class='form-control end_date ' data-link-format="yyyy-mm-dd" value="{{$row->end_date}}">
                                </div>
                                 <div class="col-md-2">
								 </div>
                            </div>
							   
						   </div>
					  </div>
				  </div>
			  </div>


			  <div class="row">
				<div class="col-md-12">
				<hr class="xlg">
				</div>
				</div>



			  
			 <div class="row">
				  <div class="col-md-12" style="padding-top: 8px;">
					  <a href="javascript:void(0);"  class="add_row additem"  rel=".rcopy"><i class="fa fa-plus"></i> New Item</a>
					  <div id="preview-area" class="chandru">
						  <table class="overflow-y preview rpt_disp_table">
							  <thead>
								<tr>
								<th>Line No</th>
								<th>Element Name</th>
								<th>Element Content</th>
								<th></th>
								</tr>
								</thead>
					  <tbody class="rpt_disp_lines_body"> <?php //dd($linedatas); ?>
						  <?php if(count($linedatas)>=1) { ?>
								@foreach($linedatas as $key=>$value)
				        <tr class="rcopy clone">
							
						  <td>
					         <input type="hidden" name="bulk_rpt_displayelements_line_id[]" class="form-control input-sm bulk_rpt_displayelements_line_id" value="{{ $value->rpt_displayelements_line_id }}">
						  </td>
							
						<td>
						 <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="{{ $key + 1 }}" readonly="readonly" >
						</td>
					    <td>		
					    <select name="bulk_element_name[]" id="bulk_element_name" class="select2 bulk_element_name" required="required">
							  <option value="">--select--</option>
							  <option <?php if($value->element_name =="TERMS&CONDITIONS") { echo "selected"; } else { echo ""; } ?> value="TERMS&CONDITIONS">TERMS&CONDITIONS</option>
							  <option <?php if($value->element_name =="HEADER") { echo "selected"; } else { echo ""; } ?> value="HEADER">HEADER</option>
							  <option <?php if($value->element_name =="FOOTER") { echo "selected"; } else { echo ""; } ?> value="FOOTER">FOOTER</option>
						 </select>
							</td>
						  <td>
							  <textarea name="bulk_element_content[]" class="bulk_element_content" id="bulk_element_content" required >{{ $value->element_content }}</textarea>
						  </td>
							
							 <td><a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
                                <input type="hidden" name="counter[]">
                               </td>
						</tr> 
						  
							  @endforeach
						  <?php } else { ?>
                              <tr class="rcopy clone">
							
						  <td>
					         <input type="hidden" name="bulk_rpt_displayelements_line_id[]" class="form-control input-sm bulk_rpt_displayelements_line_id" value="">
						  </td>
							
						<td>
						 <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="1" readonly="readonly" >
						</td>
					     <td>
                         <select name="bulk_element_name[]" id="bulk_element_name" class="select2 bulk_element_name" required=true>
							  <option value="">--select--</option>
							  <option  value="TERMS&CONDITIONS">TERMS&CONDITIONS</option>
							  <option  value="HEADER">HEADER</option>
							  <option value="FOOTER">FOOTER</option>
						 </select>
                         </td>
						  <td>
							  <textarea name="bulk_element_content[]" class="bulk_element_content" id="bulk_element_content" required=true></textarea>
						  </td>
							
							 <td><a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
                                <input type="hidden" name="counter[]">
                              </td>
						</tr> 
						  <?php } ?>
					  </tbody>
						  </table>
					  </div>
				 </div>
			 </div>
			  <div class="row">
				<div class="col-md-12">
				<hr class="xlg">
				</div>
				</div>

				
		  </div>
		
		<div class="row">
                  <div class="col-lg-12 col-md-12">
                    <div class="form-group text-center">
                       <input type="hidden" name="submit_type" class="submit_type" id="submit_type">
                        <button name="submit" type="button" class="btn save saveform" value="SAVE">Save</button>
                          <a class='btn cancel' onclick='location.href="{{ URL::to('reportelements') }}"'>Cancel</a>
                  </div>
             </div>
         </div>
		 
	</div>
</form>

<script>
	$(document).ready(function(){
		

$(document).on('click','.jcr_report_source',function()
{
	var lookupcode="lookup_type='REPORT_SOURCE'";
$(".report_source").jCombo("{{ URL::to('jcomboformlogin?table=a_lookuplines_t:lookuplines_id:lookup_code') }}&parent="+lookupcode+"&order_by=lookup_code asc",
{selected_value:""});
});		
 $(document).on('click','.saveform_old',function() {
validationrule('rptelements_form');
  var form=$("#rptelements_form");
    form.parsley();
	
  $('input[name=_token]').val("{{csrf_token()}}");
  var data = form.serialize();

        form.parsley().validate();
	 if(form.parsley().isValid()){
var url="{{ URL::to('rptdisplayelementsave') }}";
      
$.post(url, data, function(data1)
{
 var status = data1.status;
 var msg    = data1.message;
 notyMsg('success',"<i class='' style='font-size:16px'></i>"+msg+" ");

    });

	 }
      });
		
		
		
		     $(document).on('click','.saveform',function()
    {

            var btnval      = $(this).val();

            var url         ="{{ url('rptdisplayelementsave') }}";

            var red_url     ="{{ url('reportelements') }}";
            validationrule('rptelements_form');
            var formdata    = $('#rptelements_form').serialize();
            var form = $('#rptelements_form');

                    var form = $('#rptelements_form');
                    form.parsley().validate();
				 change_date();
                    if (form.parsley().isValid())
                    {

                    $.post(url,formdata,function(data)
                    {

                    var status = data.status;
                    var msg    = data.message;


                            notyMsg(status,msg);
                            setTimeout(function(){
                            window.location.href=red_url;
                            }, 1500);

                    });
                    }

    });
		 
	$('.add_row').click(function(){
				 var form = $('#rptelements_form');
                 form.parsley().destroy();
			changeclassfields();
			});
     var data ="{{\Session::get('j_date_format')}}";
			$(".add_row").relCopy(data);
		changeclassfields();
			 
	$('.add_row').click(function(){
			changeclassfields();
			});
 var dateToday = new Date();
    $('.start_date').datepicker({
      dateFormat: data,
      minDate: dateToday,
      maxDate: null,
       onSelect: function(selected) {
        $('#end_date').datepicker("option", "minDate",  $(".start_date").datepicker('getDate') )
       }
    });

    $('.end_date').datepicker({
      dateFormat: data,
      minDate: dateToday,
      maxDate: null,
    }); 
			
		
$(document).on('click','.remove',function()
{
    var index = $(this).closest('tr').index();
    var rowCount = $('.rpt_disp_table tbody tr').length;
    if(rowCount > 1)
    {
        $($(this).closest("tr")).remove();
                removeclassfields();
    }
    else
    {
        notyMsg('info',"You Can't Delete Atleast One row should be there");
    }
         
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
			var rowCount = $('.rpt_disp_table tbody tr').length;
			for(var i=0;i<=rowCount;i++)
			{
			$('.rpt_disp_table tbody tr').find('.'+className).removeClass(className+i);
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
		
		
		function removeclassfields(){
        removeClass('bulk_line_no');
        removeClass('bulk_element_name');
        removeClass('bulk_element_content');
      }
		
		function changeclassfields(){
    changeClassName('bulk_line_no');
	changeClassName('bulk_element_name');
	changeClassName('bulk_element_content');
    }
		
	
		
	});
</script>

@include('layouts.php_js_validation')
@endsection