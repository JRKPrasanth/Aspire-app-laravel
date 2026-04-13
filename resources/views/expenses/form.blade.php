@extends('layouts.header')
@section('content')
    <h3 class="text-danger"> Expenses </h3>
    @include('layouts.breadcrumb')

    <form action="" id="expenses_form" data-parsley-validate method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}

        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-primary text-white fw-bold fs-5"></div>

            <div class="card-body">
                <div class="row g-4">
                    <!-- Left Column -->
                    <div class="col-md-4">
                        <input type="hidden" id="expense_id" name="expense_id" value="{{ $row->expense_id }}">
                        <input type="hidden" id="reference_id" name="reference_id" value="{{ $row->reference_id }}">
                        <input type="hidden" id="expense_status" name="expense_status" value="{{ $row->expense_status }}">
                        <input type="hidden" name="source" value="{{ $row->source }}" />
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Expense No</label>
                            <input type="text" id="expense_no" name="expense_no" class="form-control expense_no" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Expense Total</label>
                            <input type="text" id="expense_amount" name="expense_amount" class="form-control expense_amount"
                                value="{{ $row->expense_amount }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-danger">*</label>
                            <label class="form-label fw-semibold">TDS Applicable</label>
                            <select name="tds_applicable" class="form-select select2 tds_applicable" required>
                                <option value="">-- Please Select --</option>
                                <option value="YES" {{ $row->tds_applicable == 'YES' ? 'selected' : '' }}>YES</option>
                                <option value="NO" {{ $row->tds_applicable == 'NO' ? 'selected' : '' }}>NO</option>
                            </select>
                        </div>

                        <div class="mb-3 tds_per">
                            <label class="form-label fw-semibold">TDS Percentage</label>
                            <select name="tds_prcnt" class="form-select select2 tds_prcnt">
                                {!! $tds_prcnt !!}
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Round Off</label>
                            <input type="text" id="round_off" name="round_off" class="form-control round_off"
                                value="{{ $row->round_off }}">
                        </div>

                        <div class="mb-3 supplierdiv">
                            <label class="form-label fw-semibold text-danger">*</label>
                            <label class="form-label fw-semibold">Supplier Name</label>
                            <select name="supplier_id" class="form-select select2 supplier_id" required>
                                {!! $supplier_id !!}
                            </select>
                        </div>
                    </div>

                    <!-- Middle Column -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-danger">*</label>
                            <label class="form-label fw-semibold">Expense Date</label>
                            <input type="text" id="expense_date" name="expense_date"
                                class="form-control start_date expense_date" value="{{ $row->expense_date }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-danger">*</label>
                            <label class="form-label fw-semibold">Bill Number</label>
                            <span class="badge bg-danger dup_name d-none"></span>
                            <input type="text" id="invoice" name="invoice" class="form-control invoice"
                                value="{{ $row->invoice }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-danger">*</label>
                            <label class="form-label fw-semibold">Bill Date</label>
                            <input type="text" id="bill_date" name="bill_date" class="form-control start_date bill_date"
                                value="{{ $row->bill_date }}" required>
                        </div>

                        <div class="mb-3 tds_per">
                            <label class="form-label fw-semibold">TDS Amount</label>
                            <input type="text" id="tds_amount" name="tds_amount" class="form-control tds_amount"
                                value="{{ $row->tds_amount }}" readonly>
                        </div>

                        <div class="mb-3 customerdiv">
                            <label class="form-label fw-semibold text-danger">*</label>
                            <label class="form-label fw-semibold">Customer Name</label>
                            <select name="customer_id" class="form-select select2 customer_id" required>
                                {!! $customer_id !!}
                            </select>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-danger">*</label>
                            <label class="form-label fw-semibold">Expense Type</label>
                            <select name="expense_type" class="form-select select2 expense_type" required>
                                <option value="">-- Please Select --</option>
                                <option value="SUPPLIER" {{ $row->expense_type == 'SUPPLIER' ? 'selected' : '' }}>SUPPLIER
                                </option>
                                <option value="CUSTOMER" {{ $row->expense_type == 'CUSTOMER' ? 'selected' : '' }}>CUSTOMER
                                </option>
                                <option value="EMPLOYEE" {{ $row->expense_type == 'EMPLOYEE' ? 'selected' : '' }}>EMPLOYEE
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Reverse Charge Applicable?</label>
                            <div class="form-check">
                                <input type="checkbox" name="reverse_charge[]" value="1"
                                    class="form-check-input reverse_charge" {{ $row->reverse_charge == '1' ? 'checked' : ''
                                    }}>
                                <label class="form-check-label">Yes</label>
                            </div>
                        </div>

                        <div class="mb-3 employeediv">
                            <label class="form-label fw-semibold text-danger">*</label>
                            <label class="form-label fw-semibold">Employee Name</label>
                            <select name="employee_id" class="form-select employee_id select2" required>
                                {!! $employee_id !!}
                            </select>
                        </div>
                    </div>

                    <!-- Full Width Row -->
                    <div class="col-md-4 tds_per">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">TDS Account</label>
                            <select name="tds_account_id" class="form-select tds_account_id select2">
                                {!! $tds_account_id !!}
                            </select>
                        </div>
                    </div>
                </div>





                <div class="row mt-2">
                    <div class="col-md-12">

                        <div id="preview-area" class="table-responsive">
                            <table class="table table-bordered clone_table">
                                <thead class="table-light">
                                    <tr>

                                        <th>Expense Account</th>
                                        <th>Expense Amount</th>
                                        <th class="tax">HSN Code</th>
                                        <th class="tax">Tax Group</th>
                                        <th class="tax">Tax Amount</th>
                                        <th>File Upload</th>
                                        <th></th>
                                        <th>Remarks</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="clone_lines_body">
                                    <?php if (count($linedata) >= 1) { ?>
                                    @foreach($linedata as $key => $value)
                                                                <tr>
                                                                    <td class="apprreadonlydiv">
                                                                        <input type="hidden" name="bulk_expense_line_id[]"
                                                                            class="form-control  bulk_expense_line_id "
                                                                            value="{{$value->expense_line_id}}" required>

                                                                        <input type="hidden" name="bulk_expense_id[]"
                                                                            class="form-control  bulk_expense_id " value="{{$value->expense_id}}"
                                                                            required>

                                                                        <select name="bulk_expense_account_id[]" id="bulk_expense_account_id"
                                                                            class="select2 bulk_expense_account_id" required>{!!
                                            $value->expense_account_id !!}</select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="bulk_expense_line_amount[]"
                                                                            class="form-control  bulk_expense_line_amount "
                                                                            value="{{$value->expense_line_amount}}" required>
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
                                                                    <td class="filediv">
                                                                        <input id="bulk_choosefile" class=" GetFileSizeNameAndType bulk_choosefile"
                                                                            name="bulk_choosefile[{{$key}}][]" type="file" multiple />

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
                               href="{{URL::to('')}}/Uploads/expense/{{$value->expense_line_id}}/{{$v}}">
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
                                                                            value="{{$value->remarks}}" required>
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
                                                class="form-control  bulk_expense_line_id " value="" required>
                                            <input type="hidden" name="bulk_expense_id[]"
                                                class="form-control  bulk_expense_id " value="" required>

                                            <select name="bulk_expense_account_id[]" id="bulk_expense_account_id"
                                                class="select2 bulk_expense_account_id" required>{!! $expense_account_id
                                                        !!}</select>
                                        </td>
                                        <td>
                                            <input type="text" name="bulk_expense_line_amount[]"
                                                class="form-control  bulk_expense_line_amount " value="" required>
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
                                        <td class="filediv">
                                            <input id="bulk_choosefile" class=" GetFileSizeNameAndType bulk_choosefile"
                                                name="bulk_choosefile[0][]" type="file" multiple />
                                            <input type="hidden" value="" class="bulk_existing_files"
                                                id="bulk_existing_files" />
                                        </td>
                                        <td></td>
                                        <td>
                                            <input type="text" name="bulk_remarks[]" class="form-control bulk_remarks "
                                                value="" required>
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
                            <a class='btn btn-danger px-4 me-2' onclick='location.href ="{{ url($pageModule) }}"'>Cancel</a>
                            <?php } else { ?>
                            <button type="button" class="btn btn-success px-4 me-2  saveform"
                                value="APPROVED">Approve</button>
                            <button type="button" class="btn  btn-danger px-4 me-2  saveform"
                                value="REJECTED">Reject</button>
                            <a href="{{ url('expenseapproval') }}" class='btn  btn-outline-danger px-4 me-2 '>Cancel</a>
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

        var dup_chk = true;
        function duplicate_validate() {
            var expense_id = $("#expense_id").val();
            var invoice = $("#invoice").val();
            var expense_type = $(".expense_type").val();
            var bill_date = $("#bill_date").val();
            var employee_id = $(".employee_id").val();
            var supplier_id = $(".supplier_id").val();
            var customer_id = $(".customer_id").val();
            $.ajax({
                cache: false,
                url: 'expansenamechk',
                type: 'GET',
                dataType: 'json',
                async: false,
                data: { invoice: invoice, expense_type: expense_type, bill_date: bill_date, expense_id: expense_id, employee_id: employee_id, supplier_id: supplier_id, customer_id: customer_id },
                success: function (response) {
                if (response == 1)
                {
                    $('.dup_name')
                        .html('Bill Number: ' + invoice + ' Already exists')
                        .removeClass('d-none')
                        .addClass('d-block');
                        $("#invoice").val('');
                        $("#expense_amount").val('');
                        $("#bill_date").val('');
                    dup_chk = false;
                }
                    else if(response == 0)
                    {
                           var html ="";
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

            $(document).on('keyup change', '.tds_prcnt', function () {
                var tds_prcnt = $('.tds_prcnt option:selected').val();
                if (tds_prcnt == '0') {
                    $('.tds_amount').attr('readonly', false);
                } else {
                    $('.tds_amount').attr('readonly', true);
                }
            });

            var decimal = "<?php echo \Session('decimal'); ?>";

            $(document).on(
                'keyup change',
                '.bulk_tax_group_id, .bulk_expense_line_amount, .tds_prcnt, .reverse_charge, .round_off, .tds_amount',
                function () {

                    var index = $(this).closest("tr").index();

                    var expense_amt = parseFloat($('.bulk_expense_line_amount').eq(index).val()) || 0;
                    var taxgrp = parseFloat(
                        $('.bulk_tax_group_id')
                            .eq(index)
                            .find('option:selected')
                            .data('display')
                    ) || 0;

                    var round = parseFloat($('.round_off').val()) || 0;
                    var tds_prcnt = parseFloat($('.tds_prcnt option:selected').val()) || 0;

                    // Tax per line
                    var taxamount = (expense_amt * taxgrp) / 100;
                    $('.bulk_tax_amount').eq(index).val(taxamount.toFixed(2));

                    var sum = 0,
                        sumtax = 0;

                    $('.bulk_expense_line_amount').each(function () {
                        sum += parseFloat($(this).val()) || 0;
                    });

                    $('.bulk_tax_amount').each(function () {
                        sumtax += parseFloat($(this).val()) || 0;
                    });

                    var sumtotal = sum + sumtax + round;
                    var sumreverse = sum + round;

                    var tds_amount = 0;

                    if (tds_prcnt > 0) {
                        tds_amount = sum * (tds_prcnt / 100);
                    } else {
                        tds_amount = parseFloat($('.tds_amount').val()) || 0;
                    }

                    tds_amount = Math.round(tds_amount);
                    $('.tds_amount').val(tds_amount);

                    var reverse_charge = $('.reverse_charge:checked').val();
                    var final_total = 0;

                    if (reverse_charge == 1) {

                        final_total = sumreverse - tds_amount;
                    } else {

                        final_total = sumtotal - tds_amount;
                    }

                    $('.expense_amount').val(final_total.toFixed(2));
                }
            );



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

            <?php if ($row->expense_id == "") { ?>
            $(".supplierdiv").hide();
            $(".customerdiv").hide();
            $(".employeediv").hide();
            $(".tds_per").hide();
            <?php } ?>

            <?php if ($row->tds_applicable == "YES") { ?>
                $(".tds_per").show().find('input,select').prop('required', true);
            <?php } else { ?>
                $(".tds_per").hide().find('input,select').prop('required', false);
            <?php } ?>

            $(".tds_applicable").on('change', function () {
                var tds = $(this).val();

                if (tds == "YES") {
                    $(".tds_per").show().find('input,select').prop('required', true);
                } else {
                    $(".tds_prcnt").val('').trigger('change');
                    $(".tds_per").hide().find('input,select').prop('required', false);
                }
            });


            <?php if ($row->expense_type == "SUPPLIER") { ?>
            $(".supplierdiv").show();
            $('.supplier_id').attr('required', true);
            $('.customer_id').attr('required', false);
            $('.employee_id').attr('required', false);
            $(".customerdiv").hide();
            $(".employeediv").hide();
            $(".tax").show();
            $('.customer_id').val();
            $('.employee_id').val();
            <?php } else if ($row->expense_type == "CUSTOMER") { ?>
            $(".supplierdiv").hide();
            $(".customerdiv").show();
            $('.supplier_id').attr('required', false);
            $('.customer_id').attr('required', true);
            $('.employee_id').attr('required', false);
            $(".employeediv").hide();
            $(".tax").show();
            $('.supplier_id').val();
            $('.employee_id').val();

            <?php    } else if ($row->expense_type == "EMPLOYEE") { ?>
            $(".supplierdiv").hide();
            $(".customerdiv").hide();
            $(".employeediv").show();
            $('.supplier_id').attr('required', false);
            $('.customer_id').attr('required', false);
            $('.employee_id').attr('required', true);
            $(".tax").hide();
            $('.supplier_id').val();
            $('.customer_id').val();
            <?php    } ?>


            $(".expense_type").change(function () {
                var expensetype = $(".expense_type option:selected").val();
                if (expensetype == "SUPPLIER") {
                    $(".supplierdiv").show();
                    $('.supplier_id').attr('required', true);
                    $('.customer_id').attr('required', false);
                    $('.employee_id').attr('required', false);
                    $(".customerdiv").hide();
                    $(".employeediv").hide();
                    $(".tax").show();
                    $('.customer_id').select2("val", "ALL");
                    $('.employee_id').select2("val", "ALL");
                }
                else if (expensetype == "CUSTOMER") {
                    $(".supplierdiv").hide();
                    $(".customerdiv").show();
                    $('.supplier_id').attr('required', false);
                    $('.customer_id').attr('required', true);
                    $('.employee_id').attr('required', false);
                    $(".employeediv").hide();
                    $(".tax").show();
                    $('.supplier_id').select2("val", "ALL");
                    $('.employee_id').select2("val", "ALL");
                }
                else if (expensetype == "EMPLOYEE") {
                    $(".supplierdiv").hide();
                    $(".customerdiv").hide();
                    $(".employeediv").show();
                    $('.supplier_id').attr('required', false);
                    $('.customer_id').attr('required', false);
                    $('.employee_id').attr('required', true);
                    $(".tax").hide();
                    $('.supplier_id').select2("val", "ALL");
                    $('.customer_id').select2("val", "ALL");
                }
            });

            <?php if ($aprvidenty == "INITIATED") { ?>
            $('input').attr('readonly', true);
            $('select').attr('readonly', true);
            $('select').css('pointer-events', 'none');
            $('.paidthrghdiv,.supplierdiv,.gsttreatdiv,.taxgrpdiv,.expensediv,.customerdiv,.employeediv,.apprreadonlydiv').css('pointer-events', 'none');

            <?php } ?>
            $(document).on('change', '.expense_type', function () {
                var radio = $('input[name=expense_type]:checked').val();
                if (radio == "Labour") {
                    $('#hsn').text("SAC Code");
                }
                else {
                    $('#hsn').text("HSN Code");
                }
            });


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
                var url = "{{ url('expensessave') }}";
                <?php if ($aprvidenty == "") { ?>
                var red_url = "{{ url($pageModule) }}";
                <?php } else { ?>
                var red_url = "{{ url('expenseapproval') }}";
                <?php } ?>


                validationrule('expenses_form');
                var form = $('#expenses_form');
                form.parsley().validate();
                if (form.parsley().isValid()) {
                    duplicate_validate();
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
                            processData: false,
                            contentType: false,
                            async: true,
                            xhr: function () {
                                var xhr = $.ajaxSettings.xhr();
                                if (xhr.upload) {
                                    xhr.upload.addEventListener("progress", function (event) {
                                        var percent = 0;
                                        var position = event.loaded || event.position;
                                        var total = event.total;
                                        if (event.lengthComputable) {
                                            percent = Math.round(position / total * 100);
                                        }

                                    }, true);
                                }
                                return xhr;

                            }
                        }).done(function (data) {
                            var status = data.status;
                            var msg = data.message;
                            var id = data.id;
                            var edit_url = "{{ url('expensescreate') }}/" + id;

                            if (btnval != 'SAVE' && btnval != 'DRAFT') {
                                showCustomAlert(msg,status);
                                setTimeout(function () {
                                    window.location.href = red_url;
                                }, 1500);
                            }
                            else {
                                showCustomAlert(msg,status);
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
                $(this).find('.bulk_expense_line_id').val(index + 1);
            });
        }

    </script>

@endpush