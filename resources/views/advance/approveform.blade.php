@extends('layouts.header')
@section('content')

    <h3 class="text-danger">

        <?php if ($advance_deduct_status == "advancededuct") { ?>
        Advance Deduction
        <?php } else { ?>
        Advance Approval
        <?php } ?>
    </h3>
    @include('layouts.breadcrumb')


    <div class="card shadow-lg border-0 rounded-4 p-4 mb-4">
        <div class="card-body">
            <form action="" id="advance" data-parsley-validate>
                <input type="hidden" name="edit_id" value="{{$advance_id}}" id="edit_id" />
                {{ csrf_field() }}

                <div class="row g-4">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="mb-3 row" style="pointer-events: none;">
                            <label class="col-sm-5 col-form-label">Employee <span class="text-danger">*</span></label>
                            <div class="col-sm-7">
                                <select name="employee_id" id="employee_id" class="form-select select2">
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-5 col-form-label">Advance Date</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="advance_date" name="advance_date" readonly
                                    value="{{$advance_date}}">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-5 col-form-label">Effective Date</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="effective_date" name="effective_date" readonly
                                    value="{{$effective_date}}">
                            </div>
                        </div>

                        <div class="mb-3 row" style="pointer-events: none;">
                            <label class="col-sm-5 col-form-label">Mode <span class="text-danger">*</span></label>
                            <div class="col-sm-7">
                                <select name="mode" id="mode" class="form-select select2">
                                    <option value="">--- Please Select --</option>
                                    <option value="1" {{ $mode == 1 ? "selected" : "" }}>Cash</option>
                                    <option value="2" {{ $mode == 2 ? "selected" : "" }}>Check</option>
                                    <option value="3" {{ $mode == 3 ? "selected" : "" }}>On-Line</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-5 col-form-label">Amount <span class="text-danger">*</span></label>
                            <div class="col-sm-7">
                                <input type="text" id="amount" name="amount" class="form-control" readonly
                                    value="{{$amount}}">
                            </div>
                        </div>

                        <div class="mb-3 row" style="pointer-events: none;">
                            <label class="col-sm-5 col-form-label">Approved By <span class="text-danger">*</span></label>
                            <div class="col-sm-7">
                                <select id="forwarded_id" name="forwarded_id" class="form-select select2"></select>
                            </div>
                        </div>

                        @if($advance_deduct_status == "advancededuct")
                            <div class="mb-3 row">
                                <label class="col-sm-5 col-form-label">Till Paid Amount</label>
                                <div class="col-sm-7">
                                    <input type="text" id="till_paid_amount" name="till_paid_amount" class="form-control"
                                        value="{{$paid_amount}}" readonly>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-5 col-form-label">Till Remaining Amount</label>
                                <div class="col-sm-7">
                                    <input type="text" id="till_remaining_amount" name="till_remaining_amount"
                                        class="form-control till_remaining_amount" value="{{$remaining_amount}}" readonly>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <div class="mb-3 row" style="pointer-events: none;">
                            <label class="col-sm-5 col-form-label">Advance From <span class="text-danger">*</span></label>
                            <div class="col-sm-7">
                                <select id="advance_from" name="advance_from" class="form-select select2">
                                    <option value="">--- Please Select --</option>
                                    <option value="1" {{ $advance_from == 1 ? "selected" : "" }}>Loan (EMI)</option>
                                    <option value="2" {{ $advance_from == 2 ? "selected" : "" }}>Advance (Salary)</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row emi_div" style="display:none;">
                            <label class="col-sm-5 col-form-label">EMI Month</label>
                            <div class="col-sm-7">
                                <input type="text" id="emi" name="emi" class="form-control" value="{{$emi}}" readonly>
                            </div>
                        </div>

                        <div class="mb-3 row emi_div" style="display:none;">
                            <label class="col-sm-5 col-form-label">EMI Amount</label>
                            <div class="col-sm-7">
                                <input type="text" id="emi_amount" name="emi_amount" class="form-control"
                                    value="{{$emi_amount}}" readonly>
                            </div>
                        </div>

                        <div class="mb-3 row cheque_div" style="display:none;">
                            <label class="col-sm-5 col-form-label">Cheque Number</label>
                            <div class="col-sm-7">
                                <input type="text" id="cheque_number" name="cheque_number" class="form-control"
                                    value="{{$cheque_number}}" readonly>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-5 col-form-label">Advance Reason <span class="text-danger">*</span></label>
                            <div class="col-sm-7">
                                <input type="text" id="advance_reason" name="advance_reason" class="form-control"
                                    value="{{$advance_reason}}" readonly>
                            </div>
                        </div>

                        @if($advance_deduct_status == "advancededuct")
                            <div class="mb-3 row">
                                <label class="col-sm-5 col-form-label">Deduction Date <span class="text-danger">*</span></label>
                                <div class="col-sm-7">
                                    <input type="text" id="deduction_date" name="deduction_date" class="form-control datepicker"
                                        value="{{$date}}">
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-5 col-form-label">Deduction Amount <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-7">
                                    <input type="text" id="amount_pay" name="amount_pay" class="form-control amount_pay"
                                        required>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row mt-4">
                    <div class="col text-center d-flex justify-content-center">
                        @if($advance_deduct_status == "advancededuct")
                            <button type="button" class="btn btn-success save_form1 px-4 me-2" value="3">Save</button>
                            <a href="{{ url('advancededuction') }}"><button type="button"
                                    class="btn btn-secondary clear1 px-4 me-2" id="delete">Cancel</button></a>
                        @else
                            <button type="button" class="btn btn-success save_form px-4 me-2" value="1">Approve</button>
                            <button type="button" class="btn btn-danger save_form px-4 me-2" value="2">Reject</button>
                            <a href="{{ url('addvanceapproval') }}"> <button type="button"
                                    class="btn btn-secondary clear px-4 me-2" id="delete">Cancel</button></a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>



