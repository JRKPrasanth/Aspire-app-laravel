@extends('layouts.header')
@section('content')
<h3 class="text-danger">Employee Payproposal</h3>
@include('layouts.breadcrumb')


<div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
        <form action="" id="employeepayproposal" data-parsley-validate>
            @csrf
            <input type="hidden" name="edit_id" id="edit_id" />

            <div class="row g-4">
                <!-- Column 1 -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold"><span class="text-danger">*</span> Employee</label>
                        <div class="input-group">
                            <select name="employee_id" id="employee_id" class="form-select select2 employee_id" required>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold"><span class="text-danger">*</span> BASIC</label>
                        <input type="text" name="basic" id="basic" class="form-control basic" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold"><span class="text-danger">*</span> DA</label>
                        <input type="text" name="da" id="da" class="form-control da" readonly required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold"><span class="text-danger">*</span> HRA</label>
                        <input type="text" name="hra" id="hra" class="form-control hra" readonly required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Annual Allowance</label>
                        <input type="text" name="annual_allowance" id="annual_allowance" class="form-control annual_allowance">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Voluntary PF</label>
                        <input type="text" name="volunter_pf" id="volunter_pf" class="form-control volunter_pf">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">ESI Amount</label>
                        <input type="text" name="esi_amount" id="esi_amount" class="form-control esi_amount" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">PF Amount</label>
                        <input type="text" name="pf_amount" id="pf_amount" class="form-control pf_amount" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Professional Tax</label>
                        <input type="text" name="pt_amount" id="pt_amount" class="form-control pt_amount" readonly>
                    </div>
                </div>

                <!-- Column 2 -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold"><span class="text-danger">*</span> Payroll Type</label>
                        <div class="input-group">
                            <select name="payroll_type" id="payroll_type" class="form-select select2 payroll_type" required></select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold"><span class="text-danger">*</span> Employee Type</label>
                        <select name="employee_type" id="employee_type" class="form-select select2 employee_type" required>
                            <option value="">-- Please Select --</option>
                        </select>
                    </div>

                    <div class="allowance"></div>

                </div>

                <!-- Column 3 -->
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">ESI</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input esi_on" type="radio" id="esi" name="esi" value="1">
                            <label class="form-check-label">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input esi_off" type="radio" id="esi" name="esi" value="0" checked>
                            <label class="form-check-label">No</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">PF</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input pf_on" type="radio" id="pf"  name="pf" value="1">
                            <label class="form-check-label">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input pf_off" type="radio" id="pf" name="pf" value="0" checked>
                            <label class="form-check-label">No</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Professional Tax</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input pt_on" type="radio" id="pt" name="pt" value="1">
                            <label class="form-check-label">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input pt_off" type="radio" id="pt" name="pt" value="0" checked>
                            <label class="form-check-label">No</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Gratuity Provision</label>
                        <input type="text" name="gratuity" id="gratuity" class="form-control gratuity">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold"><span class="text-danger">*</span> Gross Pay</label>
                        <input type="text" name="gross_pay" id="gross_pay" class="form-control gross_pay" readonly required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold"><span class="text-danger">*</span> Net Pay</label>
                        <input type="text" name="net_pay" id="net_pay" class="form-control net_pay" readonly required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold"><span class="text-danger">*</span> CTC Pay</label>
                        <input type="text" name="ctc_pay" id="ctc_pay" class="form-control ctc_pay" readonly required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold"><span class="text-danger">*</span> Effective Date</label>
                        <input type="text" name="effective_date" id="effective_date" class="form-control effective_date start_date"
                            required>
                    </div>

                    <div class="mb-3 d-none">
                        <label class="form-label">Bonus</label>
                        <input type="text" name="bonus" id="bonus" class="form-control bonus">
                    </div>
                </div>
            </div>

            <!-- Hidden Fields -->
                         <input type="hidden" id="h_esi_employeer_contribute" value=""> 
                        <input type="hidden" id="h_esi_company_contribute" value=""> 
                        <input type="hidden" id="h_pf_employeer_contribute" value=""> 
                        <input type="hidden" id="h_pf_company_contribute" value=""> 
                        <input type="hidden" id="h_pt_from_contribute" value=""> 
                        <input type="hidden" id="h_pt_to_contribute" value=""> 
                        <input type="hidden" id="h_pt_deduction" value=""> 
                        <input type="hidden" id="h_esi_from_contribute" value=""> 
                        <input type="hidden" id="h_esi_to_contribute" value="">
                        <input type="hidden" id="h_pf_from_contribute" value=""> 
                        <input type="hidden" id="h_pf_to_contribute" value="">
                        <input type="hidden" id="hra_per" value="">
                        <input type="hidden" id="da_per" value="">

            <!-- Save Button -->
            <div class="text-center mt-4">
                <button type="button" class="btn btn-success px-4 save_form">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>



