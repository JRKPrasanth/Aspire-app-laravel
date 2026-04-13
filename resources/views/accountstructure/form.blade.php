@extends('layouts.header')
@section('content')
<h3 class="text-danger">Account Structure</h3>
@include('layouts.breadcrumb')


<form method="post" action="" id="accstruct_form" data-parsley-validate>
    {{ csrf_field() }}

    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-danger bg-gradient text-white fw-semibold"></div>
        <div class="card-body">
            <div class="row g-4">
                <!-- Left Column -->
                <div class="col-md-6">

                    <input type="hidden" class="form-control f_account_structure_id" id="f_account_structure_id"
                        name="f_account_structure_id" value="{{ $row->f_account_structure_id }}" readonly>

                    <!-- Company Name -->
                    <div class="mb-3 row none">
                        <label class="col-sm-4 col-form-label required">Company Name</label>
                        <div class="col-sm-8">
                            <select name="company_id" class="form-select select2 company_id chckclick" required>
                                {!! $company_id !!}
                            </select>
                        </div>
                    </div>

                    <!-- Location Name -->
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label required">Location Name</label>
                        <div class="col-sm-8">
                            <select name="location_id" class="form-select location_id select2 chckclick" required>
                                {!! $location_id !!}
                            </select>
                        </div>
                    </div>

                    <!-- Costcenter -->
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Costcenter Name</label>
                        <div class="col-sm-8">
                            <select name="costcenter_id" class="form-select select2 costcenter_id chckclick">
                                {!! $costcenter_id !!}
                            </select>
                        </div>
                    </div>

                    <!-- Sub Costcenter1 -->
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Sub Costcenter1</label>
                        <div class="col-sm-8">
                            <select name="subcostcenter1_id" class="form-select select2 subcostcenter1_id chckclick">
                                {!! $subcostcenter1_id !!}
                            </select>
                        </div>
                    </div>

                    <!-- Sub Costcenter2 -->
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Sub Costcenter2</label>
                        <div class="col-sm-8">
                            <select name="subcostcenter2_id" class="form-select select2 subcostcenter2_id chckclick">
                                {!! $subcostcenter2_id !!}
                            </select>
                        </div>
                    </div>

                    <!-- Sub Costcenter3 -->
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Sub Costcenter3</label>
                        <div class="col-sm-8">
                            <select name="subcostcenter3_id" class="form-select select2 subcostcenter3_id chckclick">
                                {!! $subcostcenter3_id !!}
                            </select>
                        </div>
                    </div>

                    <!-- Main Account Code -->
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label required">Main Account Code</label>
                        <div class="col-sm-8">
                            <select name="main_account_id" class="form-select select2 main_account_id chckclick" required>
                                {!! $main_account_id !!}
                            </select>
                        </div>
                    </div>

                    <!-- Main Account Name -->
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label required">Main Account Name</label>
                        <div class="col-sm-8">
                            <select name="main_account_name" class="form-select select2 main_account_name chckclick" required>
                                {!! $main_account_name !!}
                            </select>
                        </div>
                    </div>

                    <!-- Sub Account1 -->
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label required">Sub Account1</label>
                        <div class="col-sm-8">
                            <select name="sub_account_id" id="sub_account_id" class="form-select select2 sub_account_id chckclick"
                                required>
                                {!! $sub_account_id !!}
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-6">

                    <!-- Sub Account2 -->
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label required">Sub Account2</label>
                        <div class="col-sm-8">
                            <select name="future_reference1" id="future_reference1"
                                class="form-select select2 future_reference1 chckclick" required>
                                {!! $future_reference1 !!}
                            </select>
                        </div>
                    </div>

                    <!-- Sub Account3 -->
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Sub Account3</label>
                        <div class="col-sm-8">
                            <select name="future_reference2" id="future_reference2"
                                class="form-select select2 future_reference2 chckclick">
                                {!! $future_reference2 !!}
                            </select>
                        </div>
                    </div>

                    <!-- Sub Account4 -->
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Sub Account4</label>
                        <div class="col-sm-8">
                            <select name="sub_account4_id" id="sub_account4_id"
                                class="form-select select2 sub_account4_id chckclick">
                                {!! $sub_account4_id !!}
                            </select>
                        </div>
                    </div>

                    <!-- Account Name -->
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Account Name</label>
                        <div class="col-sm-8">
                            <input type="text" id="account_name" name="account_name" class="form-control account_name chckclick"
                                value="{{ $row->account_name }}">
                        </div>
                    </div>

                    <!-- Concatenated Segments -->
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Concatenated Segments</label>
                        <div class="col-sm-8">
                            <input type="text" id="concatenated_segments" name="concatenated_segments"
                                class="form-control concatenated_segments chckclick" value="{{ $row->concatenated_segments }}">
                        </div>
                    </div>

                    <!-- Account Description -->
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Account Description</label>
                        <div class="col-sm-8">
                            <input type="text" id="account_description" name="account_description"
                                class="form-control account_description" value="{{ $row->account_description }}">
                        </div>
                    </div>

                    <!-- Reporting Group -->
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label required">Reporting Group</label>
                        <div class="col-sm-8">
                            <select id="rpt_grp" name="rpt_grp" class="form-select select2 rpt_grp" required>
                                {!! $rpt_grp !!}
                            </select>
                        </div>
                    </div>

                    <!-- Reporting Type -->
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Reporting Type</label>
                        <div class="col-sm-8">
                            <select id="rpt_type" name="rpt_type" class="form-select rpt_type select2">
                                <option value="">--Please Select--</option>
                                <option value="Balance Sheet" @if($rpt_type=='Balance Sheet' ) selected @endif>Balance
                                    Sheet</option>
                                <option value="PandL" @if($rpt_type=='PandL' ) selected @endif>P&amp;L</option>
                            </select>
                        </div>
                    </div>

                    <!-- Reporting Seq -->
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Reporting Seq.No</label>
                        <div class="col-sm-8">
                            <input type="text" id="rpt_seqno" name="rpt_seqno" class="form-control rpt_seqno"
                                value="{{ $rpt_seq }}">
                        </div>
                    </div>

                    <!-- Active -->
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Active</label>
                        <div class="col-sm-8">
                            <select name="active" class="form-select active select2">
                                <option value="Yes" {{ $row->active == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $row->active == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                    </div>

                    <!-- Created By -->
                    <div class="mb-3 row none">
                        <label class="col-sm-4 col-form-label">Created By</label>
                        <div class="col-sm-8">
                            <select name="created_by" class="form-select created_by select2">
                                {!! $created_by !!}
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center mt-4">
                <input type="hidden" name="submit_type" class="submit_type" value="">
                <button type="button" class="btn btn-success px-4 me-2 saveform">Save</button>
                <a href="{{ url($pageModule) }}" class="btn btn-secondary px-4">Cancel</a>
            </div>
        </div>
    </div>

    <input type="hidden" class="pdtindex" value="">
</form>




@endsection
@push('scripts')


<script>

    $(document).ready(function () {
        $(document).on('change', '.rpt_grp', function () {
            var rpt_grp = $(this).select2('val');
            var url = "{{url('getrptgrpdetails')}}?id=" + rpt_grp;
            $.get(url, function (data) {
                console.log(data[0].rpt_type);
                $('#rpt_type').select2('val', [data[0].rpt_type]);
                $('#rpt_seqno').val(data[0].rpt_seq);

            });
        });


        function loadDropdown(selector, url, selectedValue = "") {
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

                    var $dropdown = $(selector);
                    $dropdown.html('<option value="">-- Please Select --</option>');

                    $.each(data, function (i, item) {
                        let selected = item.val == selectedValue ? 'selected' : '';
                        $dropdown.append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
                    });

                    // If using Select2, refresh
                    $dropdown.trigger('change.select2');
                }
            });
        }




        $(document).on('change', '.costcenter_id', function () {
            var costcenter_id = $(this).val();
            $(".subcostcenter1_id, .subcostcenter2_id, .subcostcenter3_id").html('');

            if (costcenter_id !== '') {
                var condition = 'parent_class_id=' + costcenter_id;
                var url = "{{ URL::to('jcomboformcomp') }}?table=m_department_lines_t:department_line_id:sub_department_code|sub_department_name"
                    + "&order_by=sub_department_code asc&parent=" + condition;

                loadDropdown(".subcostcenter1_id", url);
            }
        });



        $(document).on('change', '.subcostcenter1_id', function () {
            var sub1 = $(this).val();
            $(".subcostcenter2_id, .subcostcenter3_id").html('');

            if (sub1 !== '') {
                var condition = 'parent_class_id=' + sub1;
                var url = "{{ URL::to('jcomboformcomp') }}?table=m_department_lines_t:department_line_id:sub_department_code|sub_department_name"
                    + "&order_by=sub_department_code asc&parent=" + condition;

                loadDropdown(".subcostcenter2_id", url);
            }
        });




        $(document).on('change', '.subcostcenter2_id', function () {
            var sub2 = $(this).val();
            $(".subcostcenter3_id").html('');

            if (sub2 !== '') {
                var condition = 'parent_class_id=' + sub2;
                var url = "{{ URL::to('jcomboformcomp') }}?table=m_department_lines_t:department_line_id:sub_department_code|sub_department_name"
                    + "&order_by=sub_department_code asc&parent=" + condition;

                loadDropdown(".subcostcenter3_id", url);
            }
        });


        $(document).on('change', '.main_account_id', function () {
            var main_account_id = $('.main_account_id').val();
            $(".future_reference1, .future_reference2, .sub_account4_id").html('');
            $(".sub_account_id").html('');

            if (main_account_id !== '') {
                var condition = "account_class_id=" + main_account_id + " and parent_class_id='0'";
                var url = "{{ URL::to('jcomboform') }}?table=f_account_codes_lines_t:account_codes_line_id:account_code|account_code_meaning"
                    + "&order_by=account_code asc&parent=" + condition;

                loadDropdown(".sub_account_id", url);
            } else {
                $('.main_account_id, .main_account_name').val('').trigger('change.select2');
            }
        });


        $(document).on('change', '.sub_account_id', function () {
            var sub_account_id = $(this).val();
            $(".future_reference1, .future_reference2, .sub_account4_id").html('');

            if (sub_account_id !== '') {
                var condition = 'parent_class_id=' + sub_account_id;
                var url = "{{ URL::to('jcomboform') }}?table=f_account_codes_lines_t:account_codes_line_id:account_code|account_code_meaning"
                    + "&order_by=account_code asc&parent=" + condition;

                loadDropdown(".future_reference1", url);
            } else {
                $('.future_reference1').val('').trigger('change.select2');
            }
        });

        $(document).on('change', '.future_reference1', function () {
            var ref1 = $(this).val();
            $(".future_reference2, .sub_account4_id").html('');

            if (ref1 !== '') {
                var condition = 'parent_class_id=' + ref1;
                var url = "{{ URL::to('jcomboform') }}?table=f_account_codes_lines_t:account_codes_line_id:account_code|account_code_meaning"
                    + "&order_by=account_code asc&parent=" + condition;

                loadDropdown(".future_reference2", url);
            } else {
                $('.future_reference2').val('').trigger('change.select2');
            }
        });


        $(document).on('change', '.future_reference2', function () {
            var ref2 = $(this).val();
            $(".sub_account4_id").html('');

            if (ref2 !== '') {
                var condition = 'parent_class_id=' + ref2;
                var url = "{{ URL::to('jcomboform') }}?table=f_account_codes_lines_t:account_codes_line_id:account_code|account_code_meaning"
                    + "&order_by=account_code asc&parent=" + condition;

                loadDropdown(".sub_account4_id", url);
            } else {
                $('.sub_account4_id').val('').trigger('change.select2');
            }
        });



        /* Purpose For Default Organization & User*/

        var company = '<?php echo Session::get('companyid'); ?>';
        $('.company_id').val(company).change();
        var location = '<?php echo Session::get('location'); ?>';
        $('.location_id').val(location).change();

        $('.chckclick').each(function () {
            $(document).on('keyup change', '.costcenter_id,.subcostcenter1_id,.subcostcenter2_id,.subcostcenter3_id,.main_account_id,.sub_account_id,.future_reference1,.future_reference2,.sub_account4_id,.account_name', function () {
                if ($('.company_id option:selected').val() != '') {
                    company_name = $('.company_id option:selected').text().split(" ", 1);
                }
                else {
                    company_name = '';
                }

                if ($('.location_id option:selected').val() != '') {
                    location_name = $('.location_id option:selected').text().split(" ", 1);
                }
                else {
                    location_name = '';
                }


                if ($('.costcenter_id option:selected').val() != '') {
                    costcenter_name = $('.costcenter_id option:selected').text().split(" ", 1);
                    concat_name = company_name + '-' + location_name + '-' + costcenter_name;

                }
                else {
                    costcenter_name = '';
                }

                if ($('.subcostcenter1_id option:selected').val() != '') {
                    subcostcenter1 = $('.subcostcenter1_id option:selected').text().split(" ", 1);

                    concat_name = company_name + '-' + location_name + '-' + costcenter_name + '-' + subcostcenter1;

                }
                else {
                    subcostcenter1 = '';
                }

                if ($('.subcostcenter2_id option:selected').val() != '') {
                    subcostcenter2 = $('.subcostcenter2_id option:selected').text().split(" ", 1);
                    concat_name = company_name + '-' + location_name + '-' + costcenter_name + '-' + subcostcenter1 + '-' + subcostcenter2;

                }
                else {
                    subcostcenter2 = '';
                }
                //     alert($('.subcostcenter3_id option:selected').val());
                if ($('.subcostcenter3_id option:selected').val() != '') {
                    subcostcenter3 = $('.subcostcenter3_id option:selected').text().split(" ", 1);
                    concat_name = company_name + '-' + location_name + '-' + costcenter_name + '-' + subcostcenter1 + '-' + subcostcenter2 + '-' + subcostcenter3;

                }
                else {
                    subcostcenter3 = '';
                }

                if ($('.main_account_id option:selected').val() != '') {
                    main_account_name = $('.main_account_id option:selected').text();
                    concat_name = company_name + '-' + location_name + '-' + costcenter_name + '-' + subcostcenter1 + '-' + subcostcenter2 + '-' + subcostcenter3 + '-' + main_account_name;
                }
                else {
                    main_account_name = '';
                }

                if ($('.sub_account_id option:selected').val() != '') {
                    sub_account_name = $('.sub_account_id option:selected').text().split(" ", 1);
                    concat_name = company_name + '-' + location_name + '-' + costcenter_name + '-' + subcostcenter1 + '-' + subcostcenter2 + '-' + subcostcenter3 + '-' + main_account_name + '-' + sub_account_name;
                }
                else {
                    sub_account_name = '';
                }


                if ($('.future_reference1 option:selected').val() != '') {
                    future_reference1 = $('.future_reference1 option:selected').text().split(" ", 1);
                    concat_name = company_name + '-' + location_name + '-' + costcenter_name + '-' + subcostcenter1 + '-' + subcostcenter2 + '-' + subcostcenter3 + '-' + main_account_name + '-' + sub_account_name + '-' + future_reference1;
                }
                else {
                    future_reference1 = '';
                }

                if ($('.future_reference2 option:selected').val() != '') {
                    future_reference2 = $('.future_reference2 option:selected').text().split(" ", 1);
                    concat_name = company_name + '-' + location_name + '-' + costcenter_name + '-' + subcostcenter1 + '-' + subcostcenter2 + '-' + subcostcenter3 + '-' + main_account_name + '-' + sub_account_name + '-' + future_reference1 + '-' + future_reference2;
                }
                else {
                    future_reference2 = '';
                }

                if ($('.sub_account4_id option:selected').val() != '') {
                    sub_account4_id = $('.sub_account4_id option:selected').text().split(" ", 1);

                    concat_name = company_name + '-' + location_name + '-' + costcenter_name + '-' + subcostcenter1 + '-' + subcostcenter2 + '-' + subcostcenter3 + '-' + main_account_name + '-' + sub_account_name + '-' + future_reference1 + '-' + future_reference2 + '-' + sub_account4_id;
                }
                else {
                    sub_account4_id = '';
                }

                if ($('.account_name').val() != '') {
                    account_name = $('.account_name').val();
                    concat_name = company_name + '-' + location_name + '-' + costcenter_name + '-' + subcostcenter1 + '-' + subcostcenter2 + '-' + subcostcenter3 + '-' + main_account_name + '-' + sub_account_name + '-' + future_reference1 + '-' + future_reference2 + '-' + sub_account4_id + '-' + account_name;
                } else {
                    account_name = '';
                }
                //value = concat_name.replace("-", "");
                $('.concatenated_segments').val(concat_name);
            });
        });

        /* Purpose For Save Function*/

        $(document).on('click', '.saveform', function () {
            var btnval = $(this).val();
            $('#savestatus').val(btnval);
            var url = "{{ url('accountstructuresave') }}";
            var red_url = "{{url('accountstructure')}}"
            var formdata = $('#accstruct_form').serialize();
            var form = $('#accstruct_form');
            form.parsley().validate();
            var form = $('#accstruct_form');
            form.parsley().validate();
            if (form.parsley().isValid()) {

                $.post(url, formdata, function (data) {
                    var status = data.status;
                    var msg = data.message;
                    var id = data.id;
                    var edit_url = "{{ url('accountstructurecreate') }}/" + id;
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
    });


</script>

@endpush