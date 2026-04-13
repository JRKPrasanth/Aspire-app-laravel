@extends('layouts.header')
@section('content')
<h3 class="text-danger">Travel Claim Request</h3>
@include('layouts.breadcrumb')



<div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
        <form id="advance" action="" data-parsley-validate>
            <input type="hidden" name="edit_id" value="{{$edit_id}}" id="edit_id" />
            {{ csrf_field() }}

            <div class="row g-3">
                <div class="col-md-6">

                    <!-- Employee -->
                    <div class="mb-3" style="pointer-events: none;">
                        <label class="form-label">Employee <span class="text-danger">*</span></label>
                        <select name="employee_id" id="employee_id" class="form-select select2 employee_id">
                            {!! $employee_id !!}
                        </select>
                    </div>

                    <!-- Claim Title -->
                    <div class="mb-3">
                        <label class="form-label">Claim Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control claim_title" id="claim_title" name="claim_title"
                            value="{{$claim_title}}" required>
                    </div>

                    <!-- Travel Date -->
                    <div class="mb-3">
                        <label class="form-label">Travel Date <span class="text-danger">*</span></label>
                        <input type="text" class="form-control start_date" id="travel_date" name="travel_date"
                            value="{{$travel_date}}" required>
                    </div>

                    <!-- Travel Purpose -->
                    <div class="mb-3">
                        <label class="form-label">Travel Purpose <span class="text-danger">*</span></label>
                        <input type="text" class="form-control travel_purpose" id="travel_purpose" name="travel_purpose"
                            value="{{$travel_purpose}}" required>
                    </div>

                    <!-- Travel Mode -->
                    <div class="mb-3">
                        <label class="form-label">Travel Mode <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select name="travel_mode" id="travel_mode" class="form-select select2 travel_mode"
                                required>
                                {!! $travel_mode !!}
                            </select>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label class="form-label">Description <span class="text-danger">*</span></label>
                        <input type="text" class="form-control description" name="description" id="description"
                            value="{{$description}}" required>
                    </div>

                    <!-- Bill Copy -->
                    <div class="mb-3">
                        <label class="form-label">Bill Copy <span class="text-danger">*</span></label>
                        <input type="file" class="form-control bill_copy" name="bill_copy" id="bill_copy" required>
                    </div>

                </div>

                <div class="col-md-6">

                    <!-- From Place -->
                    <div class="mb-3">
                        <label class="form-label">From Place <span class="text-danger">*</span></label>
                        <input type="text" class="form-control from_place" name="from_place" id="from_place"
                            value="{{$from_place}}" required>
                    </div>

                    <!-- To Place -->
                    <div class="mb-3">
                        <label class="form-label">To Place <span class="text-danger">*</span></label>
                        <input type="text" class="form-control to_place" name="to_place" id="to_place"
                            value="{{$to_place}}" required>
                    </div>

                    <!-- Distance -->
                    <div class="mb-3">
                        <label class="form-label">Distance <span class="text-danger">*</span></label>
                        <input type="text" class="form-control distance" name="distance" id="distance"
                            value="{{$distance}}" required>
                    </div>

                    <!-- Forwarded To -->
                    <div class="mb-3">
                        <label class="form-label">Forwarded To <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select name="forwarded_id" id="forwarded_id" class="form-select select2 forwarded_id"
                                required>
                                {!! $forwarded_id !!}
                            </select>
                        </div>
                    </div>

                    <!-- Date From & To -->
                    <div class="mb-3">
                        <label class="form-label">Date Range</label>
                        <div class="input-group">
                            <input type="text" class="form-control start_date_time" name="date1" id="date1"
                                value="{{$date1}}">
                            <input type="text" class="form-control end_date_time" name="date2" id="date2"
                                value="{{$date2}}">
                        </div>
                    </div>

                    <!-- Reason & Amount Repeatable Fields -->
                    <div class="mb-3">
                        <label class="form-label">Bill Amount <span class="text-danger">*</span></label>

                        <div class="field_wrapper">

                            @php
                            $reason = isset($reason) && !empty($reason) ? json_decode($reason) : [];
                            @endphp

                            @forelse ($reason as $key => $value)
                            <div class="row g-2 align-items-center mb-2 field_row">
                                <div class="col-md-5">
                                    <input type="text" name="reason[]" class="form-control" placeholder="Reason"
                                        value="{{ $value[0] }}" required>
                                </div>

                                <div class="col-md-5">
                                    <input type="text" name="amount[]" class="form-control amount sum"
                                        placeholder="Amount" value="{{ $value[1] }}" required>
                                </div>

                                <div class="col-md-2 d-flex">
                                    @if($key == 0)
                                    <button type="button" class="btn btn-success add_button btn-sm">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    @else
                                    <button type="button" class="btn btn-danger remove_button btn-sm">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <div class="row g-2 align-items-center mb-2 field_row">
                                <div class="col-md-5">
                                    <input type="text" name="reason[]" class="form-control" placeholder="Reason"
                                        required>
                                </div>

                                <div class="col-md-5">
                                    <input type="text" name="amount[]" class="form-control amount sum"
                                        placeholder="Amount" required>
                                </div>

                                <div class="col-md-2">
                                    <button type="button" class="btn btn-success add_button btn-sm">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            @endforelse

                        </div>
                    </div>

                    <!-- Total Bill Amount -->
                    <div class="mb-3 none">
                        <label class="form-label">Total Bill Amount <span class="text-danger">*</span></label>
                        <input type="text" name="bill_amount" id="bill_amount" class="form-control bill_amount"
                            value="{{$bill_amount}}" required>
                    </div>

                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center mt-4">
                <button type="button" class="btn btn-success px-4 me-2 save_form">Save</button>
                <a href="{{url('travelclaimrequest')}}"> <button type="button" class="btn btn-secondary px-4 me-2"
                        id="delete">Cancel</button></a>
            </div>
        </form>
    </div>
