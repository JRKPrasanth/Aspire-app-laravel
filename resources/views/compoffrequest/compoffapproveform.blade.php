@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Comp-Off Approval</h3>
    @include('layouts.breadcrumb')

    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-primary text-white rounded-top-4">
        </div>

        <div class="card-body p-4">
            <form action="" id="leave" data-parsley-validate>
                <input type="hidden" name="edit_id" value="{{$edit_id}}" id="edit_id" />
                {{ csrf_field() }}

                <div class="row g-4">
                    <!-- Column 1 -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="employee_id" class="form-label fw-semibold">
                                <span class="text-danger">*</span> Employee
                            </label>
                            <select name="employee_id" id="employee_id" class="form-select select2"></select>
                        </div>

                        <div class="mb-3" style="pointer-events:none;">
                            <label for="leave_type" class="form-label fw-semibold">
                                <span class="text-danger">*</span> Type
                            </label>
                            <select name="leave_type" id="leave_type" class="form-select select2"></select>
                        </div>

                        <div class="mb-3 leave_mode_hide" style="pointer-events:none;">
                            <label for="leave_mode" class="form-label fw-semibold">
                                <span class="text-danger">*</span> CompOff Mode
                            </label>
                            <select name="leave_mode" id="leave_mode" class="form-select select2"></select>
                        </div>

                        <div class="mb-3 leave_mode_hide" style="pointer-events:none;">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> Start Date
                            </label>
                            <div class="input-group">
                                <input type="text" name="start_date" class="form-control datepicker start_date"
                                    value="{{$start_date}}">
                                <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                            </div>
                        </div>

                        <div class="mb-3 leave_mode_show" style="pointer-events:none;">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> Start Date Time
                            </label>
                            <div class="input-group">
                                <input type="text" name="start_date_time" id="start_date_time"
                                    class="form-control start_date_time" value="{{$start_date_time}}">
                                <span class="input-group-text"><i class="bi bi-clock"></i></span>
                            </div>
                        </div>
                    </div>

                    <!-- Column 2 -->
                    <div class="col-md-4">
                        <div class="mb-3 leave_mode_hide">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> No of Days
                            </label>
                            <input type="text" id="no_of_days" name="no_of_days" class="form-control"
                                value="{{$no_of_days}}" readonly>
                        </div>

                        <div class="mb-3 leave_mode_show">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> No of Hrs
                            </label>
                            <input type="text" id="no_of_hrs" name="no_of_hrs" class="form-control" value="{{$no_of_hrs}}"
                                readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> CompOff Reason
                            </label>
                            <input type="text" id="reason" name="reason" class="form-control" value="{{$leave_reason}}"
                                readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> Forwarded To
                            </label>
                            <select id="forwarded_id" name="forwarded_id" class="form-select select2 forwarded_id"
                                required></select>
                        </div>

                        <div class="mb-3 leave_mode_hide" style="pointer-events:none;">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> End Date
                            </label>
                            <div class="input-group">
                                <input type="text" name="end_date" class="form-control datepicker" value="{{$end_date}}">
                            </div>
                        </div>

                        <div class="mb-3 leave_mode_show" style="pointer-events:none;">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> End Date Time
                            </label>
                            <div class="input-group">
                                <input type="text" name="end_date_time" id="end_date_time" class="form-control datepicker"
                                    value="{{$end_date_time}}">
                            </div>
                        </div>
                    </div>

                    <!-- Column 3 -->
                    <div class="col-md-4">
                        <div class="mb-3 leave_mode_hide">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> Alloted Days
                            </label>
                            <input type="text" id="alloted_days" name="alloted_days" class="form-control"
                                value="{{$no_of_days}}">
                        </div>

                        <div class="mb-3 leave_mode_show">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> Alloted Hrs
                            </label>
                            <input type="text" id="alloted_hrs" name="alloted_hrs" class="form-control"
                                value="{{$alloted_hrs}}">
                        </div>

                        <div class="mb-3" style="pointer-events:none;">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> CompOff Status
                            </label>
                            <select name="leave_status" id="leave_status" class="form-select select2"></select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> Approval Reason
                            </label>
                            <input type="text" id="approval_reason" name="approval_reason" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Approval Comments</label>
                            <input type="text" id="approval_comments" name="approval_comments" class="form-control">
                        </div>
                    </div>
                </div>

                <!-- Info Row -->
                <div class="row my-3">
                    <div class="col-md-4">&nbsp;</div>
                    <div class="col-md-4">
                        <div class="alert alert-primary text-center fw-semibold">
                            Comp-Off Leave: <span class="text-dark">{{$c_o_l}}</span>
                        </div>
                    </div>
                    <div class="col-md-4">&nbsp;</div>
                </div>

                <!-- Buttons -->

                <div class="text-center">
                    <button type="button" class="btn btn-success save_form px-4 me-2" value="APPROVE">
                        <i class="fa fa-check"></i> Approve
                    </button>
                    <button type="button" class="btn btn-danger save_form px-4 me-2" value="REJECT">
                        <i class="fa fa-times"></i> Reject
                    </button>
                    <a href="{{url('compoffapproval') }}"><button type="button" class="btn btn-secondary px-4 me-2"
                            id="delete">
                            <i class="fa fa-ban"></i> Cancel
                        </button></a>
                </div>

            </form>
        </div>
    </div>



