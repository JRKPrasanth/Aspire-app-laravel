@extends('layouts.header')
@section('content')

<h2 class="heads">ADVANCE DEDUCTIONS</h2>

<div class="card">

    <div class="card-body card-block">

        <form  action="" id="advance_deduction" data-parsley-validate >
        <input type="hidden" name="edit_id" value="" id="edit_id" />
        <input type="hidden" class="advance_id" name="advance_id" value="" id="advance_id" />
            {{ csrf_field()}}
        <div class="row">
            <div class="col-md-4">
                
                <div class="form-group row">
                    <label for="start_date" class="form-control-label col-md-5"><span style="color: red;" >*</span>Employee</label>
                    <div class="col-md-7" >
                        <select name='employee_id' rows='5'  id="employee_id" class='select2' >
                        </select>
                    </div>
                </div>
                
                <div class="form-group row">
                        <label for="inputIsValid" class="form-control-label col-md-5"><span style="color: red;">*</span>Deduction Date</label>
                        <div class="col-md-7">
                                <div class="input-group form_date " data-date=""   data-link-format="yyyy-mm-dd">
                                        <input class="form-control deduction_date datepicker" id="deduction_date" name="deduction_date"  required type="text" value=""  style="border-radius: 5px;">                                      
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                        </div>
                </div>
                

                <div class=" form-group row">
                    <label for="end_date" class="form-control-label col-md-5"><span style="color: red;">*</span>EMI Amount</label>
                   
                        <div class="col-md-7">
                             <input class="form-control  " id="emi_amount" name="emi_amount"  required type="text" value="" >
                        </div>
                    
                </div>
				</div>
                <div class="col-md-4">
                <div class=" form-group row">
                    <label for="fob_point_name" class="form-control-label col-md-5"><span style="color: red;">*</span>Amount to Pay</label>
                        <div class="col-md-7">
                            <input type="text" id="amount_to_pay" name="amount_to_pay" class="form-control amount_to_pay" value="" required>
                        </div>
                </div>
              
            
                
                    <div class="form-group row">
                        <label for="organization_id" class="form-control-label col-md-5"><span style="color: red;">*</span>EMI</label>
                            <div class="col-md-7">
                                <input type="text" id="total_emi" name="total_emi" class="form-control total_emi" value="" required>
                            </div>
                    </div>
                
                    <div class="form-group row">
                        <label for="organization_id" class="form-control-label col-md-5"><span style="color: red;" >*</span>Total Amount</label>
                            <div class="col-md-7">
                                <input type="text" id="total_amount" name="total_amount" class="form-control total_amount" value="" required>
                            </div>
                    </div>
                </div>
                <div class="col-md-4">
                
                    <div class="form-group row">
                        <label for="active" class="form-control-label col-md-5 "><span style="color: red;">*</span>Till Paid Amount</label>
                        <div class="col-md-7">
                            <input type="text" id="till_paid_amount" name="till_paid_amount" class="form-control till_paid_amount" value="" required>
                        </div>
                    </div>

                    <div class=" form-group row">
                        <label for="end_date" class="form-control-label col-md-5"><span style="color: red;" >*</span>Till Remaining Amount</label>
                        
                        <div class="col-md-7">
                            <input type="text" id="till_remaining_amount" name="till_remaining_amount" class="form-control till_remaining_amount" value="" required>
                        </div>
                    </div>
            </div>
        </div>

        <div class="row text-center">
            <button type="button"  class="btn save save_form">Save</button> &nbsp;&nbsp;&nbsp;
            <!-- <button type='button'  class='btn del clear' id="delete">Clear</button> -->
            <?php include 'toolbar.php'; ?>
        </div>
    </form>

    <!-- <div class="panel-title ">
            <div class="row">
            <div class="col-md-12">
            <button class="btn sec edit"> Edit</button>
            <button type='button' href='' class='btn del delete'>Delete</button>
    </div>
    </div>
    </div> -->

<div class="row">
<div class="col-md-12" style="padding: 15px;">
<!-- OUR CONTENT STARTS HERE -->

<table id="grid1"></table>

<!-- OUR CONTENT ENDS HERE -->


</div>
</div>
</div>
</div>


