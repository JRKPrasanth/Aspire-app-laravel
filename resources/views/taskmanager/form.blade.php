@extends('layouts.header')
@section('content')


    <h3 class="text-danger">
        <?php if ($pageMethod == "taskmanager") { ?>
        WebOps Track Create
        <?php } else if ($pageMethod == "taskmanagerupdate") { ?>
        WebOps Track - Status Update
        <?php    } else if ($pageMethod == "taskmanagerfinalapproval") { ?>
        WebOps Track - Final Approval
        <?php    } else { ?>
        WebOps Track - Head Approval
        <?php    } ?>

    </h3>
    @include('layouts.breadcrumb')

    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body">
            <form method="post" action="{{ URL::to('taskmanagersave') }}" id="taskmanager" class="taskmanager"
                enctype="multipart/form-data" data-parsley-validate>
                {{ csrf_field() }}

                <div class="row g-4">
                    <!-- Left Column -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Ticket Number</label>
                            <input type="text" name="ticket_number" class="form-control ticket_number"
                                value="{{ $row->ticket_number }}" readonly>
                                <input type="hidden" name="status" id="final_status">

                        </div>

                        <div class="mb-3">
                            <label class="form-label "> Priority</label>
                            <select name="priority" class="form-select select2 priority" required>
                                <option value="" {{ $row->priority == '' ? 'selected' : '' }}>--Please Select--</option>
                                <option value="High" {{ $row->priority == 'High' ? 'selected' : '' }}>High</option>
                                <option value="Medium" {{ $row->priority == 'Medium' ? 'selected' : '' }}>Medium</option>
                                <option value="Low" {{ $row->priority == 'Low' ? 'selected' : '' }}>Low</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label "> Category</label>
                            <select name="ticket_category" class="form-select select2 ticket_category">
                                {!! $row->ticket_category !!}
                            </select>
                        </div>

                        <div class="mb-3 none">
                            <label class="form-label "> Requested Date</label>
                            <input type="text" name="start_date" class="form-control start_date"
                                value="{{ $row->start_date }}" required readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label "> Task</label>
                            <textarea name="task" class="form-control task" rows="2" required>{!! $row->task !!}</textarea>
                        </div>
                    </div>

                    <!-- Middle Column -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label "> Team Type</label>
                            <select name="team_type" class="form-select select2 team_type" required>
                                <option value="" {{ $row->team_type == '' ? 'selected' : '' }}>--Please Select--</option>
                                <option value="Internal Team" {{ $row->team_type == 'Internal Team' ? 'selected' : '' }}>
                                    Internal Team</option>
                                <option value="External Team" {{ $row->team_type == 'External Team' ? 'selected' : '' }}>
                                    External Team</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label "> Department</label>
                            <input class="form-control taskmanager_id" id="taskmanager_id" name="taskmanager_id" size="16" type="hidden" value="{{ $row->taskmanager_id }}" readonly>
                            <select name="department" class="form-select select2 department" required>
                                {!! $row->department !!}
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label "> Sub Category</label>
                            <select name="ticket_subcategory" class="form-select select2 ticket_subcategory">
                                {!! $row->ticket_subcategory !!}
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label "> Expected Date</label>
                            <input type="text" name="end_date" class="form-control expected_date"
                                value="{{ $row->end_date }}" required readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Comments</label>
                            <textarea name="description" class="form-control description"
                                rows="2">{!! $row->description !!}</textarea>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label "> Assigned Type</label>
                            <select name="assigned_type" class="form-select select2 assigned_type" required>
                                <option value="" {{ $row->assigned_type == '' ? 'selected' : '' }}>--Please Select--</option>
                                <option value="Direct" {{ $row->assigned_type == 'Direct' ? 'selected' : '' }}>Direct</option>
                                <option value="Head of the Department" {{ $row->assigned_type == 'Head of the Department' ? 'selected' : '' }}>Head of the Department</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label "> Assigned To</label>
                            <select name="assigned_to" class="form-select select2 assigned_to">
                                {!! $row->assigned_to !!}
                            </select>
                        </div>

                        <div class="mb-3 assign_lead">
                            <label class="form-label "> Department Head</label>
                            <select name="department_lead" class="form-select select2 department_lead">
                                {!! $row->department_lead !!}
                            </select>
                        </div>

                        @if($pageMethod == "taskmanagerupdate")

                            <div class="mb-3">
                                <label class="form-label">Update Status</label>
                                <select  class="form-select select2 updatestatus">
                                    <option value="">--- Please Select ---</option>
                                    <option value="Completed">Completed</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Progress">Work In Progress</option>
                                </select>
                            </div>

                        @else

                            <div class="form-group row" style="display:none;">
                                <label for="status" class="form-control-label col-md-4">Taskmanager Status</label>
                                <div class="col-md-6">
                                    <select id="status" class="form-control status" readonly>
                                        <option value="">--Please Select--</option>
                                        <option value="INITIATED" {{ $row->status == 'INITIATED' ? 'selected' : '' }}>INITIATED
                                        </option>
                                        <option value="APPROVED" {{ $row->status == 'APPROVED' ? 'selected' : '' }}>APPROVED
                                        </option>
                                        <option value="ALLOCATED" {{ $row->status == 'ALLOCATED' ? 'selected' : '' }}>ALLOCATED
                                        </option>
                                        <option value="REJECTED" {{ $row->status == 'REJECTED' ? 'selected' : '' }}>REJECTED
                                        </option>
                                    </select>
                                </div>
                            </div>

                        @endif

                    <div class="mb-3">
                        <label class="form-label">Attach Reference File</label>

                        <input type="file" 
                            name="choosefile[]" 
                            class="form-control choosefile" 
                           
                            multiple>

                        <?php if(!empty($row->taskmanager_id)) { ?>

                            <?php 
                                $dataupload = json_decode($row->choosefile, true);
                                if(!empty($dataupload)) { 
                                    $lastFile = end($dataupload);
                            ?>

                                <!-- Hidden field for existing file -->
                                <input type="hidden" 
                                    name="existing_file" 
                                    id="existing_file" 
                                    value="{{ $lastFile }}">

                                <!-- Existing File Display -->
                                <div class="mt-3">
                                    <div class="alert alert-primary p-2">
                                        <strong>File:</strong><br>

                                        <a download 
                                        href="{{ URL::to('') }}/uploads/task_manager/{{ $row->taskmanager_id }}/{{ $lastFile }}">
                                            {{ $lastFile }}
                                        </a>
                                    </div>
                                </div>

                            <?php } ?>

                        <?php } ?>
                    </div>


                        <div class="mb-3 none">
                            <label class="form-label">Created By</label>
                            <select name="created_by" class="form-select select2 created_by">
                                {!! $row->created_by !!}
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="row mt-4">
                    <div class="col-12 text-center">
                        @if($pageMethod == "taskmanager")
                            <button type="button" class="btn btn-success saveform px-4 me-2" value="SAVE">Save</button>
                        @elseif($pageMethod == "taskmanagerupdate")
                            <button type="button" class="btn btn-success saveform px-4 me-2" value="APPROVED">Update</button>
                        @elseif($pageMethod == "taskmanagerfinalapproval")
                            <button type="button" class="btn btn-success saveform px-4 me-2 px-4 me-2"
                                value="APPROVED">Approve</button>
                            <button type="button" class="btn btn-danger saveform px-4 me-2" value="REJECTED">Reject</button>
                        @else
                            <button type="button" class="btn btn-success saveform px-4 me-2" value="ALLOCATED">Allocate</button>
                            <button type="button" class="btn btn-danger saveform px-4 me-2" value="REJECTED">Reject</button>
                        @endif
                        <a href="{{ url::to($pageMethod) }}" class="btn btn-danger px-4 me-2">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>


