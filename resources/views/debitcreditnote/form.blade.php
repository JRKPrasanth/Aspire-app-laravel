@extends('layouts.header')
@section('content')
<h3 class="text-danger"> Debit/Credit Note </h3>
@include('layouts.breadcrumb')


<form method="post" action="" id="note_form" data-parsley-validate>
    {{ csrf_field() }}

    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        </div>

        <div class="card-body card-block p-4">
            <div class="row g-4">

                <!-- ======== Column 1 ======== -->
                <div class="col-md-4">
                    <input type="hidden" class="form-control debitcredit_id" id="debitcredit_id" name="debitcredit_id"
                        value="{{ $row->debitcredit_id }}">
                    <input type="hidden" class="form-control reference_id" id="reference_id" name="reference_id"
                        value="{{ $row->reference_id }}">
                    <input type="hidden" class="form-control debitcredit_status" id="debitcredit_status"
                        name="debitcredit_status" value="{{ $row->debitcredit_status }}">

                    <!-- Source Type -->
                    <div class="form-group row mb-3">
                        <label class="form-control-label col-md-5">
                            <span class="text-danger">*</span> Source Type
                        </label>
                        <div class="col-md-7 sel2 apprreadonlydiv">
                            <select name="source_type" class="form-control select2 source_type" data-show-subtext="true"
                                data-live-search="true" required>
                                <option value="">--Please Select--</option>
                                <option value="DEBIT" {{ $row->source_type == 'DEBIT' ? 'selected' : '' }}>Debit
                                </option>
                                <option value="CREDIT" {{ $row->source_type == 'CREDIT' ? 'selected' : '' }}>Credit
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Customer Name -->
                    <div class="form-group row mb-3 customerdiv">
                        <label class="form-control-label col-md-5">Customer Name</label>
                        <div class="col-md-7">
                            <select name="customer_id" class="select2 customer_id form-control">
                                {!! $customer_id !!}
                            </select>
                        </div>
                    </div>

                    <!-- Debit Total -->
                    <div class="form-group row mb-3 Debit">
                        <label class="form-control-label col-md-5">Debit Total</label>
                        <div class="col-md-7">
                            <input type="text" id="debitcredit_amount" name="debitcredit_amount"
                                class="form-control debitcredit_amount chckclick" value="{{ $row->debitcredit_amount }}"
                                readonly>
                        </div>
                    </div>

                    <!-- Credit Total -->
                    <div class="form-group row mb-3 CRedit">
                        <label class="form-control-label col-md-5">Credit Total</label>
                        <div class="col-md-7">
                            <input type="text" id="debitcredit_amount" name="debitcredit_amount"
                                class="form-control debitcredit_amount chckclick" value="{{ $row->debitcredit_amount }}"
                                readonly>
                        </div>
                    </div>

                    <!-- Round Off -->
                    <div class="form-group row mb-3">
                        <label class="form-control-label col-md-5">Round Off</label>
                        <div class="col-md-7">
                            <input type="text" id="round_off" name="round_off" class="form-control round_off chckclick"
                                value="{{ $row->round_off }}">
                        </div>
                    </div>
                </div>

                <!-- ======== Column 2 ======== -->
                <div class="col-md-4">
                    <!-- Debit/Credit Type -->
                    <div class="form-group row mb-3">
                        <label class="form-control-label col-md-5">
                            <span class="text-danger">*</span> Debit/Credit Type
                        </label>
                        <div class="col-md-7 sel2 apprreadonlydiv">
                            <select name="debitcredit_type" class="form-control select2 debitcredit_type" required>
                                <option value="">--Please Select--</option>
                                <option value="SUPPLIER" {{ $row->debitcredit_type == 'SUPPLIER' ? 'selected' : ''
                                    }}>SUPPLIER</option>
                                <option value="CUSTOMER" {{ $row->debitcredit_type == 'CUSTOMER' ? 'selected' : ''
                                    }}>CUSTOMER</option>
                            </select>
                        </div>
                    </div>

                    <!-- Supplier Name -->
                    <div class="form-group row mb-3 supplierdiv">
                        <label class="form-control-label col-md-5">Supplier Name</label>
                        <div class="col-md-7">
                            <select name="supplier_id" class="select2 supplier_id form-control">
                                {!! $supplier_id !!}
                            </select>
                        </div>
                    </div>

                    <!-- Debit Date -->
                    <div class="form-group row mb-3 Debit">
                        <label class="form-control-label col-md-5 text-danger">Debit Date</label>
                        <div class="col-md-7 apprreadonlydiv">
                            <input class="form-control debitcredit_date start_date" id="Debit_date"
                                name="debitcredit_date1" type="text" value="{{ $row->debitcredit_date }}" required>
                            <input type="hidden" id="invoice_date" value="{{ $row->debitcredit_date }}">
                            <input type="hidden" name="source" value="{{ $row->source }}">
                        </div>
                    </div>

                    <!-- Credit Date -->
                    <div class="form-group row mb-3 CRedit">
                        <label class="form-control-label col-md-5 text-danger">Credit Date</label>
                        <div class="col-md-7 apprreadonlydiv">
                            <input class="form-control debitcredit_date start_date" id="credit_date"
                                name="debitcredit_date2" type="text" value="{{ $row->debitcredit_date }}" required>
                            <input type="hidden" id="invoice_date" value="{{ $row->debitcredit_date }}">
                            <input type="hidden" name="source" value="{{ $row->source }}">
                        </div>
                    </div>

                    <!-- Invoice No -->
                    <div class="form-group row mb-3">
                        <label class="form-control-label col-md-5">Invoice No</label>
                        <div class="col-md-7">
                            <select name="invoice_no" class="select2 invoice_no form-control">
                                {!! $invoice_no !!}
                            </select>
                        </div>
                    </div>
                </div>

                <!-- ======== Column 3 ======== -->
                <div class="col-md-4">
                    <!-- Reference No -->
                    <div class="form-group row mb-3">
                        <label class="form-control-label col-md-5">Reference No</label>
                        <div class="col-md-7">
                            <input type="text" id="reference_no" name="reference_no"
                                class="form-control reference_no chckclick" value="{{ $row->reference_no }}" required>
                        </div>
                    </div>

                    <!-- Debit No -->
                    <div class="form-group row mb-3 Debit">
                        <label class="form-control-label col-md-5">Debit No</label>
                        <div class="col-md-7">
                            <input type="text" id="debitcredit_no" name="debitcredit_no"
                                class="form-control debitcredit_no chckclick" value="" readonly>
                        </div>
                    </div>

                    <!-- Credit No -->
                    <div class="form-group row mb-3 CRedit">
                        <label class="form-control-label col-md-5">Credit No</label>
                        <div class="col-md-7">
                            <input type="text" id="debitcredit_no" name="debitcredit_no"
                                class="form-control debitcredit_no chckclick" value="" readonly>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row mt-2">
                <div class="col-md-12">

                    <div id="preview-area" class="table-responsive">
                        <table class="table table-bordered clone_table">
                            <thead class="table-light">
                                <tr>

                                    <th class="">Description</th>
                                    <th class="">Account Code</th>
                                    <th class="">Line Amount</th>
                                    <th class="tax">HSN Code</th>
                                    <th class="tax">Tax Group</th>
                                    <th class="tax">Tax Amount</th>
                                    <th>Remarks</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="clone_lines_body">
                                <?php if (count($linedata) >= 1) { ?>
                                    @foreach($linedata as $key=>$value)
                                    <tr>
                                        <td><input type="hidden" name="bulk_debitcredit_line_id[]"
                                                class="form-control  bulk_debitcredit_line_id "
                                                value="{{$value->debitcredit_line_id}}">
                                            <input type="hidden" name="bulk_debitcredit_id[]"
                                                class="form-control  bulk_debitcredit_id "
                                                value="{{$value->debitcredit_id}}">

                                            <input type="text" name="bulk_description[]"
                                                class="form-control  bulk_description " value="{{$value->description}}">
                                        </td>
                                        <td class="apprreadonlydiv">
                                            <select name="bulk_debitcredit_account_id[]" id="bulk_debitcredit_account_id"
                                                class="select2 bulk_debitcredit_account_id">{!!
                                                $value->debitcredit_account_id !!}</select>
                                        </td>
                                        <td class="">
                                            <input type="text" name="bulk_debitcredit_line_amount[]"
                                                class="form-control  bulk_debitcredit_line_amount "
                                                value="{{$value->debitcredit_line_amount}}">
                                        </td>
                                        <td class="tax apprreadonlydiv">
                                            <select name="bulk_gst_code_id[]" id="bulk_gst_code_id"
                                                class="select2 bulk_gst_code_id">{!! $value->gst_code_id !!}</select>
                                        </td>
                                        <td class="tax apprreadonlydiv">
                                            <select name="bulk_tax_group_id[]" id="bulk_tax_group_id"
                                                class="select2 bulk_tax_group_id">{!! $value->tax_group_id !!}</select>
                                        </td>
                                        <td class="tax">
                                            <input type="text" name="bulk_tax_amount[]"
                                                class="form-control  bulk_tax_amount " value="{{$value->tax_amount}}"
                                                readonly>
                                        </td>

                                        <td>
                                            <input type="text" name="bulk_remarks[]" class="form-control bulk_remarks "
                                                value="{{$value->remarks}}">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-danger remove-row">
                                                <i class="fas fa-minus-circle"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    @endforeach
                                <?php }
                                if (count($linedata) < 1) { ?>
                                    <tr class="rcopy clone">
                                        <td><input type="hidden" name="bulk_debitcredit_line_id[]"
                                                class="form-control  bulk_debitcredit_line_id " value="">
                                            <input type="hidden" name="bulk_debitcredit_id[]"
                                                class="form-control  bulk_debitcredit_id " value="">
                                            <input type="text" name="bulk_description[]"
                                                class="form-control  bulk_description " value="">
                                        </td>
                                        <td class="">
                                            <select name="bulk_debitcredit_account_id[]" id="bulk_debitcredit_account_id"
                                                class="select2 bulk_debitcredit_account_id">{!! $debitcredit_account_id
                                                !!}</select>
                                        </td>

                                        <td class="">
                                            <input type="text" name="bulk_debitcredit_line_amount[]"
                                                class="form-control  bulk_debitcredit_line_amount" value="">
                                        </td>
                                        <td class="tax">
                                            <select name="bulk_gst_code_id[]" id="bulk_gst_code_id"
                                                class="select2 bulk_gst_code_id">{!! $gst_code_id !!}</select>
                                        </td>
                                        <td class="tax">
                                            <select name="bulk_tax_group_id[]" id="bulk_tax_group_id"
                                                class="select2 bulk_tax_group_id">{!! $tax_group_id !!}</select>
                                        </td>
                                        <td class="tax">
                                            <input type="text" name="bulk_tax_amount[]"
                                                class="form-control  bulk_tax_amount " value="" readonly>
                                        </td>

                                        <td>
                                            <input type="text" name="bulk_remarks[]" class="form-control bulk_remarks "
                                                value="">
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

                    </div>
                </div>
            </div>


            <div class="row mt-4 mb-3">
                <div class="col-lg-12 col-md-12">
                    <input type="hidden" name="submit_type" class="submit_type" value="" />
                    <div class="form-group text-center actionbtn">
                        <?php if ($aprvidenty == "") { ?>
                            <button name="submit" type="button" class="btn btn-success px-4 me-2 saveform"
                                value="INITIATED">Submit</button>
                            <a class='btn btn-danger px-4 me-2' onclick='location.href ="{{ url("debitcreditnote") }}"'>Cancel</a>
                        <?php } else { ?>
                            <button type="button" class="btn btn-success px-4 me-2 saveform"
                                value="APPROVED">Approve</button>
                            <button type="button" class="btn btn-danger px-4 me-2 saveform" value="REJECTED">Reject</button>
                            <a href="{{ url('debitcreditapproval') }}" class='btn btn-outline-danger px-4 me-2'>Cancel</a>
                        <?php } ?>
                    </div>
                </div>
            </div>


        </div>
    </div>
</form>



@endsection
@push('scripts')

<script>

    $(document).ready(function () {

        var decimal = "<?php echo \Session('decimal'); ?>";

$(document).on('keyup change', '.bulk_tax_group_id, .bulk_debitcredit_line_amount, .round_off', function () {

    var $row = $(this).closest('tr');

    // Get values from current row
    var expense_amt = parseFloat($row.find('.bulk_debitcredit_line_amount').val()) || 0;

    var taxgrp = parseFloat(
        $row.find('.bulk_tax_group_id option:selected').attr('data-display')
    ) || 0;

    // Calculate tax for this row
    var taxamount = (expense_amt * taxgrp) / 100;
    $row.find('.bulk_tax_amount').val(taxamount.toFixed(2));

    // Recalculate totals
    var sum = 0;
    var sumtax = 0;

    $('.bulk_debitcredit_line_amount').each(function () {
        var v = parseFloat($(this).val());
        if (!isNaN(v)) sum += v;
    });

    $('.bulk_tax_amount').each(function () {
        var v = parseFloat($(this).val());
        if (!isNaN(v)) sumtax += v;
    });

    var sumtotal = sum + sumtax;
    var sumreverse = sum;

    // TDS calculation
    var tds_prcnt = parseFloat($('.tds_prcnt option:selected').val()) || 0;
    var tds_amount = (sum * tds_prcnt) / 100;

    var reverse_charge = $('.reverse_charge:checked').val();

    var finalAmount = 0;

    if (reverse_charge == 1) {
        if (tds_prcnt > 0) {
            $('.tds_amount').val(tds_amount.toFixed(2));
            finalAmount = sumtotal - tds_amount - sumtax;
        } else {
            finalAmount = sumreverse;
        }
    } else {
        if (tds_prcnt > 0) {
            $('.tds_amount').val(tds_amount.toFixed(2));
            finalAmount = sumtotal - tds_amount;
        } else {
            finalAmount = sumtotal;
        }
    }

    finalAmount = isNaN(finalAmount) ? 0 : finalAmount;
    $('.debitcredit_amount').val(finalAmount.toFixed(2));
});



        $(document).on('keyup', '.round_off', function () {
            var round = $(this).val();
            var totalwithrnd = 0;
            var total = $(".debitcredit_amount").val();
            if (round != "") {
                totalwithrnd = parseFloat(total) + parseFloat(round);
                totalwithround = isNaN((totalwithrnd)) ? 0 : (totalwithrnd);
                $('.debitcredit_amount').val(totalwithround);
            }
        });


        $(document).on('change', '.debitcredit_type', function () {
            const debitcredit_type = $(this).val();

            // Clear previous invoice options
            $(".invoice_no").empty().append('<option value="">-- Select Invoice --</option>');

            // Remove previous event handlers for supplier_id and customer_id to avoid duplicate binding
            $(".supplier_id").off('change.debitcredit');
            $(".customer_id").off('change.debitcredit');

            if (debitcredit_type === "SUPPLIER") {

                // Bind supplier change handler (namespaced to .debitcredit)
                $(".supplier_id").on('change.debitcredit', function () {
                    const supplier_id = $(this).val();

                    if (supplier_id) {
                        const condition = "supplier_id=" + supplier_id;
                        const url = "{{ URL::to('jcomboformlogin?table=p_po_invoice_hdr_t:po_invoice_id:bill_number') }}"
                            + "&order_by=bill_number"
                            + "&parent=" + condition;

                        $.ajax({
                            url: url,
                            type: 'GET',
                            success: function (data) {
                                // Ensure it's valid JSON
                                if (typeof data === "string") {
                                    try { data = JSON.parse(data); } catch (e) { console.error("Invalid JSON:", data); return; }
                                }

                                const $invoice = $(".invoice_no");
                                $invoice.empty().append('<option value="">-- Select Invoice --</option>');

                                $.each(data, function (i, item) {
                                    $invoice.append(`<option value="${item.val}">${item.option_name}</option>`);
                                });

                                $invoice.trigger('change.select2');
                            },
                            error: function () {
                                console.error("Error loading supplier invoices");
                            }
                        });
                    } else {
                        $(".invoice_no").empty().append('<option value="">-- Select Invoice --</option>');
                    }
                });

            } else {
                // Bind customer change handler (namespaced)
                $(".customer_id").on('change.debitcredit', function () {
                    const customer_id = $(this).val();

                    if (customer_id) {
                        const condition = "ship_to_customer_id=" + customer_id;
                        const url = "{{ URL::to('jcomboformlogin?table=s_invoice_hdr_t:invoice_hdr_id:invoice_number') }}"
                            + "&order_by=invoice_number"
                            + "&parent=" + condition;

                        $.ajax({
                            url: url,
                            type: 'GET',
                            success: function (data) {
                                // Ensure it's valid JSON
                                if (typeof data === "string") {
                                    try { data = JSON.parse(data); } catch (e) { console.error("Invalid JSON:", data); return; }
                                }

                                const $invoice = $(".invoice_no");
                                $invoice.empty().append('<option value="">-- Select Invoice --</option>');

                                $.each(data, function (i, item) {
                                    $invoice.append(`<option value="${item.val}">${item.option_name}</option>`);
                                });

                                $invoice.trigger('change.select2');
                            },
                            error: function () {
                                console.error("Error loading customer invoices");
                            }
                        });
                    } else {
                        $(".invoice_no").empty().append('<option value="">-- Select Invoice --</option>');
                    }
                });
            }
        });




        <?php if ($row->debitcredit_id == "") { ?>
            $(".supplierdiv").hide();
            $(".customerdiv").hide();
            $(".Debit,.CRedit").hide();
        <?php } ?>




        <?php if ($row->debitcredit_type == "SUPPLIER") { ?>
            $(".supplierdiv").show();
            $(".customerdiv").hide();
            $(".tax").show();


        <?php } else if ($row->debitcredit_type == "CUSTOMER") { ?>
                $(".supplierdiv").hide();
                $(".customerdiv").show();
                $(".tax").show();

        <?php } ?>


        <?php if ($row->source_type == "DEBIT") { ?>
            $(".Debit").show();

            $(".CRedit").hide();
            $("#credit_date").removeAttr('required');

        <?php } else if ($row->source_type == "CREDIT") { ?>
                $(".Debit").hide();
                $("#Debit_date").removeAttr('required');
                $(".Credit").show();


        <?php } ?>


        $(".debitcredit_type").change(function () {
            var debitcredit_type = $(".debitcredit_type option:selected").val();
            if (debitcredit_type == "SUPPLIER") {
                $(".supplierdiv").show();
                $(".customerdiv").hide();
                $(".tax").show();
            }
            else if (debitcredit_type == "CUSTOMER") {
                $(".supplierdiv").hide();
                $(".customerdiv").show();
                $(".tax").show();
            }
        });

        $(".source_type").change(function () {
            var source_type = $(".source_type option:selected").val();
            if (source_type == "DEBIT") {
                $(".CRedit").hide();
                $("#credit_date").removeAttr('required');
                $(".Debit").show();
            }
            else if (source_type == "CREDIT") {
                $(".Debit").hide();
                $("#Debit_date").removeAttr('required');
                $(".CRedit").show();

            }

        });



        <?php if ($aprvidenty == "INITIATED") { ?>
            $('input').attr('readonly', true);
            $('select').attr('readonly', true);
            $('select').css('pointer-events', 'none');
            $('.paidthrghdiv,.supplierdiv,.gsttreatdiv,.taxgrpdiv,.expensediv,.customerdiv,.apprreadonlydiv').css('pointer-events', 'none');

        <?php } ?>
        $(document).on('change', '.debitcredit_type', function () {
            var radio = $('input[name=debitcredit_type]:checked').val();
            if (radio == "Labour") {
                $('#hsn').text("SAC Code");
            }
            else {
                $('#hsn').text("HSN Code");
            }
        });


        /*Validation*/
        $(document).on('keypress', '.debitcredit_line_amount', function (ev) {
            var regex = new RegExp("^[0-9.]+$");
            var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
            if (regex.test(str)) {
                return true;
            }
            ev.preventDefault();
            return false;
        });



        $(document).on('click', '.saveform', function () {

            var btnval = $(this).val();

            if (btnval == 'INITIATED') {
                $("#debitcredit_status").val('INITIATED');
            }

            else if (btnval == 'APPROVED') {
                $("#debitcredit_status").val('APPROVED');
            }
            else {
                $("#debitcredit_status").val('REJECTED');
            }
            $('#savestatus').val(btnval);
            var url = "{{ url('debitcreditsave') }}";
            var red_url = "{{url('debitcreditnote')}}"


            validationrule('note_form');
            var form = $('#note_form');
            form.parsley().validate();
            if (form.parsley().isValid()) {
			var $btn = $(this);            
			$btn.prop('disabled', true);
                var formdata = $('#note_form').serialize();

                $.post(url, formdata, function (data) {

                    var status = data.status;
                    var msg = data.message;
                    var id = data.id;
                    var edit_url = "{{ url('debitcreditcreate') }}/" + id;
                    if (btnval != 'SAVE' && btnval != 'DRAFT') {
                        showCustomAlert(msg, status);
                        setTimeout(function () {
                            window.location.href = red_url;
                        }, 1500);
                    }
                    else {
                        showCustomAlert(msg, status);
                        setTimeout(function () {
                            window.location.href = red_url;
                        }, 1500);
                    }
                });
            }
        });


// Add Row
$(document).on('click', '.add-row', function () {
    const $lastRow = $('.clone_lines_body tr:last');
    const $newRow = $lastRow.clone(false, false); // clone without events or data

    // Clear all input and select values in the cloned row
    $newRow.find('input').val('');
    $newRow.find('select').val('').trigger('change');

    // Remove any Select2 artifacts before reinitializing
    $newRow.find('select.select2').each(function () {
        if ($.fn.select2 && $(this).hasClass("select2-hidden-accessible")) {
            $(this).select2('destroy');
        }
        $(this).removeAttr('data-select2-id'); 
       $(this).next('.select2').remove(); // remove the select2 container
    });

    // Append the cleaned-up cloned row
    $('.clone_lines_body').append($newRow);

    // Reinitialize select2
    $newRow.find('select.select2').select2({ width: '100%' });

		// Update line numbers
		updateLineNumbers();
	});



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

  // Renumber Line Nos
  function updateLineNumbers() {
    $('.clone_lines_body tr').each(function (index) {
      $(this).find('.bulk_line_no').val(index + 1);
    });
  }
		

    });


</script>

@endpush