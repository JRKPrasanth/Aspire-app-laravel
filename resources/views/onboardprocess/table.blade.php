@extends('layouts.header')
@section('content')
    <h3 class="text-danger">On Board Process</h3>
    @include('layouts.breadcrumb')
    <style>
        .select2-container--open {
            z-index: 200000 !important;
        }
    </style>

    <div class="card shadow-lg rounded-4 border-0">
        <div class="container mt-4 table-responsive">
            <table id="onBoardTbl" class="table table-bordered table-striped">
                <thead>
                    <tr class="table-warning">
                        <th>Acceptance Letter</th>
                        <th>Actions</th>
                        <th>Candidate Name</th>
                        <th>Job Description</th>
                        <th>Interview Date</th>
                        <th>Date of Joining</th>
                        <th>Salary Per Annum</th>

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
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>


    <!-- Employee Convert Modal -->

    <div class="modal fade" id="converttoemployee" tabindex="-1">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-4">

                <!-- Modal Header -->
                <div class="modal-header bg-primary text-white rounded-top">
                    <h5 class="modal-title">Convert to Employee</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <!-- Hidden Inputs -->
                <input type="hidden" id="candidate_id" name="candidate_id">
                <input type="hidden" id="interview_id" name="interview_id">

                <!-- Modal Body -->
                <div class="modal-body">

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label fw-semibold">
                            <span class="text-danger">*</span> Employee Number:
                        </label>

                        <div class="col-sm-8">
                            <input type="text" name="employee_number" id="employee_number"
                                class="form-control form-control-lg employee_number" placeholder="Enter Employee Number"
                                required>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button class="btn btn-success px-4 py-2 fw-semibold" id="employee_convert">
                            <i class="fa fa-check-circle me-1"></i> Convert
                        </button>
                    </div>

                </div>

            </div>
        </div>
    </div>


    <!-- offer Modal Employee-->

    {{ csrf_field() }}

    <div class="modal fade" id="myModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content shadow-lg border-0">
                <form id="updatepayproposal" data-parsley-validate>
                    <!-- Modal Header -->
                    <div class="modal-header bg-primary text-white rounded-top">
                        <h5 class="modal-title">Generate Offer Letter</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body" style="height: 350px;overflow-y: auto;">
                        <div class="row g-4">

                            <!-- Left Column -->
                            <div class="col-md-6">

                                <div class="mb-3 row">
                                    <label class="col-sm-5 col-form-label">
                                        <span class="text-danger">*</span> Offer Letter No:
                                    </label>
                                    <div class="col-sm-7">
                                        <input type="text" id="offer_letter_no" name="offer_letter_no"
                                            class="form-control offer_letter_no">
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-5 col-form-label">
                                        <span class="text-danger">*</span> Candidate Name:
                                    </label>
                                    <div class="col-sm-7">
                                        <input type="text" id="name_of_the_candidate" name="name_of_the_candidate"
                                            class="form-control name_of_the_candidate">
                                        <input type="hidden" id="candidate_id1" name="candidate_id1">
                                        <input type="hidden" id="interview_id1" name="interview_id1">
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-5 col-form-label">Address:</label>
                                    <div class="col-sm-7">
                                        <textarea id="address" name="address" class="form-control address"
                                            rows="3"></textarea>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-5 col-form-label">Department:</label>
                                    <div class="col-sm-7">
                                        <select id="department_id" name="department_id"
                                            class="form-select select2 department_id" required></select>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-5 col-form-label"><span class="text-danger">*</span>
                                        Position:</label>
                                    <div class="col-sm-7">
                                        <select id="job_title" name="job_title" class="form-select select2 job_title"
                                            required></select>
                                    </div>
                                </div>

                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6">

                                <div class="mb-3 row">
                                    <label class="col-sm-5 col-form-label"><span class="text-danger">*</span> Grade:</label>
                                    <div class="col-sm-7">
                                        <select id="position" name="position" class="form-select select2 position"
                                            required></select>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-5 col-form-label"><span class="text-danger">*</span>
                                        Location:</label>
                                    <div class="col-sm-7">
                                        <select id="location" name="location" class="form-select select2 location"
                                            required></select>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-5 col-form-label"><span class="text-danger">*</span> Date of
                                        Joining:</label>
                                    <div class="col-sm-7">
                                        <input type="text" id="date_of_joining" name="date_of_joining"
                                            class="form-control date_of_joining" required>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-5 col-form-label"><span class="text-danger">*</span> Employee
                                        Type:</label>
                                    <div class="col-sm-7">
                                        <select id="employee_type" name="employee_type"
                                            class="form-select select2 employee_type" required></select>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-5 col-form-label"><span class="text-danger">*</span> Group
                                        Type:</label>
                                    <div class="col-sm-7">
                                        <select id="group_type" name="group_type" class="form-select select2 group_type"
                                            required></select>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-5 col-form-label"><span class="text-danger">*</span> HQ
                                        Class:</label>
                                    <div class="col-sm-7">
                                        <select id="class_of_hq" name="class_of_hq"
                                            class="form-select select2 class_of_hq"></select>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Pay Proposal -->
                        <div class="row g-4">
                            <div class="col-md-6">
                                <h5 class="fw-bold text-primary mb-3">Pay Proposal</h5>

                                <div class="mb-3 row">
                                    <label class="col-sm-5 col-form-label">Basic:</label>
                                    <div class="col-sm-7">
                                        <input type="text" id="basic" name="basic" class="form-control basic">
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-5 col-form-label">DA:</label>
                                    <div class="col-sm-7">
                                        <input type="text" id="da" name="da" class="form-control da" readonly>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-5 col-form-label">HRA:</label>
                                    <div class="col-sm-7">
                                        <input type="text" id="hra" name="hra" class="form-control hra" readonly>
                                    </div>
                                </div>

                                <div class="allowance"></div>

                                <div class="mb-3 row">
                                    <label class="col-sm-5 col-form-label">Annual Allowance:</label>
                                    <div class="col-sm-7">
                                        <input type="text" id="annual_allowance" name="annual_allowance"
                                            class="form-control annual_allowance">
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-5 col-form-label">PF Amount:</label>
                                    <div class="col-sm-7">
                                        <input type="text" id="pf_amount" name="pf_amount" class="form-control pf_amount">
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-5 col-form-label">ESI Amount:</label>
                                    <div class="col-sm-7">
                                        <input type="text" id="esi_amount" name="esi_amount"
                                            class="form-control esi_amount">
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-5 col-form-label">PT Amount:</label>
                                    <div class="col-sm-7">
                                        <input type="text" id="pt_amount" name="pt_amount" class="form-control pt_amount">
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-6">
                                <h5 class="fw-bold text-primary mb-3">Deductions</h5>

                                <!-- ESI -->
                                <div class="mb-3 row">
                                    <label class="col-sm-5 col-form-label">ESI:</label>
                                    <div class="col-sm-7 d-flex gap-3">
                                        <div>
                                            <input type="radio" name="esi" value="1"> Yes
                                        </div>
                                        <div>
                                            <input type="radio" name="esi" value="0" checked> No
                                        </div>
                                    </div>
                                </div>

                                <!-- PF -->
                                <div class="mb-3 row">
                                    <label class="col-sm-5 col-form-label">PF:</label>
                                    <div class="col-sm-7 d-flex gap-3">
                                        <div>
                                            <input type="radio" name="pf" value="1"> Yes
                                        </div>
                                        <div>
                                            <input type="radio" name="pf" value="0" checked> No
                                        </div>
                                    </div>
                                </div>

                                <!-- PT -->
                                <div class="mb-3 row">
                                    <label class="col-sm-5 col-form-label">Professional Tax:</label>
                                    <div class="col-sm-7 d-flex gap-3">
                                        <div>
                                            <input type="radio" name="pt" value="1"> Yes
                                        </div>
                                        <div>
                                            <input type="radio" name="pt" value="0" checked> No
                                        </div>
                                    </div>
                                </div>

                                <!-- Other Deductions -->
                                <div class="mb-3 row">
                                    <label class="col-sm-5 col-form-label">Gratuity:</label>
                                    <div class="col-sm-7">
                                        <input type="text" id="gratuity" name="gratuity" class="form-control gratuity">
                                    </div>
                                </div>

                                <!-- Gross / Net -->
                                <div class="mb-3 row">
                                    <label class="col-sm-5 col-form-label"><span class="text-danger">*</span> Gross
                                        Pay:</label>
                                    <div class="col-sm-7">
                                        <input type="text" id="gross_pay" name="gross_pay" class="form-control gross_pay"
                                            readonly required>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-5 col-form-label"><span class="text-danger">*</span> Net
                                        Pay:</label>
                                    <div class="col-sm-7">
                                        <input type="text" id="net_pay" name="net_pay" class="form-control net_pay" readonly
                                            required>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label class="col-sm-5 col-form-label"><span class="text-danger">*</span> CTC
                                        Pay:</label>
                                    <div class="col-sm-7">
                                        <input type="text" id="ctc_pay" name="ctc_pay" class="form-control ctc_pay" readonly
                                            required>
                                    </div>
                                </div>

                                <!-- Mail Send -->
                                <div class="mb-3 row">
                                    <label class="col-sm-5 col-form-label"><span class="text-danger">*</span> Mail
                                        Send:</label>
                                    <div class="col-sm-7 d-flex gap-3">
                                        <div>
                                            <input type="radio" name="mail_send" value="1"> Yes
                                        </div>
                                        <div>
                                            <input type="radio" name="mail_send" value="2"> No
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success save_popup px-4 me-1">Save</button>
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    </div>

            </div>
            </form>

            <!-- Hidden Inputs -->
            <input type="hidden" id="h_esi_employeer_contribute">
            <input type="hidden" id="h_esi_company_contribute">
            <input type="hidden" id="h_pf_employeer_contribute">
            <input type="hidden" id="h_pf_company_contribute">
            <input type="hidden" id="h_pt_from_contribute">
            <input type="hidden" id="h_pt_to_contribute">
            <input type="hidden" id="h_pt_deduction">
            <input type="hidden" id="h_esi_from_contribute">
            <input type="hidden" id="h_esi_to_contribute">
            <input type="hidden" id="h_pf_from_contribute">
            <input type="hidden" id="h_pf_to_contribute">
            <input type="hidden" id="hra_per">
            <input type="hidden" id="da_per">

        </div>
    </div>


