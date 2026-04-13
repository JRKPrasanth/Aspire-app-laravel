@extends('layouts.header')
@section('content')
<div class="row">
<div class=" col-lg-12">
<div class="card">
<div class="card-header">
<h2>ADVANCE APPROVAL</h2>
</div>

    <div class="card-body card-block">

        <form  action="" id="advance" data-parsley-validate >
        <input type="hidden" name="edit_id" value="{{$advance_id}}" id="edit_id"   />
        <input type="hidden" name="status" value="{{$status}}" id="status"   />
            {{ csrf_field()}}
        <div class="row">
            <div class="col-md-6">
                
                <div class="form-group row">
                    <label for="start_date" class="form-control-label col-md-5"><span style="font-style:20px;color:red;">*</span>Employee</label>
                    <div class="col-md-7" >
                      <select name='employee_id' rows='5'  id="employee_id" class='select2' data-show-subtext="true" data-live-search="true">
                      </select>
                    </div>
                </div>
                
                <div class="form-group row">
                        <label for="inputIsValid" class="form-control-label col-md-5">Advance Date</label>
                        <div class="col-md-4">
                                <div class="input-group form_date " data-date=""   data-link-format="yyyy-mm-dd">
                                        <input class="form-control inquiry_date datepicker" id="advance_date" readonly name="advance_date"  required type="text" value="{{$advance_date}}" >
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                        </div>
                </div>
                

                <div class=" form-group row">
                    <label for="end_date" class="form-control-label col-md-5"><span style="font-style:20px;color:red;">*</span>Mode</label>
                    <div class="col-md-7" style="margin-left:-13px;">
                        <div class="col-md-7">
                            <select  id="mode" name="mode" class="select2 mode"  required>
                                    <option value="">--- Please Select --</option>
                                    <option value="1" {{$mode == 1 ? "selected" : "" }}>Cash</option>
                                    <option value="2"  {{$mode == 2 ? "selected" : "" }}>Check</option>
                                    <option value="3"  {{$mode == 3 ? "selected" : "" }}>On-Line</option>
                            </select>
                        </div>
                    </div>
                </div>
				
                <div class=" form-group row">
                    <label for="fob_point_name" class="form-control-label col-md-5"><span style="font-style:20px;color:red;">*</span>Amount</label>
                        <div class="col-md-4">
                            <input type="text" id="amount" name="amount" class="form-control amount" readonly value="{{$amount}}" required>
                        </div>
                </div>
              
            </div>

            <div class="col-md-6">
                    <div class="form-group row">
                        <label for="organization_id" class="form-control-label col-md-5"><span style="font-style:20px;color:red;">*</span>EMI MONTH</label>
                            <div class="col-md-4">
                                    <input type="text" id="emi" name="emi" readonly class="form-control emi" value="{{$emi}}" required>
                            </div>
                    </div>
                
                    <div class="form-group row">
                        <label for="active" class="form-control-label col-md-5 "><span style="font-style:20px;color:red;">*</span>Advance Reason</label>
                        <div class="col-md-4">
                            <textarea id="advance_reason" class="form-control advance_reason" readonly required name="advance_reason">{{$advance_reason}}</textarea>
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label for="end_date" class="form-control-label col-md-5"><span style="font-style:20px;color:red;">*</span>Forwarded To</label>
                        
                        <div class="col-md-4">
                            <select  id="forwarded_id" name="forwarded_id"  class="select2 forwarded_id" required>
                            </select>
                        </div>
                        
                        
                    </div>
                   
            </div>
        </div>
                
       
         @if($status == 'approve')
            @php  $button = 'Approve';  @endphp
         @elseif($status == 'reject')
            @php  $button ='Reject'; @endphp
         @endif   
        
        

        <div class="row text-center">
            <button type="button"  class="btn save save_form">{{$button}}</button> &nbsp;&nbsp;&nbsp;
            <button type='button'  class='btn del clear' id="delete">Cancel</button>
        </div>
    </form>
</div>
</div>
</div>
</div>

