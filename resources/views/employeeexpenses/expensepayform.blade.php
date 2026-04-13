@extends('layouts.header')
@section('content')
<style type="text/css">
    select { width: 400px; text-align-last:center; }
</style>


                                <h4 class="heads">
                                    <a role="button">
                                     Expenses Pay
 
 <span class="ui_close_btn"><a class="collapse-close pull-right btn-danger" onclick="location.href = '{{url('expenses')}}'"></a></span>        
 
        </h4>
      
    
    <span class="ui_close_btn"></span>
            <form method="post" action="" id="expenses_form" data-parsley-validate>
                {{ csrf_field() }}
             
                    <div class="card">
                      
                        <div class="card-body card-block">
                            <div class="col-md-4">
                                <input class="form-control expense_id" id="expense_id" name="expense_id" size="16" type="hidden" value="{{ $row->expense_id }}" readonly>
                                <input class="form-control expense_status" id="expense_status" name="expense_status" size="16" type="hidden" value="{{ $row->expense_status }}" readonly>
                                <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Expense No</label>
                                    <div class="col-md-7">
                                        <input type="text" id="expense_no" name="expense_no" class="form-control expense_no chckclick" value=""readonly >
                                    </div>
                                    <div class="col-md-2 showline">
                                    </div>
                                </div> 
                               
                                <div class="form-group row">
                                            <label for="inputIsValid" class="form-control-label col-md-5"><span style="color:red;">*</span> Expense Date</label>
                                            <div class="col-md-7 rdonly">
                                                      <div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                          <input class="form-control expense_date datepicker" id="expense_date" name="expense_date" size="16" type="text" value="{{ $row->expense_date }}" required>
                                                          

                                            </div>
                                            <input type="hidden" id="invoice_date" value="{{ $row->expense_date }}" />
                                            </div>
                                            <div class="col-md-2 showinline">
                                            </div>
                                 </div>
                                <div class="form-group row fdiv">
                                    <label for="inputIsValid" class="form-control-label col-md-5"><span style="color:red;">*</span>Expense Type</label>
                                    <div class="col-md-7 radio">
                                            <div class="l-radio" style="display: flex;">
                                               <div class="c-radio">
                                                   <input id='expense_type' class="expense_type" name='expense_type' type='radio' value="Goods" <?php if($row->expense_type == "Goods") echo "checked"; ?> /> 
                                                   <span class="check_mark"></span>
                                                   <label for="">Goods</label>
                                               </div>
                                               <div class="c-radio">
                                                   <input id='expense_type' class="expense_type" name='expense_type' type='radio' value="Labour" <?php if($row->expense_type == "Labour") echo "checked"; ?> />
                                                   <span class="check_mark"></span>
                                                   <label for="">Labour</label>
                                               </div>
                                           </div>
                                    </div>
                                    <div class="form-group row">
									   <?php if($row->expense_type == "Goods"){ ?> 
                                      <label for="inputIsValid" id="hsn" class="form-control-label col-md-5">HSN Code</label>
									   <?php } else { ?>
									   <label for="inputIsValid" id="hsn" class="form-control-label col-md-5">HSN Code</label>
									   <?php } ?>
                                    <div class="col-md-7">
                                        <input type="text" id="gst_code_id" name="gst_code_id" class="form-control gst_code_id" value="{{ $row->gst_code_id }}"  required>
                                        
                                    </div>
                                    <div class="col-md-2 showline">
                                    </div>
                                </div>
                                  
				</div>
                                
                                
                                
                                
                                
                            </div>
                            
                            <div class="col-md-4">
							 <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5"><span style="color:red;">*</span>Expense Account</label>
                                    <div class="col-md-7 expensediv ">
                                        <select name='expense_account_id' rows='5' id='expense_account_id' class='form-control expense_account_id select2 chckclick'  required >
                                            {!! $expense_account_id !!}
                                        </select>
                                    </div>
                                    <div class="col-md-2 showline">
                                    </div>
                                </div>
							  <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Tax Group</label>
                                    <div class="col-md-7 taxgrpdiv">
                                        <select name='tax_group_id' rows='5' class='select2 tax_group_id'  >
                                            {!! $tax_group_id !!}
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Supplier Name</label>
                                    <div class="col-md-7 supplierdiv">
                                        <select name='supplier_id' rows='5' class='select2 supplier_id'  >
                                            {!! $supplier_id !!}
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                </div>



                                <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Is this transaction applicable for reverse charge?</label>
                                   <div class="col-md-7">
                                        <div class="c-checkbox">
                                            <input type="checkbox" name="reverse_charge[]" value="1" <?php if($row->reverse_charge =="1") { echo "checked"; } else { echo ""; } ?> class="reverse_charge">
                                            <span class="check_mark"></span>
                                        </div>
                                  </div>
                              </div>
                               
                                


                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Remarks</label>
                                    <div class="col-md-7">
                                        <input type="text" id="remarks" name="remarks" class="form-control remarks chckclick" value="{{ $row->remarks }}" >
                                    </div>
                                    <div class="col-md-2 showline">
                                    </div>
                                </div>
                                
                                 <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5"><span style="color:red;">*</span>Invoice</label>
                                    <div class="col-md-7">
                                        <input type="text" id="invoice" name="invoice" class="form-control invoice chckclick" value="{{ $row->invoice }}" required >
                                    </div>
                                    <div class="col-md-2 showline">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5"><span style="color:red;">*</span>Expense Amount</label>
                                    <div class="col-md-7">
                                        <input type="text" id="expense_amount" name="expense_amount" class="form-control expense_amount" value="{{ $row->expense_amount }}"  required>
                                    </div>
                                    <div class="col-md-2 showline">
                                    </div>
                                </div>
                              <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5"><span style="color:red;">*</span>Pay Amount</label>
                                    <div class="col-md-7">
                                        <input type="text" id="pay_amount" name="pay_amount" class="form-control pay_amount" value="{{ $row->pay_amount }}"  required>
                                    </div>
                                    <div class="col-md-2 showline">
                                    </div>
                                </div>


                            </div>







                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <input type="hidden" name="submit_type" class="submit_type" value="" />
                                <div class="form-group text-center actionbtn">
                                 
                                    <button name="submit" type="button" class="btn save saveform" value="INITIATED">SUBMIT</button>
                                    <a class='btn cancel' onclick='location.href ="{{ url($pageModule) }}"'>Cancel</a>
                                   
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" class="pdtindex" value="" />
                    <div id="preloader">
                        <img src="https://jrlma.ca/wp-content/plugins/gallery-by-supsystic/src/GridGallery/Galleries/assets/img/loading.gif">
                    </div>
               
            </form>
            
     