@endsection
@push('scripts')


    <script>

        $(document).ready(function () {

            $('.pointer').css('pointer-events', 'none');

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
                });
            }




            /** Dropdown for reporting employee start **/
            var organization_id = '{{$organization_id}}';
            var forwarded_id = '{{$forwarded_id}}';
            var logged_id = '{{$logged_id}}';

            // Forwarded Employee
            loadDropdown(
                "#forwarded_id",
                "{{ URL::to('jcomboform') }}?table=hr_employee_t:employee_id:first_name",
                "{{$forwarded_id}}",
                "-- Select Forwarded Employee --"
            );

            // Logged Employee
            loadDropdown(
                "#employee_id",
                "{{ URL::to('jcomboform') }}?table=hr_employee_t:employee_id:first_name",
                "{{$logged_id}}",
                "-- Select Employee --"
            );

            // Leave Type
            var condition1 = 'and lookup_type="leave_type"';
            loadDropdown(
                "#leave_type",
                "{{ URL::to('jcomboform1') }}?table=a_lookuplines_t:lookuplines_id:lookup_code&parent=" + encodeURIComponent(condition1) + "&order_by=lookuplines_id asc",
                "{{$leave_type}}",
                "-- Select Leave Type --"
            );

            // Leave Mode
            var condition2 = 'and lookup_type="leave_mode"';
            loadDropdown(
                "#leave_mode",
                "{{ URL::to('jcomboform1') }}?table=a_lookuplines_t:lookuplines_id:lookup_code&parent=" + encodeURIComponent(condition2) + "&order_by=lookuplines_id asc",
                "{{$leave_mode}}",
                "-- Select Leave Mode --"
            );

            // Leave Status
            var condition3 = 'and lookup_type="leave_status"';
            loadDropdown(
                "#leave_status",
                "{{ URL::to('jcomboform1') }}?table=a_lookuplines_t:lookup_code:lookup_code&parent=" + encodeURIComponent(condition3) + "&order_by=lookuplines_id asc",
                "APPROVE",
                "-- Select Leave Status --"
            );

            // Organization
            loadDropdown(
                "#organization_id",
                "{{ URL::to('jcomboform') }}?table=m_organizations_t:organization_id:organization_name&order_by=organization_name asc",
                "{{$organization_id}}",
                "-- Select Organization --"
            );
            /** Dropdown for reporting employee end **/

            // Leave Mode/Type Visibility
            setTimeout(function () {
                var mode = $("#leave_type option:selected").text();
                if (mode == "PERMISSION") {
                    $('.leave_mode_hide').hide();
                    $('.leave_mode_show').show();
                    $('.alloted_hrs').attr('required', 'true');
                    $('.alloted_days').removeAttr('required');
                } else {
                    $('.alloted_days').attr('required', 'true');
                    $('.alloted_hrs').removeAttr('required');
                    $('.leave_mode_show').hide();
                    $('.leave_mode_hide').show();
                }
            }, 500);





            $(document).on('keyup', '#alloted_days', function () {
                var no_of_days = parseInt($('#no_of_days').val());
                var alloted_days = parseInt($('#alloted_days').val());
                if (alloted_days > no_of_days) {
                    showCustomAlert('Alloted Days is for Only ' + no_of_days, 'warning');
                    $('#alloted_days').val(no_of_days);
                }

            });
            $(document).on('keyup', '#alloted_hrs', function () {
                var no_of_hrs = parseInt($('#no_of_hrs').val());
                var alloted_hrs = parseInt($('#alloted_hrs').val());
                if (alloted_hrs > no_of_hrs) {
                    showCustomAlert('Alloted Hrs is for Only ' + no_of_hrs, 'warning');
                    $('#alloted_hrs').val(no_of_hrs);
                }

            });


            // save

            $(document).on('click', '.save_form', function () {
                var status = $(this).val();
                $('#leave_status').val(status).change();
                var url = "{{URL::to('approvecompoffsave')}}";
                var form = $('#leave');
                form.parsley().validate();
                var form = $('#leave');
                form.parsley().validate();


                if (form.parsley().isValid()) {
                    var $btn = $(this);
                    $btn.prop('disabled', true);

                    var data = $('#leave').serialize();

                    $.post(url, data, function (data1) {
                        if (data1 == 1) {
                            showCustomAlert('CompOff  Details  ' + status + ' Successfully', 'success');
                            setTimeout(function () {
                                var url = "{{URL::to('compoffapproval')}}";
                                window.location.href = url;
                            }, 1500);
                        }
                        else {
                            showCustomAlert('CompOff  Details  ' + status + ' Successfully', 'success');
                            setTimeout(function () {
                                var url = "{{URL::to('compoffapproval')}}";
                                window.location.href = url;
                            }, 1500);
                        }
                    });
                }
            });
            /***save function end **/
        });

    </script>


@endpush