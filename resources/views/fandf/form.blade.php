@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Full And Final Settlement</h3>
    @include('layouts.breadcrumb')

    <form method="POST" id="fandf" action="{{ url()->current() }}">
        @csrf
        <div class="card shadow-lg rounded-4 border-0">
            <div class="card-body">
                <input type="hidden" name="status" id="status" value="{{ $row->status }}">
                <input type="hidden" name="hr_ff_id" value="{{ $row->hr_ff_id }}">

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Employee Name</label>
                        <select id="emp_id" name="emp_id" class="form-select select2" data-live-search="true" tabindex="1">
                            {!! $row->emp_id !!}
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Imprest Amount</label>
                        <input type="text" name="imp_amount" class="form-control" value="{{ $row->imp_amount }}" readonly>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Expense Amount</label>
                        <input type="text" name="exp_amount" class="form-control" value="{{ $row->exp_amount }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Salary Amount</label>
                        <input type="text" name="sal_amount" class="form-control" value="{{ $row->sal_amount }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Balance To be paid</label>
                        <input type="text" name="balance_amount" class="form-control" value="{{ $row->balance_amount }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Addition</label>
                        <input type="text" name="addition" class="form-control addition" value="{{ $row->addition }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Deduction</label>
                        <input type="text" name="deduction" class="form-control deduction" value="{{ $row->deduction }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Actual To Pay</label>
                        <input type="text" name="paid_amount" class="form-control paid_amount"
                            value="{{ $row->paid_amount }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" class="form-control" required>{{ $row->remarks }}</textarea>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Salary Advance (if any)</label>
                        <input type="text" name="salary_advance" class="form-control" value="{{ $row->salary_advance }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Imprest Cash (if any)</label>
                        <input type="text" name="imprest_cash" class="form-control" value="{{ $row->imprest_cash }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Damages (if any)</label>
                        <input type="text" name="damages" class="form-control" value="{{ $row->damages }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Unpaid Loans</label>
                        <input type="text" name="unpaid_loans" class="form-control" value="{{ $row->unpaid_loans }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Training Expenses</label>
                        <input type="text" name="training_expenses" class="form-control"
                            value="{{ $row->training_expenses }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Others (Staff welfare)</label>
                        <input type="text" name="others_staff_welfare" class="form-control"
                            value="{{ $row->others_staff_welfare }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Notice Period Pay</label>
                        <input type="text" name="notice_period_pay" class="form-control"
                            value="{{ $row->notice_period_pay }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Non receipt of Clearance form</label>
                        <input type="text" name="non_receipt_of_clearnce_form" class="form-control"
                            value="{{ $row->non_receipt_of_clearnce_form }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Non receipt of NOC</label>
                        <input type="text" name="non_receipt_of_noc" class="form-control"
                            value="{{ $row->non_receipt_of_noc }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Paid amount if any</label>
                        <input type="text" name="paid_amount_if_any" class="form-control"
                            value="{{ $row->paid_amount_if_any }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Leave Profile</label>
                        <input type="text" name="leave_profile" class="form-control" value="{{ $row->leave_profile }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">EL</label>
                        <input type="text" name="el" class="form-control" value="{{ $row->el }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Others (if any)</label>
                        <input type="text" name="others" class="form-control" value="{{ $row->others }}">
                    </div>
                </div>

                <div class="text-center mt-4">
                    @if($row->hr_ff_id == '')
                        <button type="button" name="submit" class="btn btn-success px-4 saveform"
                            value="INITIATED">Submit</button>
                        <a href="{{ url('emplyegen') }}" class="btn btn-secondary ms-2">Cancel</a>
                    @else
                        <button type="button" name="submit" class="btn btn-success px-4 saveform"
                            value="APPROVED">Approve</button>
                        <a href="{{ url('fandf') }}" class="btn btn-secondary ms-2">Cancel</a>
                    @endif
                </div>
            </div>
        </div>
    </form>




@endsection
@push('scripts')


    <script>
        /*Purpose For Required Validation*/

        $(".select2").change(function () {
            $(this).parsley().validate();
        });


        function example() {
            $("#file_choosen").css({
                "border-color": "rgb(20, 46, 120)",
                "border-width": "1px",
                "border-style": "solid"
            });
        }


        $(document).ready(function () {

            /* Purpose For Add New Row*/

            $(document).on("click", ".saveform", function () {
                var btnval = $(this).val();
                if (btnval == 'APPROVED') {
                    var savestatus = 'APPROVED';
                    $('#status').val(savestatus);
                } else {
                    var savestatus = 'INITIATED';
                    $('#status').val(savestatus);
                }

                var emp_id = $('.emp_id').val();
                if (emp_id != '') {
                    var red_url = "{{ URL::to('fandf') }}";
                } else {
                    var red_url = "{{ URL::to('emplyegen') }}";
                }
                var url = "{{ URL::to('fandfsave') }}";
                var red_url1 = "{{ URL::to('emplyegen') }}";

                var form = $("#fandf");
                form.parsley().validate();

                if (form.parsley().isValid()) {
                    var $btn = $(this);
                    $btn.prop('disabled', true);
                    var formdata = $("#fandf").serialize();
                    var form_data = new FormData(document.getElementById("fandf"));
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
                        var auto_no = data.auto_no;
                        if (btnval != "SAVE" && btnval != "APPROVED" && btnval != "Rejected" && btnval != "Canceled") {
                            showCustomAlert(msg, status);

                            window.location.href = red_url1;
                        }
                        else {
                            showCustomAlert(msg,status);
                            window.location.href=red_url;

                        }
                    });
                }


            });

            $(".addition,.deduction").keyup(function () {

                var bal = isNaN($(".balance_amount").val()) ? 0 : parseFloat($(".balance_amount").val());
                var addition = isNaN($(".addition").val()) ? 0 : parseFloat($(".addition").val());
                var deduction = isNaN($(".deduction").val()) ? 0 : parseFloat($(".deduction").val());

                $(".paid_amount").val(bal + addition - deduction);

            });


        });

    </script>



@endpush