@endsection
@push('scripts')

    <script>

        $(document).ready(function () {
            $(document).on('change', '.assigned_to', function () {
                var assigned_to = $(this).val();
                if (assigned_to != '') {
                    var url = "{{ url::to('leaddepartment') }}?department_lead=" + assigned_to;
                    $.get(url, function (data) {
                        var data = jQuery.parseJSON(data);
                        //$(".week_off").val(data.week_off[0].week_off)

                        $(".department").html('');
                        $(".department").html(data.department);

                        console.log('dept' + data.department);
                        var dept = $('.department').val();
                        var ticket_category=$('#ticket_category').val();
                        var condition = ' task_category_id=' + ticket_category;
                        if (dept != '') {

                            const url = "{{ URL::to('jcomboform') }}" +
                                "?table=a_task_category_t:task_category_id:category_name" +
                                "&order_by=category_name asc" +
                                "&parent=" + condition;

                            $.ajax({
                                url: url,
                                type: "GET",
                                success: function (response) {
                                    let jsonData = response;

                                    // Parse if string response
                                    if (typeof response === "string") {
                                        try {
                                            jsonData = JSON.parse(response);
                                        } catch (e) {
                                            console.error("Invalid JSON:", response);
                                            return;
                                        }
                                    }

                                    // Clear and repopulate the dropdown
                                    const $dropdown = $(".ticket_category");
                                    $dropdown.empty().append('<option value="">-- Select Category --</option>');

                                    $.each(jsonData, function (i, item) {
                                        $dropdown.append(`<option value="${item.val}">${item.option_name}</option>`);
                                    });

                                    // Trigger Select2 refresh if used
                                    $dropdown.trigger("change.select2");
                                },
                                error: function (xhr, status, error) {
                                    console.error("AJAX Error:", error);
                                }
                            });
                        }


                    });



                }

            });

            $(document).on('change', '.department_lead', function () {
                var department_lead = $(this).val();
                if (department_lead != '') {
                    var url = "{{ url::to('leaddepartment') }}?department_lead=" + department_lead;
                    $.get(url, function (data) {
                        var data = jQuery.parseJSON(data);
                        //$(".week_off").val(data.week_off[0].week_off)

                        $(".department").html('');
                        $(".department").html(data.department);

                        console.log('dept' + data.department);
                        var dept = $('.department').val();

                        var condition = ' department_id=' + dept;
                        if (dept != '') {

                            const url = "{{ URL::to('jcomboform') }}" +
                                "?table=a_task_category_t:task_category_id:category_name" +
                                "&order_by=category_name asc" +
                                "&parent=" + condition;

                            $.ajax({
                                url: url,
                                type: "GET",
                                success: function (response) {
                                    let jsonData = response;

                                    // Parse if string response
                                    if (typeof response === "string") {
                                        try {
                                            jsonData = JSON.parse(response);
                                        } catch (e) {
                                            console.error("Invalid JSON:", response);
                                            return;
                                        }
                                    }

                                    // Clear and repopulate the dropdown
                                    const $dropdown = $(".ticket_category");
                                    $dropdown.empty().append('<option value="">-- Select Category --</option>');

                                    $.each(jsonData, function (i, item) {
                                        $dropdown.append(`<option value="${item.val}">${item.option_name}</option>`);
                                    });

                                    // Trigger Select2 refresh if used
                                    $dropdown.trigger("change.select2");
                                },
                                error: function (xhr, status, error) {
                                    console.error("AJAX Error:", error);
                                }
                            });
                        }


                    });



                }

            });

            $(document).on('change', '.ticket_category', function () {
                var ticket_category = $('.ticket_category').val();
                var dept = $('.department').val();

                //var condition = ' department_id=' + dept + ' and task_category_id=' + ticket_category;
                var condition = ' task_category_id='+ticket_category;    
                //if (dept != '') {

                    const url = "{{ URL::to('jcomboform') }}" +
                        "?table=a_task_subcategory_t:task_subcategory_id:subcategory_name" +
                        "&order_by=subcategory_name asc" +
                        "&parent=" + condition;

                    $.ajax({
                        url: url,
                        type: "GET",
                        success: function (response) {
                            let jsonData = response;

                            // Parse if string response
                            if (typeof response === "string") {
                                try {
                                    jsonData = JSON.parse(response);
                                } catch (e) {
                                    console.error("Invalid JSON:", response);
                                    return;
                                }
                            }

                            // Clear and repopulate the dropdown
                            const $dropdown = $(".ticket_subcategory");
                            $dropdown.empty().append('<option value="">-- Select Sub Category --</option>');

                            $.each(jsonData, function (i, item) {
                                $dropdown.append(`<option value="${item.val}">${item.option_name}</option>`);
                            });

                            // Trigger Select2 refresh if used
                            $dropdown.trigger("change.select2");
                        },
                        error: function (xhr, status, error) {
                            console.error("AJAX Error:", error);
                        }
                    });
                //}

            });

            var assign_selected = $('.assigned_type option:selected').text();
            if (assign_selected == "Direct" && assign_selected != "") {
                $('.assign_direct').show();
            } else {
                $('.assign_direct').hide();
            }
            if (assign_selected == "Head of the Department" && assign_selected != "") {
                $('.assign_lead').show();
            } else {
                $('.assign_lead').hide();
            }
            $(document).on('change', '.assigned_type', function () {
                var assign_selected = $('.assigned_type option:selected').text();
                if (assign_selected == "Direct") {
                    $('.assign_direct').show();
                    $('.assigned_to').attr('required', true);
                    $('.assign_lead').hide();
                    $('.department_lead').removeAttr('required').val('');
                    $('.department').val('');
                } else if (assign_selected == "Head of the Department") {
                    $('.assigned_to').removeAttr('required').val('');
                    $('.assign_direct').hide();
                    $('.assign_lead').show();
                    $('.department_lead').attr('required', true);
                } else {
                    $('.assign_direct').hide();
                    $('.assign_lead').hide();
                    $('.assigned_to').removeAttr('required').val('');
                    $('.department_lead').removeAttr('required').val('');
                    $('.department').val('');
                }

            });

            $(document).on('change', '.team_type', function () {

                var team_type = $('.team_type').select2('val');

                if (team_type == 'Internal Team') {
                    $(".assigned_type option[value='Direct']").attr('disabled', false);
                    $(".assigned_type option[value='Head of the Department']").attr('disabled', 'disabled');
                } else if (team_type == 'External Team') {
                    $(".assigned_type option[value='Direct']").attr('disabled', false);
                    $(".assigned_type option[value='Head of the Department']").attr('disabled', false);
                }

            });

            $(document).on('change', '.GetFileSizeNameAndType', function () {
                var fi = document.getElementById('choosefile'); // GET THE FILE INPUT AS VARIABLE.
                var totalFileSize = 0;
                // VALIDATE OR CHECK IF ANY FILE IS SELECTED.
                if (fi.files.length > 0) {
                    // RUN A LOOP TO CHECK EACH SELECTED FILE.
                    for (var i = 0; i <= fi.files.length - 1; i++) {
                        //ACCESS THE SIZE PROPERTY OF THE ITEM OBJECT IN FILES COLLECTION. IN THIS WAY ALSO GET OTHER PROPERTIES LIKE FILENAME AND FILETYPE
                        var fsize = fi.files.item(i).size;
                        totalFileSize = totalFileSize + fsize;
                        document.getElementById('fp').innerHTML =
                            document.getElementById('fp').innerHTML
                            +
                            '<tr><td><span class="note" ><br /> File:<span class="files">' + fi.files.item(i).name + '</span>&nbsp;<img src="{{URL::to('')}}/images/cancel.png" class="delete_user"></span></td></tr>';
                    }
                }
                //document.getElementById('divTotalSize').innerHTML = "Total File(s) Size is <b>" + Math.round(totalFileSize / 1024) + "</b> KB";
                /*file upload validation*/
                $('.choosefile').change(function () {

                    var fp = $(".choosefile");

                    var lg = fp[0].files.length; // get length

                    var items = fp[0].files;

                    var fileSize = 0;



                    if (lg > 0) {

                        for (var i = 0; i < lg; i++) {

                            fileSize = fileSize + items[i].size; // get file size

                        }

                        if (fileSize > 10485760) {

                            showCustomAlert('File size must not be more than 10MB','error');

                            $('.choosefile').val('');

                        }

                    }

                });
                /*file upload validation*/
            });

            <?php if ($pageMethod == "taskmanagerupdate" || $pageMethod == "taskmanagerfinalapproval") {?>
            $('.task,.end_date,.team_typerd,.assigned_typerd,.priorityrd,.departmentrd,.assigned_tord,.ticket_categoryrd,.ticket_subcategoryrd').css('pointer-events', 'none');
            <?php }?>
            <?php if ($pageMethod == "taskmanagerapproval") { ?>
            $('.task,.end_date,.team_typerd,.assigned_typerd,.priorityrd,.departmentrd,.ticket_categoryrd,.ticket_subcategoryrd').css('pointer-events', 'none');
            <?php } ?>
            $(document).on('submit', 'form', function (e) {
                let status = $('select[name="status"]').val();

                if (!status) {
                    alert('Please select a status.');
                    e.preventDefault(); // Prevent form submission
                    return false;
                }
            });
        });

        // SAVE finction


        $(document).on('click', '.saveform', function (e) {

            e.preventDefault();

            const btnVal = $(this).val();
            const $form = $('.taskmanager');

            // Set status based on button clicked
            let finalStatus = '';

                // 1️⃣ If update status dropdown has value → use it
                if ($('.updatestatus').length && $('.updatestatus').val()) {
                    finalStatus = $('.updatestatus').val();
                }
                // 2️⃣ Else use workflow status from button
                else {
                    const statusMap = {
                        'SAVE': 'INITIATED',
                        'APPROVED': 'APPROVED',
                        'REJECTED': 'REJECTED',
                        'ALLOCATED': 'ALLOCATED'
                    };
                    finalStatus = statusMap[btnVal] || '';
                }

                // 3️⃣ Set hidden status (this WILL reach controller)
                $('#final_status').val(finalStatus);

            // Validate form using Parsley
            $form.parsley().validate();
            if (!$form.parsley().isValid()) return;
            const formData = new FormData($form[0]);
            var $btn = $(this);
            $btn.prop('disabled', true);

            $.ajax({
                url: "{{ url('taskmanagersave') }}",
                type: "POST",
                data: formData,
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                success: function (data) {
                    const { status, message, id } = data;

                    showCustomAlert('Saved successfully!','success');

                    const redirectUrl = (btnVal === 'SAVENEW')
                        ? "{{ url('taskmanagercreate') }}/0"
                        : "{{ URL::to($pageMethod) }}";

                    setTimeout(() => {
                        window.location.href = redirectUrl;
                    }, 1500);
                },
                error: function (xhr) {
                showCustomAlert('Saved successfully!','success');
                         setTimeout(() => {
                        window.location.href = redirectUrl;
                    }, 1500);
                }
            });
        });

        $(document).on("focus", ".expected_date", function () {

            $(this).datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: "yy-mm-dd",
                minDate: 0,
                maxDate: +90,
                showAnim: "slideDown",
                yearRange: "-25:+0",

            });
        });
    </script>
@endpush