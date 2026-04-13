@extends('layouts.header')
@section('content')
<h3 class="text-danger">Employee Expenses</h3>
@include('layouts.breadcrumb')



<form method="post" action="" id="expenses_form" data-parsley-validate>
    {{ csrf_field() }}

    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        </div>

        <div class="card-body card-block p-4">
            <div class="row g-4">

                <div class="col-md-4">

                    <input class="form-control expense_id" id="expense_id" name="expense_id" size="16" type="hidden"
                        value="{{ $row->expense_id }}" readonly>
                    <input class="form-control reference_id" id="reference_id" name="reference_id" size="16"
                        type="hidden" value="{{ $row->reference_id }}" readonly>
                    <input class="form-control expense_status" id="expense_status" name="expense_status" size="16"
                        type="hidden" value="{{ $row->expense_status }}" readonly>
                    <div class="form-group row mb-3">
                        <label for="inputIsValid" class="form-control-label col-md-5">Expense No</label>
                        <div class="col-md-6">
                            <input type="text" id="expense_no" name="expense_no"
                                class="form-control expense_no chckclick" value="" readonly>

                        </div>
                        <div class="col-md-2 showline">
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="inputIsValid" class="form-control-label col-md-5">Round Off</label>
                        <div class="col-md-6">
                            <input type="text" id="round_off" name="round_off" class="form-control round_off chckclick"
                                value="{{ $row->round_off }}">
                        </div>
                        <div class="col-md-2 showline">
                        </div>
                    </div>

                </div>

                <div class="col-md-4">
                    <div class="form-group row">
                        <label for="inputIsValid" class="form-control-label col-md-5"><span style="color:red;">*</span>
                            Expense Date</label>
                        <div class="col-md-6 apprreadonlydiv">
                            <div class="input-group date form_date col-md-12" data-date="" data-date-format="dd MM yyyy"
                                data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <input class="form-control expense_date start_date" id="expense_date"
                                    name="expense_date" size="16" type="text" value="{{ $row->expense_date }}" required>

                            </div>

                            <input type="hidden" name="source" value="{{ $row->source }}" />
                        </div>
                        <div class="col-md-2 showinline">
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group row">
                        <label for="inputIsValid" class="form-control-label col-md-5">Expense Total</label>
                        <div class="col-md-6">
                            <input type="text" id="expense_amount" name="expense_amount"
                                class="form-control expense_amount chckclick" value="{{$row->expense_amount}}" readonly>
                            <span class="btn btn-danger dup_name" style="display:none;"></span>
                        </div>
                        <div class="col-md-2 showline">
                        </div>
                    </div>
                </div>


                <div class="row mt-2">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered clone_table" style="width: 200% !important;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Employee Name</th>
                                        <th>Bill No</th>
                                        <th>Bill Date</th>
                                        <th>Expense Account</th>
                                        <th>Expense Amount</th>
                                        <th>TDS Applicable</th>
                                        <th class="tds_hide">TDS Percentage</th>
                                        <th class="tds_hide">TDS Amount</th>
                                        <th class="tds_hide">Emp Expense Total</th>
                                        <th class="tds_hide">TDS Account</th>
                                        <th>File Upload</th>
                                        <th></th>
                                        <th>Remarks</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="clone_lines_body">
                                    <?php if (count($linedata) >= 1) { ?>
                                        @foreach($linedata as $key=>$value)
                                        <tr>
                                            <td><input type="hidden" name="bulk_expense_line_id[]"
                                                    class="form-control  bulk_expense_line_id "
                                                    value="{{$value->expense_line_id}}" required>
                                                <input type="hidden" name="bulk_expense_id[]"
                                                    class="form-control  bulk_expense_id " value="{{$value->expense_id}}"
                                                    required>
                                                <select name="bulk_employee_id[]" id="bulk_employee_id"
                                                    class="select2 bulk_employee_id" required>{!! $value->employee_id
                                                    !!}</select>
                                            </td>
                                            <td>
                                                <input type="text" name="bulk_bill_no[]" class="form-control  bulk_bill_no "
                                                    value="{{$value->bill_no}}" required>
                                            </td>
                                            <td>
                                                <input type="text" name="bulk_bill_date[]"
                                                    class="form-control start_date bulk_bill_date " value="{{$value->bill_date}}"
                                                    required>
                                            </td>
                                            <td class="apprreadonlydiv">
                                                <select name="bulk_expense_account_id[]" id="bulk_expense_account_id"
                                                    class="select2 bulk_expense_account_id" required>{!!
                                                    $value->expense_account_id !!}</select>
                                            </td>
                                            <td>
                                                <input type="text" name="bulk_expense_line_amount[]"
                                                    class="form-control  bulk_expense_line_amount "
                                                    value="{{$value->expense_line_amount}}" required>
                                                <input type="hidden" name="bulk_balance_amounts[]"
                                                    class="form-control  bulk_balance_amounts"
                                                    value="{{$value->balance_amounts}}" readonly>
                                            </td>


                                            <td>
                                                <select name='bulk_tds_applicable[]' class='bulk_tds_applicable select2'
                                                    id="bulk_tds_applicable" required>
                                                    <option value="">--Please Select--</option>
                                                    <option value="YES" <?php if ($value->tds_applicable == 'YES') {
                                                        echo "selected";
                                                    } ?>>YES</option>
                                                    <option value="NO" <?php if ($value->tds_applicable == 'NO') {
                                                        echo "selected";
                                                    } ?>>NO</option>
                                                </select>
                                            </td>
                                            <td class="tds_hide">
                                                <select name='bulk_tds_percentage[]'
                                                    class='form-control bulk_tds_percentage select2'
                                                    id="bulk_tds_percentage">
                                                    {!! $value->tds_prcnt !!}
                                                </select>
                                            </td>
                                            <td class="tds_hide">
                                                <input type="text" name="bulk_tds_amount[]" id="bulk_tds_amount"
                                                    value="{{$value->tds_amount}}" class="form-control bulk_tds_amount"
                                                    readonly>
                                            </td>

                                            <td class="tds_hide">
                                                <input type="text" id="bulk_emp_exp_total" name="bulk_emp_exp_total[]"
                                                    class="form-control bulk_emp_exp_total chckclick"
                                                    value="{{$value->emp_exp_total}}" readonly>
                                            </td>

                                            <td class="tds_hide">
                                                <select name='bulk_tds_account_id[]' id="bulk_tds_account_id"
                                                    class='bulk_tds_account_id select2'> {!! $value->tds_account_id
                                                    !!}</select>
                                            </td>

                                            <td class="filediv">
                                                <input id="bulk_choosefile" class=" GetFileSizeNameAndType bulk_choosefile"
                                                    name="bulk_choosefile[][]" type="file" multiple />
                                            </td>
                                           <td>
    <?php
    $dataupload = json_decode($value->choosefile);
    if ($dataupload != "" && $dataupload != NULL) {
        $fileup = implode(',', $dataupload);
    ?>
        <!-- Hidden input that stores all existing files for this expense line -->
        <input type="hidden"
               value="{{$fileup}}"
               class="bulk_existing_files_input"
               name="bulk_existing_file[]"
               id="bulk_existing_file" />

        <table id="file_choosen" border="1" class="file_choosen"
               style="width: 100px;margin-bottom: 1px;">
            <tbody id="fp">
                <?php foreach ($dataupload as $k => $v) { ?>
                    <tr>
                        <td style="display: inline-block">
                            <a download
                               href="{{URL::to('')}}/Uploads/empexpense/{{$value->expense_line_id}}/{{$v}}">
                                {{$v}}
                            </a>
                            &nbsp;
                            <img src="{{URL::to('')}}/images/cancel.png"
                                 data-value="{{$v}}"
                                 class="delete_user_lines"
                                 style="cursor:pointer;">
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    <?php } else { ?>
        <input type="hidden"
               value=""
               class="bulk_existing_files_input"
               name="bulk_existing_file[]"
               id="bulk_existing_file" />
    <?php } ?>
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
                                        <tr>
                                            <td><input type="hidden" name="bulk_expense_line_id[]"
                                                    class="form-control  bulk_expense_line_id " value="" required> <input
                                                    type="hidden" name="bulk_expense_id[]"
                                                    class="form-control  bulk_expense_id " value="" required>
                                                <select name="bulk_employee_id[]" id="bulk_employee_id"
                                                    class="select2 bulk_employee_id" required>{!! $employee_id !!}</select>
                                            </td>
                                            <td>
                                                <input type="text" name="bulk_bill_no[]" class="form-control  bulk_bill_no "
                                                    value="" required>
                                            </td>
                                            <td>
                                                <input type="text" name="bulk_bill_date[]"
                                                    class="form-control start_date  bulk_bill_date " value="" required>
                                            </td>
                                            <td>
                                                <select name="bulk_expense_account_id[]" id="bulk_expense_account_id"
                                                    class="select2 bulk_expense_account_id" required>{!! $expense_account_id
                                                    !!}</select>
                                            </td>
                                            <td>
                                                <input type="text" name="bulk_expense_line_amount[]"
                                                    class="form-control  bulk_expense_line_amount " value="" required>
                                                <input type="hidden" name="bulk_balance_amounts[]"
                                                    class="form-control  bulk_balance_amounts" value="" readonly>
                                            </td>

                                            <td>
                                                <select name='bulk_tds_applicable[]' class='bulk_tds_applicable select2'
                                                    id="bulk_tds_applicable" required>
                                                    <option value="">--Please Select--</option>
                                                    <option value="YES">YES</option>
                                                    <option value="NO">NO</option>
                                                </select>
                                            </td>
                                            <td class="tds_hide">
                                                <select name='bulk_tds_percentage[]'
                                                    class='form-control bulk_tds_percentage select2'
                                                    id="bulk_tds_percentage">
                                                    {!!$tds_prcnt!!}
                                                </select>
                                            </td>
                                            <td class="tds_hide">
                                                <input type="text" name="bulk_tds_amount[]" id="bulk_tds_amount" value=""
                                                    class="form-control bulk_tds_amount" tabindex="15" readonly>
                                            </td>

                                            <td class="tds_hide">
                                                <input type="text" id="bulk_emp_exp_total" name="bulk_emp_exp_total[]"
                                                    class="form-control bulk_emp_exp_total chckclick" value="" readonly>
                                            </td>

                                            <td class="tds_hide">
                                                <select name='bulk_tds_account_id[]' id="bulk_tds_account_id"
                                                    class='bulk_tds_account_id select2 tds_hide'> {!! $tds_account_id
                                                    !!}</select>
                                            </td>

                                            <td class="filediv">
                                                <input id="bulk_choosefile" class=" GetFileSizeNameAndType bulk_choosefile"
                                                    name="bulk_choosefile[][]" type="file" multiple />
                                                <input type="hidden" value="" class="bulk_existing_files"
                                                    id="bulk_existing_files" />
                                            </td>
                                            <td></td>
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
                                <button name="submit" type="button" class="btn btn-success px-4 me-2  saveform"
                                    value="INITIATED">SUBMIT</button>
                                <a class='btn btn-danger px-4 me-2 '
                                    onclick='location.href ="{{ url($pageModule) }}"'>Cancel</a>
                            <?php } else { ?>
                                <button type="button" class="btn btn-success px-4 me-2  saveform"
                                    value="APPROVED">Approve</button>
                                <button type="button" class="btn btn-danger px-4 me-2  saveform"
                                    value="REJECTED">Reject</button>
                                <a href="{{ url('empexpenseapproval') }}"
                                    class='btn btn-outline-danger px-4 me-2 '>Cancel</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

</form>

@endsection
@push('scripts')

<script>

	$('.tds_hide').hide();	
	
    var dup_chk = true;
    function duplicate_validate() {
        var expense_id = $("#expense_id").val();
        var invoice = $("#invoice").val();
        var expense_type = $(".expense_type").val();

        $.ajax({
            cache: false,
            url: 'expansenamechk',
            type: 'GET',
            dataType: 'json',
            async: false,
            data: { invoice: invoice, expense_type: expense_type, bill_date: bill_date, expense_id: expense_id, employee_id: employee_id, supplier_id: supplier_id, customer_id: customer_id },
            success: function (response) {
                if (response == 1) {
                    $('.dup_name').html('Bill Number:' + invoice + ' Already Exists on This Date');
                    $('.dup_name').show();
                    $("#invoice").val('');
                    $("#expense_amount").val('');
                    $("#bill_date").val('');
                    dup_chk = false;
                }
                else if (response == 0) {
                    var html = "";
                    $('.dup_name').hide();
                    dup_chk = true;

                }

            },
            error: function (xhr, resp, text) {
                console.log(xhr, resp, text);
            }
        });
    }
    /*End*/
    $(document).ready(function () {

        $(".expense_type").on("change", function () {

            $(".bulk_expense_account_id,.bulk_gst_code_id,.bulk_tax_group_id ").select2('destroy').val("").select2();

        });


        var decimal = "<?php echo \Session('decimal'); ?>";
        $(document).on('keyup change', '.bulk_tax_group_id,.bulk_expense_line_amount,.tds_prcnt,.round_off,.tds_amount', function () {
            var index = $(this).closest("tr").index();
            var expense_amt = $('.bulk_expense_line_amount' + index).val();
            $('.bulk_balance_amounts' + index).val(expense_amt);

            var round = $('.round_off').val();

            var sum = 0;
            var sumtax = 0;
            var sumtotal = 0;
            $('.bulk_expense_line_amount').each(function () {
                sum += parseFloat($(this).val());
            });

            sumtotal = Number(sum) + Number(round);
            sumreverse = Number(sum) + Number(round);
            var sumtotalamt = isNaN((sumtotal)) ? 0 : (sumreverse);
            $('.expense_amount').val(sumtotalamt.toFixed(2));

        });



    $(document).on('click', '.delete_user_lines', function (e) {
        e.preventDefault();

        // If you really need this check, keep it.
        // But note: {{$row->expense_id}} must exist on this page scope.
        var eid = '{{$row->expense_id ?? ""}}';

        // If you want to allow deleting even when eid is empty, remove this if block
        if (eid !== '') {

            var delete_value = $(this).data('value'); // filename

            // Find the MAIN expense line row
            var $expenseRow = $(this).closest('tr.expense-line-row');

            // Your delete icon is inside a nested file table,
            // so closest('tr.expense-line-row') may not work if the icon is not inside that row directly.
            // In that case, jump up to the nearest "outer" row:
            if ($expenseRow.length === 0) {
                $expenseRow = $(this).closest('table').closest('tr.expense-line-row');
            }

            // Find the hidden input that stores the existing files
            var $hidden = $expenseRow.find('.bulk_existing_files_input');

            var existing_value = ($hidden.val() || '');

            // Remove filename from CSV
            var list = existing_value
                .split(',')
                .map(s => s.trim())
                .filter(Boolean);

            var pos = list.indexOf(delete_value);
            if (pos > -1) {
                list.splice(pos, 1);
            }

            $hidden.val(list.join(','));
        }

        // Remove only the file row (the row inside file_choosen table)
        $(this).closest('tr').remove();

        return false;
    });


        <?php if ($aprvidenty == "INITIATED") { ?>
            $('input').attr('readonly', true);
            $('select').attr('readonly', true);
            $('select').css('pointer-events', 'none');
            $('.additem,.paidthrghdiv,.supplierdiv,.gsttreatdiv,.taxgrpdiv,.expensediv,.customerdiv,.employeediv,.apprreadonlydiv,.filediv').css('pointer-events', 'none');

        <?php } ?>

        /*Validation*/
        $(document).on('keypress', '.expense_line_amount', function (ev) {
            var regex = new RegExp("^[0-9.]+$");
            var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
            if (regex.test(str)) {
                return true;
            }
            ev.preventDefault();
            return false;
        });

        /*copy paste validation*/
        $('.expense_line_amount').bind("cut copy paste", function (e) {
            e.preventDefault();
        });
        /*copy paste validation*/

        /* Purpose For Save Function*/

        $(document).on('click', '.saveform', function () {
            var btnval = $(this).val();

            if (btnval == 'INITIATED') {
                $("#expense_status").val('INITIATED');
            }

            else if (btnval == 'APPROVED') {
                $("#expense_status").val('APPROVED');
            }
            else {
                $("#expense_status").val('REJECTED');
            }
            $('#savestatus').val(btnval);
            var url = "{{ url('empexpensessave') }}";
            <?php if ($aprvidenty == "") { ?>
                var red_url = "{{ url($pageModule) }}";
            <?php } else { ?>
                var red_url = "{{ url('empexpenseapproval') }}";
            <?php } ?>


            validationrule('expenses_form');
            var form = $('#expenses_form');
            form.parsley().validate();
            if (form.parsley().isValid()) {

                if (dup_chk == true) {
                var $btn = $(this);            
                $btn.prop('disabled', true);
                    var formdata = $('#expenses_form').serialize();
                    var form_data = new FormData(document.getElementById("expenses_form"));
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: form_data,
                        enctype: "multipart/form-data",
                        processData: false,  // tell jQuery not to process the data
                        contentType: false,   // tell jQuery not to set contentType
                        async: true,
                        xhr: function () {
                            var xhr = $.ajaxSettings.xhr();
                            if (xhr.upload) {
                                xhr.upload.addEventListener("progress", function (event) {
                                    var percent = 0;
                                    var position = event.loaded || event.position;
                                    var total = event.total;
                                    if (event.lengthComputable) {
                                        percent = Math.ceil(position / total * 100);
                                    }
                                    //update progressbar

                                }, true);
                            }
                            return xhr;

                        }
                    }).done(function (data) {
                        var status = data.status;
                        var msg = data.message;
                        var id = data.id;
                        var edit_url = "{{ url('empexpensescreate') }}/" + id;

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


                return false;
            }
            return false;
        });

    });


    $('.toold').tooltip({ 'placement': 'bottom' })

    // for tds applicable reason // vignesh m
    $(document).on('keyup change', '.bulk_expense_line_amount, .bulk_tds_percentage, .bulk_tds_amount', function () {
        var decimal = "<?php echo \Session('decimal'); ?>";
        var $row = $(this).closest("tr"); // Get the current row context
        var expense_amt = parseFloat($row.find('.bulk_expense_line_amount').val()) || 0;
        var tds_prcnt = parseFloat($row.find('.bulk_tds_percentage').val()) || 0;

        var tds_amount;
        if (tds_prcnt === 0) {
            tds_amount = parseFloat($row.find('.bulk_tds_amount').val()) || 0;
            $row.find('.bulk_tds_amount').attr('readonly', false);
        } else {
            tds_amount = parseFloat((expense_amt * tds_prcnt) / 100) || 0;
            $row.find('.bulk_tds_amount').val(tds_amount.toFixed(decimal)).attr('readonly', true);
        }

        // Calculate the net expense amount (expense amount - TDS amount)
        var net_expense_amount = expense_amt - tds_amount;
        $row.find('.bulk_emp_exp_total').val(net_expense_amount.toFixed(decimal));

        // Recalculate totals
        var total_expense = 0;
        var total_tds = 0;
        $('.bulk_emp_exp_total').each(function () {
            total_expense += parseFloat($(this).val()) || 0;
        });
        $('.bulk_tds_amount').each(function () {
            total_tds += parseFloat($(this).val()) || 0;
        });

        // Display total summary (if applicable)
        $('#total_expense').text(total_expense.toFixed(decimal));
        $('#total_tds').text(total_tds.toFixed(decimal));
    });
	
    // vignesh m
	
$(document).ready(function () {
    // Function to toggle TDS fields and headers
    function toggleTDSFields(row) {
        const tdsApplicable = row.find('.bulk_tds_applicable').val();
        const table = row.closest('table');
        const tdsHeaders = table.find('th.tds_hide');
        const tdsCells = row.find('.tds_hide');

        if (tdsApplicable === 'YES') {
            tdsHeaders.show();
            tdsCells.show();
        } else {
            tdsCells.hide();

            // Check if any other rows have TDS = YES
            const hasVisibleTDS = table.find('.bulk_tds_applicable').filter(function () {
                return $(this).val() === 'YES';
            }).length > 0;

            if (!hasVisibleTDS) {
                tdsHeaders.hide();
            }
        }
    }

    // Add .rcopy class dynamically for each row for consistency
    $('.clone_lines_body tr').addClass('rcopy');

    // Initial setup for existing rows
    $('.clone_lines_body .rcopy').each(function () {
        toggleTDSFields($(this));
    });

    // Handle change event on TDS Applicable dropdown
    $(document).on('change', '.bulk_tds_applicable', function () {
        const row = $(this).closest('tr');
        toggleTDSFields(row);
    });
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

    // ✅ Reset datepicker field properly
    $newRow.find('.start_date').each(function () {
        $(this).removeClass('hasDatepicker').removeAttr('id').val('');
        // reinitialize datepicker
        $(this).datepicker({
            dateFormat: 'yy-mm-dd'
        });
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
	
</script>

@endpush