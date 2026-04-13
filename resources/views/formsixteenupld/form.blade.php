@extends('layouts.header') @section('content')



<style>
@media  only screen and (min-width: 1500px) {
    .bulk_branch_name{width:120px !important;}
  .bulk_branch_address{width:140px !important}
  .bulk_ifsc_code{width:80px !important}
  .bulk_MICR_code{width:120px !important}
  .bulk_account_type_id{width:50px !important}
  .bulk_account_type{width:140px !important}
  .bulk_account_code_id{width:200px !important}
  .bulk_name_in_account{width:140px !important}
  .bulk_nickname_in_acoount{width:140px !important}
  .bulk_account_number{width:140px !important}
  .bulk_favouring_name{width:140px !important}
  .bulk_start_date{width:110px !important}
  .bulk_end_date{width:110px !important}
  .bulk_active{width:140px !important}
  .bulk_comments{width:180px !important}
}
  .bulk_branch_name{width:100px;}
  .bulk_branch_address{width:120px;}
  .bulk_ifsc_code{width:80px;}
  .bulk_MICR_code{width:110px;}
  .bulk_account_type{width:120px;}
  .bulk_account_type_id{width:50px !important}
  .bulk_account_code_id{width:200px !important}
  .bulk_name_in_account{width:120px;}
  .bulk_nickname_in_acoount{width:140px;}
  .bulk_account_number{width:120px;}
  .bulk_favouring_name{width:140px !important}
  .bulk_start_date{width:110px;}
  .bulk_end_date{width:110px;}
  .bulk_active{width:120px;}
  .bulk_comments{width:180px;}
</style>
<div class="ajaxLoading"></div>
<span class="ui_close_btn"></span>
<h4 class="heads">
   Bank Account
    <span class="ui_close_btn"><a href="{{ url('bankaccount') }}" class="collapse-close pull-right btn-danger" ></a></span>
        </h4>
    <form method="post" action="" id="bankaccount" data-parsley-validate>
        <input type="hidden" value="" name="savestatus" id="savestatus" /> 
        {{ csrf_field() }}
       
            <div class="card">
                
