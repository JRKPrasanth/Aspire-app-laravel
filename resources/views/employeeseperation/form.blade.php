@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Seperation Request</h3>
    @include('layouts.breadcrumb')


    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body">
            <form action="" id="save">
                <?php $data = \Session::get('data');
    if (isset($data[$pageMethod]['save'])) { ?>

                {{ csrf_field() }}
                <div class="row g-4">
                    <!-- Column 1 -->
                    <div class="col-md-4">
                        <!-- Employee Name -->
                        <div class="mb-3 row">
                            <label class="col-md-5 col-form-label ">
                                <span class="text-danger">*</span> Employee Name
                            </label>
                            <div class="col-md-8 pe-0" style="pointer-events: none;">
                                <input type="hidden" name="edit_id" class="edit_id" id="edit_id">
                                <select name="employee_id" id="employee_id" class="form-select select2" required>
                                    {!! $employee !!}
                                </select>
                                <span class="badge bg-danger dup_name d-none"></span>
                            </div>
                        </div>

                        <!-- Notice Period -->
                        <div class="mb-3 row">
                            <label class="col-md-5 col-form-label ">
                                <span class="text-danger">*</span> Notice Period
                            </label>
                            <div class="col-md-8 pe-0">
                                <select name="notice_period" id="notice_period" class="form-select select2 notice_period"
                                    required></select>
                            </div>
                        </div>
                    </div>

                    <!-- Column 2 -->
                    <div class="col-md-4">
                        <!-- Relieve Date -->
                        <div class="mb-3 row" style="pointer-events: none;">
                            <label class="col-md-5 col-form-label ">
                                <span class="text-danger">*</span> Relieve Date
                            </label>
                            <div class="col-md-8">
                                <input class="form-control releive_date" id="releive_date" name="releive_date" type="text"
                                    required>
                            </div>
                        </div>

                        <!-- Reporting -->
                        <div class="mb-3 row">
                            <label class="col-md-5 col-form-label ">
                                <span class="text-danger">*</span> Reporting
                            </label>
                            <div class="col-md-8 pe-0">
                                <select name="reporting_id" id="reporting_id" class="form-select select2 reporting_id"
                                    required>
                                    {!! $reporting !!}
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Column 3 -->
                    <div class="col-md-4">
                        <!-- Relieve Reason -->
                        <div class="mb-3 row">
                            <label class="col-md-5 col-form-label ">
                                <span class="text-danger">*</span> Relieve Reason
                            </label>
                            <div class="col-md-8">
                                <input type="text" name="releive_reason" id="releive_reason" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="row mt-4">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-success save_form px-4">
                            Save
                        </button>
                    </div>
                </div>
                <?php } ?>
            </form>
        </div>
    </div>



    <div class="card shadow-lg rounded-4 border-0">
        <div class="container mt-4">
            <table id="sepTable" class="table table-bordered table-striped w-100">
                <thead>
                    <tr class="table-warning">
                        <th>Name</th>
                        <th>Employee Number</th>
                        <th>Reporting Name</th>
                        <th>Reporting Employee Number</th>
                        <th>Notice Period</th>
                        <th>Relive Date</th>
                        <th>Relive Reason</th>
                        <th>Relive Reason by Reporting</th>
                        <th style="display:none;"></th>
                        <th style="display:none;"></th>
                        <th>Actions</th>
                    </tr>
                    <tr class="table-info">
                        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                        </th>
                        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                        </th>
                        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                        </th>
                        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                        </th>
                        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                        </th>
                        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                        </th>
                        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                        </th>
                        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                        </th>
                        <th style="display:none;"></th>
                        <th style="display:none;"></th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    {{-- DataTable will populate via AJAX --}}
                </tbody>
            </table>
        </div>
    </div>

