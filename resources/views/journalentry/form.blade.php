@extends('layouts.header')
@section('content')
<h3 class="text-danger">    Journal Entry   </h3>
@include('layouts.breadcrumb')


		<div class="card shadow-lg rounded-4 border-0">
	
            <form method="post" action="" id="journal_form" data-parsley-validate>
                {{ csrf_field() }}
            <div class="card-body card-block">
							
							

      <div class="row g-3">
        <!-- Left Column -->
        <div class="col-md-6">
          <!-- Journal Name -->
          <div class="mb-3">
            <label class="form-label fw-semibold">
              <span class="text-danger">*</span> Journal Name
            </label>
            <input type="text" id="journal_name" name="journal_name"
              class="form-control journal_name"
              value="{{ $row->journal_name }}" required>
          </div>

          <!-- Journal Category -->
          <div class="mb-3">
            <label class="form-label fw-semibold">
              <span class="text-danger">*</span> Journal Category
            </label>
            <select name="journal_category" id="journal_category"
              class="form-select select2 journal_category" value="{{ $row->journal_category }}" required>
              <option value="">-- Please Select --</option>
              <option value="PURCHASE" {{ $row->journal_category == 'PURCHASE' ? 'selected' : '' }}>PURCHASE</option>
              <option value="SALES" {{ $row->journal_category == 'SALES' ? 'selected' : '' }}>SALES</option>
              <option value="EMPLOYEE" {{ $row->journal_category == 'EMPLOYEE' ? 'selected' : '' }}>EMPLOYEE</option>
              <option value="OTHERS" {{ $row->journal_category == 'OTHERS' ? 'selected' : '' }}>OTHERS</option>
            </select>
          </div>

          <!-- Source Type -->
          <div class="mb-3 hidesource sourcetypediv readonly">
            <label class="form-label fw-semibold">
              <span class="text-danger">*</span> Source Type
            </label>
            <select name="sourcetype_id" id="sourcetype_id"
              class="form-select select2 sourcetype_id" required>
            </select>
          </div>

          <!-- Source -->
          <div class="mb-3 hidesource readonly">
            <label class="form-label fw-semibold">
              <span class="text-danger">*</span> Source
            </label>
            <select name="source" id="source"
              class="form-select select2 source" required>
            </select>
          </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-6">
          <!-- Journal Date -->
          <div class="mb-3">
            <label class="form-label fw-semibold">
              <span class="text-danger">*</span> Journal Date
            </label>
            <div class="input-group">
              <input type="text" class="form-control start_date journal_date"
                id="journal_date" name="journal_date" value="{{ $row->journal_date }}" required>
              <span class="input-group-text">
                <i class="bi bi-calendar3"></i>
              </span>
            </div>
          </div>

          <!-- Journal Type -->
          <div class="mb-3 none">
            <label class="form-label fw-semibold">Journal Type</label>
            <select name="journal_type" id="journal_type"
              class="form-select select2 journal_type">
              <option value="">-- Please Select --</option>
              @foreach ([
                'MANUAL', 'PO INVOICE', 'PAYABLES', 'SALES INVOICE',
                'RECEIVABLES', 'EXPENSES', 'PAYMENT', 'RECEIPT',
                'ADVANCE RECEIPT', 'ADVANCE PAYMENT', 'ADJUSTMENTS',
                'IMPREST', 'TRAVEL','JOB CARD STORE MOVE','PAYROLL'
              ] as $type)
              <option value="{{ $type }}" {{ $row->journal_type == $type ? 'selected' : '' }}>
                {{ $type }}
              </option>
              @endforeach
            </select>
          </div>

          <!-- Journal Status -->
          <div class="mb-3 none">
            <label class="form-label fw-semibold">Journal Status</label>
            <select name="journal_status" id="journal_status"
              class="form-select select2 journal_status">
              <option value="">-- Please Select --</option>
              @foreach (['DRAFT', 'SUBMITTED', 'APPROVED', 'REJECTED'] as $status)
              <option value="{{ $status }}" {{ $row->journal_status == $status ? 'selected' : '' }}>
                {{ $status }}
              </option>
              @endforeach
            </select>
          </div>

          <!-- Journal Reference (hidden) -->
          <div class="mb-3" style="display:none;">
            <label class="form-label fw-semibold">Journal Reference</label>
            <input type="text" id="journal_reference" name="journal_reference"
              class="form-control journal_reference"
              value="{{ $row->journal_reference }}">
          </div>
        </div>
      </div>



