@extends('layouts.header')
@section('content')
<h3 class="text-danger">Bank Account</h3>
@include('layouts.breadcrumb')


    <form method="post" action="" id="bankaccount" data-parsley-validate>
        <input type="hidden" value="" name="savestatus" id="savestatus" /> 
        {{ csrf_field() }}
       
            <div class="card shadow-lg rounded-4 border-0">
                
			<div class="card-body card-block">
				

				        <div class="row g-3">
            <!-- Bank Name -->
            <div class="col-md-4">
                <label for="bank_name" class="form-label">
                    <span class="text-danger">*</span> Bank Name
                </label>
                <input type="hidden" name="bank_account_hdr_id" id="bank_account_hdr_id" value="{{ $row->bank_account_hdr_id }}">
                <input type="text" id="bank_name" name="bank_name" 
                       class="form-control bank_name" 
                       value="{{ $row->bank_name }}" required>
            </div>

            <!-- Bank Type -->
            <div class="col-md-4">
                <label for="bank_type" class="form-label">Bank Type</label>
                <select name="bank_type" id="bank_type" class="form-select select2">
                    <option value="Co-Operative Bank" {{ $row->bank_type=='Co-Operative Bank' ? 'selected' : '' }}>Co-Operative Bank</option>
                    <option value="Commercial Bank" {{ $row->bank_type=='Commercial Bank' ? 'selected' : '' }}>Commercial Bank</option>
                    <option value="Regional Rural Bank" {{ $row->bank_type=='Regional Rural Bank' ? 'selected' : '' }}>Regional Rural Bank</option>
                    <option value="Scheduled Bank" {{ $row->bank_type=='Scheduled Bank' ? 'selected' : '' }}>Scheduled Bank</option>
                </select>
            </div>

            <!-- Active -->
            <div class="col-md-4">
                <label for="active" class="form-label">Active</label>
                <select name="active" id="active" class="form-select select2">
                    <option value="Yes" {{ $row->active=='Yes' ? 'selected' : '' }}>Yes</option>
                    <option value="No" {{ $row->active=='No' ? 'selected' : '' }}>No</option>
                </select>
            </div>

            <!-- Bank Source -->
            <div class="col-md-4">
                <label for="bank_source" class="form-label">
                    <span class="text-danger">*</span> Bank Source
                </label>
                <select name="bank_source" id="bank_source" class="form-select select2 bank_source" required>
                    <option value="">-- Please select --</option>
                    <option value="Supplier Account" {{ $row->bank_source=='Supplier Account' ? 'selected' : '' }}>Supplier Account</option>
                    <option value="Customer Account" {{ $row->bank_source=='Customer Account' ? 'selected' : '' }}>Customer Account</option>
                    <option value="Company Account" {{ $row->bank_source=='Company Account' ? 'selected' : '' }}>Company Account</option>
                </select>
            </div>

            <!-- Supplier Name -->
            <div class="col-md-4 sup_n">
                <label for="supplierid" class="form-label">Supplier Name</label>
                <select name="supplierid" id="supplierid" class="form-select supplierid select2">
                    {!! $supplierid !!}
                </select>
            </div>

            <!-- Customer Name -->
            <div class="col-md-4 cus_n">
                <label for="customerid" class="form-label">Customer Name</label>
                <select name="customerid" id="customerid" class="form-select customerid select2">
                    {!! $customerid !!}
                </select>
            </div>

            <!-- Company Name -->
            <div class="col-md-4 cmpy_n">
                <label for="company_id" class="form-label">Company Name</label>
                <select name="companyid" id="company_id" class="form-select company_id select2">
                    {!! $companyid !!}
                </select>
            </div>

            <!-- Created By -->
            <div class="col-md-4 none">
                <label for="created_by" class="form-label">Created By</label>
                <select name="created_by" id="created_by" class="form-select select2">
                    {!! $created_by !!}
                </select>
            </div>
        </div>
				
				

				   <!-------------------------Linedata -------------------------------->

                    <div class="row mt-4">
                    <div class="col-md-12">

                             <div id="preview-area" class="table-responsive">
                             <table class="table table-bordered clone_table" style="width:200%">


                                <thead class="thead table-light">
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
                                <tbody class="clone_lines_body">
                                    
                                    <?php if(count($linedata)>=1) { ?>
                                        @foreach($linedata as $key=>$value)
                                        <tr class="rcopy clone">
                                            <td>
                                                <input type="hidden" name="bulk_bank_account_line_id[]" class="form-control input-sm bulk_bank_account_line_id" value="{{ $value->bank_account_line_id }}">
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
                                                <input type="text" name="bulk_start_date[]" class="form-control input-sm start_date" value="{{ $value->start_date }}" >
                                            </td>
                                            <td>
                                                <input type="text" name="bulk_end_date[]" class="form-control input-sm bulk_end_date" value="{{ $value->end_date }}">
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

										  <td class="text-center">
											<button type="button" class="btn btn-sm btn-danger remove-row">
											  <i class="fas fa-minus-circle"></i>
											</button>
										  </td>
                                        </tr>
                                        @endforeach
                                        <?php } if(count($linedata) < 1 ) { ?>
                                            <tr class="rcopy clone">
                                                <td>
                                                <input type="hidden" name="bulk_bank_account_line_id[]" class="form-control input-sm bulk_bank_account_line_id" value="">

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
                                                <input type="text" name="bulk_start_date[]" class="form-control input-sm start_date" value="" required="required">
                                            </td>
                                            <td>
                                                <input type="text" name="bulk_end_date[]" class="form-control input-sm bulk_end_date" value="" required="required">
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
										  <td class="text-center">
											<button type="button" class="btn btn-sm btn-danger remove-row">
											  <i class="fas fa-minus-circle"></i>
											</button>
										  </td>
                                            </tr>
                                            <?php } ?>
                                </tbody>
                            </table>
                            <input type="hidden" name="enable-masterdetail" value="true">
						  <div class="text-end">
    <button type="button" class="btn btn-success btn-sm add-row">
      <i class="fas fa-plus-circle"></i> Add Row
    </button>
  </div>		 
                        </div>
                    </div>
					                    </div>	
                    <!-------------------------Linedata End-------------------------------->



                    <div class="row mt-4 mb-3">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group text-center">
                                <button type="button" class="btn btn-success px-4 me-2 saveform" value="SAVE">Save</button>
                                <a href="{{ url('bankaccount') }}" class='btn btn-secondary px-4'>Cancel</a>
                            </div>
                        </div>
                    </div>
						
						
                </div>
				</div>
    </form>

  
				
	@endsection
	@push('scripts')				
				
				

    <script>
		
        $(document).ready(function() {
            


    $('.bank_name').on('keyup',function(){
		  this.value= this.value.toUpperCase();
  });
 


   
            $('#savestatus').val('');
            $(document).on('click', '.saveform', function() {
                var btnval = $(this).val();
                $('#savestatus').val(savestatus);

                var url = "{{ url('bankaccountsave') }}";
                var create_url = "{{ url('bankaccountcreate') }}";
                var red_url = "{{ url('bankaccount') }}";
                
                var form = $('#bankaccount');

                form.parsley().validate();
                var form = $('#bankaccount');
                form.parsley().validate();

                if (form.parsley().isValid()) {

                      var $btn = $(this);            
			          $btn.prop('disabled', true);
            
                    var formdata = $('#bankaccount').serialize();
                    
                    $.post(url, formdata, function(data) {
                        var status = data.status;
                        var msg = data.message;
                        var id = data.id;
                        if (btnval != 'SAVE' && btnval != 'DRAFT') {
                            showCustomAlert(msg,status);
                            setTimeout(function() {
                                window.location.href = create_url;
                            }, 1500);
                        } else {
                            showCustomAlert(msg,status);
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


		$("#bank_source").trigger('change');

        });

		
// Add Row
// Add Row
$(document).on('click', '.add-row', function () {

    const $lastRow = $('.clone_lines_body tr:last');
    const $newRow = $lastRow.clone(false, false);

    // Clear inputs
    $newRow.find('input').val('');
    $newRow.find('select').val('').trigger('change');

    $newRow.find('.start_date, .bulk_end_date').each(function () {
        $(this)
            .removeClass('hasDatepicker')
            .removeAttr('id');
    });

    // Destroy select2 before cloning
    $newRow.find('select.select2').each(function () {
        if ($.fn.select2 && $(this).hasClass("select2-hidden-accessible")) {
            $(this).select2('destroy');
        }
        $(this).removeAttr('data-select2-id');
        $(this).next('.select2').remove();
    });

    // Append row
    $('.clone_lines_body').append($newRow);

    // Re-init select2
    $newRow.find('select.select2').select2({ width: '100%' });

    updateLineNumbers();
});


// ================= DATE PICKERS =================

// BULK START DATE
$(document).on("focus", ".start_date", function () {

    if ($(this).hasClass('hasDatepicker')) return;

    $(this).datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        minDate: 0,
        maxDate: "+3Y",
        showAnim: "slideDown",
        yearRange: "c:+3",
        onSelect: function (selectedDate) {
            const $row = $(this).closest('tr');
            $row.find('.bulk_end_date')
                .datepicker('option', 'minDate', selectedDate);
        }
    });
});


// BULK END DATE
$(document).on("focus", ".bulk_end_date", function () {

    if ($(this).hasClass('hasDatepicker')) return;

    const $row = $(this).closest('tr');
    const startDate = $row.find('.start_date').val();

    $(this).datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        minDate: startDate ? startDate : 0,
        maxDate: "+3Y",
        showAnim: "slideDown",
        yearRange: "c:+3"
    });
});


// ================= REMOVE ROW =================
$(document).on('click', '.remove-row', function () {

    const rowCount = $('.clone_lines_body tr').length;

    if (rowCount > 1) {
        $(this).closest('tr').remove();
        updateLineNumbers();
    } else {
        showCustomAlert("You Can't Delete Atleast One row should be There", "warning");
    }
});


// ================= LINE NUMBERS =================
function updateLineNumbers() {
    $('.clone_lines_body tr').each(function (index) {
        $(this).find('.bulk_line_no').val(index + 1);
    });
}

</script>    
   
@endpush