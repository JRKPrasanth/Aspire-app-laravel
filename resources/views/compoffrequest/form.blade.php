@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Comp-Off Request Applicaton</h3>
    @include('layouts.breadcrumb')


    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-4">
            <form action="" id="leave" data-parsley-validate>
                <?php $data = \Session::get('data');
    if (isset($data[$pageMethod]['save'])) { ?>
                <input type="hidden" name="edit_id" value="" id="edit_id" />
                {{ csrf_field() }}

                <div class="row g-4">
                    <!-- Employee -->
                    <div class="form-group col-md-4">
                        <label for="employee_id" class="form-control-label col-md-5">
                            <span class="red">*</span>Employee
                        </label>
                        <div class="col-md-10 employee_div">
                            <select name='employee_id' id="employee_id" class='form-select select2'></select>
                        </div>
                    </div>

                    <!-- Request For -->
                    <div class="form-group col-md-4">
                        <label for="leave_type" class="form-control-label col-md-5">
                            <span class="red">*</span>Request For
                        </label>
                        <div class="col-md-10 compoff_div">
                            <select name='leave_type' id="leave_type" class='form-select select2 leave_type'
                                required></select>
                        </div>
                    </div>

                    <!-- Worked Date -->
                    <div class="form-group col-md-4 checkin_out">
                        <label class="form-control-label col-md-5">
                            <span class="red">*</span>Worked Date
                        </label>
                        <div class="col-md-10">
                            <input class="form-control leave_combo datepicker" id="leave_combo" name="leave_combo"
                                type="text">
                        </div>
                    </div>

                    <!-- CompOff Mode -->
                    <div class="form-group col-md-4 leave_mod">
                        <label for="leave_mode" class="form-control-label col-md-5">
                            <span class="red">*</span>CompOff Mode
                        </label>
                        <div class="col-md-10">
                            <select name='leave_mode' id="leave_mode" class='form-select select2' required>
                                <option value="">-- Please Select --</option>
                                <option value="134">HALF DAY</option>
                                <option value="135">FULL DAY</option>
                            </select>
                        </div>
                    </div>

                    <!-- Half Day -->
                    <div class="half_day row g-4">
                        <div class="form-group col-md-4">
                            <label for="start_datenew" class="form-control-label col-md-5">
                                <span class="red">*</span>Start Date
                            </label>
                            <div class="col-md-10">
                                <input class="form-control start_datenew" id="start_datenew" name="start_date" required
                                    type="text">
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="end_datenew" class="form-control-label col-md-5">
                                <span class="red">*</span>End Date
                            </label>
                            <div class="col-md-10">
                                <input class="form-control end_datenew" id="end_datenew" name="end_date" required
                                    type="text">
                            </div>
                        </div>
                    </div>

                    <!-- Full Day -->
                    <div class="row g-4">
                        <div class="form-group col-md-4 full_day">
                            <label for="start_date1" class="form-control-label col-md-5">
                                <span class="red">*</span>Start Date
                            </label>
                            <div class="col-md-10">
                                <input class="form-control start_date1" id="start_date1" name="start_date1" required
                                    type="text" readonly>
                            </div>
                        </div>

                        <div class="form-group col-md-4 full_day">
                            <label for="end_date1" class="form-control-label col-md-5">
                                <span class="red">*</span>End Date
                            </label>
                            <div class="col-md-10">
                                <input class="form-control end_date1" id="end_date1" name="end_date1" required type="text"
                                    readonly>
                            </div>
                        </div>


                        <!-- No of Days -->
                        <div class="form-group col-md-4">
                            <label for="no_of_days" class="form-control-label col-md-5">No of Days</label>
                            <div class="col-md-10">
                                <input type="text" id="no_of_days" name="no_of_days" class="form-control no_of_days"
                                    readonly>
                            </div>
                        </div>
                    </div>
                    <!-- Reason -->
                    <div class="form-group col-md-4">
                        <label for="reason" class="form-control-label col-md-5">
                            <span class="red">*</span>Reason
                        </label>
                        <div class="col-md-10">
                            <input type="text" id="reason" class="form-control" name="reason" required>
                        </div>
                    </div>

                    <!-- CompOff Status -->
                    <div class="form-group col-md-4">
                        <label for="leave_status" class="form-control-label col-md-5">
                            <span class="red">*</span>CompOff Status
                        </label>
                        <div class="col-md-10 pointer">
                            <select name='leave_status' id="leave_status" class='form-select select2' required>
                                <option value="INITIATED">INITIATED</option>
                                <option value="APPROVED">APPROVED</option>
                                <option value="REJECTED">REJECTED</option>
                            </select>
                        </div>
                    </div>

                    <!-- Approvers -->
                    <div class="form-group col-md-4">
                        <label class="form-control-label col-md-5">
                            <span class="red">*</span>Approvers
                        </label>
                        <div class="col-md-10 pointer1 forwarded_div">
                            <select class="form-select select2 forwarded_id" id="forwarded_id" name="forwarded_id[]" multiple>
                                {!!$reporting!!}
                            </select>
                        </div>
                    </div>

                    <!-- Punch Info -->
                    <div class="row checkin_out mt-3">
                        <div class="col-md-offset-6 col-md-10">
                            <div class="alert alert-warning nopunch mb-2">
                                <strong>Not Punch</strong>
                            </div>
                            <div class="alert alert-success yespunch">
                                <strong>Check In:</strong> <span class="checkin"></span><br>
                                <strong>Check Out:</strong> <span class="checkout"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="button" id="save" class="btn btn-success save_form px-4">
                        Save
                    </button>
                </div>
                <?php } ?>
            </form>
        </div>
    </div>


    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3"></div>
            <div class="table-responsive">
                <table id="ReportTbl" class="table table-striped table-bordered">
                    <thead>
                        <tr class="table-warning">
                            <th></th>
                            <th class="freeze">Employee Name</th>
                            <th>Approver Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>No of Days</th>
                            <th>Alloted Days</th>
                            <th>CompOff Reason</th>
                            <th>Approve Status</th>
                            <th>CompOff Apply Date</th>

                        </tr>
                        <tr class="table-danger">
                            <th></th>
                            <th data-column="0" class="freeze">
                                <input type="text" class="column-search" placeholder="Search" />

                            </th>
                            <th data-column="1" class="freeze">
                                <input type="text" class="column-search" placeholder="Search" />

                            </th>
                            <th data-column="2">
                                <input type="text" class="column-search" placeholder="Search" />

                            </th>
                            <th data-column="3">
                                <input type="text" class="column-search" placeholder="Search" />

                            </th>
                            <th data-column="4">
                                <input type="text" class="column-search" placeholder="Search" />

                            </th>
                            <th data-column="5">
                                <input type="text" class="column-search" placeholder="Search" />

                            </th>
                            <th data-column="6">
                                <input type="text" class="column-search" placeholder="Search" />

                            </th>
                            <th data-column="7">
                                <input type="text" class="column-search" placeholder="Search" />

                            </th>
                            <th data-column="8">
                                <input type="text" class="column-search" placeholder="Search" />

                            </th>


                        </tr>
                    </thead>
                    <tbody>
                        <!-- Your dynamic row data goes here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>



    <input type='hidden' class='week_off'>