<div class="row mt-4">
  <div class="col-12 linetable">
    <div id="preview-area" class="table-responsive">
      <table class="table table-bordered clone_table">
        <thead class="table-light">
			
                    <tr>
                       
                        <th>Line No</th>
                        <th>Journal Date</th>
                        <th  class="employeediv">Employee Name</th>
                        <th  class="employeediv">Payment Number</th>
                        <th  class="employeediv">Payment Amount</th>
                        <th>Account</th>
                        <th>Debit Amount</th>
                        <th>Credit Amount</th>
                        <th></th>
                    </tr>

                </thead>
                
                <tbody class="clone_lines_body">
					
                    <?php if(count($linedata)>=1) { ?>
                        @foreach($linedata as $key=>$value)

                        <tr>
                            <td>
                                <input type="hidden" name="bulk_f_journal_entry_line_id[]" class="form-control input-sm bulk_f_journal_entry_line_id" value="{{ $value->f_journal_entry_line_id }}">
                                 <input type="hidden" name="bulk_reference_id[]" class="form-control input-sm bulk_reference_id" value="">
                                
                                <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="" readonly="readonly" ></td>
 <td class="readonly">
                                <input type="text" name="bulk_journal_date[]" class="form-control start_date input-sm bulk_journal_date" value="{{ $value->journal_date }}">
                            </td>
                            <td  class="employeediv readonly">
                                <select name="bulk_employee_id[]"  class="bulk_employee_id select2 parsley-validated employeereq" >{!! $value->employee_id !!}</select>
                            </td>
                            <td  class="employeediv readonly">
                                <select name="bulk_payment_id[]"  class="bulk_payment_id select2 parsley-validated employeereq" >{!! $value->payment_id !!}</select>
                            </td>
                            <td class="employeediv readonly">
                                <input type="text" name="bulk_payment_amount[]" class="form-control input-sm bulk_payment_amount" value="{{ $value->payment_amount }}">
                            </td>
                            <td class="readonly">
                                <select name="bulk_account_id[]"  class="bulk_account_id select2 parsley-validated" required="required">{!! $value->account_id !!}</select>
                            </td>
                            <td>
                                <input type="text" name="bulk_debit_amount[]" class="form-control input-sm bulk_debit_amount" value="{{ $value->debit_amount }}">
                            </td>
                            <td>
                                <input type="text" name="bulk_credit_amount[]" class="form-control input-sm bulk_credit_amount" value="{{ $value->credit_amount }}">
                            </td>
                <td class="text-center">
                  <button type="button" class="btn btn-sm btn-danger remove-row">
                    <i class="fas fa-minus-circle"></i>
                  </button>
                </td>
                        </tr>
                        @endforeach
                       
                        <?php } if(count($linedata) < 1 ) { ?>
                            <tr>
                                <td>
                                <input type="hidden" name="bulk_f_journal_entry_line_id[]" class="form-control input-sm bulk_f_journal_entry_line_id" value="">
                                <input type="hidden" name="bulk_reference_id[]" class="form-control input-sm bulk_reference_id" value="">

                                <input type="text" name="bulk_line_no[]" class="form-control input-sm bulk_line_no" value="" readonly="readonly" >
                            </td>
                            <td class="readonly">
                                <input type="text" name="bulk_journal_date[]" class="form-control start_date input-sm bulk_journal_date" value="">
                            </td>
                            <td class="employeediv readonly">
                                <select name="bulk_employee_id[]"  class="bulk_employee_id select2 parsley-validated employeereq">{!! $employee_id !!}</select>
                            </td>
                             <td  class="employeediv readonly">
                                <select name="bulk_payment_id[]"  class="bulk_payment_id select2 parsley-validated employeereq" ></select>
                            </td>
                             <td class="employeediv readonly">
                                <input type="text" name="bulk_payment_amount[]" class="form-control input-sm bulk_payment_amount" value="">
                            </td>
                            <td class="readonly">
                                <select name="bulk_account_id[]"  class="bulk_account_id select2 parsley-validated" required="required">{!! $account_id !!}</select>
                            </td>
                            <td>
                                <input type="text" name="bulk_debit_amount[]" class="form-control input-sm bulk_debit_amount" value="" required="required">
                            </td>
                            <td>
                                <input type="text" name="bulk_credit_amount[]" class="form-control input-sm bulk_credit_amount" value="" required="required">
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
		
		      <div class="text-end">
        <button type="button" class="btn btn-success btn-sm add-row">
          <i class="fas fa-plus-circle"></i> Add Row
        </button>
      </div>
		
            <input type="hidden" name="enable-masterdetail" value="true">
		
		
            <div class="col-lg-12 col-md-12">
                <div class="form-group text-center">
                            <?php if($aprvidenty=="") { ?> 
                    <input type="hidden" name="submit_type" class="submit_type" id="submit_type">
                    <button type="button" class="btn btn-secondary saveform px-4 me-2" value="DRAFT">Draft</button>
                    <button name="submit" type="button" class="btn btn-success px-4 me-2 saveform" value="SAVE">Save</button>
                    <a href="{{ url('journalentry') }}" class='btn btn-danger px-4 me-2'>Cancel</a>
                     <?php } else { ?>
                        <button type="button" class="btn btn-success px-4 me-2 saveform" value="APPROVED">Approve</button>
                        <button type="button" class="btn btn-danger px-4 me-2 saveform" value="REJECTED">Reject</button>
			  <a href="{{ url('journalapproval') }}" class='btn btn-outline-danger px-4 me-2'>Cancel</a>
                        <?php } ?>
                </div>
            </div>
		
        </div>
    </div> 
</div>
       

     </div>           
 
</form>
	
</div>

@endsection
@push('scripts')

<script>
	
		$(document).ready(function(){
	
         $('.journaldate,.journaltype,.journalstatus').css('pointer-events','none');   
       <?php if($aprvidenty=="INITIATED")
         { ?>
            $('input').attr('readonly', true);
			$('select').attr('readonly', true);
			$('select').css('pointer-events', 'none');

         <?php } ?>
        <?php if($aprvidenty=="SUBMITTED")
         { ?>
          $('input').attr('readonly', true);
			$('select').attr('readonly', true);
			$('select').css('pointer-events', 'none');
			$('.readonly').css('pointer-events', 'none');

         <?php } ?>       
  var data ="{{\Session::get('j_date_format')}}";


             $(".employeediv").hide();


	$(document).on("change", ".journal_category", function () {
    var cat = $(".journal_category option:selected").val();

    // Reset dropdowns
    $(".source").val("").trigger("change");
    $(".sourcetype_id").val("").trigger("change");

    // Hide / Show based on category
    $(".req, .hidesource").toggle(cat === "PURCHASE" || cat === "SALES");
    $(".employeediv").toggle(cat === "EMPLOYEE");

    $(".source, .sourcetype_id, .employeereq").removeAttr("required");
    $(".bulk_payment_id").removeAttr("required");

    if (cat === "PURCHASE") {

        loadDropdown(".sourcetype_id", "m_supplier_t:supplier_id:supplier_name", "-- Please Select Supplier --");

        // Dependent: Supplier → Bill Number
        $(document).off("change", ".sourcetype_id").on("change", ".sourcetype_id", function () {
            let supplier_id = $(this).val();
            if (supplier_id) {
                loadDropdown(
                    ".source",
                    "p_po_invoice_hdr_t:po_invoice_id:bill_number",
                    "-- Please Select Bill Number --",
                    "supplier_id=" + supplier_id
                );
            } else {
                resetDropdown(".source", "-- Please Select Bill Number --");
            }
        });
    }

    else if (cat === "SALES") {

        loadDropdown(".sourcetype_id", "m_customers_t:customer_id:customer_name", "-- Please Select Customer --");

        // Dependent: Customer → Invoice Number
        $(document).off("change", ".sourcetype_id").on("change", ".sourcetype_id", function () {
            let customer_id = $(this).val();
            if (customer_id) {
                loadDropdown(
                    ".source",
                    "s_invoice_hdr_t:invoice_hdr_id:invoice_number",
                    "-- Please Select Invoice Number --",
                    "ship_to_customer_id=" + customer_id
                );
            } else {
                resetDropdown(".source", "-- Please Select Invoice Number --");
            }
        });
    }

    else if (cat === "EMPLOYEE") {

        $(".employeediv").show();
        $(".sourcetypediv, .hidesource, .req").hide();
        $(".employeereq").attr("required", true);

        $(document).off("change", ".bulk_employee_id").on("change", ".bulk_employee_id", function () {
            let $row = $(this).closest("tr");
            let index = $row.index();
            let emp_id = $(this).val();

            if (emp_id) {
                loadDropdown(
                    `.bulk_payment_id:eq(${index})`,
                    "p_payments_t:payment_id:payment_number",
                    "-- Please Select Payment Number --",
                    "employee_id=" + emp_id
                );
            }
        });

        // Fetch payment amount on change
        $(document).off("change", ".bulk_payment_id").on("change", ".bulk_payment_id", function () {
            let $row = $(this).closest("tr");
            let index = $row.index();
            let payment_id = $(this).val();

            if (payment_id) {
                $.get("{{ URL::to('getpaymentamount') }}/" + payment_id, function (data) {
                    $row.find(".bulk_payment_amount").val(data[0]?.payment_amount || "");
                });
            }
        });
    }

    else if (cat === "OTHERS") {
        $(".req, .hidesource, .employeediv").hide();
        $(".source, .sourcetype_id, .employeereq").removeAttr("required");
    }
});


   $('#journal_date').change(function()
                {
                    var j_date=$("#journal_date").val();
                    console.log("jdate"+j_date);
                    $('.bulk_journal_date').val(j_date);
}); 


function loadDropdown(selector, tableDef, placeholder, parentCond = "") {
    let url = "{{ URL::to('jcomboformlogin') }}" + "?table=" + tableDef + "&order_by=" + tableDef.split(":")[2] ;
    if (parentCond) url += "&parent=" + parentCond;

    $.ajax({
        url: url,
        type: "GET",
        beforeSend: function () {
            $(selector).html('<option value="">Loading...</option>');
        },
        success: function (data) {
            if (typeof data === "string") {
                try {
                    data = JSON.parse(data);
                } catch (e) {
                    console.error("Invalid JSON:", data);
                    return;
                }
            }

            $(selector).html(`<option value="">${placeholder}</option>`);
            $.each(data, function (i, item) {
                $(selector).append(`<option value="${item.val}">${item.option_name}</option>`);
            });

            $(selector).trigger("change.select2");
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", error);
        }
    });
}

function resetDropdown(selector, placeholder) {
    $(selector).html(`<option value="">${placeholder}</option>`).trigger("change.select2");
}
	
	

	



   
        

                    
 /* Purpose For Save Function*/      
      
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
                 showCustomAlert('error','Debit Amount and Credit amount Should be same');
            } 
            else
            {
                var btnval = $(this).val();

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

             var formdata = $('#journal_form').serialize();
            $.post(url, formdata, function(data)
            {
            var status = data.status;
            var msg = data.message;
            var id = data.id;
            var edit_url = "{{ url('journalentrycreate') }}/" + id;
              
            if (btnval != 'SAVE' && btnval != 'DRAFT')
            {
            showCustomAlert(msg,status);
            setTimeout(function(){
            window.location.href = "{{url('journalapproval')}}";
            }, 1500);
            }
              
            else
            {
            showCustomAlert(msg,status);
            setTimeout(function(){
            window.location.href = red_url;
            }, 1500);
            }
            
            });
            }
 }
		
    });

});

	
	
 // Add Row

