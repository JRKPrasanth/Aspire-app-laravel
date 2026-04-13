@extends('layouts.header')
@section('content')
<div class="row">
<div class=" col-lg-12">
<div class="card">
<div class="card-header">
<h2>ADVANCE DEDUCTIONS</h2>
</div>

    <div class="card-body card-block">

        <form  action="" id="advance_deduction" data-parsley-validate >
        <input type="hidden" name="edit_id" value="" id="edit_id" />
        <input type="hidden" class="advance_id" name="advance_id" value="" id="advance_id" />
            {{ csrf_field()}}
        <div class="row">
            <div class="col-md-6">
                
                <div class="form-group row">
                    <label for="start_date" class="form-control-label col-md-5"><span style="font-style:20px;color:red;">*</span>Employee</label>
                    <div class="col-md-4" >
                        <select name='employee_id' rows='5'  id="employee_id" class='select2' data-show-subtext="true" data-live-search="true">
                        </select>
                    </div>
                </div>
                
                <div class="form-group row">
                        <label for="inputIsValid" class="form-control-label col-md-5"><span style="font-style:20px;color:red;">*</span>Deduction Date</label>
                        <div class="col-md-4">
                                <div class="input-group form_date " data-date=""   data-link-format="yyyy-mm-dd">
                                        <input class="form-control deduction_date datepicker" id="deduction_date" name="deduction_date"  required type="text" value="" >
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                        </div>
                </div>
                

                <div class=" form-group row">
                    <label for="end_date" class="form-control-label col-md-5"><span style="font-style:20px;color:red;">*</span>EMI Amount</label>
                    <div class="col-md-7" style="margin-left:-13px;">
                        <div class="col-md-7">
                             <input class="form-control  " id="emi_amount" name="emi_amount"  required type="text" value="" >
                        </div>
                    </div>
                </div>
				
                <div class=" form-group row">
                    <label for="fob_point_name" class="form-control-label col-md-5"><span style="font-style:20px;color:red;">*</span>Amount to Pay</label>
                        <div class="col-md-4">
                            <input type="text" id="amount_to_pay" name="amount_to_pay" class="form-control amount_to_pay" value="" required>
                        </div>
                </div>
              
            </div>

            <div class="col-md-6">
                
                    <div class="form-group row">
                        <label for="organization_id" class="form-control-label col-md-5"><span style="font-style:20px;color:red;">*</span>EMI</label>
                            <div class="col-md-4">
                                <input type="text" id="total_emi" name="total_emi" class="form-control total_emi" value="" required>
                            </div>
                    </div>
                
                    <div class="form-group row">
                        <label for="organization_id" class="form-control-label col-md-5"><span style="font-style:20px;color:red;">*</span>Total Amount</label>
                            <div class="col-md-4">
                                <input type="text" id="total_amount" name="total_amount" class="form-control total_amount" value="" required>
                            </div>
                    </div>
                
                    <div class="form-group row">
                        <label for="active" class="form-control-label col-md-5 "><span style="font-style:20px;color:red;">*</span>Till Paid Amount</label>
                        <div class="col-md-4">
                            <input type="text" id="till_paid_amount" name="till_paid_amount" class="form-control till_paid_amount" value="" required>
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label for="end_date" class="form-control-label col-md-5"><span style="font-style:20px;color:red;">*</span>Till Remaining Amount</label>
                        
                        <div class="col-md-4">
                            <input type="text" id="till_remaining_amount" name="till_remaining_amount" class="form-control till_remaining_amount" value="" required>
                        </div>
                    </div>
            </div>
        </div>

        <div class="row text-center">
            <button type="button"  class="btn save save_form">Save</button> &nbsp;&nbsp;&nbsp;
            <button type='button'  class='btn del clear' id="delete">Clear</button>
        </div>
    </form>

    <div class="panel-title ">
            <div class="row">
            <div class="col-md-12">
            <button class="btn sec edit"> Edit</button>
            <button type='button' href='' class='btn del delete'>Delete</button>
    </div>
    </div>
    </div>