<script>
	$(document).ready(function()
        {
         /** jqgrid for load advance dedution data Start **/
		 $("#grid1").jqGrid(
            {
                url: "employeeadvancedeductiongriddata",
                datatype: "json",
                mtype: "GET",
                colModel: [
                    { name: "id", label: "advance_id", width: 250, hidden: true },
                    { name: "approved_status", label: "approved_status", width: 250, hidden: true },
                    { name: "first_name", label: "Employee Name", width: 250 },
                    { name: "ref_id", label: "Referece Id", width: 250, hidden: true },
                    { name: "deduction_date", label: "Deduction Date", width: 250, hidden: true },
                    { name: "emi_amount", label: "Emi Amount", width: 250 },
                    { name: "amount_pay", label: "Amount Pay.", width: 250},
                    { name: "total_amount", label: "Total Amount", width: 250,hidden: true},
                    { name: "till_paid_amount", label: "Till Paid Amount", width: 250,hidden: true},
                    { name: "till_remaining_amount", label: "Till Remaining Amount", width: 250},
                    { name: "paid_status", label: "Paid Status", width: 250}
                   
                ],

                iconSet: "fontAwesome",
                rowNum: 10,
                rowList: [10,20,50,100,200,1000,2000],
                sortorder: "desc",
                viewrecords: true,
                gridview: true,
                rownumbers:true,
                pager: "#grid1",
                multiselect:false,
                multipageselection:true,
                searching: {
                defaultSearch: "cn",
                },
            });
           /** jqgrid for load advance dedution data End **/

           /** jqgrid for load advance dedution Export Pdf data Start **/
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
                      fileName : "Advance Deduction.pdf",
                      mimetype : "application/pdf"  
                });
            });
/** jqgrid for load advance dedution Export Pdf data end **/

/** jqgrid for load advance dedution Export Excel data Start **/
            $(document).on('click',".exportexcel",function() {
                $("#grid1").jqGrid("exportToExcel",{
                    includeLabels : true,
                        includeGroupHeader : true,
                        includeFooter: true,
                        fileName : "Advance Deduction.xlsx"
                        
                })       
                
            });
/** jqgrid for load advance dedution Export Excel data End **/

showcolumn('grid1');


