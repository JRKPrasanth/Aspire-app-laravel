@extends('layouts.header')
@section('content')
    <h3 class="text-danger"> Journal Adjustments </h3>
    @include('layouts.breadcrumb')


    <div class="container-fluid py-4">
        <form method="post" action="" id="adjustment_form" data-parsley-validate>
            @csrf

            <div class="card shadow-lg rounded-3 border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                </div>

                <div class="card-body">
                    <input type="hidden" class="adjustment_id" name="adjustment_id" value="{{ $row->adjustment_id }}">

                    <div class="row g-4">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><span class="text-danger">*</span> Adjustments Date</label>
                                <input type="text" class="form-control datepicker adjustment_date" name="adjustment_date"
                                    value="{{ $row->adjustment_date }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><span class="text-danger">*</span> Account Type</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input account_type" type="radio" name="account_type"
                                            id="debit" value="Debit" {{ $row->account_type == 'Debit' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="debit">Debit</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input account_type" type="radio" name="account_type"
                                            id="credit" value="Credit" {{ $row->account_type == 'Credit' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="credit">Credit</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><span class="text-danger">*</span> Adjustment Amount</label>
                                <input type="text" class="form-control adjustment_amount" name="adjustment_amount"
                                    value="{{ $row->adjustment_amount }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Adjustment Status</label>
                                <select class="form-select adjustment_status" name="adjustment_status" readonly>
                                    <option value="">-- Please Select --</option>
                                    <option value="DRAFT" {{ $row->adjustment_status == 'DRAFT' ? 'selected' : '' }}>DRAFT
                                    </option>
                                    <option value="INITIATED" {{ $row->adjustment_status == 'INITIATED' ? 'selected' : '' }}>
                                        INITIATED</option>
                                    <option value="APPROVED" {{ $row->adjustment_status == 'APPROVED' ? 'selected' : '' }}>
                                        APPROVED</option>
                                    <option value="REJECTED" {{ $row->adjustment_status == 'REJECTED' ? 'selected' : '' }}>
                                        REJECTED</option>
                                </select>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><span class="text-danger">*</span> Account Code</label>
                                <select name="account_code_id" id="account_code_id"
                                    class="form-select account_code_id select2" required>
                                    {!! $account_code_id !!}
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <input type="text" class="form-control description" name="description"
                                    value="{{ $row->description }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Reason Code</label>
                                <input type="text" class="form-control reason_code" name="reason_code"
                                    value="{{ $row->reason_code }}">
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="text-center mt-4">
                        <input type="hidden" name="submit_type" class="submit_type" value="" />
                        <button type="button" class="btn btn-success px-4 saveform me-2" value="SAVE">Save</button>
                        <a class="btn btn-secondary px-4" href="{{ url($pageModule) }}">Cancel</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
@push('scripts')

    <script>

        $(document).ready(function () {

            /* Purpose For Save Function*/

            $(document).on('click', '.saveform', function () {
                var btnval = $(this).val();
                if (btnval == 'APPROVED') {
                    $("#adjustment_status").val('APPROVED');
                }
                else {
                    $("#adjustment_status").val('INITIATED');
                }
                $('#savestatus').val(btnval);

                var url = "{{ url('journaladjustmentssave') }}";
                var red_url = "{{url('journaladjustments')}}"

                var formdata = $('#adjustment_form').serialize();
                validationrule('adjustment_form');
                var form = $('#adjustment_form');
                form.parsley().validate();
                if (form.parsley().isValid()) {

                    var $btn = $(this);
                    $btn.prop('disabled', true);
                    var formdata = $('#adjustment_form').serialize();

                    $.post(url, formdata, function (data) {
                        var status = data.status;
                        var msg = data.message;
                        var id = data.id;
                        var edit_url = "{{ url('journaladjustmentscreate') }}/" + id;
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

            $('.adjustment_status').css("pointer-events", "none");

        });

    </script>

@endpush