@endsection
@push('scripts')

    <script>

        // Employee dropdown

        var logged_id = '{{ $logged_id }}';
        var url = "{{ URL::to('jcomboform') }}?table=hr_employee_t:employee_id:employee_number|first_name";

        // Make AJAX request
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

                $('#employee_id').html('<option value="">-- Select Employee --</option>');

                $.each(data, function (i, item) {
                    let selected = item.val == logged_id ? 'selected' : '';
                    $('#employee_id').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
                });

                $('#employee_id').trigger('change.select2'); // if using Select2
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });


        var url = "{{ URL::to('jcomboform1') }}?table=a_lookuplines_t:lookuplines_id:lookup_code" +
            "&order_by=lookuplines_id asc";

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

                $('#leave_type').html('<option value="">-- Select Leave Type --</option>');

                $.each(data, function (i, item) {
                    let selected = item.val == 277 ? 'selected' : '';
                    $('#leave_type').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
                });

                $('#leave_type').trigger('change.select2'); // if using Select2
            },
            error: function (xhr, status, error) {
                console.error("AJAX error:", error);
            }
        });


        // table data

        $(document).ready(function () {

            var table = $('#ReportTbl').DataTable({
                processing: true,
                serverSide: false,
                scrollX: true,
                order: [[0, 'desc']],
                scrollY: "50vh",
                ajax: {
                    url: "{{ url('compoffData') }}",
                    type: "GET",

                },
                columns: [
                    { data: 'compoff_id', visible:false },
                    { class: 'freeze', data: 'employee_name', name: 'employee_name' },
                    { data: 'forwarded_name', name: 'forwarded_name' },
                    { data: 'start_date', name: 'start_date' },
                    { data: 'end_date', name: 'end_date' },
                    { data: 'no_of_days', name: 'no_of_days' },
                    { data: 'alloted_days', name: 'alloted_days' },
                    { data: 'leave_reason', name: 'leave_reason' },
                    { data: 'leave_status', name: 'leave_status' },
                    { data: 'created_at', name: 'created_at' }

                ],

                initComplete: function () {
            let api = this.api();

            let $scrollHead = $(api.table().container())
                .find('.dataTables_scrollHead thead');

            $scrollHead.find('input.column-search').on('keyup change clear', function () {

                let columnIndex = $(this).closest('th').data('column');

                if (api.column(columnIndex).search() !== this.value) {
                    api.column(columnIndex).search(this.value).draw();
                }
            });
        }

            });

            // Trigger search
            $('.report_search').on('click', function () {
                $('#ReportTbl').DataTable().ajax.reload();
            });


            // Column search
            $('#ReportTbl thead').on('keyup change', ".column-search", function () {
                var index = $(this).closest('th').index();
                table.column(index).search(this.value).draw();
            });
        });












        $(document).ready(function () {

            var g_id = "{{\Session::get('groupid')}}";
            if (g_id > 3) {
                $('.employee_div').css('pointer-events', 'none');
            }
            $('.checkin_out').hide();
            $('.compoff_div').css('pointer-events', 'none');
            $('.pointer').css('pointer-events', 'none');
            $('.half_day').hide();

            $(document).on('change', '#end_datenew', function () {
                var leave_mode = $('#leave_mode').select2('val');
                var end_date = $('#end_datenew').val();
                if (leave_mode == "134" && leave_mode != '' && end_date != '') {
                    $('#no_of_days').val('0.5');
                }
            });


            /*** based on leave mode date change start **/
            $(document).on('change', '#leave_mode', function () {

                var leave_mode = $('#leave_mode').select2('val');
                $('#no_of_days').val('');
                if (leave_mode == 134 && leave_mode != '') {
                    $('.start_date1,.end_date1').removeAttr('required');
                    $('.start_datenew,.end_datenew').attr('required', 'true');
                    $('.half_day').show();
                    $('.full_day').hide();
                    $('.start_datenew').datepicker({
                        dateFormat: "yy-mm-dd",
                        minDate: -3,
                        maxDate: 0,
                        onSelect: function (selected) {
                            $('.end_datenew').datepicker("option", "minDate", $(".start_datenew").datepicker('getDate'))
                        }, onClose: function () {
                            $(this).parsley().validate();
                        }

                    });

                    $('.end_datenew').datepicker({
                        dateFormat: "yy-mm-dd",
                        minDate: -3,
                        maxDate: 0,
                        onClose: function () {
                            $(this).parsley().validate();
                        }
                    });
                    $('.start_datenew,.end_datenew').trigger('click');
                }
                else {
                    $('.start_date1,.end_date1').attr('required', 'true');
                    $('.start_datenew,.end_datenew').removeAttr('required');
                    if (leave_mode != 134 && leave_mode != '') {

                        $('.half_day').hide();
                        $('.full_day').show();
                        $('.start_date1').datepicker({
                            dateFormat: "yy-mm-dd",
                            minDate: -3,
                            maxDate: 0,
                            onSelect: function (selected) {

                                $('.end_date1').datepicker("option", "minDate", $(".start_date1").datepicker('getDate'))
                            }, onClose: function () {
                                $(this).parsley().validate();
                            }
                        });

                        $('.end_date1').datepicker({
                            dateFormat: "yy-mm-dd",
                            minDate: -3,
                            maxDate: 0,
                            onClose: function () {
                                $(this).parsley().validate();
                            }
                        });

                    }
                }
            });

            /*** based on leave mode date change end **/
            /***** Delete Function End  ****/
            Date.prototype.addDays = function (days) {
                var date = new Date(this.valueOf())
                date.setDate(date.getDate() + days);
                return date;
            }



            /***** Based on Start date And End date- no of days calculate function start ****/
            $('#start_date1,#end_date1').change(function () {
                var emp_id = $("#employee_id").val();
                var count = 1;
                var leave_type = $(".leave_type").select2('val');
                var curDate = new Date($('#start_date1').val());
                var endDate = new Date($('#end_date1').val());

                $.get("{{URL::to('compoffdatecheck')}}?employee_id=" + emp_id + "&start_date=" + $('#start_date1').val() + "&end_date=" + $('#end_date1').val(), function (data) {

                    if (data != '0') {
                        $('#start_date1').val('');
                        $('#end_date1').val('');
                        $('.no_of_days').val('');
                        showCustomAlert('Already Comp-Off Has Been Applied', 'warning');
                    }

                });

                $.get("{{URL::to('compoffinitiatecheck')}}?employee_id=" + emp_id + "&start_date=" + $('#start_date1').val() + "&end_date=" + $('#end_date1').val() + "&leave_type=" + leave_type, function (data) {
                    if (leave_type == '277') {
                        if (data != '0' && data > 0) {
                            $('#start_date1').val('');
                            $('#end_date1').val('');
                            $('.no_of_days').val('');
                            showCustomAlert('Previous Comp-Off Yet to be Approved!', 'warning');
                        }
                    }
                });

                var week_off = $('.week_off').val().split(",");
                while (curDate <= endDate) {
                    var dayOfWeek = curDate.getDay();
                    var isWeekend = 0;

                    $.each(week_off, function (index, val) {

                        if (dayOfWeek == val)
                            isWeekend = 1;
                    });

                    if (isWeekend == 0)
                        count++;
                    curDate = curDate.addDays(1);
                }

                if (leave_type == '131' || leave_type == '130') {
                    console.log("Sdsds");
                    var bala = parseFloat($(".balance").html());
                    console.log(bala);
                    console.log(count);
                    var d = new Date();
                    var n = d.getMonth();
                    var curr_month = n + 1;
                    var remain = 12 - curr_month;
                    var eligible = Math.abs(bala - remain);
                    console.log(eligible);
                    var eligible_leave = eligible;
                    if (bala >= count && eligible_leave >= count) {
                        $('.no_of_days').val(count);
                    }
                    else {
                        showCustomAlert('No. of days exceed eligible / available balance', 'warning');
                        $('.no_of_days').val('');
                        $("#end_date1").val('');
                    }
                }
                else {
                    console.log("1234");

                    $('.no_of_days').val(count);
                }


                if (leave_type == '132') {
                    console.log("Sdsds");
                    var bala = parseFloat($(".balance").html());
                    console.log(bala);
                    console.log(count);
                    var eligible_leave = "3";
                    if (bala >= count && eligible_leave <= count)
                    // if(bala>=count)
                    {
                        $('.no_of_days').val(count);
                    }
                    else {
                        showCustomAlert('No. of days exceed eligible / available balance', 'warning');
                        $('.no_of_days').val('');
                        $("#end_date1").val('');
                    }
                }
                else {
                    console.log("1234");
                    var cnt = count - 1;
                    $('.no_of_days').val(cnt);
                }

                if (leave_type == '277') {
                    var start_date = $('.start_date1').val();
                    var end_date = $('.end_date1').val();
                    d1 = new Date(start_date);
                    d2 = new Date(end_date);

                    var cnt = parseInt((d2 - d1) / (1000 * 60 * 60 * 24), 10);
                    console.log("test" + cnt);
                    if (cnt > 0) {
                        var cnt = cnt + 1;
                        $('.no_of_days').val(cnt);
                    } else {
                        var cnt = cnt + 1;
                        $('.no_of_days').val(cnt);
                    }

                }

            });


            function validate_date() {
                var no_of_days = $('.no_of_days').val();
                var leave_mode = $('#leave_mode').select2('val');
                if (leave_mode == "134") {
                    var start_date = $('.start_datenew').val();
                    var end_date = $('.end_datenew').val();
                }
                else {
                    var start_date = $('.start_date1').val();
                    var end_date = $('.end_date1').val();
                }
                var employee_id = $('#employee_id').select2('val');
                var leave_type = $('#leave_type').select2('val');
                var result;
                if (no_of_days != "" && start_date != "" && end_date != "" && employee_id != "" && leave_type != "") {
                    $.ajax({
                        cache: false,
                        url: 'employeeleavescheck', //this is your uri
                        type: 'GET',
                        dataType: 'json',
                        async: false,
                        data: { no_of_days: no_of_days, start_date: start_date, end_date: end_date, employee_id: employee_id, leave_type: leave_type },
                        success: function (response) {
                            result = response;

                        },
                        error: function (xhr, resp, text) {
                            console.log(xhr, resp, text);
                        }
                    });
                }

                return result;
            }



            $(document).on('change', '.leave_combo', function () {

                var leave_combo = $('.leave_combo').val();
                var employee_id = $('#employee_id').select2('val');
                if (leave_combo != '' && employee_id != '') {
                    var url = "{{URL::to('getcombodate')}}/" + leave_combo + "/" + employee_id;
                    $.get(url, function (data) {
                        $('.checkin_out').show();
                        if (data['data'] == 1) {
                            $('.nopunch').hide();
                            $('.yespunch').show();
                            $('.checkin').html(data['data_combo']['check_in']);
                            $('.checkout').html(data['data_combo']['check_out']);

                        } else {
                            $('.nopunch').show();
                            $('.yespunch').hide();
                            showCustomAlert('Not Worked on these day', 'warning');
                            $('.leave_combo').val('');
                        }
                    });
                } else {
                    $('.checkin_out').hide();
                }
            });



            $(document).on('change', '#leave_mode', function () {
                var employee_id = $('#employee_id').select2('val');
                if (employee_id != '') {
                    var url = "{{ url::to('compoffapprover') }}?employee_id=" + employee_id;
                    $.get(url, function (data) {
                        var data = jQuery.parseJSON(data);


                        $("#forwarded_id").html('');
                        $("#forwarded_id").html(data.reporting);
                    });
                }

            });


            // leave type	

            $(document).on('change', '.leave_type', function () {

                var leave_type = $('#leave_type').select2('val');
                var employee_id = $('#employee_id').select2('val');

                $('#leave_mode').select2('destroy');

                if (leave_type == '131' || leave_type == '130' || leave_type == '276' || leave_type == '277') {
                    $("#leave_mode option[value='285']").attr('disabled', 'disabled');
                    $("#leave_mode option[value='286']").attr('disabled', 'disabled');
                    $("#leave_mode option[value='287']").attr('disabled', 'disabled');
                    $("#leave_mode option[value='134']").attr('disabled', false);
                    $("#leave_mode option[value='135']").attr('disabled', false);
                }
                else if (leave_type == '132') {
                    $("#leave_mode option[value='285']").attr('disabled', 'disabled');
                    $("#leave_mode option[value='286']").attr('disabled', 'disabled');
                    $("#leave_mode option[value='287']").attr('disabled', 'disabled');
                    $("#leave_mode option[value='134']").attr('disabled', 'disabled');
                    $("#leave_mode option[value='135']").attr('disabled', false);
                }
                else if (leave_type == '133') {
                    //alert("hhh");
                    $("#leave_mode option[value='285']").attr('disabled', false);
                    $("#leave_mode option[value='286']").attr('disabled', false);
                    $("#leave_mode option[value='287']").attr('disabled', false);
                    $("#leave_mode option[value='134']").attr('disabled', false);
                    $("#leave_mode option[value='135']").attr('disabled', false);
                }
                else {
                    $("#leave_mode option[value='285']").attr('disabled', false);
                    $("#leave_mode option[value='286']").attr('disabled', false);
                    $("#leave_mode option[value='287']").attr('disabled', false);
                    $("#leave_mode option[value='134']").attr('disabled', 'disabled');
                    $("#leave_mode option[value='135']").attr('disabled', 'disabled');
                }

                $('#leave_mode').select2();

            });


            $(document).on('change', '.leave_type', function () {
                var leave_type = $('#leave_type').select2('val');
                if (leave_type == "273") {
                    $('.worked_date').show();
                    $('.leave_combo').attr('required', true);
                } else {
                    $('.worked_date').hide();
                    $('.leave_combo').removeAttr('required');
                }
            });



            // save form

            $(document).on('click', '.save_form', function () {

                var url = "{{URL::to('compoffsave')}}";
                var no_of_days = $('.no_of_days').val();
                var leave_type = $('#leave_type').select2('val');

                var form = $('#leave');
                form.parsley().validate();

                var check = true;
                if (form.parsley().isValid() && check) {
                    var $btn = $(this);
                    $btn.prop('disabled', true);
                    var data = $('#leave').serialize();
                    $.post(url, data, function (data1) {
                        if (data1 == 1) {
                            showCustomAlert('CompOff Request Initiated Successfully', 'success');
                            location.reload();

                        }
                        else {
                            showCustomAlert('CompOff Request Updated Successfully', 'success');
                            location.reload();

                        }
                    });
                }
            });

            $(function () {
                $('.start_date1, .end_date1, .start_datenew, .end_datenew')
                    .datepicker({
                        dateFormat: "yy-mm-dd",
                        minDate: -3,
                        maxDate: 0
                    })
                    .prop('readonly', true);
            });

            $('.start_date1, .start_datenew').on('mousedown', function (e) {
                if (!$('#leave_mode').val()) {
                    e.preventDefault(); // stop datepicker
                    alert('Please select Leave Mode first');
                    return false;
                }
            });




        });

    </script>


@endpush