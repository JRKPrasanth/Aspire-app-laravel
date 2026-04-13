@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Direct Receipt</h3>
    @include('layouts.breadcrumb')


    <form method="post" action="" id="directreceipt_form" data-parsley-validate>
        {{ csrf_field() }}

        <div class="card shadow-lg rounded-4 border-0">
            <div class="card-body">
                <div class="row g-4">
                    <!-- Column 1 -->
                    <div class="col-md-4">
                        <input type="hidden" name="receipt_id" value="{{ $row->receipt_id }}">
                        <input type="hidden" name="receipt_number" value="{{ $row->receipt_number }}">

                        <!-- Customer Name -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control customer_account_name" id="customer_account_name"
                                name="customer_account_name" value="{{ $row->customer_account_name }}">
                            <label for="customer_account_name">Customer Name</label>
                        </div>

                        <!-- Receipt Amount -->
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control receipt_amount" id="receipt_amount"
                                name="receipt_amount" required>
                            <label for="receipt_amount">Receipt Amount <span class="text-danger">*</span></label>
                        </div>

                        <!-- Cheque No -->
                        <div class="form-floating mb-3 chequediv d-none">
                            <input type="text" class="form-control cheque_no" id="cheque_no" name="cheque_no"
                                value="{{ $row->cheque_no }}">
                            <label for="cheque_no">Cheque No <span class="text-danger">*</span></label>
                        </div>

                        <!-- Direct Account Code -->
                        <div class="mb-3">
                            <label class="form-label">Direct Account Code <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="direct_accountcodeid" class="form-select direct_accountcodeid select2"
                                    required>
                                    {!! $direct_accountcodeid !!}
                                </select>
                            </div>
                        </div>

                        <!-- Account Code -->
                        <div class="mb-3">
                            <label class="form-label">Account Code <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="account_code_id" class="form-select account_code_id select2" required>
                                    {!! $account_code_id !!}
                                </select>
                            </div>
                        </div>

                        <!-- Source Type -->
                        <div class="mb-3">
                            <label class="form-label">Source Type</label>
                            <select name="source_type_id" class="form-select source_type_id select2">
                                <option value="">-- Please Select --</option>
                                <option value="EMPLOYEE">EMPLOYEE</option>
                                <option value="SUPPLIER">SUPPLIER</option>
                                <option value="CUSTOMER">CUSTOMER</option>
                            </select>
                        </div>
                    </div>

                    <!-- Column 2 -->
                    <div class="col-md-4">
                        <!-- Receipt Date -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control datepicker receipt_date" id="receipt_date"
                                name="receipt_date" value="{{ $row->receipt_date }}">
                            <label for="receipt_date">Receipt Date</label>
                        </div>

                        <!-- Receipt Type -->
                        <div class="mb-3">
                            <label class="form-label">Receipt Type <span class="text-danger">*</span></label>
                            <select name="receipt_type_id" class="form-select select2 receipt_type_id" required>
                                <option value="">-- Please Select --</option>
                                @foreach(['CHEQUE', 'CASH', 'NEFT', 'MTPS', 'DEBIT', 'CREDIT', 'NET BANKING', 'OTHER ATMS', 'ICICI ATM', 'MOBILE BANKING', 'CASH DEPOSIT'] as $type)
                                    <option value="{{ $type }}" {{ $row->receipt_type_id == $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Bank Name -->
                        <div class="mb-3">
                            <label class="form-label">Bank Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="bank_id" id="bank_id" class="form-select select2 bank_id" required>
                                    {!! $bank_id !!}
                                </select>

                            </div>
                        </div>

                        <!-- Account Number -->
                        <div class="mb-3">
                            <label class="form-label">Account Number</label>
                            <select name="account_no" id="account_no" class="form-select select2 account_no">
                                {!! $account_no !!}
                            </select>
                        </div>

                        <!-- Employee / Supplier / Customer -->
                        <div class="mb-3 employee_id_div">
                            <label class="form-label">Employee Name</label>
                            <select name="employee_id" class="form-select select2 employee_id">
                                {!! $employee_id !!}
                            </select>
                        </div>
                        <div class="mb-3 supplier_id_div">
                            <label class="form-label">Supplier Name</label>
                            <select name="supplier_id" class="form-select select2 supplier_id">
                                {!! $supplier_id !!}
                            </select>
                        </div>
                        <div class="mb-3 customer_id_div">
                            <label class="form-label">Customer Name</label>
                            <select name="customer_id" class="form-select select2 customer_id">
                                {!! $customer_id !!}
                            </select>
                        </div>
                    </div>

                    <!-- Column 3 -->
                    <div class="col-md-4">
                        <!-- Narration -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control remarks" id="remarks" name="remarks"
                                value="{{ $row->remarks }}">
                            <label for="remarks">Narration</label>
                        </div>

                        <!-- Receipt Reference -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="receipt_reference" name="receipt_reference"
                                value="{{ $row->receipt_reference }}" required>
                            <label for="receipt_reference">Receipt Reference <span class="text-danger">*</span></label>
                        </div>

                        <!-- Cheque Date -->
                        <div class="form-floating mb-3 chequedate">
                            <input type="text" class="form-control datepicker cheque_date" id="cheque_date"
                                name="cheque_date">
                            <label for="cheque_date">Cheque Date</label>
                        </div>

                        <!-- Bank Date -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control datepicker bank_date" id="bank_date" name="bank_date">
                            <label for="bank_date">Bank Date</label>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="text-center mt-4">
                    <input type="hidden" name="submit_type" class="submit_type" value="">
                    <button type="button" class="btn btn-success px-4 saveform" value="SAVE">
                        <i class="bi bi-check-circle"></i> Save
                    </button>
                    <a href="{{ url($pageModule) }}" class="btn btn-secondary px-4 ms-2">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>



@endsection
@push('scripts')

    <script>

        $(document).ready(function () {
            $('select').css('pointer-events', 'none');

            $(".employee_id_div").css("display", "none");
            $(".supplier_id_div").css("display", "none");
            $(".customer_id_div").css("display", "none");

            $(document).on('change', '.employee_id', function () {

                if ($(this).val() != '') {
                    $(".direct_accountcodeid").val('106').change();
                }

            });
            $(document).on('change', '.source_type_id', function () {
                var source_type = $('.source_type_id').val();
                if (source_type == 'EMPLOYEE') {
                    $(".employee_id_div").css("display", "block");
                    $(".supplier_id_div").css("display", "none");
                    $(".customer_id_div").css("display", "none");
                    $(".employee_id").prop('required', true);
                    $(".supplier_id").prop('required', false);
                    $(".customer_id").prop('required', false);

                } else if (source_type == 'SUPPLIER') {
                    $(".employee_id_div").css("display", "none");
                    $(".supplier_id_div").css("display", "block");
                    $(".customer_id_div").css("display", "none");
                    $(".employee_id").prop('required', false);
                    $(".supplier_id").prop('required', true);
                    $(".customer_id").prop('required', false);
                    $(".direct_accountcodeid").val('').change();
                } else if (source_type == 'CUSTOMER') {
                    $(".employee_id_div").css("display", "none");
                    $(".supplier_id_div").css("display", "none");
                    $(".customer_id_div").css("display", "block");
                    $(".employee_id").prop('required', false);
                    $(".supplier_id").prop('required', false);
                    $(".customer_id").prop('required', true);
                    $(".direct_accountcodeid").val('').change();
                } else {
                    $(".employee_id_div").css("display", "none");
                    $(".supplier_id_div").css("display", "none");
                    $(".customer_id_div").css("display", "none");
                    $(".employee_id").prop('required', false);
                    $(".supplier_id").prop('required', false);
                    $(".customer_id").prop('required', false);
                }

            });


            $(document).on('change', '.account_no', function () {

                var account_no = $('.bank_id').val();
                var url = "{{URL::to('getaccountdetails')}}/" + account_no;
                $.get(url, function (data) {
                    $('.account_code_id').val(data[0].account_code_id).change();

                });
            });

            /*Validation*/
            $(document).on('keypress', '.receipt_amount', function (ev) {
                var regex = new RegExp("^[0-9.]+$");
                var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                if (regex.test(str)) {
                    return true;
                }
                ev.preventDefault();
                return false;
            });

            /*End*/

            $('#bank_id').on('change', function () {
                var bank = $('#bank_id').val();
                var pdt_condition = "bank_account_hdr_id=" + bank;

                var url = "{{ URL::to('jcomboform') }}?table=f_bank_account_lines_t:bank_account_line_id:account_number"
                    + "&order_by=account_number asc"
                    + "&parent=" + pdt_condition;

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (data) {
                        if (typeof data === "string") {
                            try {
                                data = JSON.parse(data);
                            } catch (e) {
                                console.error("Invalid JSON response:", data);
                                return;
                            }
                        }

                        var $dropdown = $(".account_no");
                        $dropdown.html('<option value="">-- Please Select --</option>');

                        $.each(data, function (i, item) {
                            $dropdown.append(`<option value="${item.val}">${item.option_name}</option>`);
                        });

                        // re-init select2 if used
                        $dropdown.trigger('change.select2');
                    }
                });
            });




            $('.receipt_type_id').on('change', function () {
                var pmttypeid = $('.receipt_type_id option:selected').text();
                var pmttype = $.trim(pmttypeid);
                if (pmttype == "CHEQUE") {
                    $(".cheque_no").attr('required', true);
                    $(".cheque_date").attr('required', true);
                }
                else {
                    $(".cheque_no").attr('required', false);
                    $(".cheque_date").attr('required', false);
                }
                if (pmttype != "CASH") {
                    $('.chequediv,.bankdiv').css('display', 'block');
                } else {
                    var url = "{{URL::to('getcashaccount')}}";
                    $.get(url, function (data) {
                        $('.account_code_id').val(data[0].cash_account_id).change();

                    });
                    $('#bank_id').prop('required', false);
                    $('.chequediv,.bankdiv').css('display', 'none');
                }

                if (pmttype == "CHEQUE") {

                    $('.chequelabel').html('Cheque No');
                    $('.cheque_no').attr('required', true);
                    $('.chequedate').show();
                    $(".cheque_date").attr('required', true);

                } else {
                    $('.chequelabel').html('Reference No');
                    $('.cheque_no').attr('required', false);
                    $(".cheque_date").attr('required', false);
                    $('.chequedate').hide();
                    $('.chequediv').hide();

                }

            });



            $(document).on('click', '.saveform', function () {
                var btnval = $(this).val();
                $('#savestatus').val(btnval);

                var url = "{{ url('directreceiptsave') }}";
                var red_url = "{{url('receiptsindex')}}"

                var formdata = $('#directreceipt_form').serialize();
                var form = $('#directreceipt_form');
                form.parsley().validate();
                var form = $('#directreceipt_form');
                form.parsley().validate();
                if (form.parsley().isValid()) {
                    var $btn = $(this);
                    $btn.prop('disabled', true);
                    var formdata = $('#directreceipt_form').serialize();
                    $.post(url, formdata, function (data) {
                        var status = data.status;
                        var msg = data.message;
                        var id = data.id;
                        var edit_url = "{{ url('receiptforinvoicecreate') }}/" + id;
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
        });

    </script>



@endpush