<div class="card-body card-block">
    <div class="row">
                    <div class="col-md-12">
                        <div class="form-group col-md-4 row">
                            <label for="inputIsValid" class="form-control-label col-md-5"><span style="font-style:20px;color:red;">*</span>Bank Name</label>
                            <div class="col-md-7">
                                <input class="form-control bank_account_hdr_id" id="bank_account_hdr_id" name="bank_account_hdr_id" size="16" type="hidden" value="{{ $row->bank_account_hdr_id }}" readonly>
                                <input type="text" id="bank_name" name="bank_name" class="form-control bank_name" value="{{ $row->bank_name }}" required>
                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
                        <div class="form-group col-md-4 row">
                            <label for="inputIsValid" class="form-control-label col-md-5">Bank Type</label>
                            <div class="col-md-7">
                           <select name="bank_type" class="select2 bank_type " id="bank_type" >
                                <option value="Co-Operative Bank" <?php if($row->bank_type=='Co-Operative Bank'){ echo "selected"; }?>>Co-Operative Bank</option>
                                <option value="Commercial Bank" <?php if($row->bank_type=='Commercial Bank'){ echo "selected"; }?>>Commercial Bank</option>
                                <option value="Regional Rural Bank" <?php if($row->bank_type=='Regional Rural Bank'){ echo "selected"; }?>>Regional Rural Bank</option>
                                <option value="Scheduled Bank" <?php if($row->bank_type=='Scheduled Bank'){ echo "selected"; }?>>Scheduled Bank</option>
                        </select>
                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
			
                        <div class="form-group col-md-4 row">
                            <label for="inputIsValid" class="form-control-label col-md-5">Active</label>
                            <div class="col-md-7">
                         <select name="active" class="select2 active " id="active" >
                                                    <option value="Yes" <?php if($row->active=='Yes'){ echo "selected"; }?>>Yes</option>
                                                    <option value="No" <?php if($row->active=='No'){ echo "selected"; }?>>No</option>
                                                </select>
                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
                        <div class="bank_s">
                        <div class="form-group col-md-4 row">
                            <label for="inputIsValid" class="form-control-label col-md-5"><span style="font-style:20px;color:red;">*</span>Bank Source</label>
                            <div class="col-md-7">
                           <select name="bank_source" class="select2 bank_source " id="bank_source" required>
                                <option value="" <?php if($row->bank_source=='Pleace select'){ echo "selected"; }?>>Pleace select</option>
                                <option value="Supplier Account" <?php if($row->bank_source=='Supplier Account'){ echo "selected"; }?>>Supplier Account</option>
                                <option value="Customer Account" <?php if($row->bank_source=='Customer Account'){ echo "selected"; }?>>Customer Account</option>
                                <option value="Company Account" <?php if($row->bank_source=='Company Account'){ echo "selected"; }?>>Company Account</option>
                        </select>
                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>

                        <div class="Source">
                                        <div class="form-group col-md-4 sup_n">
							  <label for="active" class="form-control-label col-md-5">Supplier Name</label>
							  <div class="col-md-7" >
								<select name='supplierid' rows='5' class='select2 supplierid' id="supplierid" tabindex="4" style="width: 100%">
							  {!! $supplierid !!}
								</select>
							  </div>
                                        </div>
                                       
                                       
                                        <div class="form-group col-md-4 cus_n">
							  <label for="active" class="form-control-label col-md-5">Customer Name</label>
							  <div class="col-md-7" >
								<select name='customerid' rows='5' class='select2 customerid' id="customerid" tabindex="4" style="width: 100%">
							  {!! $customerid !!}
								</select>
							  </div>
                                        </div>
                                       
                                        
                                        <div class="form-group col-md-4 cmpy_n">
							  <label for="active" class="form-control-label col-md-5">Company Name</label>
							  <div class="col-md-7" >
								<select name='companyid' rows='5' class='select2 companyid' id="company_id" tabindex="4" style="width: 100%">
							  {!! $companyid !!}
								</select>
							  </div>
                                        </div>
                                       </div>
                                        </div>
                        
						<div class="form-group col-md-4" style="pointer-events:none;">
							  <label for="active" class="form-control-label col-md-5">Created By</label>
							  <div class="col-md-7" >
								<select name='created_by' rows='5' class='select2 created_by' id="created_by" tabindex="4">
							  {!! $created_by !!}
								</select>
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
                        

                        <!------------------------- clone row End-------------------------------->
                        <!-------------------------Linedata -------------------------------->
                        



                             <div id="preview-area" class="chandru">
                             <table class="overflow-y preview bankacc_table">


                                <thead class="thead">
                                   <th>Branch Name</th>
                                    <th>Branch Address</th>
                                    <th>IFSC Code</th>
                                    <th>MICR Code</th>
                                    <th>Account Type Id</th>
                                    <th>Account Type</th>
                                    <th>Account Code</th>
                                    <th>Name in Account</th>
                                    <th>NickName in Account</th>
                                    <th>Account Number</th>
                                    <th>Favouring Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Active</th>
                                    <th>Comments</th>
                                    <th style="width: 35px;"></th>

                                </thead>
                                <tbody class="bankacc_lines_body">
                                    
                                    <?php if(count($linedata)>=1) { ?>
                                        @foreach($linedata as $key=>$value)
                                        <tr class="rcopy clone">
                                            <td>
                                                <input type="hidden" name="bulk_bank_account_line_id[]" class="form-control input-sm bulk_bank_account_line_id" value="{{ $value->bank_account_line_id }}">
                                            </td>
                                            <td>
                                                <input type="text" name="bulk_branch_name[]" class="form-control input-sm bulk_branch_name" value="{{ $value->branch_name }}" required="required">
                                            </td>
                                            <td>
                                                <input type="text" name="bulk_branch_address[]" class="form-control input-sm bulk_branch_address" value="{{ $value->branch_address }}" required="required">
                                            </td>
                                            <td>
                                                <input type="text" name="bulk_ifsc_code[]" class="form-control input-sm bulk_ifsc_code" value="{{ $value->ifsc_code }}" required="required">
                                            </td>
                                            <td>
                                                <input type="text" name="bulk_MICR_code[]" class="form-control input-sm bulk_MICR_code" value="{{ $value->MICR_code }}" required="required">
                                            </td>
                                             <td>
                                                <input type="text" name="bulk_account_type_id[]" class="form-control input-sm bulk_account_type_id" value="{{ $value->account_type_id }}" required="required">
                                            </td>
                                           <td>
                                                <select name="bulk_account_type[]" id="bulk_account_type" class="bulk_account_type select2 " required="required">{!! $value->account_type !!}</select>
                                            </td>
                                             <td>
                                                <select name="bulk_account_code_id[]" disabled="disabled" id="bulk_account_code_id" class="bulk_account_code_id select2 ">{!! $value->account_code_id !!}</select>
                                            </td>
                                            <td>
                                                <input type="text" name="bulk_name_in_account[]" class="form-control input-sm bulk_name_in_account" value="{{ $value->name_in_account }}" required="required">
                                            </td>
                                            <td>
                                                <input type="text" name="bulk_nickname_in_acoount[]" class="form-control input-sm bulk_nickname_in_acoount" value="{{ $value->nickname_in_acoount }}" required="required">
                                            </td>
                                             <td>
                                                <input type="text" name="bulk_account_number[]" class="form-control input-sm bulk_account_number" value="{{ $value->account_number }}" required="required" maxlength="20">
                                            </td>
                                            <td>
                                                <input type="text" name="bulk_favouring_name[]" class="form-control input-sm bulk_favouring_name" value="{{ $value->favouring_name }}">
                                            </td>
                                            
                                             <td>
                                                <input type="text" name="bulk_start_date[]" class="form-control input-sm bulk_start_date datepicker" value="{{ $value->start_date }}" >
                                            </td>
                                            <td>
                                                <input type="text" name="bulk_end_date[]" class="form-control input-sm bulk_end_date datepicker" value="{{ $value->end_date }}">
                                            </td>
                                              <td>
                                                <select name="bulk_active[]" class="select2 bulk_active " id="bulk_active" >
                                                    <option value="Yes" <?php if($value->active=='Yes'){ echo "selected"; }?>>Yes</option>
                                                    <option value="No" <?php if($value->active=='No'){ echo "selected"; }?>>No</option>
                                                </select>
                                            </td>
                                              <td>
                                                <input type="text" name="bulk_comments[]" class="form-control input-sm bulk_comments" value="{{ $value->comments }}" >
                                            </td>

                                            <td><a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
                                                <input type="hidden" name="counter[]">
                                            </td>
                                        </tr>
                                        @endforeach
                                        <?php } if(count($linedata) < 1 ) { ?>
                                            <tr class="rcopy clone">
                                                <td>
                                                <input type="hidden" name="bulk_bank_account_line_id[]" class="form-control input-sm bulk_bank_account_line_id" value="">
                                            </td>
                                            <td>
                                                <input type="text" name="bulk_branch_name[]" class="form-control input-sm bulk_branch_name" value="" required="required">
                                            </td>
                                            <td>
                                                <input type="text" name="bulk_branch_address[]" class="form-control input-sm bulk_branch_address" value="" required="required">
                                            </td>
                                            <td>
                                                <input type="text" name="bulk_ifsc_code[]" class="form-control input-sm bulk_ifsc_code" value="" required="required">
                                            </td>
                                            <td>
                                                <input type="text" name="bulk_MICR_code[]" class="form-control input-sm bulk_MICR_code" value="" required="required">
                                            </td>
                                             <td>
                                                <input type="text" name="bulk_account_type_id[]" class="form-control input-sm bulk_account_type_id" value="" required="required">
                                            </td>
                                            <td>
                                                <select name="bulk_account_type[]" id="bulk_account_type" class="bulk_account_type select2 " required="required">{!! $account_type !!}</select>
                                            </td>
                                             <td>
                                                <select name="bulk_account_code_id[]" disabled="disabled" id="bulk_account_code_id" class="bulk_account_code_id select2 ">{!! $account_code_id !!} </select>
                                            </td>
                                            <td>
                                                <input type="text" name="bulk_name_in_account[]" class="form-control input-sm bulk_name_in_account" value="" required="required">
                                            </td>
                                            <td>
                                                <input type="text" name="bulk_nickname_in_acoount[]" class="form-control input-sm bulk_nickname_in_acoount" value="" required="required">
                                            </td>
                                             <td>
                                                <input type="text" name="bulk_account_number[]" class="form-control input-sm bulk_account_number" value="" required="required"  maxlength="20">
                                            </td>
                                             <td>
                                                <input type="text" name="bulk_favouring_name[]" class="form-control input-sm bulk_favouring_name" value="">
                                            </td>
                                             <td>
                                                <input type="text" name="bulk_start_date[]" class="form-control input-sm bulk_start_date datepicker" value="" required="required">
                                            </td>
                                            <td>
                                                <input type="text" name="bulk_end_date[]" class="form-control input-sm bulk_end_date datepicker" value="" required="required">
                                            </td>
                                              <td>
                                                <select name="bulk_active[]" class="select2 bulk_active " id="bulk_active">
                                                    <option value="Yes">Yes</option>
                                                    <option value="No">No</option>
                                                </select>
                                            </td>
                                              <td>
                                                <input type="text" name="bulk_comments[]" class="form-control input-sm bulk_comments" value="" >
                                            </td>

                                            <td><a class="remove remove0"><i class="fa btn-xs fa-2x fa-minus-circle rem" aria-hidden="true"></i></a>
                                                <input type="hidden" name="counter[]">
                                            </td>
                                            </tr>
                                            <?php } ?>
                                </tbody>
                            </table>
                            <input type="hidden" name="enable-masterdetail" value="true">
                        </div>
                    </div>
                    <!-------------------------Linedata End-------------------------------->

                    <div class="row">