<script>
	$(document).ready(function()
        {
		    
                
                
                $(document).on('keypress', '.amount', function(ev){
                    
                    var regex = new RegExp("^[0-9.]+$");
                    var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                    if (regex.test(str)) 
                    {
                            return true;
                    }
                    ev.preventDefault();
                    return false;
                });
                
                $(document).on('keypress', '.emi', function(ev)
                {
                    var regex = new RegExp("^[0-9]+$");
                    var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                    if (regex.test(str)) 
                    {
                        return true;
                    }
                    ev.preventDefault();
                    return false;
                });
		
                $(document).on('click','.clear',function()
                {
                  var url = "{{URL::to('addvanceapproval')}}";
                     window.location.href=url;
                });
                
                
                $(document).on('click','.delete',function(e)
                {
                    e.preventDefault();
                    var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
                    var advance_id = jQuery("#grid1").jqGrid ('getCell', gr, 'advance_id');
                    if(advance_id != false)
                    {
                        $.get('employeeadvance/delete?del_id='+advance_id, function(data,status)
                        {
                            if(data == 1)
                            {
                                setTimeout(function()
                                {
                                    notyMsgs('Info','Cannot Be Delete.Which is in Approved State or Used in Some Where');
                                }, 2000);
                                $("#grid1")[0].triggerToolbar();
                            }
                            else if(data == 2)
                            {
                                notyMsgs('Info','Advance Details Deleted Successfully');
                                $("#grid1")[0].triggerToolbar();
                            }
                        });
                    }
                    else
                    {
                        notyMsgs('Info','Please Select a Row');
                        $("#grid1")[0].triggerToolbar();
                    }


            });
		

                $(document).on('click','.edit',function()
                {
                    var index = $("#grid1").jqGrid('getGridParam','selrow');
                    var advance_id = $("#grid1").jqGrid ('getCell', index, 'advance_id');
                    var employee_id = $("#grid1").jqGrid ('getCell', index, 'employee_id');
                    var advance_date = $("#grid1").jqGrid ('getCell', index, 'advance_date');
                    var mode = $("#grid1").jqGrid ('getCell', index, 'mode');
                    var amount = $("#grid1").jqGrid ('getCell', index, 'amount');
                    var emi = $("#grid1").jqGrid ('getCell', index, 'emi');
                    var forwarded_id = $("#grid1").jqGrid ('getCell', index, 'forwarded_id1');
                    var advance_reason = $("#grid1").jqGrid ('getCell', index, 'advance_reason');
                    var selRows= $('#grid1 tbody .ui-state-highlight').length;   
                    
                    
                    if(approved_status == 0)
                    {
                        if(advance_id != false)
                        {

                            $('#edit_id').val(advance_id);
                            $('#employee_id').select2('val',[employee_id]);
                            $('#advance_date').val(advance_date);
                            $('#mode').select2('val',[mode]);
                            $('#amount').val(amount);
                            $('#emi').val(emi);
                            $('#forwarded_id').select2('val',[forwarded_id]);
                            $('#advance_reason').val(advance_reason);
                        }
                        else
                        {
                            notyMsgs('Warning','Please Selet A Row');
                        }
                    }
                    else
                    {
                        
                        notyMsgs('Warning','Approved Status Cannot be Edit');
                    }
                }); 
		
              
                var reporting_id = '{{$forwarded_id}}';
                var logged_user = '{{$employee_id}}';
		
                $("#forwarded_id").jCombo("{{ URL::to('jcomboform?table=hr_employee_t:employee_id:first_name') }}",
                {selected_value:reporting_id});
				
                $("#employee_id").jCombo("{{ URL::to('jcomboform?table=hr_employee_t:employee_id:first_name') }}",
                {selected_value:logged_user});
                
              
                
                jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
                
                $(document).on('click','.save_form',function()
                {
                    var url	="{{URL::to('advancestatus')}}";
                    var form = $('#advance');
                    form.parsley().validate();
                    var form = $('#advance');
                    form.parsley().validate();
                    
                    change_date();
                    
                    var data	= $('#advance').serialize();
                    
                    if (form.parsley().isValid() )
                    {			
                        $.post(url,data,function(data1)
                        {
                            if(data1[0] == 1)
                            {
                                notyMsg('info','Advance '+data1[1]+' Successfully');
                                setTimeout(function()
                                {
                                   var url = "{{URL::to('addvanceapproval')}}";
                                   window.location.href=url;
                                }, 2000);
                                
                            }
                        });
                    }
                });
                });
	
	</script>
@include('layouts.php_js_validation')
@endsection
