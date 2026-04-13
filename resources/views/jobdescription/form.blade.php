@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Job Description</h3>
    @include('layouts.breadcrumb')


    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white fw-semibold">
        </div>

        <div class="card-body p-4">
            <form action="" id="jobdescription" data-parsley-validate>

                <input type="hidden" name="edit_id" value="{{$row->description_id}}" id="edit_id" />
                {{ csrf_field() }}

                <div class="row g-4">

                    <!-- ------------- Column 1 ---------------- -->
                    <div class="col-md-4">

                        <!-- Team Lead -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> Team Lead Name
                            </label>
                            <select name="team_leads_id" id="team_leads_id" class="form-select select2 team_leads_id"
                                required style="pointer-events:none;">
                                {!! $row->employee !!}
                            </select>
                        </div>

                        <!-- Description Name -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> Description Name
                            </label>
                            <input type="text" id="description_name" name="description_name"
                                class="form-control description_name" value="{{$row->description_name}}" required>
                            <span class="btn btn-danger dup_name mt-1 d-none"></span>
                        </div>

                        <!-- Required Skills -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> Required Skills
                            </label>
                            <input type="text" name="reqired_skills" id="reqired_skills" class="form-control reqired_skills"
                                value="{{$row->reqired_skills}}" required>
                        </div>

                        <!-- Month & Year -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <span class="text-danger">*</span> Month
                                </label>
                                <select id="month" name="month" class="form-select select2 month" required>
                                    <option value="">-- Select --</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    <span class="text-danger">*</span> Year
                                </label>
                                <select id="year" name="year" class="form-select select2 year" required>
                                    <option value="">-- Select --</option>
                                </select>
                            </div>
                        </div>

                        <!-- Active -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Active</label>
                            <select name="active" id="active" class="form-select select2 active">
                                <option value="Yes" {{ $row->active == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ $row->active == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>

                    </div>

                    <!-- ------------- Column 2 ---------------- -->
                    <div class="col-md-4">

                        <!-- Description -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> Description
                            </label>
                            <div class="d-flex gap-2">
                                <select name="desc_id" id="desc_id" class="form-select select2 desc_id" required>
                                    {!! $row->desc_id !!}
                                </select>

                                <a class="append_href" download>
                                    <i class="fa fa-download fs-4 text-primary"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Department -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> Department
                            </label>
                            <div class="d-flex gap-2">
                                <select name="department" id="department" class="form-select select2 department" required>
                                    {!! $row->department !!}
                                </select>
                            </div>
                        </div>

                        <!-- Position -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> Position
                            </label>
                            <div class="d-flex gap-2">
                                <select name="job_title" id="job_title" class="form-select select2 job_title" required>
                                    {!! $row->jobtitle !!}
                                </select>
                            </div>
                        </div>

                        <!-- Grade -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> Grade
                            </label>
                            <div class="d-flex gap-2">
                                <select name="position_id" id="position_id" class="form-select select2 position_id"
                                    required>
                                    {!! $row->position_id !!}
                                </select>
                            </div>
                        </div>

                        <!-- Interview Process -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> Interview Process
                            </label>
                            <div class="d-flex gap-2">
                                <select name="int_pro[]" id="int_pro" class="form-select select2 int_pro" multiple required>
                                    {!! $row->interview_process !!}
                                </select>
                            </div>
                        </div>

                        <!-- No. of Employees -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> No of Employees
                            </label>
                            <input type="text" id="no_of_persons" name="no_of_persons" class="form-control no_of_persons"
                                value="{{$row->no_of_persons}}" required>
                        </div>

                    </div>

                    <!-- ------------- Column 3 ---------------- -->
                    <div class="col-md-4">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> Min Salary (CTC)
                            </label>
                            <input type="text" id="min_salary" name="min_salary" class="form-control min_salary"
                                value="{{$row->min_salary}}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> Max Salary (CTC)
                            </label>
                            <input type="text" id="max_salary" name="max_salary" class="form-control max_salary"
                                value="{{$row->max_salary}}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> Min Experience (Years)
                            </label>
                            <input type="text" id="min_experience" name="min_experience" class="form-control min_experience"
                                value="{{$row->min_experience}}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <span class="text-danger">*</span> Max Experience (Years)
                            </label>
                            <input type="text" id="max_experience" name="max_experience" class="form-control max_experience"
                                value="{{$row->max_experience}}" required>
                        </div>

                    </div>

                </div>

                <!-- ------------- Buttons ---------------- -->
                <div class="text-center mt-4">

                    @if($pageModule == "createjobdescription")
                        <button type="button" class="btn btn-success px-4 me-2 save_form">Save</button>
                        <?php    include('toolbar.php'); ?>
                    @endif

                    @if($pageModule == "jobdescriptionapproval")
                        <button type="button" class="btn btn-success px-4 me-2 saveform" value="approve">Approve</button>
                        <button type="button" class="btn btn-danger px-4 me-2 saveform" value="reject">Reject</button>
                        <a class="btn btn-secondary px-4 me-2" href="{{ url($pageModule) }}">Cancel</a>
                    @endif

                    @if($pageModule == "jobdescriptionapprovalhr")
                        <button type="button" class="btn btn-success px-4 me-2 saveformhr" value="approve">Approve</button>
                        <button type="button" class="btn btn-danger px-4 me-2 saveformhr" value="reject">Reject</button>
                        <a class="btn btn-secondary px-4 me-2" href="{{ url($pageModule) }}">Cancel</a>
                    @endif

                </div>

            </form>
        </div>
    </div>

    <?php if ($pageModule == "createjobdescription") { ?>

    <div class="card shadow-lg rounded-4 border-0">
        <div class="container mt-4">
            <table id="DescTbl" class="table table-bordered table-striped w-100">
                <thead>
                    <tr class="table-warning">
                        <th>Description</th>
                        <th>Department</th>
                        <th>Tob Title</th>
                        <th>Grade</th>
                        <th>Req Skill</th>
                        <th>Active</th>
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
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <?php } ?>

@endsection
@push('scripts')

    <script>


        // data table funcrion	
        $(document).ready(function () {
            var table = $('#DescTbl').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('jobdescriptionformgriddata') }}",
                columns: [

                    { data: 'description_name', name: 'description_name' },
                    { data: 'sub_department_name', name: 'sub_department_name' },
                    { data: 'job_title_name', name: 'job_title_name' },
                    { data: 'position', name: 'position' },
                    { data: 'reqired_skills', name: 'reqired_skills' },
                    { data: 'active', name: 'active' },
                    {
                        data: 'description_id',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        width: '140px',
                        render: function (data, type, row) {
                            let buttons = '';
                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                                buttons += `<button class="btn btn-sm btn-primary me-1 edit-btn" 
                          data-id="${row.description_id}" 
                          data-type="${row.description_name}" 
                          data-desc="${row.position}" 
                          data-account="${row.account_id}" 
                          data-created="${row.created_id}" 
                          data-gst="${row.sub_department_name}" 
                          data-active="${row.active}">
                          <i class="bi bi-pencil"></i>
                        </button>`;
                            }
                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                                buttons += `
                        <button class="btn btn-sm btn-danger delete-btn" data-id="${row.description_id}">
                          <i class="bi bi-trash"></i>
                        </button>`;
                            }
                            return buttons;
                        }
                    }
                ]
            });

            // Individual column search
            $('#DescTbl thead').on('keyup change', ".column-search", function () {
                var colIndex = $(this).parent().index();
                table.column(colIndex).search(this.value).draw();
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
                    url: "{{ url('jobdescription/delete/') }}/" + deleteId,
                    type: "GET",
                    success: function (data) {
                        if (data == '2') {
                            $('#globalDeleteModal').modal('hide');
                            showCustomAlert('Deleted successfully!', 'success');
                            $('#DescTbl').DataTable().ajax.reload();
                        }
                        if (data == '1') {
                            $('#globalDeleteModal').modal('hide');
                            showCustomAlert("You Can't delete , Used in SomeWhere.", 'error');
                            $('#DescTbl').DataTable().ajax.reload();
                        }
                    },
                    error: function (xhr) {
                        $('#globalDeleteModal').modal('hide');
                        const errorMsg = xhr.responseJSON?.message || 'Delete failed.';
                        showCustomAlert(errorMsg, 'error');
                    }
                });
            }
        });



        $(document).on('click', '.save_form', function () {

            var url = "{{url('jobdescriptionsave')}}";
            var data = $('#jobdescription').serialize();
            var form = $('#jobdescription');
            form.parsley().validate();
            var form = $('#jobdescription');
            form.parsley().validate();
            if (form.parsley().isValid()) {
                $.post(url, data, function (data1) {
                    if (data1 == 1) {
                        showCustomAlert('Job Description Saved Successfully', 'success');
                        setTimeout(function () {
                            window.location.reload();
                        }, 500);

                    }
                    else {
                        showCustomAlert('Job Description Updated Successfully', 'success');
                        setTimeout(function () {
                            window.location.reload();
                        }, 500);
                    }

                });
            }
        });



        $(document).on('click', '.saveformhr', function () {
            var status = $(this).val();
            var description_id = $('#edit_id').val();
            var url = "{{ URL::to('approvedescriptionhr') }}/" + status + "/" + description_id;
            $.get(url, function (data) {
                if (data == 4) {
                    url = "{{ URL::to('jobdescriptionapprovalhr')}}";

                    showCustomAlert('Job Description Rejected Successfully', 'success');
                    setTimeout(function () {
                        window.location.href = url;
                    }, 2000);
                }
                if (data == 3) {
                    url = "{{ URL::to('jobdescriptionapprovalhr')}}";

                    showCustomAlert('Job Description Approved Successfully', 'success');
                    setTimeout(function () {
                        window.location.href = url;
                    }, 2000);
                }
            });
        });

        $(document).on('click', '.saveform', function () {
            var status = $(this).val();
            var description_id = $('#edit_id').val();
            var url = "{{ URL::to('approvedescription') }}/" + status + "/" + description_id;
            $.get(url, function (data) {
                if (data == 2) {
                    url = "{{ URL::to('jobdescriptionapproval')}}";

                    showCustomAlert('Job Description Rejected Successfully', 'success');
                    setTimeout(function () {
                        window.location.href = url;
                    }, 2000);
                }
                if (data == 1) {
                    url = "{{ URL::to('jobdescriptionapproval')}}";

                    showCustomAlert('Job Description Approved Successfully', 'success');
                    setTimeout(function () {
                        window.location.href = url;
                    }, 2000);
                }
            });
        });

        $('.append_href').hide();
        $(document).on('change', '.desc_id', function () {
            var desc_id = $(this).val();
            if (desc_id != '') {
                var url = "{{URL::to('jobdescriptionfile')}}?del_id=" + desc_id;
                $.get(url, function (data) {
                    var file = $.trim(data);
                    if (file != '') {
                        $('.append_href').show();
                        var x = "{{URL::to('descriptionupload')}}/" + file;
                        $('.append_href').attr('href', x);
                        $('.desc_file').attr('pointer-events', 'auto');
                    }
                    else {
                        $('.append_href').attr('href', '#');
                        $('.append_href').hide();
                    }
                });
            } else {
                $('.append_href').attr('href', '#');
                $('.append_href').hide();
            }

        });


        $(document).on('keypress', '#min_experience,#max_experience,#min_salary,#max_salary,#no_of_persons', function (ev) {
            var regex = new RegExp("^[0-9.]+$");
            var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
            if (regex.test(str)) {
                return true;
            }
            ev.preventDefault();
            return false;
        });

        var condition1 = '1=1';

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

                $('.month').html('<option value="">-- Select Month --</option>');

                $.each(data, function (i, item) {
                    let selected = item.val == "{{ $row->month ?? '' }}" ? 'selected' : '';
                    $('.month').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
                });

                $('.month').trigger('change.select2');
            }

        });


        // validate alphabets only 
        $(document).on('keypress', '#description_name,#reqired_skills', function (ev) {
            var regex = new RegExp("^[a-z,A-Z.,' ']+$");
            var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
            if (regex.test(str)) {
                return true;
            }
            ev.preventDefault();
            return false;
        });


        /*    <?php  if ($pageModule == "jobdescriptionapproval" || $pageModule == "jobdescriptionapprovalhr") { ?>
        $('#jobdescription input').attr('readonly', 'readonly');
        $('.approvediv').css('pointer-events', 'none');
        setTimeout(function () {
            $('.desc_id').trigger('change');
        }, 500);
        var lang = '';
        var int_pro = "{{$row->interview_process_id}}";
        var multiple_employee = JSON.parse(int_pro.replace(/&quot;/g, '"'));
        if (int_pro != '') {
            $('#int_pro').select2('val', [multiple_employee]);
        }

        <?php } ?> */
    </script>

@endpush