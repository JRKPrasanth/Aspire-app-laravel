@extends('layouts.header')
@section('content')


<span class="ui_close_btn"></span>
<h2 class="heads">EMPLOYEE CONVERSION
<span class="ui_close_btn"><a class="collapse-close pull-right btn-danger" onclick='location.href="{{ URL::to("empuploaddoc") }}"'></a></span>
</h2>

    

<div class="card">

            <form  action=""  id="save" enctype="multipart/form-data">
                <div class="card-body card-block">
                    <input type="hidden" name="edit_id" value="" id="edit_id" />
                    <input type="hidden" name="employee_id" value="{{$employee_id}}" id="employee_id"/>
                {{ csrf_field()}}
                <div class="row">

                        <div class="form-group col-md-4">
                                <label for="inputIsValid" class="form-control-label col-md-5">Employee Name</label>
                                <div class="col-md-7">
                                    <select  id="employee_name" name="employee_name" class="select2 employee_name"  required>
                                        {!! $employee_name !!}
                                    </select>
                                </div>
                        </div>
                        <div class="form-group col-md-4">
                                <label for="inputIsValid" class="form-control-label col-md-5">Mobile</label>
                                <div class="col-md-7">
                                    <input type="text" id="mobile" name="mobile" class="form-control mobile" value="{{$mobile_number}}" required>
                                </div>
                        </div>
                        <div class="form-group col-md-4">
                                <label for="inputIsValid" class="form-control-label col-md-5">Mail</label>
                                <div class="col-md-7">
                                    <input type="text" id="mail" name="mail" class="form-control mail" value="{{$email}}" required>
                                </div>
                        </div>
                </div>
                <br>
                        <div class="custom_div col-md-12">
                            <fieldset><legend>Company's Provision</legend>   

                            <div class="col-md-12">
                                <table class="col-md-12 table table-bordered company_provision">
                                <thead>
                                    <tr>
                                        <th>DOCUMENT NAME</th>
                                        <th>DATE OF PROVISION</th>
                                        <th>REMARKS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php 
                                   
                                    $emp_remark = isset($company_remarks) && !empty($company_remarks) ? json_decode($company_remarks) : 0;
                                    $count =  isset($company_provision_data) && !empty($company_provision_data) ?  json_decode($company_provision_data) : 0;
                                    @endphp
                                    @if($count>0)
                                 
                                    @foreach ($company_provision as $key => $value)
									  
                                   <?php
									
                                    $temp_count = count($count);
                                    
                                  //  dd($temp_count);
									 foreach ($count as $key1 => $value1) { 
                                        if($company_provision[$key]->id == $count[$key1][0])
                                        {

                                            $checked= 'checked';
                                            $date = $count[$key1][1];
                                            $remarks = $emp_remark[$key1][1];
                                        }
                                        else
                                        {
                                            $checked ='';
                                            $date ='';
                                            $remarks ='';
                                        }
									 }
                                    
                                     
                                        
                                    ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" style="cursor:pointer;" id="company_doc" {{$checked}} name="company_doc{{$key}}" value="{{$value->id}}">
												
                                                {{$value->document}}
                                                 <br>
                                            </td>
                                            <td>
                                                <div class="input-group m-b  " style="width:200px !important;">
                                                    <input type="text"  id="provision_date{{$key}}" name="provision_date[]" value="{{$date}}" class="form-control datepicker provision_date{{$key}}" style="border-radius: 5px;">
                                                </div>
                                            </td>
                                            <td>
                                                <textarea class="company_remarks form-control" rows="3" cols="3" name="company_remarks[]" id="company_remarks">{{$remarks}}</textarea>
                                           </td>
                                        </tr>
                                       
                                    @endforeach
                                    @else
                                    @foreach ($company_provision as $key => $value)
                                    <tr>
                                        
                                            <td>
                                                <input type="checkbox" style="cursor:pointer;" id="company_doc" name="company_doc{{$key}}" value="{{$value->id}}">

                                                {{$value->document}}
                                                 <br>
                                            </td>
                                            <td>
                                                <div class="input-group m-b  " style="width:200px !important;">
                                                    <input type="text"  id="provision_date{{$key}}" name="provision_date[]" class="form-control datepicker provision_date{{$key}}" value="" style="border-radius: 5px;">
                                                </div>
                                            </td>
                                            <td>
                                                <textarea class="company_remarks form-control" rows="3" cols="3" name="company_remarks[]" id="company_remarks"></textarea>
                                           </td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                            </table>
                           </div>
                    </fieldset>
                    

                    <style>
                        .icheckbox_square-green,
                    .iradio_square-green {
                        z-index:99999;
                    }

                    input,textarea{
                        border-radius: 5px;
                    }
                    </style>
                    </div>
                             <br><br>
                        <div class="employee_div col-md-12">

                            <fieldset><legend>Employee's Provision</legend>   
                        <div class="col-md-12">
                            <div class="col-md-4"></div><div class="col-md-4">
                                
                            </div><div class="col-md-4"></div>
                            <a href="javascript:void(0);" class="add_row additem newitem" rel=".rcopy">
                            <i class="fa fa-plus"></i> New Item</a>
                            <table class="col-md-12 table table-bordered employee_provision">
                                <thead>
                                    <tr>
                                        <th>DOCUMENT NAME</th>
                                        <th>FILE UPLOAD</th>
                                        <th>REMARKS</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                        <tbody>
                            @php 
                               $employee_remarks = isset($employee_remarks) && !empty($employee_remarks) ? json_decode($employee_remarks) : 0;
                            $count1 = isset($employee_provision_data) && !empty($employee_provision_data) ? json_decode($employee_provision_data) : 0;
                            @endphp
						
							   @if($count1 >0)
							
                            @foreach($count1 as $key => $value)
					
                    
                            <tr class="clone clonedInput rcopy">
                                <td>
                                    <div class="form-group col-md-8">
                                       <select style="width:'250px'" name="document[]" id="document" data-photo="" class="select2  document">
										     {!! config('global.CONT')->jCombo('m_employee_doc_check_list','id','emp_document',$count1[$key][0])!!}
                                       </select>
                                     </div>			
                                </td>
                                <td>    
                                    
                                    <div class="file_name_div file_name_div0" >
                                        <input type="file" name="file_upload[]" class="file_name" >
										
									<?php if($count1[$key][1] !='') {?>
									  <a download href="{{"../documentupload/".$employee_id."/".$count1[$key][1]}}">
                                       <button type="button"  class="lst btn download " >Download</button>
                                   </a>
									<?php } ?>
										</div> 
                                </td>
                                <td>
                                    <textarea class="employee_remarks form-control employee_remarks" rows="4" cols="3" name="employee_remarks[]" id="employee_remarks">  <?php echo $employee_remarks[$key][1]; ?></textarea>
                                </td>
                                <td>                        
                                   <a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a> <input type="hidden" name="counter[]">
                                </td>                               
                            </tr>
                            @endforeach
                            @else
                            <tr class="clone clonedInput rcopy">
                                <td>
                                    <div class="form-group col-md-8">
                                       <select style="width:'250px'" name="document[]" id="document" data-photo="" class="select2  document">
                                           {!! $employee_provision !!}
                                       </select>
                                     </div>			
                                </td>
                                <td>    
                                    
                                    <div class="file_name_div file_name_div0" >
                                        <input type="file" name="file_upload[]" class="file_name" ></div> 
                                </td>
                                <td>
                                    <textarea class="emp_remarks form-control employee_remarks" rows="4" cols="3" name="employee_remarks[]" id="employee_remarks"></textarea>
                                </td>
                                <td>                        
                                   <a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a> <input type="hidden" name="counter[]">
                                </td>                               
                            </tr>
                            @endif

                            </tbody>
                        </table>
                            
                        </div>

                      


                    </fieldset>
                    <style>
                        .icheckbox_square-green,
                    .iradio_square-green {
                        z-index:99999;
                    }
                    </style>
                    </div>
                <div class="row">
                    <div class="col-md-12 text-center ">
                        <button type="button" class="btn  save">Save</button>
                        <a class="btn cancel" onclick="location.href='{{url('empuploaddoc') }}'">Cancel</a> 
                    </div>
                </div>
                </div>
            </form>

