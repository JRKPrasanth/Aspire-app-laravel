@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Advance Payment</h3>
    @include('layouts.breadcrumb')
    <?php include('tools_menu.php');?>




    <form method="post" action="" id="advance_form" data-parsley-validate>
        {{ csrf_field() }}

        <div class="card shadow-lg rounded-4 border-0">
            <div class="card-body card-block">
                <div class="row">
                    <div class="col-md-6">
                        <input class="form-control payment_id" id="payment_id" name="payment_id" size="16" type="hidden"
                            value="{{ $row->payment_id }}" readonly>
                        <input class="form-control payment_number" id="payment_number" name="payment_number" size="16"
                            type="hidden" value="{{ $row->payment_number }}" readonly>
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-5">Purchase Order No</label>
                            <div class="col-md-5 sel2">
                                <select name='po_hdr_id[]' rows='5' class='form-control po_hdr_id' required readonly
                                    multiple>
                                    {!! $po_hdr_id !!}
                                </select>
                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-5">Supplier Name</label>
                            <div class="col-md-5">
                                <select name='supplier_id' rows='5' class='form-control supplier_id' readonly>
                                    {!! $supplier_id !!}
                                </select>
                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-5">Supplier Bank Name</label>
                            <div class="col-md-5">
                                <input type="text" id="supplier_bank_id" name="supplier_bank_id"
                                    class="form-control supplier_bank_id" value="{{ $row->supplier_bank_id}}" readonly>
                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-5">Supplier Account Name</label>
                            <div class="col-md-5">
                                <input type="text" id="supplier_account_name" name="supplier_account_name"
                                    class="form-control supplier_account_name" value="{{ $row->supplier_account_name}} "
                                    readonly>
                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-5">Supplier Account No</label>
                            <div class="col-md-5">
                                <input type="text" id="supplier_account_no" name="supplier_account_no"
                                    class="form-control supplier_account_no" value="{{ $row->supplier_account_no}}"
                                    readonly>
                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-5">Supplier Favouring Name</label>
                            <div class="col-md-5">
                                <input type="text" id="favouring_name" name="favouring_name"
                                    class="form-control favouring_name" value="{{ $row->favouring_name}}">
                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-5">Supplier IFSC Code</label>
                            <div class="col-md-5">
                                <input type="text" id="supplier_ifsc_code" name="supplier_ifsc_code"
                                    class="form-control supplier_ifsc_code" value="{{ $row->supplier_ifsc_code}}" readonly>
                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-5">PO Amount</label>
                            <div class="col-md-5">
                                <input type="text" id="po_amount" class="form-control po_amount chckclick"
                                    value="{{ $row->po_amount }}" readonly>
                            </div>
                            <div class="col-md-2 showline">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-5"><span
                                    style="color:red;">*</span>Advance Payment Date</label>
                            <div class="col-md-5">
                                <div class="input-group form_date col-md-8" data-date="" data-date-format="dd MM yyyy"
                                    data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                    <input class="form-control payment_date" id="payment_date"
                                        name="payment_date" size="16" type="text" value="{{ $row->payment_date }}" required>
                                </div>
                            </div>
                            <div class="col-md-2 showline">
                            </div>
                        </div>
                        <div class="row mb-3 " style="display:none;">
                            <label for="inputIsValid" class="col-form-label col-md-5">Advance Payment Status</label>
                            <div class="col-md-5">
                                <select type="text" name="payment_status" id="payment_status"
                                    class="form-control payment_status" readonly>
                                    <option value="">-- Please Select --</option>
                                    <option <?php if ($row->payment_status == "DRAFT")
        echo "selected"; ?> value="DRAFT">DRAFT
                                    </option>
                                    <option <?php if ($row->payment_status == "INITIATED")
        echo "selected"; ?>
                                        value="INITIATED">INITIATED</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
                        <div class="row mb-3 " style="display:none;">
                            <label for="inputIsValid" class="col-form-label col-md-5">Payment Source</label>
                            <div class="col-md-5">
                                <select type="text" name="payment_source" id="payment_source"
                                    class="form-control payment_source" readonly>
                                    <option value="">-- Please Select --</option>
                                    <option <?php if ($row->payment_source == "ADVANCE")
        echo "selected"; ?> value="ADVANCE">
                                        ADVANCE</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-5">UTR Number</label>
                            <div class="col-md-5">
                                <input type="text" id="payment_reference" class="form-control payment_reference" value="" />
                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-5">Sender Information</label>
                            <div class="col-md-5">
                                <input type="text" id="sender_information" class="form-control sender_information" name="sender_information"
                                    value="{{ $row->sender_information }}">

                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-5">Narration</label>
                            <div class="col-md-5">
                                <input type="text" name="remarks" id="remarks" class="form-control remarks"
                                    value="{{ $row->remarks }}">

                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>



                    </div>



                    <div class="col-md-6">
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-5"><span
                                    style="color:red;">*</span>Advance Amount</label>
                            <div class="col-md-5">
                                <input type="text" id="advance_amount" name="advance_amount"
                                    class="form-control advance_amount chckclick" value="" required readonly>
                            </div>
                            <div class="col-md-2 d-flex align-items-center">
                                <button type="button" class="btn btn-outline-primary btn-sm rounded-circle shadow-sm"
                                    data-bs-toggle="modal" data-bs-target="#myModal" title="Add Payment">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>

                            <div class="col-md-2 showline">
                            </div>
                        </div>
                        <div class="row mb-3 tds_amount">
                            <label for="inputIsValid" class="col-form-label col-md-5"><span style="color:red;">*</span>TDS
                                Applicable</label>
                            <div class="col-md-5 tds_apply_div">
                                <select name='tds_applicable' rows='5' class='tds_applicable select2'
                                    data-show-subtext="true" data-live-search="true" required>
                                    <option value="">--Please Select--</option>
                                    <option value="YES" <?php if ($row->tds_applicable == 'YES') {
        echo "selected";
    } ?>>YES
                                    </option>
                                    <option value="NO" <?php if ($row->tds_applicable == 'NO') {
        echo "selected";
    } ?>>NO
                                    </option>
                                </select>
                            </div>

                        </div>
                        <div class="row mb-3 tds_div">
                            <label for="inputIsValid" class="col-form-label col-md-5">TDS Percentage</label>
                            <div class="col-md-5 pointerEvents" style="pointer-events:none;">
                                <select name='tds_prcnt' rows='5' class='tds_prcnt select2' data-show-subtext="true"
                                    data-live-search="true">
                                    {!! $tds_prcnt !!}
                                </select>
                            </div>
                            <div class="col-md-1">
                            </div>
                        </div>
                        <div class="row mb-3 tds_div">
                            <label for="inputIsValid" class="col-form-label col-md-5">TDS Amount</label>
                            <div class="col-md-5">
                                <input type="text" name="tds_amount" id="tds_amount" value="{{$row->tds_amount}}"
                                    class="form-control tds_amount" readonly tabindex="15">
                            </div>
                            <div class="col-md-1">
                            </div>
                        </div>
                        <div class="row mb-3 tds_div">
                            <label for="inputIsValid" class="col-form-label col-md-5">TDS Account</label>
                            <div class="col-md-5 pointerEvents" style="pointer-events:none;">
                                <select name='tds_account_id' rows='5' class='tds_account_id select2'> {!! $tds_account_id
                                    !!}
                                </select>
                            </div>
                            <div class="col-md-1">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-5"><span
                                    style="color:red;">*</span>Payment Amount</label>
                            <div class="col-md-5">
                                <input type="text" id="payment_amount" name="payment_amount"
                                    class="form-control payment_amount chckclick" value="{{ $row->payment_amount }}"
                                    required readonly>
                            </div>
                            <div class="col-md-2 showline">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-5"><span
                                    style="color:red">*</span>Payment Type</label>
                            <div class="col-md-5 sel2">
                                <select name='payment_type_id' rows='5' class='form-control payment_type_id select2'
                                    data-show-subtext="true" data-live-search="true" required>
                                    <option value="">--Please Select--</option>
                                    <option <?php if ($row->payment_type_id == "CHEQUE") {
        echo "selected";
    } else {
        echo "";
    } ?> value="CHEQUE">CHEQUE</option>
                                    <option <?php if ($row->payment_type_id == "CASH") {
        echo "selected";
    } else {
        echo "";
    } ?> value="CASH">CASH</option>
                                    <option <?php if ($row->payment_type_id == "NEFT") {
        echo "selected";
    } else {
        echo "";
    } ?> value="NEFT">NEFT</option>
                                    <option <?php if ($row->payment_type_id == "MTPS") {
        echo "selected";
    } else {
        echo "";
    } ?> value="MTPS">MTPS</option>
                                    <option <?php if ($row->payment_type_id == "RTGS") {
        echo "selected";
    } else {
        echo "";
    } ?> value="RTGS">RTGS</option>
                                    <option <?php if ($row->payment_type_id == "IMPS") {
        echo "selected";
    } else {
        echo "";
    } ?> value="IMPS">IMPS</option>
                                    <option <?php if ($row->payment_type_id == "ONLINE") {
        echo "selected";
    } else {
        echo "";
    } ?> value="ONLINE">ONLINE</option>

                                </select>
                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
                        <div class="row mb-3 chequediv">
                            <label for="inputIsValid" class="col-form-label col-md-5"><span class="bankdiv"
                                    style="color:red">*</span>Bank Name</label>
                            <div class="col-md-5 sel2">
                                <select name='bank_id' rows='5' id='bank_id' class='select2 bank_id' required>
                                    {!! $bank_id !!}
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3 accnumb chequediv">
                            <label for="inputIsValid" class="col-form-label col-md-5">Account Number</label>
                            <div class="col-md-5 supplier_div">
                                <select name='account_no' rows='5' id='account_no' class='select2 account_no'>
                                    {!! $account_no !!}
                                </select>
                            </div>
                            <div class="col-md-2 showinline">
                            </div>
                        </div>

                        <div class="row mb-3 acccode">
                            <label for="inputIsValid" class="col-form-label col-md-5"><span
                                    style="color:red;">*</span>Account Code</label>
                            <div class="col-md-5">
                                <select name='account_code_id' rows='5' class='select2 account_code_id' required>
                                    {!! $account_code_id !!}
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3 chequediv">
                            <label for="inputIsValid" class="col-form-label col-md-5 chequelabel">Cheque No</label>
                            <div class="col-md-5 supplier_div">
                                <input type="text" id="cheque_no" name="cheque_no" class="form-control cheque_no"
                                    value="" />
                                <!--{{ $row->cheque_no }}-->
                            </div>
                            <div class="col-md-2 showinline">
                            </div>
                        </div>

                    </div>

                </div>


                <div class="row mt-2">
                    <div class="col-lg-12 col-md-12">
                        <input type="hidden" name="submit_type" class="submit_type" value="" />
                        <div class="form-group text-center actionbtn">

                            <button name="submit" type="button" class="btn btn-success px-4 me-2 saveform"
                                value="SAVE">Save</button>
                            <a class='btn btn-danger px-4' onclick='location.href ="{{ url($pageModule) }}"'>Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" class="pdtindex" value="" />
        <!-- Bootstrap 5 Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModal" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content shadow-lg border-0 rounded-4">

                    <!-- Modal Header -->
                    <div class="modal-header bg-primary text-white rounded-top-4">
                        <h5 class="modal-title fw-bold" id="poBalanceModalLabel">
                            <i class="bi bi-receipt-cutoff me-2"></i>PO Balance Details
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body bg-light">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle mb-0">
                                <thead class="table-primary text-center">
                                    <tr>
                                        <th>PO Number</th>
                                        <th>Balance Amount</th>
                                        <th>Payment Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1; @endphp
                                    @foreach ($po_balamt as $v)
                                        <tr>
                                            <td class="fw-semibold">{{ $v->po_number }}</td>
                                            <td class="text-end text-success fw-bold balance_amount balance_amount{{ $i }}"
                                                id="{{ $v->balance_amount }}">
                                                ₹{{ number_format($v->balance_amount, 2) }}
                                            </td>
                                            <td class="text-center">
                                                <input type="number" step="0.01" name="paymentamt[{{ $v->po_invoice_id }}]"
                                                    class="form-control text-end paymentamt" placeholder="Enter amount" min="0">
                                                <input type="hidden" class="po_tax_total" value="{{ $v->po_tax_total }}"
                                                    readonly>
                                            </td>
                                        </tr>
                                        @php $i++; @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer bg-white rounded-bottom-4">
                        <button type="button" class="btn btn-success px-4 addpayamts" data-bs-dismiss="modal">
                            <i class="bi bi-plus-circle me-1"></i> Add
                        </button>
                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Close
                        </button>
                    </div>

                </div>
            </div>
        </div>


    </form>


