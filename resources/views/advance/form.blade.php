@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Advance Request</h3>
    @include('layouts.breadcrumb')


    <?php $current_date = date("Y-m-d"); ?>
    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-primary text-white rounded-top-4">
        </div>
        <div class="card-body p-4">
            <form action="" id="advance" data-parsley-validate>
                <?php $data = \Session::get('data');
    if (isset($data[$pageMethod]['save'])) { ?>

                <input type="hidden" name="edit_id" id="edit_id" value="" />
                {{ csrf_field() }}

                <div class="row g-4">
                    <!-- Column 1 -->
                    <div class="col-md-4">
                        <?php    $emp_id = \Session::get('id'); ?>
                        <?php if($emp_id == 600 || $emp_id == 155){ ?>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> Employee
                            </label>
                            <select name="employee_id" id="employee_id" class="form-select select2 employee_id"
                                data-live-search="true">
                            </select>
                        </div>
                        <?php } else { ?>
                        <div class="mb-3 employee_list">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> Employee
                            </label>
                            <select name="employee_id" id="employee_id" class="form-select select2 employee_id"
                                data-live-search="true">
                            </select>
                        </div>
                        <?php } ?>
                        <div class="mb-3" style="pointer-events: none;">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> Advance Date
                            </label>
                            <input class="form-control advance_date datepicker advance_date" id="advance_date"
                                name="advance_date" required readonly value="{{ $current_date }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> Forwarded To
                            </label>
                            <div class="input-group">
                                <select id="forwarded_id" name="forwarded_id" class="form-select select2 forwarded_id"
                                    required></select>
                            </div>
                        </div>
                    </div>

                    <!-- Column 2 -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> Advance From
                            </label>
                            <select id="advance_from" name="advance_from" class="form-select select2 advance_from" required>
                                <option value="">--- Please Select --</option>
                                <option value="1">Loan (EMI)</option>
                                <option value="2">Advance (Salary)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> Effective Date
                            </label>
                            <input type="text" class="form-control effective_date" id="effective_date" name="effective_date"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> Amount
                            </label>
                            <input type="text" id="amount" name="amount" class="form-control amount" required>
                        </div>
                    </div>

                    <!-- Column 3 -->
                    <div class="col-md-4">
                        <div class="mb-3 emi_div" style="display:none;">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> EMI Month
                            </label>
                            <input type="text" id="emi" name="emi" class="form-control emi" required>
                        </div>

                        <div class="mb-3 emi_div" style="display:none;">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> EMI Amount
                            </label>
                            <input type="text" id="emi_amount" name="emi_amount" class="form-control emi_amount" readonly>
                        </div>

                        <div class="mb-3 emi_div" style="display:none;">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> Loan Document Upload
                            </label>
                            <input type="file" name="loan_document" id="loan_document" class="form-control loan_document">
                        </div>

                        <div class="mb-3 cheque_div" style="display:none;">
                            <label class="form-label fw-semibold">Cheque Number</label>
                            <input type="text" id="cheque_number" name="cheque_number" class="form-control cheque_number">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> Advance Reason
                            </label>
                            <input type="text" id="advance_reason" name="advance_reason" class="form-control advance_reason"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> Mode
                            </label>
                            <select id="mode" name="mode" class="form-select select2 mode" required>
                                <option value="">--- Please Select --</option>
                                <option value="1">Cash</option>
                                <option value="2">Cheque</option>
                                <option value="3">On-Line</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="text-center mt-4">
                    <button type="button" class="btn btn-success px-4 save_form">
                        Save
                    </button>
                </div>

                <?php } ?>
            </form>
        </div>
    </div>


    <div class="card shadow-lg rounded-4 border-0">
        <div class="container mt-4 table-responsive">
            <table id="AdvTbl" class="table table-bordered table-striped w-100">
                <thead>
                    <tr class="table-warning">
                        <th>employee_id</th>
                        <th style="didsplay:none">advance reason</th>
                        <th>Employee Name</th>
                        <th>Advance Date</th>
                        <th>Effective Date</th>
                        <th style="didsplay:none">Mode</th>
                        <th>Amount</th>
                        <th>Paid Amount</th>
                        <th>Remaining Amount</th>
                        <th>Forward To</th>
                        <th>Status</th>
                        <th style="didsplay:none">Status</th>
                        <th style="didsplay:none">Reporting Id</th>
                        <th>Actions</th>

                    </tr>

                    <tr class="table-info">


                        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                        </th>
                        <th style="didsplay:none"><input type="text" class="form-control form-control-sm column-search"
                                placeholder="Search" /></th>
                        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                        </th>
                        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                        </th>
                        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                        </th>
                        <th style="didsplay:none"><input type="text" class="form-control form-control-sm column-search"
                                placeholder="Search" /></th>
                        <th style="didsplay:none"><input type="text" class="form-control form-control-sm column-search"
                                placeholder="Search" /></th>
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
                        <th style="didsplay:none"><input type="text" class="form-control form-control-sm column-search"
                                placeholder="Search" /></th>
                        <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" />
                        </th>



                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>