@endsection
@push('scripts')
    <script>

        $(document).ready(function () {
            var table = $('#sepTable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                scrollY: "50vh",
                ajax: "{{ route('employeeseparationdata') }}",
                columns: [
                    { data: 'first_name', name: 'first_name' },
                    { data: 'employee_number', name: 'employee_number' },
                    { data: 'rel_name', name: 'rel_name' },
                    { data: 'rel_number', name: 'rel_number' },
                    { data: 'lookup_code', name: 'lookup_code' },
                    { data: 'relieve_date', name: 'relieve_date' },
                    { data: 'relieve_reason', name: 'relieve_reason' },
                    { data: 'relieve_status_approve', name: 'relieve_status_approve' },
                    { data: 'notice_period', name: 'notice_period', visible: false },
                    { data: 'relieving_by_hr', name: 'relieving_by_hr', visible: false },
                    {
                        data: 'relieve_id',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            let buttons = '';
                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                                buttons += `
                <button type="button" class="btn btn-sm btn-primary edit-btn"
                  data-id="${row.relieve_id}"
                  data-rdate="${row.relieve_date}"
                  data-reason="${row.relieve_reason}"
                  data-notice="${row.notice_period}"
                  data-releive="${row.relieving_by_hr}">
                  <i class="bi bi-pencil"></i>
                </button>`;
                            }
                            return buttons;
                        }
                    }
                ]
            });



            $('#sepTable thead').on('keyup change', '.column-search', function () {
                let index = $(this).closest('th').index();
                table.column(index).search(this.value).draw();
            });
        });

        // notice period	
        var condition1 = ' and lookup_type="noticeperiod"';
        var url = "{{ URL::to('jcomboform1') }}?table=a_lookuplines_t:lookuplines_id:lookup_code&parent=" + encodeURIComponent(condition1) + "&order_by=lookuplines_id asc";

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

                $('#notice_period').html('<option value="">-- Select Notice Period --</option>');

                $.each(data, function (i, item) {
                    let selected = item.val == "{{ $row->notice_period ?? '' }}" ? 'selected' : '';
                    $('#notice_period').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
                });

                $('#notice_period').trigger('change.select2');
            }
        });

        //edit

        $(document).on('click', '.edit-btn', function () {

            const id = $(this).data('id');
            const rdate = $(this).data('rdate');
            const reason = $(this).data('reason');
            const notice = $(this).data('notice');
            const releive = $(this).data('releive');
            const active = $(this).data('active'); // Added missing active variable

            // Fill form fields
            $('input[name="relieve_id"]').val(id);
            $('input[name="releive_reason"]').val(reason);

            if ($.trim(rdate) !== '' && rdate !== '0000-00-00') {
                const parsedDate = $.datepicker.parseDate("yy-mm-dd", rdate);
                $('#releive_date').val(
                    $.datepicker.formatDate("{{ \Session::get('j_date_format') }}", parsedDate)
                );
            } else {
                $('#releive_date').val('');
            }

            // For select2 fields, use .val().trigger('change')
            $('select[name="notice_period"]').val(notice).trigger('change');

        });


        function duplicate_validate() {

            var emp_id = $("#employee_id").select2("val");
            $.ajax({
                cache: false,
                url: 'relivecheck', //this is your uri
                type: 'GET',
                dataType: 'json',
                async: false,
                data: { emp_id: emp_id },
                success: function (response) {
                                    if (response == 1)
                {
                    $('.dup_name')
                        .html('Already Relieve Requested')
                        .removeClass('d-none')
                        .addClass('d-block');

                       $("#employee_id").select2('val', ['']);
                        $("#employee_id").parsley().validate();
                    dup_chk = false;
                }
                    else if(response == 0)
                    {
                        var html = "";
                        $('.dup_name').hide();
                        dup_chk = true;

                    }
                },
                error: function (xhr, resp, text) {
                    console.log(xhr, resp, text);
                }
            });
        }

        var dup_chk_1 = true;
        function check_reassign() {
            var emp_id = $("#employee_id").select2("val");
            $.ajax({
                cache: false,
                url: "{{URL::to('relievecheckreassign')}}", //this is your uri
                type: 'GET',
                dataType: 'json',
                async: false,
                data: { emp_id: emp_id },
                success: function (response) {
                    
                    if (response == 1) {
                        showCustomAlert('Reassign the Employee Reporting and then Request', 'warning');
                        $("#employee_id").select2('val', ['']);
                        $("#employee_id").parsley().validate();
                        dup_chk_1 = false;
                    }
                    else if (response == 0) {
                        var html = "";

                        dup_chk_1 = true;
                    }
                },
                error: function (xhr, resp, text) {
                    console.log(xhr, resp, text);
                }
            });
        }


        /** save function */
        $(document).on('click', '.save_form', function () {
            var form = $('#save');
            form.parsley().validate();
            check_reassign();
            duplicate_validate();

            if (form.parsley().isValid() && dup_chk && dup_chk_1) {
                var $btn = $(this);            
	            $btn.prop('disabled', true);
                var myDate = $('#releive_date').val();
                var parsedDate = $.datepicker.parseDate("{{\Session::get('j_date_format')}}", myDate);
                $('#releive_date').val($.datepicker.formatDate("yy-mm-dd", parsedDate));

                var data = $("#save").serialize();
                var url = "{{URL::to('/save/releiverequest')}}";

                $.post(url, data, function (data) {
                    if (data == 1) {
                        showCustomAlert('Seperation Request Saved Successfully','success');
                        setTimeout(function () {
                            var url = "{{URL::to('separationrequest')}}";
                            window.location.href = url;
                        }, 100);
                    }
                    if (data == 2) {
                        showCustomAlert('Seperation Request Updated Successfully','success');
                        setTimeout(function () {
                            var url = "{{URL::to('separationrequest')}}";
                            window.location.href = url;
                        }, 100);
                    }
                });
            }
        });






        //relieve date calculation
        $(document).on('change', '#notice_period', function () {
            const v = $('#notice_period').val();
            let d = new Date();

            switch (v) {
                case '167': /* today */ break;
                case '168': d = addDays(d, 15); break;
                case '169': d = addMonths(d, 1); break;
                case '170': d = addDays(d, 45); break;
                case '175': d = addMonths(d, 1); d = addDays(d, 15); break;
                case '171': d = addMonths(d, 2); break;
                case '172': d = addMonths(d, 3); break;
                default: $('#releive_date').val(''); return;
            }

            $('#releive_date').val($.datepicker.formatDate("{{ \Session::get('j_date_format') ?? 'dd-mm-yy' }}", d));
            if ($("#releive_date").parsley) {
                $("#releive_date").parsley().reset(); // or destroy if you really need to
            }
        });

        function addDays(date, days) {
            const d = new Date(date);
            d.setDate(d.getDate() + days);
            return d;
        }
        function addMonths(date, months) {
            const d = new Date(date);
            d.setMonth(d.getMonth() + months);
            return d;
        }


    </script>

@endpush