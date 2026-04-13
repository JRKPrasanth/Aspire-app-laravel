@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Advance Receipt</h3>
    @include('layouts.breadcrumb')
    <?php error_reporting(0); ?>

    <form method="post" action="" id="advance_form" data-parsley-validate>
        {{ csrf_field() }}

        <div class="card shadow-lg rounded-4 border-0">
            <div class="card-body card-block">
                <div class="row">
                    <div class="col-md-6">
                        <input class="form-control receipt_id" id="receipt_id" name="receipt_id" size="16" type="hidden"
                            value="{{ $row->receipt_id }}" readonly>
                        <input type="hidden" name="receipt_number" class="form-control receipt_number"
                            value="{{$row->receipt_number}}">
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-6"><span style="color:red">*</span>Sales
                                Order No</label>
                            <div class="col-md-6 sel2">
                                <select name='sales_hdr_id' rows='5' class='form-control sales_hdr_id select2' required>
                                    {!! $sales_hdr_id !!}
                                </select>
                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-6">Customer Name</label>
                            <div class="col-md-6">
                                <select name='customer_id' rows='5' class='form-control customer_id select2' readonly>
                                    {!! $customer_id !!}
                                </select>
                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-6">Customer Account Name</label>
                            <div class="col-md-6">
                                <input type="text" name="customer_account_name" id="customer_account_name"
                                    class="form-control customer_account_name" value="{{$row->customer_account_name}}"
                                    readonly>
                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-6">Customer Bank Name</label>
                            <div class="col-md-6">
                                <input type="text" name="bank_name" id="bank_name" class="form-control bank_name"
                                    value="{{$row->bank_name}}" readonly>

                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-6">Customer Account Number</label>
                            <div class="col-md-6">
                                <input type="text" name="account_number" id="account_number"
                                    class="form-control account_number" value="{{$row->account_number}}" readonly>

                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-6">Customer IFSC Code</label>
                            <div class="col-md-6">
                                <input type="text" name="ifsc_code" id="ifsc_code" class="form-control ifsc_code"
                                    value="{{$row->ifsc_code}}" readonly>

                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-6">SO Amount</label>
                            <div class="col-md-6">
                                <input type="text" id="so_amount" name="so_amount" class="form-control so_amount chckclick"
                                    value="{{ $row->so_amount }}" readonly>
                            </div>
                            <div class="col-md-2 showline">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-6">Invoice Currency</label>
                            <div class="col-md-6">
                                <input type="text" id="invoice_currency" name="invoice_currency"
                                    class="form-control invoice_currency chckclick" value="{{ $row->invoice_currency }}"
                                    readonly>
                                <input type="hidden" id="invoice_currency_id" name="invoice_currency_id"
                                    class="form-control invoice_currency_id chckclick"
                                    value="{{ $row->invoice_currency_id }}" readonly>
                            </div>
                            <div class="col-md-2 showline">

                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-6"><span
                                    style="color:red;">*</span>Advance Amount</label>
                            <div class="col-md-6">
                                <input type="text" id="receipt_amount" name="receipt_amount"
                                    class="form-control receipt_amount chckclick" value="{{ $row->receipt_amount }}"
                                    required>
                            </div>
                            <div class="col-md-2 showline">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-6">Receipt Date</label>
                            <div class="col-md-6">
                                <input type="text" name="receipt_date" id="receipt_date"
                                    class="form-control receipt_date " value="" required="true">

                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>

                        <div class="row mb-3" style="display:none">
                            <label for="inputIsValid" class="col-form-label col-md-6">Advance Source</label>
                            <div class="col-md-6">
                                <select type="text" name="receipt_source" id="receipt_source"
                                    class="form-control receipt_source select2" readonly>
                                    <option <?php if ($row->receipt_source == "ADVANCE")
        echo "selected"; ?> value="ADVANCE">
                                        ADVANCE</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-6"><span
                                    style="color:red">*</span>Receipt Type</label>
                            <div class="col-md-6 sel2">
                                <select name='receipt_type_id' rows='5' class='form-control receipt_type_id select2'
                                    data-show-subtext="true" data-live-search="true" required>
                                    <option value="">--Please Select--</option>
                                    <option <?php if ($row->receipt_type_id == "CHEQUE") {
        echo "selected";
    } else {
        echo "";
    } ?> value="CHEQUE">CHEQUE</option>
                                    <option <?php if ($row->receipt_type_id == "CASH") {
        echo "selected";
    } else {
        echo "";
    } ?> value="CASH">CASH</option>
                                    <option <?php if ($row->receipt_type_id == "NEFT") {
        echo "selected";
    } else {
        echo "";
    } ?> value="NEFT">NEFT</option>
                                    <option <?php if ($row->receipt_type_id == "MTPS") {
        echo "selected";
    } else {
        echo "";
    } ?> value="MTPS">MTPS</option>

                                </select>
                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>


                    </div>
                    <div class="col-md-6">

                        <div class="row mb-3 chequediv">
                            <label for="inputIsValid" class="col-form-label col-md-6">Cheque Date</label>
                            <div class="col-md-6">
                                <input type="text" name="cheque_date" id="cheque_date"
                                    class="form-control cheque_date" required="true">

                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
                        <div class="row mb-3" style="display:none">
                            <label for="inputIsValid" class="col-form-label col-md-6">Advance Status</label>
                            <div class="col-md-6">
                                <select type="text" name="receipt_status" id="receipt_status"
                                    class="form-control receipt_status select2">
                                    <option <?php if ($row->receipt_status == "INITIATED")
        echo "selected"; ?>
                                        value="INITIATED">INITIATED</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-6">Reference No</label>
                            <div class="col-md-6">
                                <input type="text" name="reference_no" id="reference_no" class="form-control reference_no"
                                    value="">

                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
                        <div class="row mb-3 chequediv">
                            <label for="inputIsValid" class="col-form-label col-md-6"><span class="bankdiv"
                                    style="color:red">*</span>Bank Name</label>
                            <div class="col-md-6 sel2">
                                <select name='bank_id' rows='5' id='bank_id' class='select2 bank_id ' required>
                                    {!! $bank_id  !!}
                                </select>
                            </div>

                        </div>
                        <div class="row mb-3 chequediv">
                            <label for="inputIsValid" class="col-form-label col-md-6">Account Number</label>
                            <div class="col-md-6 ">
                                <select name='account_no' rows='5' id='account_no' class='select2 account_no'>
                                    {!! $account_no !!}
                                </select>
                            </div>
                            <div class="col-md-2 showinline">
                            </div>
                        </div>
                        <div class="row mb-3 chequediv">
                            <label for="inputIsValid" class="col-form-label col-md-6">Cheque No</label>
                            <div class="col-md-6 ">
                                <input type="text" id="cheque_no" name="cheque_no" class="form-control cheque_no"
                                    value="{{ $row->cheque_no }}" />
                            </div>
                            <div class="col-md-2 showinline">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-6"><span
                                    style="color:red">*</span>Receipt Reference</label>
                            <div class="col-md-6">
                                <input type="text" id="receipt_reference" name="receipt_reference"
                                    class="form-control receipt_reference" value="{{ $row->receipt_reference }}" required />
                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-6"><span
                                    style="color:red;">*</span>Account Code</label>
                            <div class="col-md-6">
                                <select name='account_code_id' rows='5' class='select2 account_code_id select2' required>
                                    {!! $account_code_id !!}
                                </select>
                            </div>

                        </div>
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-6">Remarks</label>
                            <div class="col-md-6">
                                <input type="text" name="remarks" id="remarks" class="form-control remarks"
                                    value="{{ $row->remarks }}">

                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
                        <div class="row mb-3 exchanger">
                            <label for="inputIsValid" class="col-form-label col-md-6">Exchange Rate</label>
                            <div class="col-md-6">
                                <input type="hidden" id="exchangerate" name="exchangerate"
                                    class="form-control exchangerate " value="">
                                <input type="text" id="exchangerateshow" class="form-control exchangerateshow " value="">
                            </div>
                            <div class="col-md-2 showline">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputIsValid" class="col-form-label col-md-6">Exchange Amount</label>
                            <div class="col-md-6">
                                <input type="text" id="exchangeamount" name="exchangeamount"
                                    class="form-control exchangeamount " value="">
                            </div>
                            <div class="col-md-2 showline">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="active" class="col-form-label col-md-6">Created By</label>
                            <div class="col-md-6" style="pointer-events:none;">
                                <select name='created_by' rows='5' class='select2 created_by' id="created_by">
                                    {!! $created_by !!}
                                </select>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="row mb-3 mt-4">
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
    </form>



