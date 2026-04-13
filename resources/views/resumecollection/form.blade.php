@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Resume Collection</h3>
    @include('layouts.breadcrumb')

<div class="card shadow-lg rounded-4 border-0">
    <div class="card-header bg-primary text-white">
    </div>

    <div class="card-body p-4">

        <form id="resumeupload" data-parsley-validate>
            {{ csrf_field() }}

            <input type="hidden" name="edit_id" value="{{$row->resume_id}}" id="edit_id" />

            <!-- Row 1 -->
            <div class="row g-4">

                <!-- Candidate Name -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold required">Candidate Name</label>
                    <input type="text" class="form-control" id="name_of_the_candidate" name="name_of_the_candidate"
                        value="{{$row->name_of_the_candidate}}" required>
                    <small class="text-danger dup_name d-none"></small>
                </div>

                <!-- Gender -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold required">Gender</label>
                    <select name="gender" id="gender" class="form-select select2" required>
                        <option value="">-- Select --</option>
                        <option value="male" {{ $row->gender=="male" ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ $row->gender=="female" ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <!-- Email -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold required">Email</label>
                    <input type="text" id="email" name="email" class="form-control" value="{{$row->email}}" required>
                    <small class="text-danger email_val d-none">Invalid email format</small>
                </div>

                <!-- Mobile -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold required">Mobile Number</label>
                    <input type="text" id="mobile_no" maxlength="12" name="mobile_no"
                        class="form-control" value="{{$row->mobile_no}}" required>
                </div>

                <!-- Marital Status -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Marital Status</label>
                    <select name="marital_status" id="marital_status" class="form-select select2">
                        <option value="">-- Select --</option>
                        <option value="single" {{$row->marital_status=="single" ? 'selected' : ''}}>Single</option>
                        <option value="married" {{$row->marital_status=="married" ? 'selected' : ''}}>Married</option>
                    </select>
                </div>

                <!-- Qualification -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold required">Qualification</label>
                    <input type="text" class="form-control" id="qualificaition"
                        name="qualificaition" value="{{$row->qualificaition}}" required>
                </div>

                <!-- Skills -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold required">Skills</label>
                    <textarea class="form-control" id="skills" name="skills" rows="2" required>{{$row->skills}}</textarea>
                </div>

                <!-- Experience Type -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold required">Experience</label>
                    <select name="exp_level" id="exp_level" class="form-select select2 exp_level" required>
                        <option value="">-- Select --</option>
                        <option value="1" {{$row->exp_level=="1" ? 'selected' : ''}}>Fresher</option>
                        <option value="2" {{$row->exp_level=="2" ? 'selected' : ''}}>Experience</option>
                    </select>
                </div>

                <!-- Location -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Current Location</label>
                    <input type="text" class="form-control" id="location" name="location"
                        value="{{$row->location}}">
                </div>

                <!-- State -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold">State</label>
                    <input type="text" class="form-control" id="state" name="city" value="{{$row->city}}">
                </div>

                <!-- Expected Salary -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Expected Salary (CTC)</label>
                    <input type="text" class="form-control" id="expected_salary"
                        name="expected_salary"
                        value="{{$row->expected_salary}}">
                </div>

                <!-- Permanent Address -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold required">Permanent Address</label>
                    <textarea class="form-control" name="address" id="address" rows="2" required>{{$row->address}}</textarea>
                </div>

                <!-- Resume Upload -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Resume Upload</label>
                    <input type="file" class="form-control" name="upload_file" id="upload_file">
                </div>
            </div>

            <!-- Experience Block -->
            <hr class="my-4">
                @php 
                
                if($row->exp_level == 2)
                  $button = 'required';
                else
                  $button = '';
                @endphp
                <div class="experience"  style="display:block;">
            <h5 class="fw-bold text-primary">Current Company Details</h5>

            <div class="row g-4 mt-1">

                <!-- Years of Exp -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold required">Total Experience (Years)</label>
                    <input type="text" class="form-control" id="years_of_experience"
                        name="years_of_experience" {{$button}} value="{{$row->years_of_experience}}">
                </div>

                <!-- Current Company -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Current Company</label>
                    <input type="text" class="form-control" id="current_company"
                        name="current_company" {{$button}} value="{{$row->current_company}}">
                </div>

                <!-- Company Experience -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold required">Current Company Experience</label>
                    <input type="text" class="form-control" id="current_company_exp"
                        name="current_company_exp" {{$button}} value="{{$row->current_company_exp}}">
                </div>

                <!-- Position -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Designation</label>
                    <input type="text" class="form-control" id="current_position"
                        name="current_position" {{$button}}  value="{{$row->current_position}}">
                </div>

                <!-- Salary -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold required">Current Salary</label>
                    <input type="text" class="form-control" {{$button}} id="current_salary"
                        name="current_salary" value="{{$row->current_salary}}">
                </div>

                <!-- Notice Period -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold required">Notice Period</label>
                    <select name="notice_period" id="notice_period"
                        class="form-select select2" {{$button}}>
                        <option value="">-- Select --</option>
                        <option value="1" {{ $row->notice_period=="1" ? 'selected' : '' }}>15 Days</option>
                        <option value="2" {{ $row->notice_period=="2" ? 'selected' : '' }}>1 Month</option>
                        <option value="3" {{ $row->notice_period=="3" ? 'selected' : '' }}>45 Days</option>
                        <option value="4" {{ $row->notice_period=="4" ? 'selected' : '' }}>2 Months</option>
                        <option value="5" {{ $row->notice_period=="5" ? 'selected' : '' }}>3 Months</option>
                        <option value="6" {{ $row->notice_period=="6" ? 'selected' : '' }}>Custom</option>
                    </select>
                </div>
            </div>
</div>
            <!-- Buttons -->
            <div class="text-center mt-4">
                <button type="button" class="btn btn-success px-4 me-2 save_form">Save</button>
                <button type="button" class="btn btn-danger px-4 canceled" id="delete">Cancel</button>
            </div>

        </form>
    </div>
</div>



@endsection
@push('scripts')


    <script>

        function ValidateEmail(email) {
            var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
            return expr.test(email);
        };

        $(document).on('keypress', '.mobile_no', function (ev) {
            var regex = new RegExp("^[0-9.]+$");
            var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
            if (regex.test(str)) {
                return true;
            }
            ev.preventDefault();
            return false;
        });

        $(document).on('change', '#upload_file', function () {
            var product_file = $('.upload_file').prop("files")[0];
            var ext = product_file.type;
            var extension = (product_file.name).substr((product_file.name).lastIndexOf(".") + 1);
            if (extension != 'docs' && extension != 'pdf') {
                showCustomAlert('Please Choosean Valid Pdf or Word file.', 'info');
                $('.upload_file').val('');

            }
        });

        $(document).ready(function () {
            /**************** Experience Select option input Show Start ***********/
            $('.experience').hide();

            $(document).on('change', '#exp_level', function () {
                var exp = $('#exp_level').select2('val');

                if (exp == '2') {
                    setTimeout(function () {
                        $('.experience').css("display", "block");

                        $('#current_company_exp,#current_salary,#years_of_experience,#notice_period').attr('required', true);
                    }, 200);
                }

                else {
                    setTimeout(function () {
                        $('.experience').css("display", "none");
                        $('#current_company_exp,#current_salary,#years_of_experience,#notice_period').removeAttr('required');

                    }, 200);
                }
            });


            /**************** Resume Cancel Start ***********/
            $(document).on('click', '.canceled', function () {
                url = "{{ url('resumecollection')}}",
                    window.location.href = url;
            });
            /**************** Resume Cancel End ***********/


            var exp_level = '{{$row->exp_level}}';

            if (exp_level == '2') {
                $('.experience').css("display", "block");
                setTimeout(function () {
                    $('#current_company_exp,#current_salary,#years_of_experience,#notice_period').attr('required', true);
                }, 200);
            }
            else {
                $('.experience').css("display", "none");
                $('#current_company_exp,#current_salary,#years_of_experience,#notice_period').removeAttr('required');
            }
            /**************** Experience Select option input Show End ***********/

            /**************** Notice Period Start ***********/
            $(document).on('change', '.notice_period', function () {
                var id = $('.notice_period').select2('val');

                if (id == 6) {
                    $('.custom').css("display", "block");
                }
                else {
                    $('.custom').css("display", "none");
                }
            });
            /**************** Notice Period End ***********/

            /**************** Resume Collection Save Start ***********/
            $(document).on('click', '.save_form', function () {
                var form_data = new FormData(document.getElementById('resumeupload'));
                var form = $('#resumeupload');
                form.parsley().validate();
                if (!ValidateEmail($("#email").val())) {
                    $('.email_val').attr('id', 1);
                }
                else {
                    $('.email_val').attr('id', 0);
                }
                /**************** email validation start ***********/
                var mail = $('.email_val').attr('id');
                var email = $("#email").val();
                console.log(mail);
                if (email != "") {
                    if (mail == 1) {
                        $('.email_val').show();
                    } else {
                        $('.email_val').hide();
                    }
                }
                /**************** email validation end ***********/
                if (form.parsley().isValid() && mail != 1) {
              var $btn = $(this);            
			  $btn.prop('disabled', true);
                    $.ajax({
                        url: "{{ url('resumesave')}}",
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
                            showCustomAlert('Resume Details Saved Successfully', 'success');
                            setTimeout(function () {
                                var url = "{{ URL::to('resumecollection') }}";
                                window.location.href = url;
                            }, 2000);
                        }
                        if (data == 2) {
                            showCustomAlert('Resume Details Updated Successfully', 'success');
                            setTimeout(function () {
                                var url = "{{ URL::to('resumecollection') }}";
                                window.location.href = url;
                            }, 2000);
                        }
                        else {
                            $(".alert-success").hide();
                            $(".alert-danger").fadeIn(800);

                        }
                    }).fail(function (data, status) {


                        $(".alert-success").hide();
                        $(".alert-danger").fadeIn(800);

                    });
                }
            });
            /**************** Resume Collection Save End ***********/
        });

    </script>


@endpush