<div class="row">
<div class="col-md-12" style="padding: 15px;">
<!-- OUR CONTENT STARTS HERE -->

<table id="grid1"></table>

<!-- OUR CONTENT ENDS HERE -->


</div>
</div>
</div>
</div>
</div>
</div>

<script>
	$(document).ready(function()
        {
           
           
            
		$('#emi_amount,#total_emi,#total_amount,#till_paid_amount,#till_remaining_amount').prop('readonly',true);
                
                $(document).on('change','#employee_id', function()
                {
                    var employee_id = $('#employee_id').select2('val');
                    if(employee_id != ''){
                    var url="{{URL::to('employeededuction')}}?employee_id="+employee_id;
                    $.get(url, function(data,status)
                    {
                        console.log(data);
                        if(data['result'] == 0)
                        {
                            $('#emi_amount').val('');
                            $('#total_amount').val('');   
                            $('#till_paid_amount').val('');   
                            $('#till_remaining_amount').val('');   
                            $('#total_emi').val('');  
                            $('.save_form').prop('disabled',true);
                            $('.advance_id').val('');   
                            
                            notyMsgs('Info','Advance Status is in '+data['status']+'..');
                        }
                        else if(data['result'] == 1)
                        {
                           
                            $('#emi_amount').val(data['emi_amount']);
                            $('#total_amount').val(data['amount']);   
                            $('#till_paid_amount').val(data['paid_amount']);   
                            $('#till_remaining_amount').val(data['remaining_amount']);   
                            $('#total_emi').val(data['emi']);
                            $('.advance_id').val(data['advance_id']);
                            $('.save_form').prop('disabled',false);
                           
                        }
                        else if(data['result'] == 2)
                        {
                           
                            $('#emi_amount').val('');
                            $('#total_amount').val('');   
                            $('#till_paid_amount').val('');   
                            $('#till_remaining_amount').val('');   
                            $('#total_emi').val('');  
                            $('.save_form').prop('disabled',true);
                            $('.advance_id').val('');   
                            notyMsgs('Info','There is No Advance Amount To Pay');
                        }
                            
                    });
                    }
                });
              
                
                $(document).on('keypress', '.amount_to_pay', function(ev){
                    
                    var regex = new RegExp("^[0-9.]+$");
                    var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                    if (regex.test(str)) 
                    {
                        return true;
                    }
                    ev.preventDefault();
                    return false;
                });
                
                 $(document).on('keyup', '.amount_to_pay', function(){
                    
                    var amount_to_pay = parseInt($(this).val());
                    var till_remaining_amount = $('.till_remaining_amount').val();
                    
                    if(amount_to_pay > till_remaining_amount)
                    {
                        notyMsgs('Info','Amount Not Be Exceed Than Remaining Amoun');
                        $('.amount_to_pay').val('');
                    }
                    
                });
		
                $(document).on('click','.clear',function()
                {
                    $('#edit_id').val('');
                    $('#advance_date').val('');
                    $('#mode').val('').select2();
                    $('#amount').val('');
                    $('#emi').val('');
                    $('#advance_reason').val('');  
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
                
				
                $("#employee_id").jCombo("{{ URL::to('jcomboform?table=hr_employee_t:employee_id:first_name') }}",
                {selected_value:''});
                
              
                
               // jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
                
                $(document).on('click','.save_form',function()
                {
                    var url	="{{URL::to('advancedeductionsave')}}";
                    var form = $('#advance_deduction');
                    form.parsley().validate();
                    var form = $('#advance_deduction');
                    form.parsley().validate();
                    
                    change_date();
                    
                    var data	= $('#advance_deduction').serialize();
                    if (form.parsley().isValid())
                    {			
                        $.post(url,data,function(data1)
                        {
                            if(data1 == 1)
                            {
                                notyMsg('info','Advance details Saved Successfully');
                                $("#grid1")[0].triggerToolbar();
                                
                            }
                            else
                            {
                                notyMsg('info','Advance details Successfully'); 
                                $("#grid1")[0].triggerToolbar();
                               
                            }
                        });
                    }
                });
                });
	
	</script>
@include('layouts.php_js_validation')
@endsection