<div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-3">
        </div>
        <div class="table-responsive">
            <table id="payTbl" class="table table-bordered table-striped" style="width:170%!important;">
                <thead>
                    <tr class="table-warning">
                        <th>Actions</th>
                        <th class="freeze">Employee Name</th>
                        <th>Payroll Type</th>
                        <th>BASIC</th>
                        <th>HRA</th>
                        <th>DA</th>
                        <th>Annual Allowance</th>
                        <th>Gratuity</th>
                        <th>Ctc</th>
                        <th>Gross Pay</th>
                        <th>Net Amount</th>
                        <th>Esi</th>
                        <th>PF</th>
                        <th>Volunter PF</th>
                        <th>ESI Amount</th>
                        <th>PF Amount</th>
                        <th>PT Amount</th>
                        <th>Effective Date</th>

                    </tr>
                    <tr class="table-info">
                        <th class="freeze"><input type="text" class="form-control form-control-sm column-search"
                                placeholder="Search" /></th>
                        <th><input type="text" class="form-control form-control-sm column-search"
                                placeholder="Search" /></th>
                        <th><input type="text" class="form-control form-control-sm column-search"
                                placeholder="Search" /></th>
                        <th><input type="text" class="form-control form-control-sm column-search"
                                placeholder="Search" /></th>
                        <th><input type="text" class="form-control form-control-sm column-search"
                                placeholder="Search" /></th>
                        <th><input type="text" class="form-control form-control-sm column-search"
                                placeholder="Search" /></th>
                        <th><input type="text" class="form-control form-control-sm column-search"
                                placeholder="Search" /></th>
                        <th><input type="text" class="form-control form-control-sm column-search"
                                placeholder="Search" /></th>
                        <th><input type="text" class="form-control form-control-sm column-search"
                                placeholder="Search" /></th>
                        <th><input type="text" class="form-control form-control-sm column-search"
                                placeholder="Search" /></th>
                        <th><input type="text" class="form-control form-control-sm column-search"
                                placeholder="Search" /></th>
                        <th><input type="text" class="form-control form-control-sm column-search"
                                placeholder="Search" /></th>
                        <th><input type="text" class="form-control form-control-sm column-search"
                                placeholder="Search" /></th>
                        <th><input type="text" class="form-control form-control-sm column-search"
                                placeholder="Search" /></th>
                        <th><input type="text" class="form-control form-control-sm column-search"
                                placeholder="Search" /></th>
                        <th><input type="text" class="form-control form-control-sm column-search"
                                placeholder="Search" /></th>
                        <th><input type="text" class="form-control form-control-sm column-search"
                                placeholder="Search" /></th>
                        <th><input type="text" class="form-control form-control-sm column-search"
                                placeholder="Search" /></th>

                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>






@endsection
@push('scripts')

