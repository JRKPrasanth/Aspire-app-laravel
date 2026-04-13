@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Attendance Import</h3>
    @include('layouts.breadcrumb')


    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body">
            <div class="row">

                <!-- File Upload Section -->
                <div class="col-md-6">
                    <h5 class="mb-3 text-primary text-center">File Upload</h5>
                    <form id="file_up">
                        {{ csrf_field() }}

                        <div class="mb-3 row">
                            <label for="month" class="col-sm-3 col-form-label">Month</label>
                            <div class="col-sm-9">
                                <select name="month" id="month" class="form-select select2 month" readonly required>
                                    <!-- Month options here -->
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="year" class="col-sm-3 col-form-label">Year</label>
                            <div class="col-sm-9">
                                <select name="year" id="year" class="form-select select2">
                                    <!-- Year options here -->
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="file_upload" class="col-sm-3 col-form-label">Choose File</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input type="file" id="file_upload" name="file_upload" class="form-control file_upload"
                                        required>
                                </div>
                                <small class="b_name text-muted"></small>
                            </div>
                        </div>

                        <div class="mb-3 text-center">
                            <button type="button" id="upload" class="btn btn-primary me-2">
                                <i class="fa fa-upload"></i> Upload
                            </button>
                            <a href="{{ url('/download/emp_attendance.csv') }}" class="btn btn-warning" download>
                                <i class="fa fa-download"></i> Download Template
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Approval Section -->
                <div class="col-md-6">
                    <h5 class="mb-3 text-success text-center">Approval</h5>
                    <form id="validate_form" action="">
                        {{ csrf_field() }}

                        <div class="mb-3 row">
                            <label for="month1" class="col-sm-3 col-form-label">Month</label>
                            <div class="col-sm-9">
                                <select name="month1" id="month1" class="form-select select2 month" readonly required>
                                    <!-- Month options -->
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="year1" class="col-sm-3 col-form-label">Year</label>
                            <div class="col-sm-9">
                                <select name="year1" id="year1" class="form-select select2">
                                    <!-- Year options -->
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 text-center">
                            <button type="button" id="save" class="btn btn-success approve_btn">
                                <i class="fa fa-check"></i> Approve
                            </button>
                        </div>
                    </form>
                </div>

            </div> <!-- row -->
        </div> <!-- card-body -->
    </div> <!-- card -->



    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
            </div>
            <div class="table-responsive">
                <table id="UplTbl" class="table table-bordered table-striped w-100">
                    <thead>
                        <tr class="table-warning">
                            <th>Employee Name</th>
                            <th>Emp Number</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Checkin Date</th>
                            <th>Working Hours</th>
                            <th>Upload Status</th>

                        </tr>
                        <tr class="table-info">
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

                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>


@endsection
@push('scripts')


    <script>

        /** current year selected and dropdown load **/
        var min = 2024,
            max = new Date().getFullYear(),
            select = document.getElementById('year');

        for (var i = max; i >= min; i--) {
            var opt = document.createElement('option');
            opt.value = i;
            opt.innerHTML = i;
            select.appendChild(opt);

        }
        /** current year selected and dropdown load **/
        var min = 2024,
            max = new Date().getFullYear(),
            select = document.getElementById('year1');

        for (var i = max; i >= min; i--) {
            var opt = document.createElement('option');
            opt.value = i;
            opt.innerHTML = i;
            select.appendChild(opt);

        }


        // month		

        var url = "{{URL::to('jcomboformlogin?table=month:id:description') }}&order_by=id";

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

                $('.machine_id').html('<option value="">-- Select Month --</option>');

                $.each(data, function (i, item) {
                    let selected = item.val == "{{ $row->month ?? '' }}" ? 'selected' : '';
                    $('.month').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
                });

                $('.month').trigger('change.select2');
            }

        });


        // table data 		
        $(document).ready(function () {
            var table = $('#UplTbl').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('attendanceimportdatagrid') }}",
                columns: [
                    { data: 'first_name', name: 'first_name' },
                    { data: 'emp_id', name: 'emp_id' },
                    { data: 'check_in', name: 'check_in' },
                    { data: 'check_out', name: 'check_out' },
                    { data: 'atten_date', name: 'atten_date' },
                    { data: 'working_hours', name: 'working_hours' },
                    { data: 'upload_status', name: 'upload_status' }
                ]
            });

            // Individual column search
            $('#UplTbl thead').on('keyup change', ".column-search", function () {
                var colIndex = $(this).closest('th').index();
                table.column(colIndex).search(this.value).draw();
            });
        });

        $(document).on('change', '.file_upload', function () {
            var file = $(this).val();
            var file_name = $('.file_upload')[0].files[0].name;
            $('.b_name').html(file_name);
        });

        // aprove
        $(".approve_btn").click(function () {

            var month = $("#month1").select2('val');
            var year = $("#year1").select2('val');
            if (month == "" && year == "") {
                showCustomAlert('Require Month And Year', 'warning');
            }
            else {

                var url = "{{URL::to('monthapproval')}}/?month=" + month + "&year=" + year;
                $.get(url, function (data) {

                    var data = $.trim(data);
                    if (data == "1") {
                        $('#UplTbl').DataTable().ajax.reload();
                    }
                });
            }
        });

        // upload

        $(document).on('click', '#upload', function () {

            var file_upload = $('#file_upload').val();

            if (file_upload != '') {

                var form_data = new FormData(document.getElementById('file_up'));
                $.ajax({
                    url: "{{URL::to('attendanceupload')}}",
                    type: "POST",
                    data: form_data,
                    enctype: 'multipart/form-data',
                    processData: false,
                    contentType: false,
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

                        showCustomAlert('Details Uploaded  Successfully', 'success');
                        setTimeout(function () {
                            location.reload();
                        }, 2000);
                    }
                    else if (data == 2) {
                        showCustomAlert('Not a Valid File Extension', 'error');
                        setTimeout(function () {
                            location.reload();
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
            else {
                showCustomAlert('Choose a File', 'warning');
            }
        });   		
    </script>

@endpush