<div class="col-md-12">
<hr class="xlg">
</div>
</div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group text-center">
                                <button type="button" class="btn save saveform" value="SAVENEW">Submit and New</button>
                                <button type="button" class="btn save saveform" value="SAVE">Submit</button>
                                <a href="{{ url('bankaccount') }}" class='btn cancel'>Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>

           
    </form>

  

    <script>
        $(document).ready(function() {
            
var data ="{{\Session::get('j_date_format')}}";
	$(".add_row").on('click',function(){
  var form = $('#bankaccount');
  form.parsley().destroy();
});
$(".add_row").relCopy(data);
   changeclassfields();

    $('.bank_name').on('keyup',function(){
		  this.value= this.value.toUpperCase();
  });
 
           $('.add_row').click(function(){
                // $('.onclickrel').trigger('click');
                  var rowCount = $('.bankacc_table tbody tr').length;
    	          var index = rowCount - 1;
             changeclassfields();
             $('.bulk_active'+index).val('Yes').change();
            });

   
            $('#savestatus').val('');
            $(document).on('click', '.saveform', function() {
                var btnval = $(this).val();
                $('#savestatus').val(savestatus);

                var url = "{{ url('bankaccountsave') }}";
                var create_url = "{{ url('bankaccountcreate') }}";
                var red_url = "{{ url('bankaccount') }}";
                validationrule('bankaccount');
                
                var form = $('#bankaccount');

                form.parsley().validate();
                var form = $('#bankaccount');
                form.parsley().validate();

                if (form.parsley().isValid()) {
                  $(".ajaxLoading").show();
                    
                    change_date();
                    var formdata = $('#bankaccount').serialize();
                    
                    $.post(url, formdata, function(data) {
                        var status = data.status;
                        var msg = '<span style="color:#090065"></span>  ' + data.message;
                        var id = data.id;
                        if (btnval != 'SAVE' && btnval != 'DRAFT') {
                            notyMsg(status, msg);
                            setTimeout(function() {
                                window.location.href = create_url;
                            }, 1500);
                        } else {
                            notyMsg(status, msg);
                            setTimeout(function() {
                                window.location.href = red_url;
                            }, 1500);
                        }
                    });
                }

            });

      $(".Source").hide();
    $(".bank_source").change(function(){
        var banksource = $(".bank_source option:selected").val();
        $(".Source").show();
        if(banksource == "Supplier Account" ){
           $('#bulk_account_code_id').attr('disabled',true);
          $('#bulk_account_code_id,.bulk_MICR_code').attr('required',false);
         $('.supplierid').attr('required',true);
         $('.companyid,.customerid').attr('required',false);
          // $(".bulk_account_code_id").hide();
            $(".sup_n").show();
             $(".cus_n").hide();
              $(".cmpy_n").hide();
        }
      else if(banksource == "Company Account" ){
         $('#bulk_account_code_id').attr('disabled',false);
        $('#bulk_account_code_id,.bulk_MICR_code').attr('required',true);
        $('.companyid').attr('required',true);
        $('.supplierid,.customerid').attr('required',false);
            $(".sup_n").hide();
             $(".cus_n").hide();
              $(".cmpy_n").show();
        }
        else  if (banksource == "Customer Account" )
        {
           $('#bulk_account_code_id').attr('disabled',true);
          $('#bulk_account_code_id,.bulk_MICR_code').attr('required',false);
          $('.customerid').attr('required',true);
          $('.supplierid,.companyid').attr('required',false);
          $(".sup_n").hide();
          $(".cus_n").show();
          $(".cmpy_n").hide();
        }
        else{
          $('#bulk_account_code_id,.bulk_MICR_code').attr('required',false);
          $('.supplierid,.companyid,.customerid,.bulk_MICR_code').attr('required',false);
           $(".sup_n").hide();
           $(".cus_n").hide();
           $(".cmpy_n").hide();
        }
    });

            var index = $('.clone').closest('tr').index();
            changeclassfields();

            $(document).on('click', '.remove', function() {
                var index = $(this).closest('tr').index();
                var rowCount = $('.bankacc_table tbody tr').length;
                if (rowCount > 1) {
                    $($(this).closest("tr")).remove();
                    removeclassfields();
                } else {
                    notyMsg('info',"You Can't Delete Atleast One row should be there");
                }
            });


$("#bank_source").trigger('change');



        });

        function changeclassfields() {
            changeClassName('bulk_bank_account_line_id');
            changeClassName('bulk_branch_name');
            changeClassName('bulk_branch_address');
            changeClassName('bulk_ifsc_code');
            changeClassName('bulk_MICR_code');
            changeClassName('bulk_account_type');
            changeClassName('bulk_name_in_account');
             changeClassName('bulk_nickname_in_acoount');
              changeClassName('bulk_account_number');
              changeClassName('bulk_favouring_name');
               changeClassName('bulk_start_date');
               changeClassName('bulk_end_date');
               changeClassName('bulk_active');
               changeClassName('bulk_comments');
               changeClassName('bulk_account_type_id');
               changeClassName('bulk_account_code_id');
        }

        function removeclassfields() {
            removeClass('bulk_bank_account_line_id');
            removeClass('bulk_branch_name');
            removeClass('bulk_branch_address');
            removeClass('bulk_ifsc_code');
            removeClass('bulk_MICR_code');
            removeClass('bulk_account_type');
            removeClass('bulk_name_in_account');
             removeClass('bulk_nickname_in_acoount');
              removeClass('bulk_account_number');
              removeClass('bulk_favouring_name');
               removeClass('bulk_start_date');
               removeClass('bulk_end_date');
               removeClass('bulk_active');
               removeClass('bulk_comments');
               removeClass('bulk_account_type_id');
               removeClass('bulk_account_code_id');
        }
        /************ Karthigaa purpose to remove row action ********************/
        function removeClass(className) {
            var rowCount = $('.bankacc_table tbody tr').length;
            for (var i = 0; i <= rowCount; i++) {
                $('.bankacc_table tbody tr').find('.' + className).removeClass(className + i);
            }
            $('.' + className).each(function(index) {
               
                $(this).addClass(className + index);
            });
        }

        function changeClassName(className) {
            $('.' + className).each(function(index) {
                $(this).removeClass(className + '0');
                $(this).addClass(className + index);
            });
        }
    </script>
    
<script>
var dateToday = new Date();
  var data ="{{\Session::get('j_date_format')}}";
    $( ".bulk_end_date" ).datepicker({
      changeMonth: true,
      dateFormat: data,
      changeYear: true,
      minDate: 0,
			maxDate: +1095,
      //maxDate: null,
      onClose: function () {
        $(this).parsley().validate();
        }

    }).attr('readonly', 'readonly');
</script>    
   
    @include('layouts.php_js_validation') @endsection