</div>














	<script>
	$(document).ready(function(){
 var dateToday = new Date();
  var data ="{{\Session::get('j_date_format')}}";
  $( ".provision_date" ).datepicker({
      changeMonth: true,
      dateFormat: data,
      changeYear: true,	  minDate: dateToday,
      maxDate: null,
      onClose: function () {
        $(this).parsley().validate();
        }

    });

            $('.reset').click(function()
            {
                $('#department_name').val('');
                $('#description').val('');
                $('#edit_id').val('');
                $('.dup_name').hide();
            });
            $(".add_row").relCopy();
            changeclassfields();
            $('.add_row').click(function() 
            {
                changeclassfields();
            });
        
        function changeclassfields() 
        {
                changeClassName('document');
                changeClassName('emp_remarks');
                changeClassName('file_name');
                changeClassName('employee_remarks');
        }
        
        function changeClassName(className) {
        $('.' + className).each(function(index) {
            $(this).removeClass(className + '0');
           // $('#input1').attr('name', 'other_amount');
            $(this).addClass(className + index);
        });
    }
    $(document).on('click', '.remove', function() {
                var index = $(this).closest('tr').index();
                var rowCount = $('.employee_provision tbody tr').length;
                if (rowCount > 1) {
                    $($(this).closest("tr")).remove();
                    removeclassfields();
                } else {
                    notyMsg('warning',"You Can't Delete Atleast One row should be there");
                }
            });
    function removeclassfields() {
              removeClass('document');
              removeClass('file_name');
              removeClass('emp_remarks');
              removeClass('employee_remarks');
        }
		
            var dup_chk = true;
            function duplicate_validate()
            {
                var department_name = $(".department_name").val();
                var edit_id = $("#edit_id").val();

                $.ajax({
                    cache: false,
                    url: 'employeedepartment/checkname', //this is your uri
                    type: 'GET',
                    dataType: 'json',
                    async : false,
                    data: {department_name : department_name,edit_id : edit_id},
                    success: function(response)
                    {
                        console.log(response);
                        if(response == 1)
                        {
                            $('.dup_name').html('Department Name:'+department_name+' Already Exists');
                            $('.dup_name').show();
                            $(".department_name").val('');
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



        $(document).on('click','.save',function(e){


            
            
                var form_data = new FormData(document.getElementById('save'));
                var form = $('#save');
                form.parsley().validate();
                if (form.parsley().isValid())
                {

                    $.ajax({
                          url: "{{ url('documentssave')}}",
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
                           
                            if(data == 1)
                            {
                                notyMsgs('success','Document Uploaded Successfully');
                                setTimeout(function(){
                                    var url = "{{ URL::to('empuploaddoc') }}";
                                    window.location.href=url;
                                }, 2000);
                            }
                            if(data == 2)
                            {
                                notyMsgs('success','Document Updated Successfully');
                                setTimeout(function(){
                                   var url = "{{ URL::to('empuploaddoc') }}";
                                    window.location.href=url;
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
                });



	});
	</script>
@include('layouts.php_js_validation')
@endsection