@endsection
@push('scripts')

    <script>


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


        var reporting_id = '{{$reporting_id}}';
        var logged_user = '{{$logged_user}}';
        var condition = "employee_id!=" + logged_user;

        var paid_status = '{{$paid_status}}';
        var remaining_amount = '{{$remaining_amount}}';
        var approved_status = '{{$approved_status}}';
        $('.employee_list').css('pointer-events','none');    

        // Forwarded ID (Exclude Logged User)
        loadDropdown(
            "#forwarded_id",
            "{{ URL::to('jcomboform') }}?table=hr_employee_t:employee_id:employee_number|first_name&parent=" + encodeURIComponent(condition) + "&order_by=employee_id asc",
            reporting_id,
            "-- Select Forwarded Employee --"
        );

        // Logged-in User
        loadDropdown(
            "#employee_id",
            "{{ URL::to('jcomboform') }}?table=hr_employee_t:employee_id:employee_number|first_name",
            logged_user,
            "-- Select Employee --"
        );

        // on change 
        $(document).on('change', '.advance_from', function () {
            var advance_from = $(this).select2('val');
            if (advance_from == "1") {
                $('.emi_div').css("display", "block");
                $('.emi').attr("required", "true");
                $('.loan_document').attr("required", "true");
            }
            else {
                $('.emi_div').css("display", "none");
                $('.emi').removeAttr("required", "false");
                $('.loan_document').removeAttr("required", "false");

            }
        });

        $(document).on('change', '.mode', function () {
            var mode = $(this).select2('val');
            if (mode != "1") {
                $('.cheque_div').css("display", "block");

            }
            else {
                $('.cheque_div').css("display", "none");

            }
        });

        $(document).on('change', '.emi,.amount', function (ev) {
            var emi = $('.emi').val();
            var amount = $('.amount').val();
            if (amount != "" && emi != '') {
                var emi_amount = amount / emi;
                $('.emi_amount').val(emi_amount);
                $(".emi_amount").parsley().destroy();
            }
            else {
                $('.emi_amount').val('');
                $('.emi_amount').parsley().validate();
            }
        });

        $(document).on('keypress', '.amount,.emi', function (ev) {

            var regex = new RegExp("^[0-9.]+$");
            var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
            if (regex.test(str)) {
                return true;
            }
            ev.preventDefault();
            return false;
        });


        // table data
        $(document).ready(function () {
            var table = $('#AdvTbl').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('employeeadvancegriddata') }}",
                columns: [

                    { data: 'employee_id', name: 'm_advances_t.employee_id', visible: false },
                    { data: 'advance_reason', name: 'm_advances_t.advance_reason' },
                    { data: 'first_name', name: 'm_advances_t.first_name' },
                    { data: 'advance_date', name: 'm_advances_t.advance_date' },
                    { data: 'effective_date', name: 'm_advances_t.effective_date' },
                    { data: 'mode', name: 'm_advances_t.mode', visible: false },
                    { data: 'amount', name: 'm_advances_t.amount' },
                    { data: 'paid_amount', name: 'm_advances_t.paid_amount' },
                    { data: 'remaining_amount', name: 'm_advances_t.remaining_amount' },
                    { data: 'forwarded_id', name: 'm_advances_t.forwarded_id' },
                    { data: 'approved_status_name', name: 'm_advances_t.approved_status_name' },
                    { data: 'approved_status', name: 'm_advances_t.approved_status', visible: false },
                    { data: 'approved_id', name: 'm_advances_t.approved_id', visible: false },

                    {
                        data: 'advance_id',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            let buttons = '';
                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                                buttons += `
                    <button type="button" class="btn btn-sm btn-primary edit-btn"
                      data-id="${row.advance_id}"
                      data-advance="${row.mode}"
                      data-reason="${row.advance_reason}"
                      data-name="${row.employee_id}"
                      data-report="${row.forwarded_id}"
                      data-advdate="${row.advance_date}"
                      data-efdate="${row.effective_date}"
                      data-amount="${row.amount}">
                      <i class="bi bi-pencil"></i>
                    </button>`;
                            }
                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                                buttons += `
                        <button type="button" class="btn btn-sm btn-danger delete-btn"
                          data-id="${row.advance_id}">
                          <i class="bi bi-trash"></i>
                        </button>`;
                            }
                            return buttons;
                        }

                    }
                ]
            });


            $('#AdvTbl thead').on('keyup change', '.column-search', function () {
                let index = $(this).closest('th').index();
                table.column(index).search(this.value).draw();
            });
        });


        //	save
        /***** save function start **/
        $(document).on('click', '.save_form', function () {



            var url = "{{URL::to('advancesave')}}";
            var form = $('#advance');
            form.parsley().validate();

            if (form.parsley().isValid()) {
                if (paid_status == 0) {
                    setTimeout(function () {
                        showCustomAlert('Already Got a Advance .Pending  Amount is ' + remaining_amount, 'Warning');
                    }, 1500);
                }
                else if (paid_status != 0) {

                    var data = $('#advance').serialize();
                    var form_data = new FormData(document.getElementById('advance'));
                    var $btn = $(this);
                    $btn.prop('disabled', true);
                    $.ajax({

                        url: "{{URL::to('advancesave')}}",
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
                    }).done(function (data1) {
                        if (data1 == 1) {

                            showCustomAlert('Advance details Updated  Successfully', 'success');

                            location.reload();
                            $('#advance_date').val('');
                            $('#effective_date').val('');
                            $('#loan_document').val('');
                            $('#mode').select2('val', [' ']);
                            $('#advance_from').select2('val', [' ']);
                            $('#amount').val('');
                            $('#cheque_number').val('');
                            $('#emi').val('');
                            $('#advance_reason').val('');
                            $('.emi_div').css("display", "none");
                            $('.emi').removeAttr("required", "false");
                            $('.loan_document').removeAttr("required", "false");
                            $('.cheque_div').css("display", "none");

                        }
                        else {
                            showCustomAlert('Advance details Saved Successfully', 'success');

                            location.reload();
                            $('#advance_date').val('');
                            $('#effective_date').val('');
                            $('#loan_document').val('');
                            $('#advance_from').select2('val', [' ']);
                            $('#mode').select2('val', [' ']);
                            $('#edit_id').val('');
                            $('#amount').val('');
                            $('#emi').val('');
                            $('#cheque_number').val('');
                            $('#advance_reason').val('');
                            $('.emi_div').css("display", "none");
                            $('.emi').removeAttr("required", "false");
                            $('.loan_document').removeAttr("required", "false");
                            $('.cheque_div').css("display", "none");

                        }
                    });
                }

            }
        });


        // delete

        let deleteId = null;

        $(document).on('click', '.delete-btn', function () {
            deleteId = $(this).data('id');
            $('#globalDeleteModal').modal('show');
        });

        $('#globalConfirmDeleteBtn').on('click', function () {
            if (deleteId) {
                $.ajax({
                    url: "{{ url('employeeadvance/delete') }}/" + deleteId,
                    type: "GET",
                    success: function (data) {
                        $('#globalDeleteModal').modal('hide');
                        if (data == 1) {

                            showCustomAlert('Cannot Be Delete.Which is in Approved State or Used in Some Where', 'error');

                        }
                        else if (data == 2) {
                            showCustomAlert('Advance Details Deleted Successfully', 'success');

                        }
                        $('#AdvTbl').DataTable().ajax.reload();

                    },
                    error: function (xhr) {
                        $('#globalDeleteModal').modal('hide');
                        const errorMsg = xhr.responseJSON?.message || 'Delete failed.';
                        showCustomAlert(errorMsg, 'error');
                    }
                });
            }
        });



        // date picker
        var dateToday = new Date();

        $("#advance_date").datepicker({
            changeMonth: true,
            dateFormat: "yy-mm-dd",
            changeYear: true,
            minDate: -30,
            maxDate: +30,
            onClose: function () {
                $(this).parsley().validate();
            }

        }).attr('readonly', 'readonly');

        $(".effective_date").datepicker({
            changeMonth: true,
            dateFormat: "yy-mm-dd",
            changeYear: true,
            minDate: dateToday,
            maxDate: +60,
            onClose: function () {
                $(this).parsley().validate();
            }

        }).attr('readonly', 'readonly');


        // edit function


        // edit function
        $(document).on('click', '.edit-btn', function () {

            const id = $(this).data('id');
            const advance = $(this).data('advance');
            const reason = $(this).data('reason');
            const name = $(this).data('name');
            const report = $(this).data('report');
            const advdate = $(this).data('advdate');
            const efdate = $(this).data('efdate');
            const amount = $(this).data('amount');



            const active = $(this).data('active');

            // Fill form fields
            $('input[name="edit_id"]').val(id);
            $('input[name="advance_date"]').val(advdate);
            $('input[name="effective_date"]').val(efdate);
            $('input[name="amount"]').val(amount);
            $('input[name="advance_reason"]').val(reason);

            // For select2 fields, use .val().trigger('change')

            $('select[name="employee_id"]').val(name).trigger('change');
            $('select[name="forwarded_id"]').val(report).trigger('change');
            $('select[name="advance_from"]').val(advance).trigger('change');
            $('select[name="mode"]').val(advance).trigger('change');


        });




    </script>


@endpush