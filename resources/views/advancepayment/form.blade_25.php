@extends('layouts.header')
@section('content')
<span class="ui_close_btn"></span>


<?php include('tools_menu.php'); ?>
<h4 class="heads">
    Advance Payment <span class="ui_close_btn"><a class="collapse-close pull-right btn-danger"
            onclick="location.href = '{{url('advancepayment')}}'"></a></span>
</h4>

<form method="post" action="" id="advance_form" data-parsley-validate>
    {{ csrf_field() }}

    <div class="card">
        <div class="card-body card-block">
            <div class="row">
                <div class="col-md-6">
                    <input class="form-control payment_id" id="payment_id" name="payment_id" size="16" type="hidden"
                        value="{{ $row->payment_id }}" readonly>
                    <input class="form-control payment_number" id="payment_number" name="payment_number" size="16"
                        type="hidden" value="{{ $row->payment_number }}" readonly>
                    <div class="form-group row">
                        <label for="inputIsValid" class="form-control-label col-md-5">Purchase Order No</label>
                        <div class="col-md-5 sel2">
                            <select name='po_hdr_id[]' rows='5' class='form-control po_hdr_id' required readonly
                                multiple>
                                {!! $po_hdr_id !!}
                            </select>
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputIsValid" class="form-control-label col-md-5">Supplier Name</label>
                        <div class="col-md-5">
                            <select name='supplier_id' rows='5' class='form-control supplier_id' readonly>
                                {!! $supplier_id !!}
                            </select>
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputIsValid" class="form-control-label col-md-5">Supplier Bank Name</label>
                        <div class="col-md-5">
                            <input type="text" id="supplier_bank_id" name="supplier_bank_id"
                                class="form-control supplier_bank_id" value="{{ $row->supplier_bank_id}}" readonly>
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputIsValid" class="form-control-label col-md-5">Supplier Account Name</label>
                        <div class="col-md-5">
                            <input type="text" id="supplier_account_name" name="supplier_account_name"
                                class="form-control supplier_account_name" value="{{ $row->supplier_account_name}} "
                                readonly>
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputIsValid" class="form-control-label col-md-5">Supplier Account No</label>
                        <div class="col-md-5">
                            <input type="text" id="supplier_account_no" name="supplier_account_no"
                                class="form-control supplier_account_no" value="{{ $row->supplier_account_no}}"
                                readonly>
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputIsValid" class="form-control-label col-md-5">Supplier Favouring Name</label>
                        <div class="col-md-5">
                            <input type="text" id="favouring_name" name="favouring_name"
                                class="form-control favouring_name" value="{{ $row->favouring_name}}">
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputIsValid" class="form-control-label col-md-5">Supplier IFSC Code</label>
                        <div class="col-md-5">
                            <input type="text" id="supplier_ifsc_code" name="supplier_ifsc_code"
                                class="form-control supplier_ifsc_code" value="{{ $row->supplier_ifsc_code}}" readonly>
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputIsValid" class="form-control-label col-md-5">PO Amount</label>
                        <div class="col-md-5">
                            <input type="text" id="po_amount" name="po_amount" class="form-control po_amount chckclick"
                                value="{{ $row->po_amount }}" readonly>
                        </div>
                        <div class="col-md-2 showline">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputIsValid" class="form-control-label col-md-5"><span
                                style="color:red;">*</span>Advance Payment Date</label>
                        <div class="col-md-5">
                            <div class="input-group form_date col-md-8" data-date="" data-date-format="dd MM yyyy"
                                data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                <input class="form-control datepicker payment_date" id="payment_date"
                                    name="payment_date" size="16" type="text" value="{{ $row->payment_date }}" required>
                            </div>
                        </div>
                        <div class="col-md-2 showline">
                        </div>
                    </div>
                    <div class="form-group row " style="display:none;">
                        <label for="inputIsValid" class="form-control-label col-md-5">Advance Payment Status</label>
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
                    <div class="form-group row " style="display:none;">
                        <label for="inputIsValid" class="form-control-label col-md-5">Payment Source</label>
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
                    <div class="form-group row">
                        <label for="inputIsValid" class="form-control-label col-md-5">UTR Number</label>
                        <div class="col-md-5">
                            <input type="text" id="payment_reference" name="payment_reference"
                                class="form-control payment_reference" value="" />
                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputIsValid" class="form-control-label col-md-5">Sender Information</label>
                        <div class="col-md-5">
                            <input type="text" name="sender_information" id="sender_information"
                                class="form-control sender_information" value="{{ $row->sender_information }}">

                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputIsValid" class="form-control-label col-md-5">Narration</label>
                        <div class="col-md-5">
                            <input type="text" name="remarks" id="remarks" class="form-control remarks"
                                value="{{ $row->remarks }}">

                        </div>
                        <div class="col-md-2">
                        </div>
                    </div>



                </div>



                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="inputIsValid" class="form-control-label col-md-5"><span
                                style="color:red;">*</span>Advance Amount</label>
                        <div class="col-md-5">
                            <input type="text" id="advance_amount" name="advance_amount"
                                class="form-control advance_amount chckclick" value="" required readonly>
                        </div>
                        <div class="col-md-2 showinline">
                            <i class="fa fa-plus" aria-hidden="true" data-toggle="modal" data-target="#myModal" style="color: #142e78;
    font-size: 13px;
    padding: 5px;
    border: 1px solid;
    cursor: pointer;"></i>
                        </div>
                        <div class="col-md-2 showline">
                        </div>
                    </div>
                    <div class="form-group row tds_amount">
                        <label for="inputIsValid" class="form-control-label col-md-5"><span
                                style="color:red;">*</span>TDS Applicable</label>
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
                    <div class="form-group row tds_div">
                        <label for="inputIsValid" class="form-control-label col-md-5">TDS Percentage</label>
                        <div class="col-md-5">
                            <input type="text" name="tds_prcnt" id="tds_prcnt" value="{{$row->tds_prcnt}}"
                                class="form-control tds_prcnt" readonly>
                        </div>
                        <div class="col-md-1">
                        </div>
                    </div>
                    <div class="form-group row tds_div">
                        <label for="inputIsValid" class="form-control-label col-md-5">TDS Amount</label>
                        <div class="col-md-5">
                            <input type="text" name="tds_amount" id="tds_amount" value="{{$row->tds_amount}}"
                                class="form-control tds_amount" readonly tabindex="15">
                        </div>
                        <div class="col-md-1">
                        </div>
                    </div>
                    <div class="form-group row tds_div">
                        <label for="inputIsValid" class="form-control-label col-md-5">TDS Account</label>
                        <div class="col-md-5" style="pointer-events:none;">
                            <select name='tds_account_id' rows='5' class='tds_account_id select2' readonly> {!!
                                $tds_account_id !!}
                            </select>
                        </div>
                        <div class="col-md-1">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputIsValid" class="form-control-label col-md-5"><span
                                style="color:red;">*</span>Payment Amount</label>
                        <div class="col-md-5">
                            <input type="text" id="payment_amount" name="payment_amount"
                                class="form-control payment_amount chckclick" value="{{ $row->payment_amount }}"
                                required>
                        </div>
                        <div class="col-md-2 showline">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputIsValid" class="form-control-label col-md-5"><span
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
                    <div class="form-group row chequediv">
                        <label for="inputIsValid" class="form-control-label col-md-5"><span class="bankdiv"
                                style="color:red">*</span>Bank Name</label>
                        <div class="col-md-5 sel2">
                            <select name='bank_id' rows='5' id='bank_id' class='select2 bank_id' required>
                                {!! $bank_id !!}
                            </select>
                        </div>
                        <div class="col-md-2 showinline">
                            <span class="showspan"> <i class="fa fa-refresh jcr_bank_id"></i></span>
                        </div>
                    </div>
                    <div class="form-group row chequediv">
                        <label for="inputIsValid" class="form-control-label col-md-5">Account Number</label>
                        <div class="col-md-5 supplier_div">
                            <select name='account_no' rows='5' id='account_no' class='select2 account_no'>
                                {!! $account_no !!}
                            </select>
                        </div>
                        <div class="col-md-2 showinline">
                        </div>
                    </div>

                    <div class="form-group row ">
                        <label for="inputIsValid" class="form-control-label col-md-5"><span
                                style="color:red;">*</span>Account Code</label>
                        <div class="col-md-5">
                            <select name='account_code_id' rows='5' class='select2 account_code_id' required>
                                {!! $account_code_id !!}
                            </select>
                        </div>
                        <div class="col-md-2 showinline">
                            <span class="showspan"><i class="fa fa-refresh jcr_account_code_id"></i></span>
                        </div>
                    </div>
                    <div class="form-group row chequediv">
                        <label for="inputIsValid" class="form-control-label col-md-5 chequelabel">Cheque No</label>
                        <div class="col-md-5 supplier_div">
                            <input type="text" id="cheque_no" name="cheque_no" class="form-control cheque_no"
                                value="{{ $row->cheque_no }}" readonly />
                        </div>
                        <div class="col-md-2 showinline">
                        </div>
                    </div>

                </div>

            </div>


            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <input type="hidden" name="submit_type" class="submit_type" value="" />
                    <div class="form-group text-center actionbtn">
                        <!--<button type="button" class="btn draft saveform" value="DRAFT">Draft</button>-->
                        <button name="submit" type="button" class="btn save saveform" value="SAVE">Save</button>
                        <a class='btn cancel' onclick='location.href ="{{ url($pageModule) }}"'>Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" class="pdtindex" value="" />
    <div id="preloader">
        <img
            src="https://jrlma.ca/wp-content/plugins/gallery-by-supsystic/src/GridGallery/Galleries/assets/img/loading.gif">
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">PO Balance Details</h4>
                </div>
                <div class="modal-body">

                    <table class="table table-bordered">

                        <tr style="background:#05234e;color:#fff;font-size: 14px;">
                            <th>PO Number</th>
                            <th>Balance Amount</th>
                            <th>Payment Amount</th>
                        </tr>

                        <?php $i = 1;
                        foreach ($po_balamt as $k => $v) { ?>
                            <tr>
                                <td>{{$v->po_number}}</td>
                                <td class="checkadv balance_amount balance_amount{{$i}}" id="{{$v->balance_amount}}">
                                    {{$v->balance_amount}}</td>
                                <td><input type="text" name="paymentamt[{{$v->po_invoice_id}}]" class="paymentamt"></td>

                            </tr>
                            <?php $i++;
                        } ?>

                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default addpayamt " data-dismiss="modal">Add</button>
                    <button type="button" class="btn btn-default close1 " data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <!--end-->
