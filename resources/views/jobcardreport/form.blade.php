@extends('layouts.header')
@section('content')


<style>

@media  only screen and (min-width: 1500px) {
  .bulk_line_no{width:200px !important;}
  .bulk_account_id{width:400px !important;}
  .bulk_journal_date{width:300px !important;}
  .bulk_debit_amount{width:200px !important;}
  .bulk_credit_amount{width:200px !important;}  
}
  .bulk_line_no{width:150px;}
  .bulk_account_id{width:350px;}
  .bulk_journal_date{width:150px !important;}
  .bulk_debit_amount{width:80px;}
  .bulk_credit_amount{width:80px;}
</style>


    <span class="ui_close_btn"></span>
  


<div id="accordion">
<div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a role="button">
                                       Journal Entry
                                    </a>

                                 
        </h4>
           <?php if($aprvidenty=="") { ?> 
<span class="ui_close_btn"><a class="collapse-close pull-right btn-danger" onclick="location.href = '{{url('journalentry')}}'"></a></span>
<?php } else {?>
<span class="ui_close_btn"><a class="collapse-close pull-right btn-danger" onclick="location.href = '{{url('journalapproval')}}'"></a></span>
<?php } ?>
    </div>
</div>  


      
                <div class="card">

            <form method="post" action="" id="journal_form" data-parsley-validate>
                {{ csrf_field() }}
                   


                        <div class="card-body card-block">
                            <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                <input class="form-control journal_entry_id" id="journal_entry_id" name="journal_entry_id" size="16" type="hidden" value="{{ $row->journal_entry_id }}" readonly>

                                
                               <div class="form-group row">
                                   <label for="inputIsValid" class="form-control-label col-md-5"><span style="color:red; font-size:20px;" >*</span>Journal Name</label>
                                    <div class="col-md-7">
                                        <input type="text" id="journal_name" name="journal_name" class="form-control journal_name " value="{{ $row->journal_name }}" required>
                                    </div>
                                    <div class="col-md-2 showline">
                                    </div>
                                </div>
                                
                                 <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Journal Date</label>
                                    <div class="col-md-7 journaldate">
                                        <div class="input-group form_date col-md-12" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                            <input class="form-control datepicker journal_date" id="journal_date" name="journal_date" size="16" type="text" value="{{ $row->journal_date }}" >
                                            <!-- <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span> -->
                                        </div>
                                    </div>
                                   
                                </div>
                             
                               
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Journal Type</label>
                                    <div class="col-md-7 journaltype">
                                        <select type="text" name="journal_type" id="journal_type" class="select2 journal_type" >
                                        <option value="">-- Please Select --</option>
                                        <option <?php if($row->journal_type=="MANUAL" ) echo "selected"; ?> value="MANUAL">MANUAL</option>
                                        <option <?php if($row->journal_type=="PO INVOICE" ) echo "selected"; ?> value="PO INVOICE">PO INVOICE</option>
                                        <option <?php if($row->journal_type=="PAYABLES" ) echo "selected"; ?> value="PAYABLES">PAYABLES</option>
                                        <option <?php if($row->journal_type=="SALES INVOICE" ) echo "selected"; ?> value="SALES INVOICE">SALES INVOICE</option>
                                        <option <?php if($row->journal_type=="RECEIVABLES" ) echo "selected"; ?> value="RECEIVABLES">RECEIVABLES</option>
                                        <option <?php if($row->journal_type=="EXPENSES" ) echo "selected"; ?> value="EXPENSES">EXPENSES</option>
                                        <option <?php if($row->journal_type=="PAYMENT" ) echo "selected"; ?> value="PAYMENT">PAYMENT</option>
                                        <option <?php if($row->journal_type=="RECEIPT" ) echo "selected"; ?> value="RECEIPT">RECEIPT</option>
                                        <option <?php if($row->journal_type=="ADVANCE RECEIPT" ) echo "selected"; ?> value="ADVANCE RECEIPT">ADVANCE RECEIPT</option>
                                        <option <?php if($row->journal_type=="ADVANCE PAYMENT" ) echo "selected"; ?> value="ADVANCE PAYMENT">ADVANCE PAYMENT</option>
                                        <option <?php if($row->journal_type=="ADJUSTMENTS" ) echo "selected"; ?> value="ADJUSTMENTS">ADJUSTMENTS</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Journal Status</label>
                                    <div class="col-md-7 journalstatus">
                                        <select type="text" name="journal_status" id="journal_status" class="select2 journal_status" >
                                        <option value="">-- Please Select --</option>
                                        
                                        <option <?php if($row->journal_status=="DRAFT" ) echo "selected"; ?> value="DRAFT">DRAFT</option>
                                        <option <?php if($row->journal_status=="SUBMITTED" ) echo "selected"; ?> value="SUBMITTED">SUBMITTED</option>
                                        <option <?php if($row->journal_status=="APPROVED" ) echo "selected"; ?> value="APPROVED">APPROVED</option>
                                        <option <?php if($row->journal_status=="REJECTED" ) echo "selected"; ?> value="REJECTED">REJECTED</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                </div>
                                <div class="form-group row" style="display:none">
                                    <label for="inputIsValid" class="form-control-label col-md-5">Journal Reference</label>
                                    <div class="col-md-7">
                                        <input type="text" id="journal_reference" name="journal_reference" class="form-control journal_reference" value="{{ $row->journal_reference }}">
                                    </div>
                                    <div class="col-md-2 showline">
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
    <div class="col-md-12">
        <!------------------------- clone row start -------------------------------->
