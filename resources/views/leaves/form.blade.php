@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Leave Applicaton</h3>
    @include('layouts.breadcrumb')
    <style>
        .type-selector {
            display: flex;
            gap: 1.5rem;
            padding: 1rem 0;
            background: #f9f9f9;
            border-radius: 1rem;
            margin-top: 1rem;
        }

        .type-selector .form-check-input {
            display: none;
        }

        .type-selector .form-check-label {
            padding: 0.6rem 1.5rem;
            border: 2px solid #ccc;
            border-radius: 2rem;
            background-color: #fff;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            font-weight: 500;
            color: #333;
        }

        .type-selector .form-check-input:checked+.form-check-label {
            background-color: #0d6efd;
            color: #fff;
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
    </style>
    <div class="card shadow-lg rounded-4 border-0 container">
        <div class="card-body">
            <form action="" id="leave" data-parsley-validate>
                <?php $data = \Session::get('data');
    if (isset($data[$pageMethod]['save'])) { ?>
                <input type="hidden" name="edit_id" value="" id="edit_id" />
                {{ csrf_field() }}
                <div class="container-fluid">
                    <div class="row g-3">

                        <!-- Employee -->
                        <div class="col-md-4">
                            <label for="start_date" class="form-label"><span class="red">*</span>Employee</label>
                            <div class="employee_div">
                                <select name="employee_id" id="employee_id" class="select2 form-select"></select>
                            </div>
                        </div>

                        <!-- Leave Type -->
                        <div class="col-md-4 leave_type_div">
                            <label class="form-label"><span class="text-danger">*</span> Leave Type</label>
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 me-2">
                                    <select name="leave_type" id="leave_type" class="form-select select2 leave_type"
                                        required></select>
                                </div>
                                <div><span class="badge bg-primary">LB: <span class="balance">0</span></span></div>
                            </div>
                        </div>

                        <!-- Worked Date -->
                        <div class="form-group col-md-4 worked_date">
                            <label class="form-label"><span class="red">*</span>Worked Date</label>
                            <input type="text" class="form-control leave_combo datepicker" id="leave_combo"
                                name="leave_combo" value="">
                        </div>

                        <!-- Leave Mode -->
                        <div class="form-group col-md-4 leave_mod">
                            <label class="form-label"><span class="red">*</span>Leave Mode</label>
                            <select name="leave_mode" id="leave_mode" class="select2 form-select leave_mode" required>
                                <option value="">-- Please Select --</option>
                                <option value="134">HALF DAY</option>
                                <option value="135">FULL DAY</option>
                                <option value="285">HALF AN HOUR</option>
                                <option value="286">ONE HOUR</option>
                                <option value="287">ONE AND HALF HOUR</option>
                            </select>
                        </div>

                        <!-- Half Day Section -->
                        <div class="half_day row g-3">
                            <div class="form-group col-md-4">
                                <label class="form-label"><span class="red">*</span>Session</label>
                                <select name="session" id="session" class="select2 session form-select">
                                    <option value="">-- Please Select --</option>
                                    <option value="514">FORENOON</option>
                                    <option value="515">AFTERNOON</option>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label class="form-label"><span class="red">*</span>Start Date</label>
                                <input type="text" class="form-control start_datenew" id="start_datenew" name="start_date"
                                    required>
                            </div>

                            <div class="form-group col-md-4">
                                <label class="form-label"><span class="red">*</span>End Date</label>
                                <input type="text" class="form-control end_datenew" id="end_datenew" name="end_date"
                                    required>
                            </div>
                        </div>

                        <!-- Other Leave Type Section -->
                        <div class="other_leavetype">
                            <div class="full_day row g-3">
                                <div class="form-group col-md-4">
                                    <label class="form-label"><span class="red">*</span>Start Date</label>
                                    <input type="text" class="form-control start_date_full" id="start_date_full"
                                        name="start_date1" required readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="form-label"><span class="red">*</span>End Date</label>
                                    <input type="text" class="form-control end_date_full" id="end_date_full"
                                        name="end_date1" required readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="form-label"><span class="text-danger">*</span> No of Days</label>
                                    <input type="text" id="no_of_days" name="no_of_days" class="form-control no_of_days"
                                        readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Permission Section -->
                        <div class="permission row g-3">
                            <div class="form-group col-md-4">
                                <label class="form-label"><span class="red">*</span>Start DateTime</label>
                                <input type="text" class="form-control start_date_time" id="start_date_time"
                                    name="start_date_time" required readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="form-label"><span class="red">*</span>End DateTime</label>
                                <input type="text" class="form-control end_date_time" id="end_date_time"
                                    name="end_date_time" required readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="form-label">No of Hours</label>
                                <input type="text" id="no_of_hrs" name="no_of_hrs" class="form-control no_of_hrs" readonly>
                            </div>
                        </div>

                        <!-- On Duty Section -->
                        <div class="onduty row g-3">
                            <div class="form-group col-md-4">
                                <label class="form-label"><span class="red">*</span>OD Start DateTime</label>
                                <input type="text" class="form-control od_start_date" id="od_start_date"
                                    name="od_start_date" required readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="form-label"><span class="red">*</span>OD End DateTime</label>
                                <input type="text" class="form-control od_end_date" id="od_end_date" name="od_end_date"
                                    required readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="form-label">OD No of Hours</label>
                                <input type="text" id="od_no_of_days" name="od_no_of_days"
                                    class="form-control od_no_of_days" readonly>
                            </div>
                        </div>

                        <!-- Reason -->
                        <div class="form-group col-md-4">
                            <label class="form-label"><span class="red">*</span>Reason</label>
                            <input type="text" id="reason" class="form-control reason" name="reason" required>
                        </div>

                        <!-- Leave Status -->
                        <div class="form-group col-md-4 employee_div">
                            <label class="form-label"><span class="red">*</span>Leave Status</label>
                            <select name="leave_status" id="leave_status" class="select2 form-select" required>
                                <option value="INITIATED">INITIATED</option>
                                <option value="APPROVED">APPROVED</option>
                                <option value="REJECTED">REJECTED</option>
                            </select>
                        </div>

                        <!-- Approvers -->
                        <div class="form-group col-md-4">
                            <label class="form-label"><span class="red">*</span>Approvers</label>
                            <div class="d-flex align-items-center gap-2">
                                <div class="forwarded_div w-100">
                                    <select id="forwarded_id" class="select2 forwarded_id form-select" name="forwarded_id[]"
                                        multiple>
                                        {!!$reporting!!}
                                    </select>
                                </div>
                                <!--     <div class="showinline">
                                            <span class="showspan">
                                                <i class="fa fa-refresh jcr_reporting_id"></i>
                                            </span>
                                        </div> -->
                            </div>
                        </div>
                        <!-- for half day purpose  -->
                        <div class="form-group col-md-4 nohalfday">
                            <label class="form-label"><span class="text-danger">*</span> No of Days</label>
                            <input type="text" id="no_of_days1" name="no_of_days" class="form-control no_of_days" value=""
                                readonly>
                        </div>

                        <!-- Check In/Out -->
                        <div class="row checkin_out">
                            <div class="col-md-6 offset-md-6">
                                <div class="alert alert-success nopunch">
                                    <strong>Not Punch</strong>
                                </div>
                                <div class="alert alert-success yespunch">
                                    <strong>Check In:</strong> <span class="checkin"></span><br>
                                    <strong>Check Out:</strong> <span class="checkout"></span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Buttons -->

                    <div class="text-center mt-4">
                        <button type="button" id="save" class="btn btn-success save_form px-4">Save</button>
                    </div>
                    <?php } ?>
                </div>
            </form>


        </div>
    </div>


    <!-- Radio Button Controls -->
    <!-- Type Selection -->
    <div class="type-selector mb-3">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="request_type" id="leave_tbl" value="leave_tbl" checked>
            <label class="form-check-label" for="leave_tbl">Leave</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="request_type" id="permission_tbl" value="permission_tbl">
            <label class="form-check-label" for="permission_tbl">Permission</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="request_type" id="od_tbl" value="od_tbl">
            <label class="form-check-label" for="od_tbl">OD</label>
        </div>
    </div>

    <!-- Table -->
    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table id="leavTbl" class="table table-bordered table-striped w-100">
                    <thead>
                        <tr class="table-warning">
                            <th>Employee Name</th>
                            <th>Reporting Name</th>
                            <th>Leave Type</th>
                            <th><span class="header-4"></span></th>
                            <th><span class="header-5"></span></th>
                            <th><span class="header-6"></span></th>
                            <th><span class="header-7"></span></th>
                            <th><span class="header-8"></span></th>
                            <th><span class="header-9"></span></th>
                            <th><span class="header-10"></span></th>
                            <th><span class="header-11"></span></th>
                            <th><span class="header-12"></span></th>
                            <th><span class="header-13"></span></th>
                            <th><span class="header-14"></span></th>
                            <th><span class="header-15"></span></th>
                            <th>Approve Status</th>
                            <th>Leave Apply Date</th>
                            <th></th>
                        </tr>
                        <tr class="table-info">
                            @for ($i = 0; $i < 18; $i++)
                                <th><input type="text" class="form-control form-control-sm column-search" /></th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>



@endsection
@push('scripts')

    <script>

        // new scripts -- VIGNESH M

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

        // leave type dropdown	
        <?php $groupname = \Session::get('groupname'); ?>
        var condition1 = '';

        @if($groupname == '14')
            condition1 = ' and lookup_type="leave_type"';
        @else
            condition1 = ' and lookup_type="leave_type" AND lookup_code != "SICK LEAVE"';
        @endif

                        var url = "{{ URL::to('jcomboform1') }}?table=a_lookuplines_t:lookuplines_id:lookup_code" +
            "&parent=" + encodeURIComponent(condition1) +
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
                    $('#leave_type').append(`<option value="${item.val}">${item.option_name}</option>`);
                });

                $('#leave_type').trigger('change.select2'); // if using Select2
            },
            error: function (xhr, status, error) {
                console.error("AJAX error:", error);
            }
        });


        // table data	
        $(document).ready(function () {
            var table = $('#leavTbl').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 5,
                ajax: {
                    url: "{{ route('leaveData') }}",
                    type: "GET",
                    data: function (d) {
                        const selectedType = $("input[name='request_type']:checked").val();
                        if (selectedType === 'leave_tbl') d.leave_type = 'LEAVE';
                        else if (selectedType === 'permission_tbl') d.leave_type = 'PERMISSION';
                        else if (selectedType === 'od_tbl') d.leave_type = 'ON-DUTY';
                    }
                },
                columns: [
                    { data: 'employee_name' },
                    { data: 'forwarded_name' },
                    { data: 'leave_type' },

                    // Leave
                    { data: 'start_date' },
                    { data: 'end_date' },
                    { data: 'no_of_days' },
                    { data: 'alloted_days' },

                    // Permission
                    { data: 'start_date_time' },
                    { data: 'end_date_time' },
                    { data: 'no_of_hrs' },
                    { data: 'alloted_hrs' },

                    // OD
                    { data: 'od_start_date' },
                    { data: 'od_end_date' },
                    { data: 'od_no_of_days' },
                    { data: 'od_alloted_days' },

                    { data: 'leave_status' },
                    { data: 'created_at' },

                    { data: 'leave_id', visible: false, orderable: false, searchable: false }
                ]
            });

            const leaveCols = [3, 4, 5, 6];
            const permissionCols = [7, 8, 9, 10];
            const odCols = [11, 12, 13, 14];

            function toggleColumns(type) {
                [...leaveCols, ...permissionCols, ...odCols].forEach(i => table.column(i).visible(false));
                if (type === 'leave_tbl') leaveCols.forEach(i => table.column(i).visible(true));
                if (type === 'permission_tbl') permissionCols.forEach(i => table.column(i).visible(true));
                if (type === 'od_tbl') odCols.forEach(i => table.column(i).visible(true));
            }

            function updateHeaderText(type) {
                const headers = {
                    4: '', 5: '', 6: '', 7: '', 8: '', 9: '',
                    10: '', 11: '', 12: '', 13: '', 14: '', 15: ''
                };
                if (type === 'leave_tbl') {
                    headers[4] = 'Start Date';
                    headers[5] = 'End Date';
                    headers[6] = 'No of Days';
                    headers[7] = 'Alloted Days';
                } else if (type === 'permission_tbl') {
                    headers[8] = 'Start Date Time';
                    headers[9] = 'End Date Time';
                    headers[10] = 'No of Hours';
                    headers[11] = 'Alloted Hours';
                } else if (type === 'od_tbl') {
                    headers[12] = 'OD Start Date';
                    headers[13] = 'OD End Date';
                    headers[14] = 'OD No of Days/Hrs';
                    headers[15] = 'OD Alloted Days/Hrs';
                }
                Object.entries(headers).forEach(([index, text]) => {
                    $('.header-' + index).text(text);
                });
            }

            $("input[name='request_type']").change(function () {
                const type = $(this).val();
                toggleColumns(type);
                updateHeaderText(type);
                table.ajax.reload();
            });

            const initialType = $("input[name='request_type']:checked").val();
            toggleColumns(initialType);
            updateHeaderText(initialType);

            $('#leavTbl thead').on('keyup change', ".column-search", function () {
                var colIndex = $(this).parent().index();
                table.column(colIndex).search(this.value).draw();
            });
        });



        // permission date picker	

        $(document).ready(function () {
            const dateFormat = "{{ \Session('j_date_format') ?? 'yy-mm-dd' }}";
            const timeFormat = "HH:mm:ss"; // 24-hour format with seconds

            // Initially disable end_date_time
            $(".end_date_time").prop("disabled", true).val("");

            var dateToday = new Date();
            dateToday.setDate(dateToday.getDate() - 5);



            $(document).on("focus", ".start_date_time", function () {
                $(this).datetimepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: dateFormat,
                    timeFormat: timeFormat,
                    controlType: 'select',
                    oneLine: true,
                    showSecond: true,
                    minDate: dateToday,

                    showAnim: "slideDown",
                    onClose: function (selectedDateTime) {
                        if (selectedDateTime) {
                            const startDate = $(this).datetimepicker("getDate");

                            // Enable end_date_time
                            $(".end_date_time").prop("disabled", false);

                            // Reinitialize end_date_time with updated minDate
                            $(".end_date_time").datetimepicker("destroy").datetimepicker({
                                changeMonth: true,
                                changeYear: true,
                                dateFormat: dateFormat,
                                timeFormat: timeFormat,
                                controlType: 'select',
                                oneLine: true,
                                showSecond: true,
                                minDate: startDate, // Disallow dates before start time

                                showAnim: "slideDown",
                                yearRange: "-25:+0"
                            });
                        }
                    }
                });
            });
        });


        // old to new 

        var g_id = "{{\Session::get('groupid')}}";
        if (g_id > 1) {
            $('.employee_div').css('pointer-events', 'none');
        }
        $('.checkin_out').hide();



    $('#end_datenew').change(function () {

    const emp_id = $('#employee_id').val();
    const leave_type = $('.leave_type').select2('val');
    const leave_mode = $('#leave_mode').select2('val');
    const end_date = $('#end_datenew').val();

      if(leave_mode == "134" && leave_mode != '' && end_date != '' && leave_type !='276')
            {

    $.get(
        "{{ URL::to('leaveinitiatecheck') }}",
        {
            employee_id: emp_id,
            start_date: $('#start_datenew').val(),
            end_date: end_date,
            leave_type: leave_type
        },
       
        
        function (data) {

            // default half-day
            $('#no_of_days1').val('0.5');

            // Apply restriction ONLY for this mode
            if (leave_mode === '134' && leave_type !== '276') {

                const balance = parseFloat($('.balance').html()) || 0;
                const currentMonth = new Date().getMonth() + 1;
                const remainingMonths = 12 - currentMonth;

                const eligible = Math.abs(balance - remainingMonths);
                const usedLeave = parseFloat(data) || 0;
                const eligible_leave = Math.max(0, eligible - usedLeave);

                const no_of_days = 0.5; // half-day


                if (no_of_days > eligible_leave) {
                    $('#start_datenew').val('');
                    $('#end_datenew').val('');
                    $('#no_of_days1').val('');
                    $('#end_datenew').prop('disabled', true);

                    showCustomAlert('No. of days exceed eligible / available balance','error');
                }
            }
        }
    );
              }else{
                $('#no_of_days1').val('0.5');
            }
});



        // Hide half_day and onduty sections initially
        $('.half_day').hide();
        $('.nohalfday').hide();
        $('.onduty').hide();


        $(document).on('change', '#leave_mode', function () {
            const leave_mode = $('#leave_mode').val();
            const leave_type = $('.leave_type').val();

            $('#no_of_days').val('');
            const dateToday = new Date();
            dateToday.setDate(dateToday.getDate() - 5);

            // Reset all required attributes and UI blocks
            $('.start_date_full, .end_date_full, .start_datenew, .end_datenew, .session, .od_start_date, .od_end_date').removeAttr('required');
            $('.half_day, .nohalfday, .onduty',).hide();

            if (leave_mode === '134') {
                if (leave_type === '133') {
                    // On Duty Mode
                    $('.onduty').show();
                    $('.other_leavetype').hide();
                    $('.od_start_date, .od_end_date').attr('required', true);
                } else {
                    // Half Day Mode
                    $('.half_day, .nohalfday').show();
                    $('.full_day').hide();
                    $('.other_leavetype').show();

                    $('.start_datenew, .end_datenew, .session').attr('required', true);

                    $(".end_datenew").prop("disabled", true).val("");

                    $(document).on("focus", ".start_datenew", function () {
                        $(this).datepicker({
                            changeMonth: true,
                            changeYear: true,
                            dateFormat: "yy-mm-dd",
                            minDate: dateToday,

                            showAnim: "slideDown",
                            yearRange: "-25:+0",
                            onClose: function (selectedDate) {
                                if (selectedDate) {
                                    const startDate = $(this).datetimepicker("getDate");

                                    // Enable end_datenew
                                    $(".end_datenew").prop("disabled", false);

                                    // Reinitialize end_datenew with updated minDate
                                    $(".end_datenew").datepicker("destroy").datepicker({
                                        changeMonth: true,
                                        changeYear: true,
                                        dateFormat: "yy-mm-dd",
                                        minDate: startDate, // Disallow dates before start time

                                        showAnim: "slideDown",
                                        yearRange: "-25:+0"
                                    });
                                }
                            }
                        });
                    });


                    $('.start_datenew, .end_datenew').trigger('click');
                }
            } else if (leave_mode && !['285', '286', '287'].includes(leave_mode)) {
                // Full day for standard leaves (not special codes)
                $('.full_day').show();
                $('.other_leavetype').show();

                $('.start_date_full, .end_date_full').attr('required', true);

                $(".end_date_full").prop("disabled", true).val("");

                $(document).on("focus", ".start_date_full", function () {
                    $(this).datepicker({
                        changeMonth: true,
                        changeYear: true,
                        dateFormat: "yy-mm-dd",
                        minDate: dateToday,

                        showAnim: "slideDown",
                        yearRange: "-25:+0",
                        onClose: function (selectedDate) {
                            if (selectedDate) {
                                const startDate = $(this).datetimepicker("getDate");

                                // Enable end_date_full
                                $(".end_date_full").prop("disabled", false);

                                // Reinitialize end_date_full with updated minDate
                                $(".end_date_full").datepicker("destroy").datepicker({
                                    changeMonth: true,
                                    changeYear: true,
                                    dateFormat: "yy-mm-dd",
                                    minDate: startDate,

                                    showAnim: "slideDown",
                                    yearRange: "-25:+0"
                                });
                            }
                        }
                    });
                });

            } else {
                // Hide all for other leave_mode values
                $('.full_day').hide();
                $('.onduty').hide();
                $('.other_leavetype').hide();
            }

            // Optional: validation reminder if no mode selected
            if (!leave_mode) {
                showCustomAlert('Please select a valid leave mode', 'warning');
            }
        });


        // Utility function: Add days to a Date prototype
        Date.prototype.addDays = function (days) {
            const date = new Date(this.valueOf());
            date.setDate(date.getDate() + days);
            return date;
        };

        // Calculate leave days based on start and end date
        $('#start_date_full, #end_date_full').change(function () {
            const emp_id = $('#employee_id').val();
            const leave_type = $('.leave_type').val();
            const startDateStr = $('#start_date_full').val();
            const endDateStr = $('#end_date_full').val();

            if (!emp_id || !startDateStr || !endDateStr || !leave_type) return;

            const startDate = new Date(startDateStr);
            const endDate = new Date(endDateStr);
            let count = 0;

            // Weekend filter (Sunday and 2nd Saturday)
            const week_off = $('.week_off').val()?.split(',') || [];

            let curDate = new Date(startDate);
            while (curDate <= endDate) {
                const dayOfWeek = curDate.getDay();
                const dayOfMonth = curDate.getDate();
                let isWeekend = (dayOfWeek == 0 || (dayOfWeek == 6 && dayOfMonth > 7 && dayOfMonth <= 14));

                $.each(week_off, function (_, val) {
                    if (parseInt(val) === dayOfWeek) isWeekend = true;
                });

                if (!isWeekend) count++;
                curDate = curDate.addDays(1);
            }

            // Leave duplication check
            $.get(`{{ URL::to('leavedatecheck') }}?employee_id=${emp_id}&start_date=${startDateStr}&end_date=${endDateStr}`, function (data) {
                if (data !== '0') {
                    $('#start_date_full, #end_date_full').val('');
                    $('.no_of_days').val('');
                    showCustomAlert('Already Leave Has Been Applied', 'info');
                    return;
                }

                // EL limit per month check
                if (leave_type === '132') {
                    $.get(`{{ URL::to('earnleavedatecountcheck') }}?employee_id=${emp_id}&start_date=${startDateStr}&end_date=${endDateStr}&leave_type=${leave_type}`, function (data) {
                        if (parseInt(data) >= 2) {
                            $('#start_date_full, #end_date_full').val('');
                            $('.no_of_days').val('');
                            showCustomAlert('Eligible times of EL Exceed for this month', 'error');
                            return;
                        }
                    });
                }

                // Leave balance / approval logic
$.get(
  `{{ URL::to('leaveinitiatecheck') }}?employee_id=${emp_id}&start_date=${startDateStr}&end_date=${endDateStr}&leave_type=${leave_type}`,
  function (data) {

    const balance = parseFloat($('.balance').html()) || 0;
    const currentMonth = new Date().getMonth() + 1;
    const remainingMonths = 12 - currentMonth;

    // SAME LOGIC AS OLD CODE
    const eligible = Math.abs(balance - remainingMonths);
    const eligible_leave = Math.abs(eligible - parseFloat(data || 0));

    const no_of_days = count;

    if (['131', '130'].includes(leave_type)) {

      if (no_of_days > eligible_leave) {
        $('.no_of_days').val('');
        $('.start_date_full').val('');
        $('.end_date_full').val('');
        showCustomAlert(
          'No. of days exceed eligible / available balance',
          'error'
        );
      } else {
        $('.no_of_days').val(no_of_days);
      }
    } else if (leave_type === '132') {

                        if (parseInt(data) > 0) {
                            showCustomAlert('Previous EL Yet to be Approved!', 'info');
                        } else {
                            const exactDays = parseInt((endDate - startDate) / (1000 * 60 * 60 * 24), 10) + 1;
                            console.log(balance);
                            if (balance >= exactDays) {
                                $('.no_of_days').val(exactDays);
                            } else {
                                $('.no_of_days').val('');
                                $('.start_date_full').val('');
                                $('.end_date_full').val('');
                                showCustomAlert('No. of days exceed eligible / available balance', 'error');
                            }
                        }
                    } else if (leave_type === '277') {
                        if (parseInt(data) > 0) {
                            showCustomAlert('Previous Comp-Off Yet to be Approved!', 'warning');
                        } else {
                            if (balance >= count) {
                                $('.no_of_days').val(count);
                            } else {
                                $('.no_of_days').val('');
                                $('.start_date_full').val('');
                                $('.end_date_full').val('');
                                showCustomAlert('No. of days exceed eligible / available balance', 'warning');
                            }
                        }
                    } else {
                        // Default case for any other leave type
                        $('.no_of_days').val(count);
                    }
                });
            });

        });


        // Calculate leave days based on start and end date
        $('#start_date_time, #end_date_time').change(function () {
            const emp_id = $('#employee_id').val();
            const leave_type = $('.leave_type').val();
            const startDateStr = $('#start_date_time').val();
            const endDateStr = $('#end_date_time').val();

            if (!emp_id || !startDateStr || !endDateStr || !leave_type) return;

            // Leave balance / approval logic
            $.get(`{{ URL::to('permissioninitiatecheck') }}?employee_id=${emp_id}&start_date=${startDateStr}&end_date=${endDateStr}&leave_type=${leave_type}`, function (data) {
               
            
                if (data.already_applied == 1) {
                    $('.no_of_hrs').val('');
                    $('.start_date_time').val('');
                    $('.end_date_time').val('');
                    showCustomAlert('Already Permission Applied for the date', 'error');
                    return;
                }
            
            const remaing = 3.00 - parseFloat(data.no_of_days);
                $('.balance').html(remaing);

                if (data.count >= '2') {

                    $('.no_of_hrs').val('');
                    $('.no_of_days').val('');
                    $('.start_date_time').val('');
                    $('.end_date_time').val('');
                    showCustomAlert('Only Two Permissions Available For Month', 'error');

                }

                if (leave_type === '265') {

                    if (remaing >= '0') {

                        const start = $('.start_date_time').val();
                        const end = $('.end_date_time').val();
                        const leaveMode = $('#leave_mode').val();
                        const startDate = new Date(start);
                        const endDate = new Date(end);
                        const diffMinutes = (endDate - startDate) / (1000 * 60);

                        // Determine allowed duration
                        let allowedMinutes = 60;
                        if (leaveMode === '287') {
                            allowedMinutes = 90;
                        }

                        // Allow only exact allowed duration
                        if (diffMinutes !== allowedMinutes) {
                            const correctedEnd = new Date(startDate.getTime() + allowedMinutes * 60000);
                            const formattedEnd = correctedEnd.toISOString().slice(0, 16).replace("T", " ");
                            $('.end_date_time').val(formattedEnd);
                            $('.no_of_hrs').val('');
                            showCustomAlert(`Only ${allowedMinutes === 90 ? '1.30' : '1.00'} hour${allowedMinutes === 90 ? 's' : ''} allowed for this leave mode`, 'error');
                            return;
                        }

                        // Valid: Set exact value
                        const hours = Math.floor(diffMinutes / 60);
                        const minutes = Math.round(diffMinutes % 60).toString().padStart(2, '0');
                        $('.no_of_hrs').val(`${hours}.${minutes}`);
                    } else {
                        $('.no_of_hrs').val('');
                        $('.start_date_time').val('');
                        $('.end_date_time').val('');
                        showCustomAlert('No. of Hrs exceed eligible / available balance', 'error');
                    }
                } else {
                    // Default case for any other leave type
                    $('.no_of_hrs').val('');
                }
            });
        });


        //  Validate leave date function (synchronous check)
        function validate_date() {
            const no_of_days = $('.no_of_days').val();
            const leave_mode = $('#leave_mode').val();
            const start_date = leave_mode === '134' ? $('.start_datenew').val() : $('.start_date_full').val();
            const end_date = leave_mode === '134' ? $('.end_datenew').val() : $('.end_date_full').val();
            const employee_id = $('#employee_id').val();
            const leave_type = $('#leave_type').val();
            let result = null;

            if (no_of_days && start_date && end_date && employee_id && leave_type) {
                $.ajax({
                    url: 'employeeleavescheck',
                    type: 'GET',
                    dataType: 'json',
                    async: false,
                    cache: false,
                    data: {
                        no_of_days,
                        start_date,
                        end_date,
                        employee_id,
                        leave_type
                    },
                    success: function (response) {
                        result = response;
                    },
                    error: function (xhr, status, error) {
                        console.error('Validation check failed:', status, error);
                    }
                });
            }

            return result;
        }

        //  Fetch combo date and show punch-in/out details
        $(document).on('change', '.leave_combo', function () {
            const leave_combo = $(this).val();
            const employee_id = $('#employee_id').val();

            if (!leave_combo || !employee_id) {
                $('.checkin_out').hide();
                return;
            }

            const url = `{{ URL::to('getcombodate') }}/${leave_combo}/${employee_id}`;

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
                    showCustomAlert('Not Worked on these day', 'error');
                    $('.leave_combo').val('');
                }
            });
        });

        // Update forwarding/reporting employee dropdown on employee change
        $(document).on('change', '#employee_id', function () {
            const employee_id = $(this).val();

            if (!employee_id) return;

            const url = `{{ url::to('leaveapprover') }}?employee_id=${employee_id}`;

            $.get(url, function (data) {
                const parsed = JSON.parse(data);
                $('#forwarded_id').html(parsed.reporting || '');
            });
        });

        $('.permission, .worked_date').hide();

        $(document).on('change', '.leave_type', function () {
            const leave_type = $('#leave_type').val();
            const employee_id = $('#employee_id').val();

            // Reset permission section visibility and field requirements
            if (leave_type === '265') {
                $('.permission').show();
                $('.other_leavetype').hide();

                $('.start_date_full, .end_date_full, .start_datenew, .end_datenew').removeAttr('required');
                $('.start_date_time, .end_date_time').attr('required', true);
            } else {
                $('.permission').hide();
                $('.other_leavetype').show();

                $('.start_date_time, .end_date_time').removeAttr('required');
                $('.start_date_full, .end_date_full').attr('required', true);
            }

            // Reset leave mode dropdown state
            $('#leave_mode').select2('destroy');

            // Manage which leave_mode options are disabled/enabled based on leave_type
            const disableAll = ['285', '286', '287', '134', '135'];
            disableAll.forEach(value => $(`#leave_mode option[value='${value}']`).prop('disabled', true));

            if (['131', '130', '276', '277'].includes(leave_type)) {
                $('#leave_mode option[value=\"134\"], #leave_mode option[value=\"135\"]').prop('disabled', false);
            } else if (leave_type === '132') {
                $('#leave_mode option[value=\"135\"]').prop('disabled', false);
            } else if (leave_type === '133') {
                $('#leave_mode option[value=\"134\"], #leave_mode option[value=\"135\"]').prop('disabled', false);
            } else {
                $('#leave_mode option[value=\"286\"], #leave_mode option[value=\"287\"]').prop('disabled', false);
            }

            $('#leave_mode').select2();

            // Fetch leave balance from server
            if (leave_type) {
                const url = `{{ url::to('leavetypebase') }}/${leave_type}?employee_id=${employee_id}`;
                $.get(url, function (data) {
                    const balance = parseFloat(data) || 0;
                    $('.balance').html(balance);

                    if (balance <= 0 && ['131', '130', '132'].includes(leave_type)) {
                        showCustomAlert("You don't have leave balance for this type", 'info');
                        $('#leave_type').val('').trigger('change.select2');
                    }
                });

                // Disable LTDIS interactions
                $('.ltdis').css('pointer-events', 'none');
            }

            // Special handling for leave type 273 (Comp-Off worked date entry)
            if (leave_type === '273') {
                $('.worked_date').show();
                $('.leave_combo').attr('required', true);
            } else {
                $('.worked_date').hide();
                $('.leave_combo').removeAttr('required');
            }
        });


        $(document).on('change', '.leave_type', function () {
            var leave_type = $('#leave_type').select2('val');

                if (leave_type !== '') {
                   $('.leave_type_div').css('pointer-events', 'none');
                }

            if (leave_type == "273") {
                $('.worked_date').show();
                $('.leave_combo').attr('required', true);
            } else {
                $('.worked_date').hide();
                $('.leave_combo').removeAttr('required');
            }
        });


        // save function

        $(document).on('click', '.save_form', function () {

            const url = "{{ URL::to('leavesave') }}";
            const form = $('#leave');
            form.parsley().validate();

            if (!form.parsley().isValid()) {
                return;
            }

            const formData = form.serialize();
            var $btn = $(this);
            $btn.prop('disabled', true);
            $.post(url, formData, function (response) {
                if (response === 1) {
                    showCustomAlert('Leave Application Submitted','success');
                } else {
                    showCustomAlert('Leave Application Submitted','success');
                }

                $('.reset').trigger('click');
                setTimeout(() => location.reload(), 1000);
            }).fail(function (xhr, status, error) {
                console.error('Save failed:', error);
                showCustomAlert('Something went wrong. Please try again.','error');
            });
        });


        // OD	

        $(document).ready(function () {
            const dateFormat = "{{ \Session('j_date_format') ?? 'yy-mm-dd' }}";
            const timeFormat = "HH:mm:ss"; // 24-hour format with seconds

            // Initially disable od_end_date
            $(".od_end_date").prop("disabled", true).val("");

            var dateToday = new Date();
            dateToday.setDate(dateToday.getDate() - 5);



            $(document).on("focus", ".od_start_date", function () {
                $(this).datetimepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: dateFormat,
                    timeFormat: timeFormat,
                    controlType: 'select',
                    oneLine: true,
                    showSecond: true,
                    minDate: dateToday,

                    showAnim: "slideDown",
                    onClose: function (selectedDateTime) {
                        if (selectedDateTime) {
                            const startDate = $(this).datetimepicker("getDate");

                            // Enable od_end_date
                            $(".od_end_date").prop("disabled", false);

                            // Reinitialize od_end_date with updated minDate
                            $(".od_end_date").datetimepicker("destroy").datetimepicker({
                                changeMonth: true,
                                changeYear: true,
                                dateFormat: dateFormat,
                                timeFormat: timeFormat,
                                controlType: 'select',
                                oneLine: true,
                                showSecond: true,
                                minDate: startDate, // Disallow dates before start time
                                showAnim: "slideDown",
                                yearRange: "-25:+0"
                            });
                        }
                    }
                });
            });
        });

        // Calculate duration when dates change
        $(document).on("change", ".od_start_date, .od_end_date", function () {
            const startStr = $('.od_start_date').val();
            const endStr = $('.od_end_date').val();

            if (!startStr || !endStr) {
                $('.od_no_of_days').val('');
                return;
            }

            const startDate = new Date(startStr);
            const endDate = new Date(endStr);

            if (isNaN(startDate) || isNaN(endDate)) {
                $('.od_no_of_days').val('');
                showCustomAlert('Invalid date format', 'error');
                return;
            }

            // Duration check
            const diffMs = endDate - startDate;
            if (diffMs < 0) {
                $('.od_no_of_days').val('');
                $('.od_end_date').val('');
                showCustomAlert('End date/time cannot be earlier than start date/time', 'error');
                return;
            }

            const totalMinutes = Math.floor(diffMs / (1000 * 60));
            const hours = Math.floor(totalMinutes / 60);
            const minutes = totalMinutes % 60;

            const formatted = `${hours}.${minutes.toString().padStart(2, '0')}`;
            $('.od_no_of_days').val(formatted);
        });


    </script>


@endpush