</form>



<script>

    $(document).ready(function () {
        $('select').css('pointer-events', 'none');


        var decimal = "<?php echo \Session('decimal'); ?>";
        /* purpose:add advance amount*/
        $(document).on('click', '.addpayamt', function () {
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
        /* end */

        $('.payment_date,.account_code_div').css("pointer-events", "none");
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
        /*End*/
        $(".tds_div").hide();
        /* Purpose For TDS Applicable */
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
                            $('.tds_prcnt').val(data.tds_percentage);
                            $('.tds_account_id').val(data.tds_account_id).change();
                            var data1 = data.tds_percentage;
                            var advanceamt = $(".advance_amount").val();
                            var tds_prcnt = $('.tds_prcnt').val();
                            if (tds_prcnt != '') {
                                var tds_amount = (advanceamt * (data1 / 100));
                                $('.tds_amount').val(tds_amount.toFixed(2));
                                sumwithtds = Number(advanceamt) - Number(tds_amount);
                                $('#payment_amount').val(sumwithtds.toFixed(2));
                            }
                        }
                        else {
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
                $('.tds_prcnt').val("");
                $('.tds_amount').val("");
                $('.tds_account_id').val("").change();
                $(".tds_div").hide();
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

        $('.bank_id').on('change', function () {
            var bank = $(this).val();
            var url = "{{ URL::to('jcomboform1') }}?table=f_bank_account_lines_t:bank_account_line_id:account_number"
                + "&parent=and bank_account_hdr_id=" + bank
                + "&order_by=account_number asc";

            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                    // Parse JSON string if needed
                    if (typeof data === "string") {
                        try {
                            data = JSON.parse(data);
                        } catch (e) {
                            console.error("Invalid JSON response:", data);
                            return;
                        }
                    }

                    // Reset dropdown
                    $('.account_no').html('<option value="">-- Select Account No --</option>');

                    // Populate options
                    $.each(data, function (i, item) {
                        let selected = item.val == "{{ $row->account_no ?? '' }}" ? 'selected' : '';
                        $('.account_no').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
                    });

                    // Trigger select2 refresh if used
                    $('.account_no').trigger('change.select2');
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", error);
                }
            });
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
                $('.cheque_no').attr('readonly', true);
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
        /*End*/
        /* Purpose for Default Cheque Number*/
        $(document).on('change', '.account_no', function () {
            var accid = $('.account_no').val();
            var cheque_no = $('.cheque_no').val();
            var pmttypeid = $('.payment_type_id option:selected').text();
            var pmttype = $.trim(pmttypeid);
            $('.mcontent2').html('');
            if (pmttype == "CHEQUE") {
                if (accid != "") {
                    var url = "{{URL::to('getpaymentchequeno')}}/" + accid;
                    $.get(url, function (data) {
                        var end = data['endcheque'];
                        if ($.trim(data['nocheque']) == "nocheque") {
                            showCustomAlert('Please Assign a Cheque for this Account', 'error');

                        }
                        else if (data['chequeno'] > end) {
                            showCustomAlert('No Cheque Found', 'error');
                        }
                        else {
                            $(".cheque_no").val(data['chequeno']);
                        }
                    });
                }

                else {
                    $(".cheque_no").val('');
                }
            }
        });
        /*End*/
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

</script>


@endpush