@endsection
@push('scripts')

    <script>



        function loadDropdown(selector, url, selectedValue, defaultText = "-- Select --") {
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

                    $(selector).html(`<option value="">${defaultText}</option>`);

                    $.each(data, function (i, item) {
                        let selected = item.val == selectedValue ? 'selected' : '';
                        $(selector).append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
                    });

                    $(selector).trigger('change.select2');
                }
            }); // <-- This closing brace and semicolon was missing
        }

        // Variables from Blade
        var reporting_id = '{{$forwarded_id}}';
        var employee_id = '{{$employee_id}}';

        // Forwarded ID (Exclude Logged User)
        loadDropdown(
            "#forwarded_id",
            "{{ URL::to('jcomboform') }}?table=hr_employee_t:employee_id:employee_number|first_name",
            reporting_id,
            "-- Select Forwarded Employee --"
        );

        // Logged-in User
        loadDropdown(
            "#employee_id",
            "{{ URL::to('jcomboform') }}?table=hr_employee_t:employee_id:employee_number|first_name",
            employee_id,
            "-- Select Employee --"
        );

        $(document).on('change', '.amount_pay', function (ev) {
            var till_remaining_amount = parseFloat($('.till_remaining_amount').val());
            var amount_pay = parseFloat($('.amount_pay').val());
            if (amount_pay != '') {
                if (amount_pay > till_remaining_amount) {
                    $('.amount_pay').val('');
                    showCustomAlert('Deduction Amount Not Greater than Remaining Amount', 'warning');
                }
            }
        });


        $(document).on('keypress', '.amount,.amount_pay', function (ev) {

            var regex = new RegExp("^[0-9.]+$");
            var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
            if (regex.test(str)) {
                return true;
            }
            ev.preventDefault();
            return false;
        });
        // save 


        /** Advance Form  Employee Save data Start **/
        $(document).on('click', '.save_form1', function () {
            var url = "{{URL::to('advancedeductionsave')}}";
            var form = $('#advance');
            form.parsley().validate();

            if (form.parsley().isValid()) {
                var $btn = $(this);
                $btn.prop('disabled', true);
                var data = $('#advance').serialize();
                $.post(url, data, function (data1) {
                    if (data1 == 1) {
                        showCustomAlert('Advance details Saved Successfully', 'success');
                        var url = "{{URL::to('advancededuction')}}";
                        window.location.href = url;
                    }

                });
            }
        });
        /** Advance Form  Employee Save data End **/

        $(document).on('click', '.save_form', function () {
            /** Advance Form  Employee Save data start **/
            var status = $(this).val();
            var advance_id = '{{$advance_id}}';
            var url = "{{URL::to('advancestatus')}}?id=" + advance_id + "&status=" + status;

            $.get(url, function (data1) {
                var $btn = $(this);
                $btn.prop('disabled', true);
                if (data1[0] == 1) {
                    if (data1[1] == "Approved")
                        var not = "success";
                    else if (data1[1] == "Deducted")
                        var not = "success";
                    else
                        var not = "error";
                    showCustomAlert('Advance ' + data1[1] + ' Successfully', 'success');
                    setTimeout(function () {
                        if (data1[1] != "Deducted")
                            var url = "{{URL::to('addvanceapproval')}}";
                        else
                            var url = "{{URL::to('advancededuction')}}";
                        window.location.href = url;
                    }, 2000);

                }
            });

        });

    </script>

@endpush