<script>

    $(document).ready(function(){
        
        
//          $('input').attr('readonly', true);
//$('select').attr('readonly', true);
//$('select').css('pointer-events', 'none');
//$('.paidthrghdiv,.supplierdiv,.gsttreatdiv,.taxgrpdiv,.expensediv').css('pointer-events','none');

        
    $(document).on('change', '.expense_type', function() {
        var radio=$('input[name=expense_type]:checked').val();
        if(radio=="Labour"){
			$('#hsn').text("SAC Code");
        }
        else{
             $('#hsn').text("HSN Code");
        }
    });
    /*Validation*/
	$(document).on('keypress','.expense_amount,.pay_amount', function(ev){
			var regex = new RegExp("^[0-9.]+$");
					var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
					if (regex.test(str)) {
						return true;
					}
					ev.preventDefault();
					return false;
		});
	/*End*/
        
 /*Karthigaa Purpose For Save Function*/      
      
    $(document).on('click', '.saveform', function() {
    var btnval = $(this).val();
    
		if(btnval == 'INITIATED')
		{
			$("#expense_status").val('INITIATED');
		}

	    else if(btnval == 'APPROVED')
	    {
			$("#expense_status").val('APPROVED');
	    }
		else
		{
			$("#expense_status").val('REJECTED');
		}
    $('#savestatus').val(btnval);
    var url = "{{ url('expensessave') }}";
    var red_url = "{{url('expenses')}}";
    var app_url = "{{url('expenseapproval')}}";
      
        validationrule('expenses_form');
	var form = $('#expenses_form');
        form.parsley().validate();
        if (form.parsley().isValid())
        {
            change_date();
            var formdata	= $('#expenses_form').serialize();
                     
            $.post(url, formdata, function(data)
            {
            var status = data.status;
            var msg = '<span style="color:#090065"></span>' + data.message;
            var id = data.id;
            var edit_url = "{{ url('expensescreate') }}/" + id;
            if (btnval != 'SAVE' && btnval != 'DRAFT')
            {
            notyMsg(status, msg);
            setTimeout(function(){
            window.location.href = app_url;
            }, 1500);
            }
            else
            {
            notyMsg(status, msg);
            setTimeout(function(){
            window.location.href = red_url;
            }, 1500);
            }
            });
            }
    });
    });</script>
<script>
    $(window).on('load', function(){
    //preloader
    var preLoder = $("#preloader");
    preLoder.fadeOut(500);
    var backtoTop = $('.back-to-top')
            backtoTop.fadeOut(100);
    });
</script>

@include('layouts.php_js_validation')
@endsection