/*purpose:clear search the jqgrid*/
  $(".clearsearch").click(function()
  {
    var grid = $("#grid1");
    grid.jqGrid('setGridParam',{search:false});
    var postData = grid.jqGrid('getGridParam','postData');
    $.extend(postData,{filters:""});
    grid.trigger("reloadGrid",[{page:1}]);
    $('input[id*="gs_"]').val("");
    
  });
  /*end*/
            
		$('#emi_amount,#total_emi,#total_amount,#till_paid_amount,#till_remaining_amount').prop('readonly',true);
                
                $(document).on('change','#employee_id', function()
                {
                    /** jqgrid for load advance dedution Calculation data Start **/

                    var employee_id = $('#employee_id').select2('val');
                    if(employee_id != ''){
                    var url="{{URL::to('employeededuction')}}?employee_id="+employee_id;
                    $.get(url, function(data,status)
                    {
                        
                        if(data['result'] == 0)
                        {
                            $('#emi_amount').val('');
                            $('#total_amount').val('');   
                            $('#till_paid_amount').val('');   
                            $('#till_remaining_amount').val('');   
                            $('#total_emi').val('');  
                            $('.save_form').prop('disabled',true);
                            $('.advance_id').val('');   
                            
                            notyMsgs('Info','Advance Status is in '+data['status']+'');
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
                /** jqgrid for load advance dedution Calculation data End **/
              
                /** jqgrid for load advance dedution amount validation data Start **/
                $(document).on('keypress', '.amount_to_pay', function(ev)
                {
                    
                    var regex = new RegExp("^[0-9.]+$");
                    var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                    if (regex.test(str)) 
                    {
                        return true;
                    }
                    ev.preventDefault();
                    return false;
                });
                
                 $(document).on('keyup', '.amount_to_pay', function()
                 {
                    var amount_to_pay = parseInt($(this).val());
                    var till_remaining_amount = $('.till_remaining_amount').val();
                    
                    if(amount_to_pay > till_remaining_amount)
                    {
                        notyMsgs('Info','Amount Not Be Exceed Than Remaining Amount');
                        $('.amount_to_pay').val('');
                    }
                    
                });
		/** jqgrid for load advance dedution amount validation data End **/

        /** jqgrid for load Clear search data Start **/
                $(document).on('click','.clear',function()
                {
                     $('#emi_amount').val('');
					 $('#total_amount').val('');   
					 $('#till_paid_amount').val('');   
					 $('#till_remaining_amount').val('');   
					 $('#total_emi').val('');  
					 $('.advance_id').val('');   
					 $('#employee_id').select2('val',['']);   
					 $('.deduction_date,.amount_to_pay').val('');   
					 $('#edit_id').val('');   
                });
                /** jqgrid for load Clear search data End **/

                
            //     $(document).on('click','.delete',function(e)
            //     {
            //         e.preventDefault();
            //         var gr = jQuery("#grid1").jqGrid('getGridParam','selrow');
            //         var advance_id = jQuery("#grid1").jqGrid ('getCell', gr, 'id');

            //         if(gr)
            //         {
            //     swal({
            //       title: "Are you sure?",
            //       text: "You want to delete!",
            //       type: "warning",
            //       showCancelButton: !0,
            //       confirmButtonColor: "#DD6B55",
            //       confirmButtonText: "Yes",
            //       cancelButtonText: "No",
            //       closeOnCancel:!1
            //     }, function(e) {
            //     if(e == true)
            //     {
            //             $.get('employeeadvance/delete?del_id='+advance_id, function(data,status)
            //             {
            //                 if(data == 1)
            //                 {
            //                     setTimeout(function()
            //                     {
            //                         notyMsgs('Info','Cannot Be Delete.Which is in Approved State or Used in Some Where');
            //                     }, 2000);
            //                     $("#grid1")[0].triggerToolbar();
            //                 }
            //                 else if(data == 2)
            //                 {
            //                     notyMsgs('Info','Advance Details Deleted Successfully');
            //                     $("#grid1")[0].triggerToolbar();
            //                 }
            //             });
            //        }
            //             else
            //             {
            //               $('.apply').css('display','none');
            //               swal("Cancelled");
            //             }
            //     });
            //     $('.apply').css('display','none');
            //     }
            //         else
            //         {
            //             notyMsgs('Info','Please Select a Row');
            //             $("#grid1")[0].triggerToolbar();
            //         }


            // });
		
                /** jqgrid for load Advance deduction Edit data Start **/
                $(document).on('click','.edit',function()
                {
                    var index = $("#grid1").jqGrid('getGridParam','selrow');
                    var advance_id = $("#grid1").jqGrid ('getCell', index, 'advance_id');
                    var employee_id = $("#grid1").jqGrid ('getCell', index, 'employee_id');
                    var advance_date = $("#grid1").jqGrid ('getCell', index, 'advance_date');
                    var approved_status = $("#grid1").jqGrid ('getCell', index, 'approved_status');
                    var mode = $("#grid1").jqGrid ('getCell', index, 'mode');
                    var selRows= $('#grid1 tbody .ui-state-highlight').length;   
                    if(approved_status )
                    {
                        if(advance_id == 0 )
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
                            notyMsgs('Warning','Approved Status Cannot be Edit');
                        }
                    }
                    else
                    {
                        notyMsgs('info','Please Selet A Row');
                        
                    }
                }); 
                
				/** jqgrid for load Advance deduction Edit data end **/

                $("#employee_id").jCombo("{{ URL::to('jcomboform?table=hr_employee_t:employee_id:first_name') }}",
                {selected_value:''});
                
              
                
                /** jqgrid for load Advance deduction Save data Start **/
               // jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
                jQuery("#grid1").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
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
                                notyMsg('success','Advance details Saved Successfully');
                                $("#grid1")[0].triggerToolbar();
                                $('.clear').trigger('click');
                                
                            }
                            else
                            {
                                notyMsg('success','Advance details Saved Successfully'); 
                                $("#grid1")[0].triggerToolbar();
                                $('.clear').trigger('click');
                               
                            }
                        });
                    }
                });
                /** jqgrid for load Advance deduction Save data end **/
                });
	
	</script>
@include('layouts.php_js_validation')
@endsection