$(document).on('click', '.add-row', function () {

    const $lastRow = $('.clone_lines_body tr:last');
    const $newRow  = $lastRow.clone(false, false); // clone without events

    // 🔹 Clear all input fields
    $newRow.find('input').val('');

    // 🔹 Clear selects
    $newRow.find('select').val('');

    // 🔹 Remove Select2 completely before reinit
    $newRow.find('select.select2').each(function () {
        if ($(this).hasClass("select2-hidden-accessible")) {
            $(this).select2('destroy');
        }
        $(this).removeAttr('data-select2-id');
        $(this).next('.select2').remove();
    });

    // 🔹 Remove datepicker artifacts
    $newRow.find('.ui-datepicker-trigger').remove();
    $newRow.find('.hasDatepicker').removeClass('hasDatepicker').removeAttr('id');

    // 🔹 Append new row first
    $('.clone_lines_body').append($newRow);

    // 🔹 Set journal date only for NEW ROW
    var j_date = $("#journal_date").val();
    $newRow.find('.bulk_journal_date').val(j_date);

    // 🔹 Reinitialize Select2 only in new row
    $newRow.find('select.select2').select2({
        width: '100%'
    });

    // 🔹 Reinitialize Datepicker only in new row
    $newRow.find('.bulk_journal_date').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        showAnim: "slideDown"
    });

    // 🔹 Update row numbers
    updateLineNumbers();
});

function updateLineNumbers() {
    $('.clone_lines_body tr').each(function (index) {
        $(this).find('.bulk_line_no').val(index + 1);
    });
}


function reinitDatepicker($row) {

    $row.find('.datepicker').each(function () {
        $(this).removeClass('hasDatepicker').removeAttr('id'); 
        $(this).datepicker({
            dateFormat: 'yy-mm-dd', 
            changeMonth: true,
            changeYear: true,
            autoclose: true
        });
    });
}




  // Remove button
  $(document).on('click', '.remove-row', function () {
    const rowCount = $('.clone_lines_body tr').length;
    if (rowCount > 1) {
      $(this).closest('tr').remove();
      updateLineNumbers();
    } else {
      showCustomAlert("You Can't Delete Atleast One row should be There", "warning");
    }
  });


</script>

@endpush