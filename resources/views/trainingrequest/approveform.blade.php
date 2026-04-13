@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Request For Training</h3>
    @include('layouts.breadcrumb')


    <div class="card shadow-lg rounded-4 border-0">
        <input id="change_emp" name="change_emp" type="hidden" value="0">
        <div class="card-body">
            <form id="prdsubcat" method="post" action="" data-parsley-validate>
                {{ csrf_field() }}
                <input type="hidden" value="" name="savestatus" id="savestatus" />
                <input type="hidden" name="edit_id" value="" id="edit_id" />

                <div class="row g-4">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <!-- Topic -->
                        <div class="row mb-3 align-items-center div_disable">
                            <label class="col-md-5 col-form-label text-md-end">
                                <span class="text-danger">*</span> Topic
                            </label>
                            <div class="col-md-6">
                                <select name="department_topic_id" class="form-select select2 department_topic_id"
                                    id="department_topic_id">
                                    <option value="">-- Select Option --</option>
                                    @foreach($department_topic_id as $key => $val)
                                        <option value="{{ $val->department_topic_id }}" {{ $department_topic == $val->department_topic_id ? 'selected' : '' }}>
                                            {{ $val->topic_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Active -->
                        <div class="row mb-3 align-items-center div_disable">
                            <label class="col-md-5 col-form-label text-md-end">Active</label>
                            <div class="col-md-6">
                                <select name="active" class="form-select select2 active" id="active">
                                    <option value="Yes" {{ $active == 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ $active == 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>

                        <!-- Reject Reason -->
                        <div class="row mb-3 align-items-center">
                            <label class="col-md-5 col-form-label text-md-end">Reject Reason</label>
                            <div class="col-md-6">
                                <input type="text" name="reject_reason" id="reject_reason"
                                    class="form-control reject_reason" />
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <!-- Request Type -->
                        <div class="row mb-3 align-items-center div_disable">
                            <label class="col-md-5 col-form-label text-md-end">
                                <span class="text-danger">*</span> Request Type
                            </label>
                            <div class="col-md-6">
                                <select name="request_type" class="form-select select2 request_type" id="request_type">
                                    <option value="">-- Select Option --</option>
                                    <option value="SELF" {{ $request_type == 'SELF' ? 'selected' : '' }}>SELF</option>
                                    <option value="TEAM" {{ $request_type == 'TEAM' ? 'selected' : '' }}>Team</option>
                                    <option value="CUSTOM" {{ $request_type == 'CUSTOM' ? 'selected' : '' }}>Team</option>
                                </select>
                            </div>
                        </div>

                        <!-- Employee -->
                        <div class="row mb-3 align-items-center employee_div div_disable">
                            <label class="col-md-5 col-form-label text-md-end">
                                <span class="text-danger">*</span> Employee
                            </label>
                            <div class="col-md-6">
                                <select name="employee_id[]" class="form-select select2 employee_id" id="employee_id"
                                    multiple>
                                    {!! $employee_id !!}
                                </select>
                            </div>
                        </div>

                        <!-- Created By (Hidden) -->
                        <div class="row mb-3 d-none div_disable">
                            <label class="col-md-5 col-form-label text-md-end">Created By</label>
                            <div class="col-md-6" style="pointer-events: none;">
                                <select name="created_by" class="form-select select2 created_by" id="created_by">
                                    {!! $created_by !!}
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Buttons -->
            <div class="row mt-4 text-center">
                <div class="col">
                    <button type="submit" class="btn btn-success approve-btn saveform px-4 me-2"
                        value="APPROVED">Approve</button>
                    <button type="submit" class="btn btn-danger reject-btn saveform px-4 me-2"
                        value="REJECTED">Reject</button>
                    <a href="{{ URL::to('trainingrequestapprove') }}" class="btn btn-secondary px-4 me-2">Cancel</a>
                </div>
            </div>
        </div>
    </div>


@endsection
@push('scripts')
    <script>

        $('.saveform').click(function () {
            var btn_val = $(this).val();
            $('.savestatus').val(btn_val);
            if (btn_val == 'REJECTED') {
                $('#reject_reason').attr('required', true);
            } else {
                $('#reject_reason').attr('required', false);
            }
            var reject_reason = $('#reject_reason').val();
            var training_request_id = "<?php echo $training_request_id;?>";
            var url = "{{ URL::to('trainingrequestapproved') }}/" + training_request_id + "?reject_reason=" + reject_reason + "&val=" + btn_val;
            var red_url = "{{ URL::to('trainingrequestapprove') }}";


            var form = $("#prdsubcat");
            form.parsley();
            $('input[name="_token"]').val("{{csrf_token()}}");
            var data = form.serialize();
            form.parsley().validate();
            if (form.parsley().isValid()) {
                var $btn = $(this);
                $btn.prop('disabled', true);
                $.get(url, function (data) {
                    var status = data.status;
                    var msg = data.message;
                    showCustomAlert(msg, 'success');
                    window.location.replace(red_url);
                });
            }
        });


        /*Duplicate Validate Check*/
        var dup_chk = true;
        function duplicate_validate() {

            var product_group_id = $(".product_group_id").val();
            var product_category_id = $(".product_category_id").val();
            var subcategory_name = $(".subcategory_name").val();
            var edit_id = $("#edit_id").val();

            $.ajax({
                cache: false,
                url: "{{URL::to('/productsubcategorycheckname/')}}", //this is your uri
                type: 'GET',
                dataType: 'json',
                async: false,
                data: { product_group_id: product_group_id, product_category_id: product_category_id, subcategory_name: subcategory_name, edit_id: edit_id },
                success: function (response) {
                    console.log(response);
                    if (response == 1) {
                        $('.dup_name').html('Product Subcategory Name:' + subcategory_name + ' Already Exists');
                        $('.dup_name').show();
                        $(".subcategory_name").val('');
                        dup_chk = false;

                    }
                    else if (response == 0) {
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
        /*End*/

        $(document).ready(function () {

            var comp = '{{ \Session::get('companyid')}}';

            /*Purpose for Grid Data*/
            $('.subcategory_name').on('keyup', function () {
                this.value = this.value.toUpperCase();
            });


            $(document).on('click', '.jcr_department_topic_id', function () {
                $(".department_id").jCombo("{{ URL::to('jcombojoinselect?table=m_department_t:department_id:department_name') }}&order_by=department_code asc",
                    { selected_value: "" });
            });
            $(document).on('click', '.jcr_topic_id', function () {
                $(".topic_id").jCombo("{{ URL::to('jcomboformlogin?table=t_topic_tbl:topic_id:topic_name') }}&order_by=topic_name asc",
                    { selected_value: "" });
            });
            $(document).on('change', '.request_type', function () {
                var request_type = $('.request_type').val();
                // if($('.change_emp').val()==0){
                $('.ajaxLoading').show();
                var condition = '1=1';

                if (request_type == 'TEAM') {
                    $('.employee_div').css('pointer-events', '');
                    //   $('.employee_id').removeAttr('readonly');
                    $(".employee_id").jCombo("{{ URL::to('jcomboform?table=hr_employee_t:employee_id:employee_number|first_name') }}&order_by=first_name asc&parent=" + condition,
                        { selected_value: "" });

                } else if (request_type == 'SELF') {
                    $('.employee_div').css('pointer-events', 'none');
                    // $('.employee_id').attr('readonly', 'readonly');
                    $(".employee_id").jCombo("{{ URL::to('jcomboform?table=hr_employee_t:employee_id:employee_number|first_name') }}&order_by=first_name asc&parent=" + condition,
                        { selected_value: "<?php echo $emp_id;?>" });

                } else if (request_type == 'CUSTOM') {
                    condition = '1=1';
                    // $('.employee_div').css('pointer-events','none');
                    // $('.employee_id').attr('readonly', 'readonly');
                    $(".employee_id").jCombo("{{ URL::to('jcomboform?table=hr_employee_t:employee_id:first_name') }}&order_by=first_name asc&parent=" + condition,
                        { selected_value: "" });
                } else {
                    $('.employee_div').css('pointer-events', 'none');
                    // $('.employee_id').attr('readonly', 'readonly');
                    $(".employee_id").jCombo("{{ URL::to('jcomboform?table=hr_employee_t:employee_id:employee_number|first_name') }}&order_by=first_name asc&parent=" + condition,
                        { selected_value: "" });
                }
                $('.ajaxLoading').hide();
                // }else{
                //     $('.change_emp').val(0);
                // }
            });

            /*End*/

        });
    </script>
@endpush