@endsection
@push('scripts')


    <script>


        // data table funcrion	
       $(document).ready(function () {
    var table = $('#onBoardTbl').DataTable({
        processing: true,
        serverSide: true,
        order: [[4, 'desc']],
        ajax: "{{ route('onboardlistdata') }}",
        columns: [
    // ✅ Acceptance Letter column
                    {
                        data: 'acceptance_status',   // ✅ use acceptance_status
                        name: 'acceptance_status',
                        orderable: false,
                        searchable: true,
                        className: 'text-center',
                        render: function (data, type, row) {

                            let yesChecked = data == 1 ? 'checked' : '';
                            let noChecked = data == 0 ? 'checked' : '';

                            return `
                    <div class="d-flex justify-content-center gap-2">
                        <div class="form-check">
                            <input class="form-check-input acceptance_letter"
                                type="radio"
                                name="acceptance_${row.interview_id}"
                                value="1"
                                data-id="${row.interview_id}"
                                ${yesChecked}>
                            <label class="form-check-label">Yes</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input acceptance_letter"
                                type="radio"
                                name="acceptance_${row.interview_id}"
                                value="0"
                                data-id="${row.interview_id}"
                                ${noChecked}>
                            <label class="form-check-label">No</label>
                        </div>
                    </div>`;
                        }
                    },

                    {
                        data: 'interview_id',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '140px',
                        render: function (data, type, row) {

                            let buttons = '';

                            buttons += `
                        <button class="btn btn-sm btn-primary create" data-id="${row.interview_id}"
                                data-bs-toggle="tooltip" 
                                data-bs-placement="top" 
                                title="Create Offer Letter" data-action="createoffer">
                          <i class="bi bi-envelope-paper"></i>
                        </button>`;


                            buttons += `
                        <button class="btn btn-sm btn-warning letter action view" data-id="${row.interview_id}"
                                data-bs-toggle="tooltip" 
                                data-bs-placement="top" 
                                title="Offer Letter" data-action="viewoffer">
                          <i class="bi bi-file-earmark-diff"></i>
                        </button>`;


                            buttons += `
                        <button class="btn btn-sm btn-primary ctc action costsheet" data-id="${row.interview_id}"
                                data-bs-toggle="tooltip" 
                                data-bs-placement="top" 
                                title="Cost Sheet" data-action="costsheet" >
                         <i class="bi bi-file-earmark-spreadsheet"></i>
                        </button>`;


                            buttons += `
                        <button class="btn btn-sm btn-secondary letter action appoinments" data-id="${row.interview_id}"
                                data-bs-toggle="tooltip" 
                                data-bs-placement="top" 
                                title="Appoinment Letter" data-action="viewappointment">
                          <i class="bi bi-envelope-plus-fill"></i>
                        </button>`;


                            buttons += `
                        <button class="btn btn-sm btn-success letter converts mt-2" data-id="${row.interview_id}"
                                data-rid="${row.interview_id}"
                                data-bs-toggle="tooltip" 
                                data-bs-placement="top" 
                                title="Convert Employee" data-action="convertemployee">
                          <i class="bi bi-person-plus-fill"></i>
                        </button>`;



                            return buttons;
                        }
                    },

            { data: 'name_of_the_candidate', name: 'name_of_the_candidate' },
            { data: 'description_name', name: 'description_name' },
            { data: 'interview_date', name: 'interview_date' },
            { data: 'date_of_joining', name: 'date_of_joining' },
            { data: 'salary_annum', name: 'salary_annum' },
            { data: 'acceptance_status', name: 'acceptance_status', visible: false },
            { data: 'approve_status', name: 'approve_status', visible: false },
            { data: 'employee_status', name: 'employee_status', visible: false },
            { data: 'offer_status', name: 'offer_status', visible: false }
        ],

        // set button states row-wise after every draw
        rowCallback: function (row, data) {
            updateRowActions($(row), data);
        }
    });

    // Individual column search
    $('#onBoardTbl thead').on('keyup change', '.column-search', function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
    });


    function updateRowActions($row, rowData) {
        let offer_status = parseInt(rowData.offer_status) || 0;
        let employee_status = parseInt(rowData.employee_status) || 0;
        let approve_status = parseInt(rowData.approve_status) || 0;
        let acceptance_status = parseInt(rowData.acceptance_status) || 0;

        let $create = $row.find('.create');
        let $view = $row.find('.view');
        let $costsheet = $row.find('.costsheet');
        let $appointment = $row.find('.appoinments');
        let $convert = $row.find('.converts');
        let $acceptance = $row.find('.acceptance_letter');

        // Offer letter logic
        if (offer_status !== 1) {
            $create.prop('disabled', false);
            $view.prop('disabled', true);
            $costsheet.prop('disabled', true);
            $acceptance.prop('disabled', true);
        } else {
            $create.prop('disabled', true);
            $view.prop('disabled', false);
            $costsheet.prop('disabled', false);
            $acceptance.prop('disabled', false);
        }

        // Appointment letter logic
        if (offer_status === 1 && acceptance_status === 1) {
            $appointment.prop('disabled', false);
        } else {
            $appointment.prop('disabled', true);
        }

        // Acceptance approval logic
        if (offer_status === 1 && acceptance_status === 1 && approve_status === 1) {
            $acceptance.filter('[value="1"]').prop('checked', true);
            $acceptance.filter('[value="0"]').prop('disabled', true);
        } else {
            $acceptance.prop('disabled', false);
        }

        // Convert employee logic
        if (offer_status === 1 && acceptance_status === 1 && approve_status === 0 && employee_status === 0) {
            $convert.html('Convert To Employee');
            $convert.prop('disabled', false);
        } else if (offer_status === 1 && acceptance_status === 1 && approve_status === 1 && employee_status === 1) {
            $convert.html('Converted To Employee');
            $convert.prop('disabled', true);
        } else {
            $convert.prop('disabled', true);
        }
    }
});



        $(document).on('click', '.acceptance_letter', function () {

            var result = $(this).val()
            var interview_id = $(this).data('id');

            var url = "{{ URL::to('acceptanceletter') }}/" + result + "/" + interview_id;
            $.get(url, function (data) {
                if (data == 1) {
                    showCustomAlert('Acceptance Accepted Successfully', 'success');
                    location.reload();
                }
                else if (data == 2) {
                    showCustomAlert('Updated Failed', 'error');
                    location.reload();
                }
            });


        });



        $('#employee_type').on('change', function () {
            var emp_type = $(this).val();
            if (emp_type != '') {
                $('.class_of_hq').removeAttr('required');
                if (emp_type == '231') {
                    $('.class_of_hq').attr('required', true);
                } else {
                    $('.class_of_hq').removeAttr('required');
                }

                var url = "{{ URL::to('employeetypegetallowanceget') }}/" + emp_type;
                $.get(url, function (data) {
                    if ($.trim(data['html']) == '') {
                        $('.allowance').html('');
                    }
                    else {
                        $('.allowance').html(data['html']);
                    }
                    $('#hra_per').val(data['hra_per']);
                    $('#da_per').val(data['da_per']);
                });
            }

        });


        // calculate gross salary
        $(document).on('change', '.allowance,.basic,.da,.hra,.annual_allowance,.gratuity', function () {
            var sum = 0;
            var deduction = 0;
            var pf_company_contribute = 0;
            var pf_contribute = 0;
            var esi_company_contribute = 0;
            var esi_contribute = 0;
            var basic = (parseFloat($('.basic').val()) ? parseFloat($('.basic').val()) : 0);
            var hra_per = $('#hra_per').val();
            var da_per = $('#da_per').val();
            var val_hra = (basic * hra_per) / 100;
            var val_da = (basic * da_per) / 100;
            $('.da').val(val_da);
            $('.hra').val(val_hra);
            var da = (parseFloat($('.da').val()) ? parseFloat($('.da').val()) : 0);
            var hra = (parseFloat($('.hra').val()) ? parseFloat($('.hra').val()) : 0);
            var annual_allowance = (parseFloat($('.annual_allowance').val()) ? parseFloat($('.annual_allowance').val()) : 0);
            var gratuity = (parseFloat($('.gratuity').val()) ? parseFloat($('.gratuity').val()) : 0);

            var allowance = 0;
            //calculate allowance
            jQuery('.allowance').each(function () {
                var value = (parseFloat($(this).val()) ? parseFloat($(this).val()) : 0);
                allowance = allowance + value;
            });
            sum = basic + da + hra + allowance;
            var pf_value = basic + da;
            // if pf checked

            var pf = $('#pf:checked').val();

            if (pf == 1) {
                $('.pf_amount').attr('required', true);

                var pf_com = $('#h_pf_company_contribute').val();
                var pf_emp = $('#h_pf_employeer_contribute').val();
                var pf_from = $('#h_pf_from_contribute').val();
                var pf_to = $('#h_pf_to_contribute').val();
                var pf_com_s = pf_com.split(',');
                var pf_emp_s = pf_emp.split(',');
                var pf_from_s = pf_from.split(',');
                var pf_to_s = pf_to.split(',');
                $.each(pf_from_s, function (key, value) {

                    if (pf_value >= value && pf_value <= pf_to_s[key]) {

                        pf_company_contribute = pf_value * pf_emp_s[key] / 100;
                        var pf_company = (pf_value * pf_com_s[key]) * 12;
                        pf_contribute = parseFloat(pf_company / 100);

                        return false;

                    } else {
                        pf_value = pf_to_s[key];
                        pf_company_contribute = pf_value * pf_emp_s[key] / 100;
                        var pf_company = (pf_value * pf_com_s[key]) * 12;
                        pf_contribute = parseFloat(pf_company / 100);

                        return false;
                    }
                });

            }

            // if esi checked 
            var esi = $('#esi:checked').val();
            if (esi == 1) {

                var esi_com = $('#h_esi_company_contribute').val();
                var esi_emp = $('#h_esi_employeer_contribute').val();
                var esi_from = $('#h_esi_from_contribute').val();
                var esi_to = $('#h_esi_to_contribute').val();
                var esi_com_s = esi_com.split(',');
                var esi_emp_s = esi_emp.split(',');
                var esi_from_s = esi_from.split(',');
                var esi_to_s = esi_to.split(',');
                $.each(esi_from_s, function (key, value) {
                    if (sum >= value && sum <= esi_to_s[key]) {

                        esi_company_contribute = sum * esi_emp_s[key] / 100;
                        var esi_company = (sum * esi_com_s[key]) * 12;
                        esi_contribute = parseFloat(esi_company / 100);
                        return false;

                    } else {

                        esi_company_contribute = 0;
                        esi_contribute = 0;
                        return false;

                    }
                });

            }

            // if pt checked
            var pt = $('#pt:checked').val();

            if (pt == 1) {

                var from = $('#h_pt_from_contribute').val();
                var to = $('#h_pt_to_contribute').val();
                var from_value = from.split(',');
                var to_value = to.split(',');
                $.each(from_value, function (key, value) {
                    if (sum >= value && sum <= to_value[key]) {
                        var deduction_arrs = $('#h_pt_deduction').val();
                        var deduction_arr = deduction_arrs.split(',');
                        deduction = parseFloat(deduction_arr[key]);
                        return false;
                    } else {
                        deduction = 0;
                    }
                });

            }

            var gross_pay = (sum).toFixed();
            var total = ((sum) - (pf_company_contribute + esi_company_contribute + deduction)).toFixed();
            var allo_esi_pf = ((sum) * 12);
            var ctc = (allo_esi_pf + annual_allowance + gratuity + esi_contribute + pf_contribute).toFixed();

            $('#gross_pay').val(gross_pay);
            $('#gross_pay').parsley().destroy();
            $('#net_pay').val(total);
            $('#net_pay').parsley().destroy();
            $('#ctc_pay').val(ctc);
            $('#ctc_pay').parsley().destroy();
        });
        // while esi pf pt check calculate gross deduction 
        $(document).on('click', '#pf,#esi,#pt', function () {
            var sum = 0;
            var deduction = 0;
            var pf_company_contribute = 0;
            var pf_contribute = 0;
            var esi_company_contribute = 0;
            var esi_contribute = 0;
            var basic = (parseFloat($('.basic').val()) ? parseFloat($('.basic').val()) : 0);
            var hra_per = $('#hra_per').val();
            var da_per = $('#da_per').val();
            var val_hra = (basic * hra_per) / 100;
            var val_da = (basic * da_per) / 100;
            $('.da').val(val_da);
            $('.hra').val(val_hra);
            var da = (parseFloat($('.da').val()) ? parseFloat($('.da').val()) : 0);
            var hra = (parseFloat($('.hra').val()) ? parseFloat($('.hra').val()) : 0);
            var annual_allowance = (parseFloat($('.annual_allowance').val()) ? parseFloat($('.annual_allowance').val()) : 0);
            var gratuity = (parseFloat($('.gratuity').val()) ? parseFloat($('.gratuity').val()) : 0);

            var allowance = 0;
            //calculate allowance
            jQuery('.allowance').each(function () {
                var value = (parseFloat($(this).val()) ? parseFloat($(this).val()) : 0);
                allowance = allowance + value;
            });
            sum = basic + da + hra + allowance;
            var pf_value = basic + da;

            $('.pf_amount').removeAttr('required');
            $('.esi_amount').removeAttr('required');
            $('.pt_amount').removeAttr('required');

            // if pf checked
            var pf = $('#pf:checked').val();

            if (pf == 1) {
                $('.pf_amount').attr('required', true);
                var pf_com = $('#h_pf_company_contribute').val();
                var pf_emp = $('#h_pf_employeer_contribute').val();
                var pf_from = $('#h_pf_from_contribute').val();
                var pf_to = $('#h_pf_to_contribute').val();
                var pf_com_s = pf_com.split(',');
                var pf_emp_s = pf_emp.split(',');
                var pf_from_s = pf_from.split(',');
                var pf_to_s = pf_to.split(',');
                $.each(pf_from_s, function (key, value) {

                    if (pf_value >= value && pf_value <= pf_to_s[key]) {

                        pf_company_contribute = pf_value * pf_emp_s[key] / 100;
                        var pf_company = (pf_value * pf_com_s[key]) * 12;
                        pf_contribute = parseFloat(pf_company / 100);

                        return false;

                    } else {
                        pf_value = pf_to_s[key];
                        pf_company_contribute = pf_value * pf_emp_s[key] / 100;
                        var pf_company = (pf_value * pf_com_s[key]) * 12;
                        pf_contribute = parseFloat(pf_company / 100);

                        return false;
                    }
                });

            }

            // if esi checked 
            var esi = $('#esi:checked').val();
            if (esi == 1) {
                $('.esi_amount').attr('required', true);
                var esi_com = $('#h_esi_company_contribute').val();
                var esi_emp = $('#h_esi_employeer_contribute').val();
                var esi_from = $('#h_esi_from_contribute').val();
                var esi_to = $('#h_esi_to_contribute').val();
                var esi_com_s = esi_com.split(',');
                var esi_emp_s = esi_emp.split(',');
                var esi_from_s = esi_from.split(',');
                var esi_to_s = esi_to.split(',');
                $.each(esi_from_s, function (key, value) {
                    if (sum >= value && sum <= esi_to_s[key]) {

                        esi_company_contribute = sum * esi_emp_s[key] / 100;
                        var esi_company = (sum * esi_com_s[key]) * 12;
                        esi_contribute = parseFloat(esi_company / 100);
                        return false;

                    } else {
                        esi_company_contribute = 0;
                        esi_contribute = 0;
                        return false;
                    }
                });

            }

            // if pt checked
            var pt = $('#pt:checked').val();

            if (pt == 1) {
                $('.pt_amount').attr('required', true);
                var from = $('#h_pt_from_contribute').val();
                var to = $('#h_pt_to_contribute').val();
                var from_value = from.split(',');
                var to_value = to.split(',');
                $.each(from_value, function (key, value) {

                    if (sum >= value && sum <= to_value[key]) {
                        var deduction_arrs = $('#h_pt_deduction').val();
                        var deduction_arr = deduction_arrs.split(',');
                        deduction = parseFloat(deduction_arr[key]);
                        return false;
                    } else {
                        deduction = 0;
                    }
                });

            }

            var gross_pay = (sum).toFixed();
            var total = ((sum) - (pf_company_contribute + esi_company_contribute + deduction)).toFixed();
            var allo_esi_pf = ((sum) * 12);
            var ctc = (allo_esi_pf + annual_allowance + gratuity + esi_contribute + pf_contribute).toFixed();

            $('#gross_pay').val(gross_pay);
            $('#gross_pay').parsley().destroy();
            $('#net_pay').val(total);
            $('#net_pay').parsley().destroy();
            $('#ctc_pay').val(ctc);
            $('#ctc_pay').parsley().destroy();
        });
        // clear form feilds 
        $(document).on('click', '.clear', function () {
            var form = $("#employeepayproposal");
            form.parsley().destroy();

            $('#edit_id').val('');
            $('#employee_id').val('').select2();
            $('#payroll_type').val('').select2();
            $('#basic').val('');
            $('#hra').val('');
            $('#da').val('');
            $('#allowance').val('');
            $('#annual_allowance').val('');
            $('#gratuity').val('');
            $('#ctc_pay').val('');
            $('#gross_pay').val('');
            $('.esi_off').prop('checked', true);
            $('.pf_off').prop('checked', true);
            $('.pt_off').prop('checked', true);
            $('#h_esi_employeer_contribute').val('');
            $('#h_esi_company_contribute').val('');
            $('#h_pf_employeer_contribute').val('');
            $('#h_pf_company_contribute').val('');
            $('#h_esi_from_contribute').val('');
            $('#h_esi_to_contribute').val('');
            $('#h_pf_from_contribute').val('');
            $('#h_pf_to_contribute').val('');
            $('#h_pt_from_contribute').val('');
            $('#h_pt_to_contribute').val('');
            $('#h_pt_deduction').val('');
            $('#hra_per').val('');
            $('#da_per').val('');

        });


        /** esi pf pt calculation start **/
        $(document).on('click', '.action', function () {

            var result = [];
            var action = $(this).attr('data-action');
            var cellvalue = $(this).data('id');

            var url = action;
            var print_url = url + '/' + cellvalue + '/' + action;
            window.open(print_url, '_blank');

        });



        // create offer letter

        $(document).on('click', '.create', function () {

            var interview_id = $(this).data('id');

            $('#myModal').modal('show');
            $.get('offerdetails?interview_id=' + interview_id, function (data, status) {
                $('#interview_id1').val(data['interview_id']);
                $('.job_title').select2('val', [(data['job_title'])]);
                $('#name_of_the_candidate').val(data['name_of_the_candidate']);
                $('#date_of_joining').val(data['date_of_joining']);
                $('#candidate_id1').val(data['candidate_id']);
                $('.address').val(data['address']);
                $('#h_esi_employeer_contribute').val(data['esi']);
                $('#h_esi_company_contribute').val(data['esi_c']);
                $('#h_pf_employeer_contribute').val(data['pf']);
                $('#h_pf_company_contribute').val(data['pf_c']);
                $('#h_esi_from_contribute').val(data['esi_from']);
                $('#h_esi_to_contribute').val(data['esi_to']);
                $('#h_pf_from_contribute').val(data['pf_from']);
                $('#h_pf_to_contribute').val(data['pf_to']);
                $('#h_pt_from_contribute').val(data['pt_from']);
                $('#h_pt_to_contribute').val(data['pt_to']);
                $('#h_pt_deduction').val(data['deduct']);
            });


        });


        $('.close').click(function () {
            $('#converttoemployee').modal('hide');
        });

        $(document).on('click', '.save_popup', function () {

            var url = "{{url('savepayproposal')}}";
            var form = $('#updatepayproposal');
            form.parsley().validate();
            if (form.parsley().isValid()) {
                var data = $('#updatepayproposal').serialize();
                $.post(url, data, function (data1) {

                    console.log(data1);
                    if (data1 == 1) {
                        showCustomAlert('PayProposal Saved  Successfully', 'success');
                        $('.close').trigger('click');
                        location.reload();
                    }
                    else {
                        showCustomAlert('Scheduled Updated Successfully', 'success');
                        $('.close').trigger('click');
                        location.reload();
                    }

                });
            }
        });

        // Convert Employee

        $(document).on('click', '.converts', function () {
            $('#converttoemployee').modal('show');
            $('#converttoemployee').css('margin', 'auto');
            var interview_id = $(this).data('id');
            var resume_id = $(this).data('rid');
            $('#interview_id').val(interview_id);
            $('#candidate_id').val(resume_id);

        });


        $(document).on('click', '#employee_convert', function () {

            var employee_number = $('#employee_number').val();
            var interview_id = $('#interview_id').val();
            var candidate_id = $('#candidate_id').val();
            if (employee_number == "" || employee_number == null) {
                showCustomAlert('Please Enter Employee Number', 'info');

            } else {

                var url = "{{ URL::to('convertemployee') }}/" + interview_id + "/" + employee_number + "/" + candidate_id;
                $.get(url, function (data) {
                    if (data == 1) {
                        showCustomAlert('Employee Converted Successfully', 'success');
                        location.reload();
                    }
                    else if (data == 2) {
                        showCustomAlert('Employee Conversion Failed', 'error');
                        location.reload();
                    }
                });
            }

        });


        $(document).on("focus", ".date_of_joining", function () {
            var dateToday = new Date();
            $(this).datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: "yy-mm-dd",
                minDate: dateToday,
                maxDate: null,
                showAnim: "slideDown",
                yearRange: "-25:+0",

            });
        });


        $(document).ready(function () {

            $('#invoicesoqtyModal').modal('hide');
            $(document).on('keypress', '#basic,#da,#hra,.allowance,#annual_allowance,#gratuity', function (ev) {
                var regex = new RegExp("^[0-9.]+$");
                var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                if (regex.test(str)) {
                    return true;
                }
                ev.preventDefault();
                return false;
            });

            /** Number Validation End **/
            $('.create').attr('data-action', 'createoffer');
            $('.view').attr('data-action', 'viewoffer');
            $('.costsheet').attr('data-action', 'costsheet');
            $('.appoinment').attr('data-action', 'viewappointment');
            $('.convert').attr('data-action', 'convertemployee');


            function loadDropdown(selector, url, data, placeholder = '-- Please Select --') {

                $.ajax({
                    url: url,
                    type: 'GET',
                    data: data,
                    dataType: 'json',   // ✅ IMPORTANT
                    success: function (response) {

                        var $el = $(selector);
                        $el.empty();
                        $el.append(`<option value="">${placeholder}</option>`);

                        // ✅ response is array of objects
                        $.each(response, function (index, item) {
                            $el.append(
                                `<option value="${item.val}">${item.option_name}</option>`
                            );
                        });

                        // refresh select2 if used
                        if ($el.hasClass("select2-hidden-accessible")) {
                            $el.trigger('change.select2');
                        } else {
                            $el.trigger('change');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Dropdown error:", error);
                    }
                });
            }


            // Employee Type
            loadDropdown(
                '#employee_type',
                "{{ URL::to('jcomboform1') }}",
                {
                    table: "a_lookuplines_t:lookuplines_id:lookup_code",
                    parent: 'and lookup_type="EMPLOYEE_TYPE"'
                }
            );
            // Group type
            loadDropdown(
                '#group_type',
                "{{ URL::to('jcomboformlogin') }}",
                {
                    table: "a_m_group_t:group_id:group_name",
                    parent: ''
                }
            );

            // Hq
            loadDropdown(
                '#class_of_hq',
                "{{ URL::to('jcomboform1') }}",
                {
                    table: "a_lookuplines_t:lookuplines_id:lookup_code",
                    parent: 'and lookup_type="CLASS_OF_HQ"'
                }
            );
            // Department
            loadDropdown(
                '.department_id',
                "{{ URL::to('jcomboform1') }}",
                {
                    table: "m_department_lines_t:department_line_id:sub_department_name"
                }
            );
            //Job tittle
            loadDropdown(
                '#job_title',
                "{{ URL::to('jcomboform1') }}",
                {
                    table: "m_job_title:job_title_id:job_title_name"
                }
            );
            // location
            loadDropdown(
                '.location',
                "{{ URL::to('jcomboform1') }}",
                {
                    table: "m_location_t:location_id:location_name"
                }
            );
            // position
            loadDropdown(
                '.position',
                "{{ URL::to('jcomboform1') }}",
                {
                    table: "m_position:position_id:position",
                    parent: '',
                    order_by: "position asc"
                }
            );




        });

    </script>


@endpush