</div>




@endsection
@push('scripts')


<script>

    $(document).ready(function () {

        var logged_user = '{{$logged_user}}';
        var condition = "  employee_id!=" + logged_user;



        /******** Bill Amount calculation Start  ***********/
        $(document).on('change', '.date1', function (ev) {
            var emp_id = $('.employee_id').select2('val');
            var today = ($(this).val()).split(" ");
            var today_time = today[1].split(":");

            if (today_time[0] >= 14) {
                var sum = (isNaN(parseFloat($('.bill_amount').val()))) ? 0 : parseFloat($('.bill_amount').val());
                var total = sum + 100;
                $('#bill_amount').val(total);

                var url = "{{URL::to('travelamountget')}}/" + emp_id;
                $.get(url, function (data) {
                    if (data != 0) {
                        if (total > data) {
                            $('#bill_amount').val('');
                            showCustomAlert('Travel Amount for these Employee Group is exceeds', 'error');
                        }
                    } else {
                        $('#bill_amount').val('');
                        showCustomAlert('Travel Amount not assign to these Employee Group', 'error');
                    }
                });
                $("#bill_amount").parsley().destroy();
            }

        });

        $(document).on('change', '.date2', function (ev) {
            var emp_id = $('.employee_id').select2('val');
            var today = ($(this).val()).split(" ");
            var today_time = today[1].split(":");
            if (today_time[0] >= 20) {
                var sum = (isNaN(parseFloat($('.bill_amount').val()))) ? 0 : parseFloat($('.bill_amount').val());
                var total = sum + 50;
                $('#bill_amount').val(total);
                var url = "{{URL::to('travelamountget')}}/" + emp_id;
                $.get(url, function (data) {
                    if (data != 0) {
                        if (total > data) {
                            $('#bill_amount').val('');
                            showCustomAlert('Travel Amount for these Employee Group is exceeds', 'error');
                        }
                    } else {
                        $('#bill_amount').val('');
                        showCustomAlert('Travel Amount not assign to these Employee Group', 'error');
                    }
                });

                $("#bill_amount").parsley().destroy();
            }

        });





        $(document).on('change', '.travel_date', function (ev) {

            $('.date1,.date2').datetimepicker({
                format: 'dd-mm-yyyy hh:ii:ss', autoClose: true, startDate: '', endDate: '',
            }).prop('readonly', true);
        });



        $('.date1').datetimepicker({ format: 'dd-mm-yyyy hh:ii:ss', autoClose: true, }).on('changeDate', function (ev) {
            $(this).datetimepicker('hide');
        }).prop('readonly', true);



        $('.date2').datetimepicker({ format: 'dd-mm-yyyy hh:ii:ss', autoClose: true, pickerPosition: 'bottom-left' }).on('changeDate', function (ev) {
            $(this).datetimepicker('hide');
        }).prop('readonly', true);




        /******* Numbers Only calculation Start  ***********/

        $(document).on('keypress', '#amount,.distance', function (ev) {

            var regex = new RegExp("^[0-9.]+$");
            var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
            $("#bill_amount").parsley().destroy();
            if (regex.test(str)) {
                return true;
            }
            ev.preventDefault();

            return false;
        });



        /******* BillAmount calculation Start  ***********/

        $(document).on('keyup', '.sum', function (ev) {

            var emp_id = $('.employee_id').select2('val');
            var sum = 0;
            var value = $('.sum').val();
            $('.sum').each(function () {

                sum += (isNaN(parseInt($(this).val()))) ? 0 : parseInt($(this).val())
            });
            if ($('.date2').val() != '') {
                var today = ($('.date2').val()).split(" ");
                var today_time = today[1].split(":");
                if (today_time[0] >= 20) {
                    sum = sum + 50;
                }
            }
            if ($('.date1').val() != '') {
                var today1 = ($('.date1').val()).split(" ");
                var today_time1 = today1[1].split(":");
                if (today_time1[0] >= 14) {
                    sum = sum + 100;
                }
            }
            $('#bill_amount').val(sum);
            var url = "{{URL::to('travelamountget')}}/" + emp_id;
            $.get(url, function (data) {
                if (data != 0) {
                    if (sum > data) {
                        $('#bill_amount').val('');
                        showCustomAlert('Travel Amount for these Employee Group is exceeds', 'warning');
                    }
                } else {
                    $('#bill_amount').val('');
                    showCustomAlert('Travel Amount not assign to these Employee Group', 'warning');
                }
            });

        });


        /******* Numbers Only calculation Start  ***********/

        $(document).on('keypress', '.emi', function (ev) {
            var regex = new RegExp("^[0-9]+$");
            var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
            if (regex.test(str)) {
                return true;
            }
            ev.preventDefault();
            return false;
        });
        /******* Numbers Only calculation End  ***********/

        // validate alphabets only 
        $(document).on('keypress', '#claim_title,#from_place,#to_place,#travel_purpose', function (ev) {
            var regex = new RegExp("^[a-z,'',A-Z.,' ']+$");
            var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
            if (regex.test(str)) {
                return true;
            }
            ev.preventDefault();
            return false;
        });




        $(document).ready(function () {

            var maxField = 10;
            var wrapper = $('.field_wrapper');

            // Add button click
            $(document).on('click', '.add_button', function () {

                var totalRows = wrapper.find('.field_row').length;

                if (totalRows < maxField) {

                    var html = `
                <div class="row g-2 align-items-center mb-2 field_row">
                    <div class="col-md-5">
                        <input type="text" name="reason[]" class="form-control" placeholder="Reason" required>
                    </div>

                    <div class="col-md-5">
                        <input type="text" name="amount[]" class="form-control amount sum" placeholder="Amount" required>
                    </div>

                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger remove_button btn-sm">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
            `;

                    wrapper.append(html);
                }
            });

            // Remove Row
            $(document).on('click', '.remove_button', function () {

                $(this).closest('.field_row').remove();
                calculateBillAmount();  // Recalculate automatically
            });


            $(document).on('keyup change', '.amount', function () {
                calculateBillAmount();
            });


            function calculateBillAmount() {
                let total = 0;

                $('.amount').each(function () {
                    total += parseFloat($(this).val()) || 0;
                });

                $('#bill_amount').val(total);

                checkEligibility(total);   // Check if employee is eligible
            }

            function checkEligibility(sum) {

                var emp_id = $('.employee_id').val();

                if (!emp_id) return; // Skip if no employee selected

                $.get("{{ URL::to('travelamountget') }}/" + emp_id, function (limit) {

                    if (limit == 0) {
                        $('#bill_amount').val('');
                        showCustomAlert('Travel Amount not assigned to this employee group', 'error');
                        return;
                    }

                    if (sum > limit) {
                        $('#bill_amount').val('');
                        showCustomAlert('Travel Amount exceeds limit for this employee group', 'error');
                    }

                });

            }

        });



        /*******  Save Button Start ***********/

        $(document).on('click', '.save_form', function () {
            var url = "{{URL::to('travelclaimsave')}}";

            var form_data = new FormData(document.getElementById('advance'));
            var form = $('#advance');
            form.parsley().validate();

            if (form.parsley().isValid()) {

                var $btn = $(this);
                $btn.prop('disabled', true);
                $.ajax({
                    url: "{{ url('travelclaimsave')}}",
                    type: "POST",
                    data: form_data,
                    enctype: 'multipart/form-data',
                    processData: false,  // tell jQuery not to process the data
                    contentType: false,   // tell jQuery not to set contentType
                    async: true,
                    xhr: function () {
                        var xhr = $.ajaxSettings.xhr();
                        if (xhr.upload) {
                            xhr.upload.addEventListener('progress', function (event) {
                            }, true);
                        }
                        return xhr;
                    }
                }).done(function (data, status) {

                    if (data == 1) {
                        showCustomAlert('Travel Claim Saved Successfully', 'success');

                        var url = "{{URL::to('travelclaimrequest')}}";
                        setTimeout(function () {
                            window.location.href = url;
                        }, 1000);
                    }
                    if (data == 2) {
                        showCustomAlert('Travel Claim details Updated Successfully', 'success');

                        var url = "{{URL::to('travelclaimrequest')}}";
                        setTimeout(function () {
                            window.location.href = url;
                        }, 1000);
                    }

                }).fail(function (data, status) {

                    $(".alert-success").hide();
                    $(".alert-danger").fadeIn(800);

                });
            }
        }); /*******  Save Button End ***********/


    });

</script>

@endpush