<script>

    // onchange	
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

    // Payroll Type
    var conditionPayroll = 'and lookup_type="payroll_type"';
    var payrollUrl = "{{ URL::to('jcomboform1') }}?table=a_lookuplines_t:lookuplines_id:lookup_code&parent=" + encodeURIComponent(conditionPayroll) + "&order_by=lookup_type asc";
    loadDropdown("#payroll_type", payrollUrl, "", "-- Select Payroll Type --");

    // Employee
    var employeeUrl = "{{ URL::to('jcomboform') }}?table=hr_employee_t:employee_id:employee_number|first_name";
    loadDropdown("#employee_id", employeeUrl, "", "-- Select Employee --");

    // Employee Type
    var conditionEmpType = 'and lookup_type="EMPLOYEE_TYPE"';
    var empTypeUrl = "{{ URL::to('jcomboform1') }}?table=a_lookuplines_t:lookuplines_id:lookup_code&parent=" + encodeURIComponent(conditionEmpType) + "&order_by=lookup_type asc";
    loadDropdown("#employee_type", empTypeUrl, "", "-- Select Employee Type --");


    $(document).on('change', '#employee_id', function () {

        var employee_id = $('#employee_id').select2('val');
        var edit_id = $('#edit_id').val();
        if (employee_id != '') {

            $.get("{{URL::to('getesipf')}}?emp_id=" + employee_id, function (data) {
                $('#h_esi_employeer_contribute').val(data['esi']);
                $('#h_pf_employeer_contribute').val(data['pf']);
                $('#h_esi_company_contribute').val(data['esi_c']);
                $('#h_pf_company_contribute').val(data['pf_c']);
                $('#h_esi_from_contribute').val(data['esi_from']);
                $('#h_esi_to_contribute').val(data['esi_to']);
                $('#h_pf_from_contribute').val(data['pf_from']);
                $('#h_pf_to_contribute').val(data['pf_to']);
                $('#h_pt_from_contribute').val(data['pt_from']);
                $('#h_pt_to_contribute').val(data['pt_to']);
                $('#h_pt_deduction').val(data['deduct']);
                $('#hra_per').val(data['hra_per']);
                $('#da_per').val(data['da_per']);
            });
            // already payproposal generated check
            var url = "{{ URL::to('payproposalcheck') }}?edit_id=" + edit_id + '&employee_id=' + employee_id;
            $.get(url, function (data) {
                if ($.trim(data) == 1) {
                    $('#employee_id').select2('val', ['']);
                    $('#employee_type').select2('val', ['']);
                    $('.allowance').html('');
                    showCustomAlert('Pay Proposal Already generated for the Employee', 'warning');
                }

            });
        }
    });


    // validations	
    $(document).on('keypress', '#basic,#da,#hra,.allowance,#annual_allowance,#gratuity', function (ev) {
        var regex = new RegExp("^[0-9.]+$");
        var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
        if (regex.test(str)) {
            return true;
        }
        ev.preventDefault();
        return false;
    });


    $(document).on('keypress', '.amount', function (ev) {
        var regex = new RegExp("^[0-9.]+$");
        var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
        if (regex.test(str)) {
            return true;
        }
        ev.preventDefault();
        return false;
    });



    $(document).on('change', '.volunter_pf,.allowance,.basic,.da,.hra,.annual_allowance,.gratuity', function () {

        var sum = 0;
        var deduction = 0;
        var pf_company_contribute = 0;
        var pf_contribute = 0;
        var esi_company_contribute = 0;
        var esi_contribute = 0;
        var basic   = parseFloat($('.basic').val()) || 0;
        var hra_per = parseFloat($('#hra_per').val()) || 0;
        var da_per  = parseFloat($('#da_per').val()) || 0;
    console.log(basic,hra_per,da_per);
        var val_hra = Math.round((basic * hra_per) / 100);
        var val_da  = Math.round((basic * da_per) / 100);

        $('.hra').val(val_hra);
        $('.da').val(val_da);

        var da = (parseFloat($('.da').val()) ? parseFloat($('.da').val()) : 0);
        var volunter_pf = (parseFloat($('.volunter_pf').val()) ? parseFloat($('.volunter_pf').val()) : 0);
        var hra = (parseFloat($('.hra').val()) ? parseFloat($('.hra').val()) : 0);
        var annual_allowance = (parseFloat($('.annual_allowance').val()) ? parseFloat($('.annual_allowance').val()) : 0);
        var gratuity = (parseFloat($('.gratuity').val()) ? parseFloat($('.gratuity').val()) : 0);

        var allowance = 0;
        var allowance_deduct = 0;
        //calculate allowance
        jQuery('.allowance').each(function () {
            var value = (parseFloat($(this).val()) ? parseFloat($(this).val()) : 0);
            var type = $(this).attr('data-index');
            if (type == "Allowance")
                allowance = allowance + value;
            else
                allowance_deduct = allowance_deduct + value;
        });
        sum = basic + da + hra + allowance;
        var pf_value = basic + da;
        // if pf checked
        var pf = $('#pf:checked').val();
        if (pf == 1) {
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

                    pf_company_contribute = Math.round(pf_value * pf_emp_s[key] / 100);
                    var pf_company = (pf_value * pf_com_s[key]) * 12;
                    pf_contribute = Math.round(parseFloat(pf_company / 100));

                    return false;

                } else {
                    pf_value = pf_to_s[key];
                    pf_company_contribute = Math.round(pf_value * pf_emp_s[key] / 100);
                    var pf_company = (pf_value * pf_com_s[key]) * 12;
                    pf_contribute = Math.round(parseFloat(pf_company / 100));

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

                    esi_company_contribute = Math.ceil(sum * esi_emp_s[key] / 100);
                    var esi_company = (sum * esi_com_s[key]) * 12;
                    esi_contribute = Math.round(parseFloat(esi_company / 100));
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
                    deduction = Math.round(parseFloat(deduction_arr[key]));
                    return false;
                } else {
                    deduction = 0;
                }
            });

        }

        var gross_pay = (sum).toFixed();
        var total = ((sum) - (pf_company_contribute + esi_company_contribute + deduction + volunter_pf + allowance_deduct)).toFixed();
        var allo_esi_pf = ((sum) * 12);
        var ctc = (allo_esi_pf + annual_allowance + gratuity + esi_contribute + pf_contribute).toFixed();

        $('#esi_amount').val(esi_company_contribute);
        $('#pf_amount').val(pf_company_contribute);
        $('#pt_amount').val(deduction);
        $('#gross_pay').val(gross_pay);
        $('#gross_pay').parsley().destroy();
        $('#net_pay').val(total);
        $('#net_pay').parsley().destroy();
        $('#ctc_pay').val(ctc);
        $('#ctc_pay').parsley().destroy();
    });

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
        var val_hra = Math.round((basic * hra_per) / 100);
        var val_da = Math.round((basic * da_per) / 100);
        $('.da').val(val_da);
        $('.hra').val(val_hra);
        var da = (parseFloat($('.da').val()) ? parseFloat($('.da').val()) : 0);
        var volunter_pf = (parseFloat($('.volunter_pf').val()) ? parseFloat($('.volunter_pf').val()) : 0);
        var hra = (parseFloat($('.hra').val()) ? parseFloat($('.hra').val()) : 0);
        var annual_allowance = (parseFloat($('.annual_allowance').val()) ? parseFloat($('.annual_allowance').val()) : 0);
        var gratuity = (parseFloat($('.gratuity').val()) ? parseFloat($('.gratuity').val()) : 0);

        var allowance = 0;
        var allowance_deduct = 0;
        //calculate allowance
        jQuery('.allowance').each(function () {
            var value = (parseFloat($(this).val()) ? parseFloat($(this).val()) : 0);
            var type = $(this).attr('data-index');
            if (type == "Allowance")
                allowance = allowance + value;
            else
                allowance_deduct = allowance_deduct + value;
        });
        sum = basic + da + hra + allowance;
        var pf_value = basic + da;
        // if pf checked
        var pf = $('#pf:checked').val();

        if (pf == 1) {

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

                    pf_company_contribute = Math.round(pf_value * pf_emp_s[key] / 100);
                    var pf_company = (pf_value * pf_com_s[key]) * 12;
                    pf_contribute = Math.round(parseFloat(pf_company / 100));

                    return false;

                } else {
                    pf_value = pf_to_s[key];
                    pf_company_contribute = Math.round(pf_value * pf_emp_s[key] / 100);
                    var pf_company = (pf_value * pf_com_s[key]) * 12;
                    pf_contribute = Math.round(parseFloat(pf_company / 100));

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

                    esi_company_contribute = Math.ceil(sum * esi_emp_s[key] / 100);
                    var esi_company = (sum * esi_com_s[key]) * 12;
                    esi_contribute = Math.round(parseFloat(esi_company / 100));
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
                    deduction = Math.round(parseFloat(deduction_arr[key]));
                    return false;
                } else {
                    deduction = 0;
                }
            });

        }

        var gross_pay = (sum).toFixed();
        var total = ((sum) - (pf_company_contribute + esi_company_contribute + deduction + volunter_pf + allowance_deduct)).toFixed();
        var allo_esi_pf = ((sum) * 12);
        var ctc = (allo_esi_pf + annual_allowance + gratuity + esi_contribute + pf_contribute).toFixed();
        $('#esi_amount').val(esi_company_contribute);
        $('#pf_amount').val(pf_company_contribute);
        $('#pt_amount').val(deduction);
        $('#gross_pay').val(gross_pay);
        $('#gross_pay').parsley().destroy();
        $('#net_pay').val(total);
        $('#net_pay').parsley().destroy();
        $('#ctc_pay').val(ctc);
        $('#ctc_pay').parsley().destroy();
    });

    $('#employee_id').on('change', function () {
        var emp_type = $(this).val();
        if (emp_type != '') {
            var url = "{{ URL::to('employeetypegetallowance') }}/" + emp_type;
            $.get(url, function (data) {
                if ($.trim(data['html']) == '') {
                    $('.allowance').html('');
                }
                else {
                    $('.allowance').html(data['html']);

                }
                $('.employee_type').select2('val', [data['id']]);
                $(".employee_type").parsley().destroy();
            });
        }

    });

    // save form 
    $(document).on('click', '.save_form', function () {
        var url = "{{URL::to('employeepayproposal')}}";
        var form = $('#employeepayproposal');
        form.parsley().validate();
        var form = $('#employeepayproposal');
        var data = $('#employeepayproposal').serialize();
        form.parsley().validate();
        if (form.parsley().isValid()) {
            var $btn = $(this);
            $btn.prop('disabled', true);
            $.post(url, data, function (data1) {
                if (data1 == 1) {
                    showCustomAlert('Pay Proposal Saved Successfully', 'success');
                    window.location.reload();
                    form[0].reset();
                    $('.select2').val('').trigger('change');
                }
                else {
                    showCustomAlert('Pay Proposal Updated Successfully', 'success');
                    window.location.reload();
                    form[0].reset();
                    $('.select2').val('').trigger('change');
                }
            });
        }
    });

    // data table funcrion	
    $(document).ready(function () {
        var table = $('#payTbl').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('employeepayproposalgriddata') }}",
            columns: [

                {
                    data: 'id',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        let buttons = '';
                        if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                            buttons += `
				<button type="button" class="btn btn-sm btn-primary edit-btn"
				        data-id="${row.id}"
						data-employee_id="${row.employee_id}"
						data-payroll_type="${row.payroll_type}"
						data-basic_pay="${row.basic_pay}"
						data-hra="${row.hra}"
						data-da="${row.da}"
						data-gratuity="${row.gratuity}"
						data-allowance='${row.allowance}' // store JSON string
						data-annual_allowance="${row.annual_allowance}"
						data-esi="${row.esi}"
						data-pf="${row.pf}"
						data-pt="${row.pt}"
						data-ctc_pay="${row.ctc_pay}"
						data-gross_pay="${row.gross_pay}"
						data-net_pay="${row.net_pay}"
						data-volunter_pf="${row.volunter_pf}"
						data-esi_amount="${row.esi_amount}"
						data-pf_amount="${row.pf_amount}"
						data-pt_amount="${row.pt_amount}"
						data-effective_date="${row.effective_date}"
						data-allowance="${row.allowance}"
						data-employee_type="${row.employee_type}">
				  <i class="bi bi-pencil"></i>
				</button>`;
                        }
                        if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                            buttons += `
					<button type="button" class="btn btn-sm btn-danger delete-btn"
					  data-id="${row.id}">
					  <i class="bi bi-trash"></i>
					</button>`;
                        }
                        return buttons;
                    }

                },
                { class: 'freeze', data: 'first_name', name: 'first_name' },
                { data: 'lookup_code', name: 'lookup_code' },
                { data: 'basic_pay', name: 'basic_pay' },
                { data: 'hra', name: 'hra' },
                { data: 'da', name: 'da' },
                { data: 'annual_allowance', name: 'annual_allowance' },
                { data: 'gratuity', name: 'gratuity' },
                { data: 'ctc_pay', name: 'ctc_pay' },
                { data: 'gross_pay', name: 'gross_pay' },
                { data: 'net_pay', name: 'net_pay' },
                { data: 'esi_status', name: 'esi_status' },
                { data: 'pf_status', name: 'pf_status' },
                { data: 'volunter_pf', name: 'volunter_pf' },
                { data: 'esi_amount', name: 'esi_amount' },
                { data: 'pf_amount', name: 'pf_amount' },
                { data: 'pt_amount', name: 'pt_amount' },
                { data: 'effective_date', name: 'effective_date' },
                { data: 'allowance', name: 'allowance', visible: false },
                { data: 'esi', name: 'esi' , visible: false},
                { data: 'pf', name: 'pf' , visible: false},
                { data: 'pt', name: 'pt' , visible: false},
            ]
        });


        $('#payTbl thead').on('keyup change', '.column-search', function () {
            let index = $(this).closest('th').index();
            table.column(index).search(this.value).draw();
        });
    });


    // delete function

    let deleteId = null;

    $(document).on('click', '.delete-btn', function () {
        deleteId = $(this).data('id');
        $('#globalDeleteModal').modal('show');
    });

    $('#globalConfirmDeleteBtn').on('click', function () {
        if (deleteId) {
            $.ajax({
                url: "{{ url('payproposaldelete') }}/" + deleteId,
                type: "GET",
                success: function (response) {
                    $('#globalDeleteModal').modal('hide');
                    showCustomAlert('Deleted successfully!', 'success');
                    $('#payTbl').DataTable().ajax.reload();

                },
                error: function (xhr) {
                    $('#globalDeleteModal').modal('hide');
                    const errorMsg = xhr.responseJSON?.message || 'Delete failed.';
                    showCustomAlert(errorMsg, 'error');
                }
            });
        }
    });

    // edit 	
    $(document).on('click', '.edit-btn', function () {

    const $btn = $(this);

    // Destroy old parsley validation
    const form = $("#employeepayproposal");
    form.parsley().destroy();

    // Read values from button (data-*)
    const id = $btn.data('id');
    const employee_id = $btn.data('employee_id');
    const payroll_type = $btn.data('payroll_type');
    const basic_pay = $btn.data('basic_pay');
    const hra = $btn.data('hra');
    const da = $btn.data('da');
    const gratuity = $btn.data('gratuity');
    const allowance = $btn.attr('data-allowance') || $btn.data('allowance'); // IMPORTANT for JSON
    const annual_allowance = $btn.data('annual_allowance');

    const esi = $btn.data('esi');
    const pf  = $btn.data('pf');
    const pt  = $btn.data('pt');
console.log(esi,pf,pt);
    const ctc_pay = $btn.data('ctc_pay');
    const gross_pay = $btn.data('gross_pay');
    const net_pay = $btn.data('net_pay');
    const volunter_pf = $btn.data('volunter_pf');

    const esi_amount = $btn.data('esi_amount');
    const pf_amount  = $btn.data('pf_amount');
    const pt_amount  = $btn.data('pt_amount');

    const effective_date = $btn.data('effective_date');
    const employee_type = $btn.data('employee_type');

    // -------------------------
    // Set form values
    // -------------------------
    $('#edit_id').val(id);

    $('#employee_id').val(employee_id).trigger('change'); // select2
    $('#payroll_type').val(payroll_type).trigger('change'); // select2

    $('#basic').val(basic_pay);
    $('#hra').val(hra);
    $('#da').val(da);
    $('#gratuity').val(gratuity);

    $('#net_pay').val(net_pay);
    $('#annual_allowance').val(annual_allowance);
    $('#volunter_pf').val(volunter_pf);

    $('#ctc_pay').val(ctc_pay);
    $('#gross_pay').val(gross_pay);

    $('#pt_amount').val(pt_amount);
    $('#pf_amount').val(pf_amount);
    $('#esi_amount').val(esi_amount);

    // Effective date formatting (YYYY-MM-DD -> DD-MM-YYYY)
    if (effective_date && effective_date !== '0000-00-00') {
        const dateParts = String(effective_date).split('-');
        if (dateParts.length === 3) {
            $('#effective_date').val(`${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`);
        }
    } else {
        $('#effective_date').val('');
    }

    // -------------------------
    // Set ESI / PF / PT radios (reliable)
    // -------------------------
    const esiVal = Number(esi) ? 1 : 0;
    const pfVal  = Number(pf) ? 1 : 0;
    const ptVal  = Number(pt) ? 1 : 0;

    $(`input[name="esi"][value="${esiVal}"]`).prop('checked', true).trigger('change');
    $(`input[name="pf"][value="${pfVal}"]`).prop('checked', true).trigger('change');
    $(`input[name="pt"][value="${ptVal}"]`).prop('checked', true).trigger('change');

    // OPTIONAL: disable amount boxes when tax is No
    $('#esi_amount').prop('disabled', esiVal === 0);
    $('#pf_amount').prop('disabled', pfVal === 0);
    $('#pt_amount').prop('disabled', ptVal === 0);

    // -------------------------
    // Allowance JSON apply
    // -------------------------
    try {
        const parsedAllowance = JSON.parse(allowance || "[]");

        const url = `{{URL::to('allowancegetid')}}/${employee_id}`;
        $.get(url, function (payallow) {
            if (!Array.isArray(payallow) || !Array.isArray(parsedAllowance)) return;

            $.each(parsedAllowance, function (key, value) {
                const k = key + 1;
                const inputClass = `.allowance_id${k}`;
                // your original logic:
                $(inputClass).val(value[payallow[key]]);
            });
        });

    } catch (e) {
        console.error('Invalid allowance JSON:', e);
    }

    // -------------------------
    // Set employee type after small delay
    // -------------------------
    setTimeout(() => {
        $('#employee_type').val(employee_type).trigger('change');
    }, 300);

});


</script>


@endpush