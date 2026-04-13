@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Payment Edit</h3>
    @include('layouts.breadcrumb')

    <form action="" id="payment_form" class="payment_form needs-validation" enctype="multipart/form-data"
        method="POST">
        {{ csrf_field() }}

        <div class="card shadow-sm rounded-4 border-0">
            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-4">
                        <label for="ann_name" class="form-label">Payment Number</label>
                        <input type="hidden" name="payment_id" value="{{ $row->payment_id }}">
                        <input type="text" class="form-control payment_number" id="payment_number" name="payment_number"
                            placeholder="" value="{{ $row->payment_number }}" readonly>
                    </div>

                   <div class="col-md-4 none">
                        <label for="name" class="form-label">Recipient Name</label>
                        <select class="form-select name select2" id="name" name="name" readonly>
                        {!! $payee !!} 
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="payment_date" class="form-label">Payment Date</label>
                        <input class="form-control ann_date" id="payment_date" name="payment_date" value="{{ $row->payment_date }}">
                    </div>

                   <div class="col-md-4">
                        <label for="payment_type" class="form-label">Payment Type</label>
                          <select name='payment_type_id' rows='5' class='form-control payment_type_id select2' data-show-subtext="true" data-live-search="true" required>
                              <option value="">--Please Select--</option>
                              <option <?php if($row->payment_type_id =="CHEQUE") { echo "selected"; } else { echo ""; } ?> value="CHEQUE">CHEQUE</option>
                              <option <?php if($row->payment_type_id =="CASH") { echo "selected"; } else { echo ""; } ?> value="CASH">CASH</option>
                              <option <?php if($row->payment_type_id =="NEFT") { echo "selected"; } else { echo ""; } ?> value="NEFT">NEFT</option>
                              <option <?php if($row->payment_type_id =="MTPS") { echo "selected"; } else { echo ""; } ?> value="MTPS">MTPS</option>
                              <option <?php if($row->payment_type_id =="IMPS") { echo "selected"; } else { echo ""; } ?> value="IMPS">IMPS</option>
                              <option <?php if($row->payment_type_id =="RTGS") { echo "selected"; } else { echo ""; } ?> value="RTGS">RTGS</option>
			      <option <?php if($row->payment_type_id =="ONLINE") { echo "selected"; } else { echo ""; } ?> value="ONLINE">ONLINE</option>
				  <option <?php if($row->payment_type_id =="IMPREST") { echo "selected"; } else { echo ""; } ?> value="IMPREST">IMPREST</option>
                              </select>
                    </div>

                    <div class="col-md-4">
                        <label for="cheque_no" class="form-label">Cheque Number</label>
                        <input class="form-control cheque_no" id="cheque_no" name="cheque_no" value="{{ $row->cheque_no }}">
                    </div>

                     <div class="col-md-4">
                        <label for="payment_source" class="form-label">Payment Source</label>
                             <select type="text" name="payment_source" id="payment_source" class="form-control select2 payment_source">
                             <option value="">-- Please Select --</option>
                             <option <?php if($row->payment_source=="EXPENSE" ) echo "selected"; ?> value="EXPENSE">EXPENSE</option>
                              <option <?php if($row->payment_source=="INVOICE" ) echo "selected"; ?> value="INVOICE">INVOICE</option>
                             <option <?php if($row->payment_source=="IMPREST" ) echo "selected"; ?> value="IMPREST">IMPREST</option>
                             <option <?php if($row->payment_source=="PROMOTIONAL" ) echo "selected"; ?> value="PROMOTIONAL">PROMOTIONAL</option>
                             <option <?php if($row->payment_source=="ADVANCE" ) echo "selected"; ?> value="ADVANCE">ADVANCE</option>
                             </select>
                    </div>

                    <div class="col-md-4">
                        <label for="payment_amount" class="form-label">Payment Amount</label>
                        <input class="form-control payment_amount" id="payment_amount" name="payment_amount" value="{{ $row->payment_amount }}">
                    </div>


                      <div class="col-md-4">
                        <label for="bank_id" class="form-label">Bank Name</label>
                        <select class="form-select bank_id select2" id="bank_id" name="bank_id" readonly>
                        {!! $bank_id !!} 
                        </select>
                    </div>

                      <div class="col-md-4">
                        <label for="account_no" class="form-label">Account Code</label>
                        <select class="form-select account_no select2" id="account_no" name="account_no" readonly>
                         {!! $account_no !!} 
                        </select>
                    </div>

                    <div class="col-12 text-center mt-4">
                        <button type="button" class="btn btn-success px-4 me-2 saveform">Update</button>
                        <a class="btn btn-secondary px-4" href="{{ url($pageMethod) }}">Cancel</a>
                    </div>
                </div>
            </div>
        </div>

    </form>



@endsection
@push('scripts')


    <script>

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
                // Parse JSON if returned as string
                if (typeof data === "string") {
                    try {
                        data = JSON.parse(data);
                    } catch (e) {
                        console.error("Invalid JSON response:", data);
                        return;
                    }
                }

                var $account = $(".account_no");
                $account.empty().append('<option value="">-- Select Account --</option>');

                $.each(data, function (i, item) {
                    $account.append(`<option value="${item.val}">${item.option_name}</option>`);
                });

                $account.trigger('change.select2'); // refresh Select2 if used
            },
            error: function () {
                console.error("Failed to load account numbers");
            }
        });
        } else {
            $(".account_no").empty().append('<option value="">-- Select Account --</option>');
        }
    });

        //  form save

        $(document).ready(function () {
            $(document).on('click', '.saveform', function (e) {
                e.preventDefault();

                var btnval = $(this).val();
                $('#savestatus').val(btnval);

                var form = $('#payment_form');
                form.parsley().validate();

                if (form.parsley().isValid()) {
                    var saveurl = "{{ url('updatepayment') }}";
                    var red_url = "{{ url('paymentdetailindex') }}";

                    // Use FormData for file upload
                    var formData = new FormData(form[0]);
                    var $btn = $(this);
                    $btn.prop('disabled', true);
                    $.ajax({
                        url: saveurl,
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            var status = data.status;
                            var msg = data.message;
                            showCustomAlert(msg, status);

                            if (status === 'success') {
                                setTimeout(function () {
                                    window.location.href = red_url;
                                }, 1500);
                            }
                        },
                        error: function (xhr) {
                            showCustomAlert("Upload failed. Try again.", "error");
                        }
                    });
                }
            });
        });



        $(document).ready(function () {

            var dateToday = new Date();

            // Calculate the date 3 months from today
            var maxDate = new Date();
            var minDate = new Date();
            maxDate.setMonth(maxDate.getMonth() + 3);
            minDate.setMonth(minDate.getMonth() - 3);

            $(".ann_date").datepicker({
                changeMonth: true,
                dateFormat: "yy-mm-dd",
                changeYear: true,
                minDate: minDate,
                maxDate: maxDate,
                onClose: function () {
                    $(this).parsley().validate();
                }
            }).attr('readonly', 'readonly');

        });



    </script>
@endpush