<a href="javascript:void(0);" class="add_row additem" rel=".rcopy"><i class="fa fa-plus"></i> New Item</a>
        <!-------------------------Linedata -------------------------------->
        <div id="preview-area" class="chandru">
               <table class="overflow-y preview journal_table">
                <thead>
                    <tr>
                       
                        <th>Line No</th>
                        <th>Journal Date</th>
                        <th>Account</th>
                        <th>Debit Amount</th>
                        <th>Credit Amount</th>
                        <th></th>
                    </tr>

                </thead>
                
                <tbody class="acc_codes_lines_body">
                    <?php if(count($linedata)>=1) { ?>
                        @foreach($linedata as $key=>$value)

                        <tr class="rcopy clone">
                            <td>
                                <input type="hidden" name="bulk_f_journal_entry_line_id[]" class="form-control input-sm bulk_f_journal_entry_line_id" value="{{ $value->f_journal_entry_line_id }}">
                            </td>
                            <td>
                                <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="" readonly="readonly" >
                            </td>
                            <td>
                                <input type="text" name="bulk_journal_date[]" class="form-control datepicker input-sm bulk_journal_date" value="{{ $value->journal_date }}">
                            </td>
                            <td>
                                <select name="bulk_account_id[]" id="bulk_account_id" class="bulk_account_id select2 parsley-validated" required="required">{!! $value->account_id !!}</select>
                            </td>
                            <td>
                                <input type="text" name="bulk_debit_amount[]" class="form-control input-sm bulk_debit_amount" value="{{ $value->debit_amount }}">
                            </td>
                            <td>
                                <input type="text" name="bulk_credit_amount[]" class="form-control input-sm bulk_credit_amount" value="{{ $value->credit_amount }}">
                            </td>
                            
                            <td><a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
                        <input type="hidden" name="counter[]">
                    </td>
                        </tr>
                        @endforeach
                       
                        <?php } if(count($linedata) < 1 ) { ?>
                            <tr class="rcopy clone">
                                <td>
                                <input type="hidden" name="bulk_f_journal_entry_line_id[]" class="form-control input-sm bulk_f_journal_entry_line_id" value="">
                            </td>
                            <td>
                                <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="" readonly="readonly" >
                            </td>
                            <td>
                                <input type="text" name="bulk_journal_date[]" class="form-control datepicker input-sm bulk_journal_date" value="">
                            </td>
                            <td>
                                <select name="bulk_account_id[]" id="bulk_account_id" class="bulk_account_id select2 parsley-validated" required="required">{!! $account_id !!}</select>
                            </td>
                            <td>
                                <input type="text" name="bulk_debit_amount[]" class="form-control input-sm bulk_debit_amount" value="">
                            </td>
                            <td>
                                <input type="text" name="bulk_credit_amount[]" class="form-control input-sm bulk_credit_amount" value="">
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
                            <?php if($aprvidenty=="") { ?> 
                    <input type="hidden" name="submit_type" class="submit_type" id="submit_type">
                    <button type="button" class="btn draft saveform" value="DRAFT">Draft</button>
                    <button name="submit" type="button" class="btn save check saveform" value="SAVE">Save</button>
                    <a href="{{ url('journalentry') }}" class='btn cancel'>Cancel</a>
                     <?php } else { ?>
                        <button type="button" class="btn approve saveform" value="APPROVED">Approve</button>
                        <button type="button" class="btn reject saveform" value="REJECTED">Reject</button>
			  <a href="{{ url('journalapproval') }}" class='btn cancel'>Cancel</a>
                        <?php } ?>
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



     </div>           
 
</form>
</div>
<script>
$(document).ready(function(){
         $('.journaldate,.journaltype,.journalstatus').css('pointer-events','none');   
       <?php if($aprvidenty=="INITIATED")
         { ?>
          $('input').attr('readonly', true);
$('select').attr('readonly', true);
$('select').css('pointer-events', 'none');
//  $('.journaldate,.journaltype,.journalstatus').css('pointer-events','none');
         <?php } ?>
             
  var data ="{{\Session::get('j_date_format')}}";
        $(".add_row").relCopy(data);
        changeclassfields();
   
   $('.add_row').click(function(){
           // $('.onclickrel').trigger('click');
            var rowCount = $('.journal_table tbody tr').length;
    	//var index = rowCount - 1;
             changeclassfields();
            // $('.bulk_active'+index).val('Yes').change();
    });
    
//select2
   
        
  var index = $('.clone').closest('tr').index();
changeclassfields();
                    
 /*Karthigaa Purpose For Save Function*/      
      
    $(document).on('click', '.saveform', function() {
                var dbtamt = 0;
                var crtamt = 0;
                $('.bulk_debit_amount').each(function(){
                        dbtamt +=Number(isNaN($(this).val())?0:$(this).val());
                });
                $('.bulk_credit_amount').each(function(){
                        crtamt += Number(isNaN($(this).val())?0:$(this).val());
                });
              
              
            if(Math.round(dbtamt) != Math.round(crtamt)){
                 notyMsg('error','Debit Amount and Credit amount Should be same');
            } 
            else
            {
                var btnval = $(this).val();
//                alert(btnval);
            if(btnval == 'APPROVED'){
                    $("#journal_status").val('APPROVED');
            }
            else if(btnval=='DRAFT'){
                       $("#journal_status").val('DRAFT');
            }
             
            else{
               $("#journal_status").val('SUBMITTED'); 
            }
            
                $('#savestatus').val(btnval);
                var url = "{{ url('journalentrysave') }}";
                var red_url = "{{url('journalentry')}}"
                validationrule('journal_form');
   
                 var form = $('#journal_form');
            form.parsley().validate();
            var form = $('#journal_form');
            form.parsley().validate();
            if (form.parsley().isValid())
            {
                 change_date();
                  var formdata = $('#journal_form').serialize();
            $.post(url, formdata, function(data)
            {
            var status = data.status;
            var msg = '<span style="color:#090065"></span>' + data.message;
            var id = data.id;
            var edit_url = "{{ url('journalentrycreate') }}/" + id;
              
            if (btnval != 'SAVE' && btnval != 'DRAFT')
            {
            notyMsg(status, msg);
            setTimeout(function(){
            window.location.href = "{{url('journalapproval')}}";
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
 }
    });
    $(document).on('click','.remove',function()
{
	var index = $(this).closest('tr').index();
	var rowCount = $('.journal_table tbody tr').length;
	if(rowCount > 1)
	{
		$($(this).closest("tr")).remove();
		removeclassfields();
	}
	else
	{
		notyMsg('error',"You Can't Delete Atleast One row should be there");
	}
});

	
function changeclassfields(){
    changeClassName('bulk_line_no');
changeClassName('bulk_f_journal_entry_line_id');
changeClassName('bulk_account_id');
changeClassName('bulk_debit_amount');
changeClassName('bulk_credit_amount');
}
function removeclassfields(){
    removeClass('bulk_line_no');
removeClass('bulk_f_journal_entry_line_id');
removeClass('bulk_account_id');
removeClass('bulk_debit_amount');
removeClass('bulk_credit_amount');
}
/************ Karthigaa purpose to remove row action ********************/
function removeClass(className)
{
	var rowCount = $('.journal_table tbody tr').length;
	for(var i=0;i<=rowCount;i++)
	{
	$('.journal_table tbody tr').find('.'+className).removeClass(className+i);
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
});
   </script>
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