@endsection
@push('scripts')

    <script>

        $(document).ready(function () {
            $('select').css('pointer-events', 'none');

            var decimal = "<?php echo \Session('decimal'); ?>";
            /*Karthigaa purpose:add advance amount*/
            $(document).on('click', '.addpayamts', function () {
                var tot = 0;
                var index = $(this).closest('tr').index();
                $(".paymentamt").each(function () {
                    tot += parseFloat($(this).val());
                });
                $('.advance_amount').val(tot.toFixed(decimal));
            });

            $(document).on('keyup', '.paymentamt', function () {
                var index = $(this).closest('tr').index();
                var payment = parseInt($(this).val());
                var balance_amount = parseInt($('.balance_amount' + index).attr('id'));
                var payment_amount = $(".payment_amount").val();
                if (balance_amount > 0) {
                    if (payment > balance_amount) {
                        showCustomAlert('Payment amount is not more than balance amount', 'info');
                        $(this).val('');
                    }
                } else {
                    if (payment < balance_amount) {
                        showCustomAlert('Payment amount is not more than balance amount', 'info');
                        $(this).val('');
                    }
                }
            });


            $('.account_code_div,.acccode').css("pointer-events", "none");
            /*Validation*/
            $(document).on('keypress', '.payment_amount,.advance_amount', function (ev) {
                var regex = new RegExp("^[0-9.]+$");
                var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                if (regex.test(str)) {
                    return true;
                }
                ev.preventDefault();
                return false;
            });

            $(".tds_div").hide();


            /*Karthigaa Purpose For TDS Applicable */
            $(".tds_applicable").change(function () {
                var supplier_id = $('.supplier_id').val();
                var tds = $(".tds_applicable option:selected").val();
                if (tds == "YES") {
                    var advanceamt = $(".advance_amount").val();
                    if (advanceamt != "") {
                        $(".tds_div").show();
                        var url = "{{ URL::to('loadtds') }}/" + supplier_id + "/" + tds;
                        $.get(url, function (data) {
                            if (data.tds_percentage != "") {
                                $(".pointerEvents").css("pointer-events", "auto");
                                $('.tds_prcnt').select2("val", data.tds_percentage);
                                $('.tds_account_id').val(data.tds_account_id).change();
                                var data1 = data.tds_percentage;
                                var advanceamt = $(".advance_amount").val();
                                var tds_prcnt = $(".tds_prcnt option:selected").text();
                                var po_amount = $('.po_amount').val();

                                var po_tax_total = $('.po_tax_total').val();
                                if (tds_prcnt != '') {

                                    var amounta = Number(po_tax_total) / Number(po_amount);
                                    var gts = (amounta * advanceamt);
                                    var tdsamt = Number(advanceamt) - Number(gts);
                                    var tds_amount = Math.round((Number(tds_prcnt) * Number(tdsamt) / 100));

                                    $('.tds_amount').val(tds_amount.toFixed(2));
                                    sumwithtds = Number(advanceamt) - Number(tds_amount);
                                    $('#payment_amount').val(sumwithtds.toFixed(2));
                                }
                            }
                            else {
                                $(".pointerEvents").css("pointer-events", "auto");

                                $(".tds_prcnt").select2('destroy').val("").select2();

                                showCustomAlert("TDS Percentage Not Set For This Supplier", "error");
                            }

                        });
                    } else {
                        showCustomAlert("Please Enter Advance Amount", "error");
                        $(".tds_applicable").val("").change();
                    }
                }
                else {
                    var advanceamt = $(".advance_amount").val();
                    $('#payment_amount').val(advanceamt);
                    $('.tds_prcnt').select2("val", "");
                    $('.tds_amount').val("");
                    $('.tds_account_id').val("").change();
                    $(".tds_div").hide();
                }
            });
            $(".tds_amount").change(function () {
                var tds_amount = $(this).val();
                var advanceamt = $(".advance_amount").val();
                var tds_prcnt = $(".tds_prcnt option:selected").text();
                if (tds_prcnt == 0) {
                    var payamt = parseFloat(advanceamt) - Number(tds_amount);
                    $(".payment_amount").val(payamt);
                } else {
                    $('.tds_amount').attr('readonly', false);
                }
            });
            $(".tds_prcnt").change(function () {
                var advanceamt = $(".advance_amount").val();
                var po_amount = $('.po_amount').val();
                var po_tax_total = $('.po_tax_total').val();
                var tds = $(".tds_applicable option:selected").val();
                if (advanceamt != "") {
                    if (tds == 'YES') {
                        $(".pointerEvents").css("pointer-events", "auto");
                        var tds_prcnt = $(".tds_prcnt option:selected").text();
                        if (tds_prcnt != '0') {
                            var amounta = Number(po_tax_total) / Number(po_amount);
                            var gts = (amounta * advanceamt);
                            var tdsamt = Number(advanceamt) - Number(gts);
                            var tds_amount = Math.round((Number(tds_prcnt) * Number(tdsamt) / 100));
                            $('.tds_amount').attr('readonly', true);
                            $('.tds_amount').val(tds_amount.toFixed(2));
                            sumwithtds = Number(advanceamt) - Number(tds_amount);
                            $('#payment_amount').val(sumwithtds.toFixed(2));
                        } else {
                            $('.tds_amount').attr('readonly', false);
                            $('.tds_account_id').val("").change();
                        }
                    } else {
                        showCustomAlert("Please TDS Percentage", "error");
                        $(".tds_prcnt").select2("val", "");
                        // $(".tds_prcnt").val("");
                        $('.tds_amount').val("");
                        $('.tds_account_id').val("").change();
                    }
                }
                else {
                    showCustomAlert("Please Enter Advance Amount", "error");
                    $(".tds_applicable").val("").change();
                }
            });

            $('.payment_amount').bind("cut copy paste", function (e) {
                e.preventDefault();
            });
            /*copy paste validation*/

            $(document).on('keyup', '.advance_amount', function () {
                var advamt = parseFloat($(this).val());
                var poamt = parseFloat($('.po_amount').val());
                if (advamt > poamt) {
                    showCustomAlert("Advance Should be Less than PO Amount", "error");
                    $('.advance_amount').val('');
                } else {
                    $('#payment_amount').val(advamt);
                }
            });




            /* Purpose for Bank based Account Code load*/
            $(document).on('change', '.account_no', function () {
                var account_no = $('.account_no').val();
                var url = "{{URL::to('getaccountdetails')}}/" + account_no;
                if (account_no != '') {
                    $.get(url, function (data) {
                        console.log(data);
                        $('.account_code_id').val(data[0].account_code_id).change();

                    });
                }
            });
            /*End*/

            $('.payment_type_id').on('change', function () {
                var pmttypeid = $('.payment_type_id option:selected').text();
                var pmttype = $.trim(pmttypeid);
                if (pmttype != "CASH") {
                    $('.account_code_id').val('').change();
                    $('.bank_id').val('').change();
                    $('.account_no').val('').change();
                    $('.chequediv').css('display', 'block');
                } else {
                    var url = "{{URL::to('getcashaccount')}}";
                    $.get(url, function (data) {
                        $('.account_code_id').val(data[0].cash_account_id).change();
                    });
                    $('#bank_id').prop('required', false);
                    $('.chequediv,.bankdiv').css('display', 'none');
                } if (pmttype == "CHEQUE") {

                    $('.chequelabel').html('Cheque No');
                    $('.cheque_no').attr('readonly', false);
                } else {

                    $('.chequelabel').html('Reference No');
                    $('.cheque_no').attr('readonly', false);


                }

                if (pmttype == "CHEQUE" || pmttype == "CASH" || pmttype == "ONLINE") {
                    $(".supplier_bank_id").attr('required', false);
                    var form = $('#advance_form');
                    form.parsley().destroy();

                }
                else {

                    $(".supplier_bank_id").attr('required', true);
                    var form = $('#advance_form');
                    form.parsley().destroy();

                }

            });

            /* Purpose for Po based Supplier and amount load*/
            $(document).on('change', '.po_hdr_id', function () {
                var poid = $('.po_hdr_id').val();

                var url = "{{URL::to('getpodetails')}}/" + poid;
                $.get(url, function (data) {
                    $('.supplier_id').val(data[0].supplier_id).change();
                    $('.po_amount').val(data[0].po_grand_total);
                });
            });

            /* Purpose For Save Function*/

            $(document).on('click', '.saveform', function () {
                var btnval = $(this).val();
                if (btnval == 'DRAFT') {
                    $("#payment_status").val('DRAFT');
                } else {
                    $("#payment_status").val('INITIATED');
                }
                $('#savestatus').val(btnval);

                var url = "{{ url('advancepaymentsave') }}";
                var red_url = "{{url('advancepayment')}}"

                var formdata = $('#advance_form').serialize();
                validationrule('advance_form');
                var form = $('#advance_form');
                form.parsley().validate();
                if (form.parsley().isValid()) {
                    var $btn = $(this);
                    $btn.prop('disabled', true);
                    var formdata = $('#advance_form').serialize();


                    $.post(url, formdata, function (data) {
                        var status = data.status;
                        var msg = data.message;
                        var id = data.id;
                        var edit_url = "{{ url('advancepaymentcreate') }}/" + id;
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

        $('#bank_id').on('change', function () {
            var bank = $(this).val();

            if (bank !== '') {
                var url = "{{ URL::to('jcomboform') }}" +
                    "?table=f_bank_account_lines_t:bank_account_line_id:account_number" +
                    "&order_by=account_number asc" +
                    "&parent=bank_account_hdr_id=" + bank;

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (data) {
                        // Convert JSON string if necessary
                        if (typeof data === "string") {
                            try {
                                data = JSON.parse(data);
                            } catch (e) {
                                console.error("Invalid JSON response:", data);
                                return;
                            }
                        }

                        // Populate account_no dropdown
                        var $account = $(".account_no");
                        $account.empty().append('<option value="">-- Select Account --</option>');

                        $.each(data, function (i, item) {
                            $account.append(`<option value="${item.val}">${item.option_name}</option>`);
                        });

                        $account.trigger('change.select2'); // if using Select2
                    },
                    error: function () {
                        console.error("Failed to load account numbers");
                    }
                });
            } else {
                $(".account_no").empty().append('<option value="">-- Select Account --</option>');
            }
        });

                        const dateToday = new Date();
                             $(".payment_date").datepicker("destroy").datepicker({
                                
                                    changeMonth: true,
                                    changeYear: true,
                                    dateFormat: "yy-mm-dd",
                                    maxDate: dateToday,
                                    showAnim: "slideDown",
                                    yearRange: "-25:+0"
                                });
    </script>

@endpush