@endsection
@push('scripts')

    <script>

        $(document).ready(function () {
            $('select').css('pointer-events', 'none');

            /*Validation*/
            $(document).on('keypress', '.advance_amount', function (ev) {
                var regex = new RegExp("^[0-9.]+$");
                var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                if (regex.test(str)) {
                    return true;
                }
                ev.preventDefault();
                return false;
            });

            /*End*/

            $(document).on('keyup', '.exchangerate', function () {
                var receipt_amt1 = parseFloat($(".receipt_amount").val());
                var exchangerate = parseFloat($(".exchangerate").val());
                var receipt_amt = isNaN(parseFloat(receipt_amt1)) ? 0 : parseFloat(receipt_amt1);
                if (receipt_amt != '0') {
                    var examount = exchangerate * receipt_amt;
                    var examount1 = isNaN(parseFloat(examount)) ? 0 : parseFloat(examount);
                    var examount12 = parseFloat(examount1).toFixed(2);
                    $(".exchangeamount").val(examount12);
                } else {
                    showCustomAlert("Please Enter Advance Amount", "error");
                    $(".exchangeamount").val("");
                    $(".exchangerate").val("");
                }
            });

            $(document).on('keyup', '.exchangeamount', function () {

                var receipt_amt1 = parseFloat($(".receipt_amount").val());
                var exchangeamount = parseFloat($(".exchangeamount").val());
                var receipt_amt = isNaN(parseFloat(receipt_amt1)) ? 0 : parseFloat(receipt_amt1);
                if (receipt_amt != '0') {
                    var examount = exchangeamount / receipt_amt;
                    var examount1 = isNaN(parseFloat(examount)) ? 0 : parseFloat(examount);
                    var examount12 = parseFloat(examount1).toFixed(4);
                    var examountshow = parseFloat(examount1).toFixed(2);
                    $(".exchangerate").val(examount12);
                    $(".exchangerateshow").val(examountshow);
                } else {
                    showCustomAlert("Please Enter Advance Amount", "error");
                    $(".exchangeamount").val("");
                    $(".exchangerate").val("");
                }
            });


            $(document).on('keyup', '.advance_amount', function () {
                var advamt = parseInt($(this).val());
                var poamt = parseInt($('.so_amount').val());
                if (advamt > poamt) {
                    showCustomAlert("Advance Should be Less than PO Amount", "error");
                    $('.advance_amount').val('');
                }
            });


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
                if (pmttype == "CHEQUE" || pmttype == "NEFT") {
                    $('.chequediv').css('display', 'block');
                } else {
                    $('#bank_id').prop('required', false);
                    $(".chequediv").attr('required', false);
                    $('.bankdiv').css('display', 'none');
                    $('.chequediv').css('display', 'none');
                }
            });


            /* Purpose for Bank based Account Code load*/
            $(document).on('change', '.account_no', function () {
                var account_no = $('.account_no').val();
                var url = "{{URL::to('getaccountdetails')}}/" + account_no;
                if (account_no != '') {
                    $.get(url, function (data) {
                        $('.account_code_id').val(data[0].account_code_id).change();

                    });
                }
            });
    /*End*/


    /* $('.receipt_type_id').on('change',function(){
        var pmttypeid=$('.receipt_type_id option:selected').text();
        var pmttype=$.trim(pmttypeid);
        if(pmttype!="CASH"){
             $('.account_code_id').val('').change();  
             $('.bank_id').val('').change();  
             $('.account_no').val('').change();  
            $('.chequediv').css('display','block');
        }else{
               var url="{{URL::to('getcashaccount')}}";
            $.get(url, function (data) {
                $('.account_code_id').val(data[0].cash_account_id).change();
            });
            $('#bank_id').prop('required', false);
            $(".cheque_date").attr('required', false);
            $('.chequediv,.bankdiv').css('display', 'none');
        } 
        if (pmttype == "CHEQUE") {

            $('.chequelabel').html('Cheque No');
            $('.cheque_no').attr('', true);
            $(".cheque_date").attr('required', true);
        } else {

            $('.chequelabel').html('Reference No');
            $('.cheque_no').attr('', false);
            $(".cheque_date").attr('required', false);
        }

        if (pmttype == "CHEQUE" || pmttype == "CASH") {
            $(".supplier_bank_id").attr('required', false);
            var form = $('#advance_form');
            form.parsley().destroy();

        }
        else {
            $(".cheque_date").attr('required', false);
            $(".supplier_bank_id").attr('required', true);
            var form = $('#advance_form');
            form.parsley().destroy();

        }	

    }); 
*/


        /* Purpose for So based Customer and amount load*/
        $(document).on('change', '.sales_hdr_id', function () {
            var soid = $('.sales_hdr_id').val();

            var url = "{{URL::to('getsodetails')}}/" + soid;
            $.get(url, function (data) {
                $('.customer_id').val(data[0].ship_to_customer_id).change();
                $('.so_amount').val(data[0].order_total);
            });
        });


        /* Purpose For Save Function*/

        $(document).on('click', '.saveform', function () {
            var btnval = $(this).val();
            if (btnval == 'DRAFT') {
                $("#advance_status").val('DRAFT');
            } else {
                $("#advance_status").val('INITIATED');
            }
            $('#savestatus').val(btnval);

            var url = "{{ url('advancereceiptsave') }}";
            var red_url = "{{url('advancereceipt')}}"

            var formdata = $('#advance_form').serialize();

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
                    var edit_url = "{{ url('advancereceiptcreate') }}/" + id;
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


    const dateFormat = "{{ \Session('j_date_format') ?? 'yy-mm-dd' }}";

    $(document).on("focus", ".receipt_date", function () {
        $(this).datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: dateFormat,
          minDate: new Date(2025, 12, 1),
          maxDate: 0,
          showAnim: "slideDown",
          yearRange: "-25:+0",

        });
      });

    $(document).on("focus", ".cheque_date", function () {
        $(this).datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: dateFormat,
          minDate: new Date(2025, 12, 1),
          maxDate: 0,
          showAnim: "slideDown",
          yearRange: "-25:+0",

        });
      });

    </script>

@endpush