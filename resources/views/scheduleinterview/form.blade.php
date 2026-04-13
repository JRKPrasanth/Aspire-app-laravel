@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Interview</h3>
    @include('layouts.breadcrumb')



    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body">

            <form id="interviewschedule" data-parsley-validate>
                {{ csrf_field() }}
                <input type="hidden" name="edit_id" id="edit_id" />

                <div class="row g-4">

                    <!-- LEFT SIDE -->
                    <div class="col-md-6">

                        <!-- Candidate Name -->
                        <div class="mb-3 row">
                            <label class="col-md-5 col-form-label fw-semibold">
                                <span class="text-danger">*</span> Candidate Name
                            </label>
                            <div class="col-md-6">
                                <select id="name_of_the_candidate" name="name_of_the_candidate" class="form-select select2"
                                    required>
                                    {!! $row->employee !!}
                                </select>
                            </div>
                        </div>

                        <!-- Job Description -->
                        <div class="mb-3 row">
                            <label class="col-md-5 col-form-label fw-semibold">
                                <span class="text-danger">*</span> Job Description
                            </label>
                            <div class="col-md-6">
                                <select id="job_description_name" name="job_description_name" class="form-select select2"
                                    required>
                                    {!! $row->job_description !!}
                                </select>
                            </div>
                        </div>

                        <!-- Active -->
                        <div class="mb-3 row">
                            <label class="col-md-5 col-form-label fw-semibold">Active</label>
                            <div class="col-md-6">
                                <select id="active" name="active" class="form-select select2">
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <!-- RIGHT SIDE -->
                    <div class="col-md-6">

                        <!-- Interview Date -->
                        <div class="mb-3 row">
                            <label class="col-md-5 col-form-label fw-semibold">
                                <span class="text-danger">*</span> Interview Date & Time
                            </label>
                            <div class="col-md-7">
                                <input type="text" class="form-control start_date_time" id="interview_date"
                                    name="interview_date" required>
                            </div>
                        </div>

                        <!-- Remarks -->
                        <div class="mb-3 row">
                            <label class="col-md-5 col-form-label fw-semibold">Remarks</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" id="remarks" name="remarks">
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Buttons -->
                <div class="text-center mt-4">
                    <button type="button" class="btn btn-success px-4 save_form me-2">Save</button>
                    <?php include('toolbar.php'); ?>
                    @if ($pageUrl == "searchcandidate" || $pageUrl == "resumecollection")
                        <a class="btn btn-secondary px-4" href="{{ url($pageModule) }}">Cancel</a>
                    @endif
                </div>

            </form>

            @if ($pageUrl == "createinterview")
                <div class="row mt-4">
                    <div class="col-md-12">
                        <table id="grid1"></table>
                    </div>
                </div>
            @endif

        </div>
    </div>



    <form id="updateselection" data-parsley-validate>
        {{ csrf_field() }}

        <input type="hidden" id="update_interview_id" name="update_interview_id">

        <div class="modal fade" id="myModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content rounded-4 shadow-lg">

                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Interview Process</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="row g-4">

                            <!-- LEFT -->
                            <div class="col-md-6">

                                <div class="mb-3">
                                    <label class="form-label"><span class="text-danger">*</span> Name</label>
                                    <input type="text" id="can_name" name="can_name" class="form-control" readonly>
                                    <input type="hidden" id="cand_name" name="cand_name">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><span class="text-danger">*</span> Interview Process</label>
                                    <select id="inter_process" name="inter_process" class="form-select select2"
                                        required></select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><span class="text-danger">*</span> Department</label>
                                    <select id="department_id" name="department_id" class="form-select select2"
                                        required></select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><span class="text-danger">*</span> Process Status</label>
                                    <select id="process_status" name="process_status" class="form-select select2" required>
                                        <option value="">-- Please Select --</option>
                                        <option value="1">Selected</option>
                                        <option value="2">Waiting List</option>
                                        <option value="3">Rejected</option>
                                    </select>
                                </div>

                                <div class="mb-3 select_status">
                                    <label class="form-label"><span class="text-danger">*</span> Selection Status</label>
                                    <select id="selection_status" name="selection_status" class="form-select select2"
                                        required>
                                        <option value="">-- Please Select --</option>
                                        <option value="1">Yes</option>
                                        <option value="2">No</option>
                                    </select>
                                </div>

                            </div>

                            <!-- RIGHT -->
                            <div class="col-md-6">

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <input id="description_name" name="description_name" class="form-control">
                                    <input type="hidden" id="description_id" name="description_id">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><span class="text-danger">*</span> Percentage %</label>
                                    <input type="text" id="percentage" name="percentage" class="form-control" maxlength="2">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><span class="text-danger">*</span> Position</label>
                                    <select id="j_title" name="j_title" class="form-select select2"></select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Remark</label>
                                    <textarea id="ramarks1" name="ramarks1" class="form-control"></textarea>
                                </div>

                                <div class="mb-3 date_of_join">
                                    <label class="form-label"><span class="text-danger">*</span> Date of Joining</label>
                                    <input type="text" id="date_of_joining" name="date_of_joining"
                                        class="form-control datepicker">
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-success me-2 px-4 save_popup">Save</button>
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    </div>

                </div>
            </div>
        </div>
    </form>



@endsection
@push('scripts')

    <script>

        $(document).ready(function () {
            /** Jqgrid SCHEDULE INTERVIEW Load data Start **/

            var date_format = "{{\Session::get('p_date_format')}}";
            var date_format = "{{\Session::get('p_date_format')}}";
            <?php if ($pageUrl == "searchcandidate" || $pageUrl == "resumecollection") { ?>
            $('.delete,.more').hide();
            <?php } ?>
            <?php if ($pageUrl == "createinterview") { ?>

            /** Jqgrid Interview Schedule load data Start **/
            $("#grid1").jqGrid(
                {
                    url: "interveiwschedulregriddata",
                    datatype: "json",
                    mtype: "GET",
                    colModel: [
                        { name: "interview_id", label: "interview_id", width: 250, hidden: true },
                        { name: "name_of_the_candidate", label: "Candidate  Name.", width: 250 },
                        { name: "resume_id", label: "Candidate  Name.", width: 250, hidden: true },
                        { name: "department", label: "Department", width: 250, hidden: true },
                        { name: "description_name", label: "Description Name", width: 250 },
                        { name: "description_id", label: "Description Name", width: 250, hidden: true },
                        { name: "job_description_name", label: "Description Name", width: 250, hidden: true },
                        { name: "interview", label: "Interview Process", width: 250, hidden: true },
                        { name: "job_title", label: "Job Titile", width: 250, hidden: true },
                        { name: "date", label: "Date", width: 250, editable: true, formatter: 'date', editrules: { date: true }, formatoptions: { newformat: date_format } },
                        { name: "interview_date", label: "Interview Date", width: 250 },
                        { name: "interview_process", label: "Interview Process", width: 250, hidden: true },
                        { name: "result", label: "Status", width: 250, search: false },
                        { name: "remarks", label: "Remarks", width: 250, hidden: true },
                        { name: "active", label: "Active", width: 250 },
                    ],

                    iconSet: "fontAwesome",
                    rowNum: 10,
                    rowList: [10, 20, 50, 100, 200, 250, 500, 1000, 2000],
                    sortname: "interview_id",
                    sortorder: "desc",
                    viewrecords: true,
                    gridview: true,
                    rownumbers: true,
                    pager: "#grid1",
                    multiselect: false,
                    multipageselection: true,
                    searching: {
                        defaultSearch: "cn",
                    },
                });
            /** Jqgrid Interview Schedule load data End **/

            jQuery("#grid1").jqGrid('filterToolbar', { stringResult: true, searchOnEnter: false });
            $("#gs_date").attr("placeholder", "Eg:2018-10-31");
            $("#gs_interview_date").attr("placeholder", "Eg:2018-10-31");

            /** Jqgrid Interview Schedule Export to Pdf Start **/
            $(document).on('click', ".exportpdf", function () {
                $("#grid1").jqGrid('exportToPdf', {
                    title: null,
                    orientation: 'portrait',
                    pageSize: 'A4',
                    description: null,
                    onBeforeExport: null,
                    download: 'download',
                    includeLabels: true,
                    includeGroupHeader: true,
                    includeFooter: true,
                    fileName: "Interview.pdf",
                    mimetype: "application/pdf"
                });

            });
            /** Jqgrid Interview Schedule Export to Pdf End **/


            $(document).on('click', ".jcr_name_of_the_candidate", function () {

                $("#name_of_the_candidate").jCombo("{{ URL::to('jcomboformcomp?table=add_resume:resume_id:name_of_the_candidate') }}",
                    { selected_value: '' });
            });

            $(document).on('click', ".jcr_job_description_name", function () {
                $("#job_description_name").jCombo("{{ URL::to('jcomboform?table=hr_job_description:description_id:description_name') }}",
                    { selected_value: '' });
            });






            /** Jqgrid Interview Schedule Export to Excel Start **/
            $(document).on('click', ".exportexcel", function () {
                $("#grid1").jqGrid("exportToExcel", {
                    includeLabels: true,
                    includeGroupHeader: true,
                    includeFooter: true,
                    fileName: "Interview.xlsx"
                })
            });
            /** Jqgrid Interview Schedule Export to Excel End **/

            <?php } ?>

            /** Interview Schedule Date Picker change Formate Start **/
            var today = new Date();
            today.setDate(today.getDate() - 1);
            var todaytime = new Date();
            todaytime.setHours(0);
            todaytime.setDate(today.getDate() + 1);
            $('.interview_date').datetimepicker({ format: 'dd-mm-yyyy hh:ii:ss', autoClose: true, startDate: todaytime });
            $('.interview_date').datetimepicker({ format: 'dd-mm-yyyy hh:ii:ss', autoClose: true, }).on('changeDate', function (ev) {
                $(this).datetimepicker('hide');
                $(this).parsley().validate();
            });
            $(".clearsearch").click(function () {
                var grid = $("#grid1");
                grid.jqGrid('setGridParam', { search: false });
                var postData = grid.jqGrid('getGridParam', 'postData');
                $.extend(postData, { filters: "" });
                grid.trigger("reloadGrid", [{ page: 1 }]);
                $('input[id*="gs_"]').val("");

            });
            /** Interview Schedule Date Picker change Formate End **/



            $(document).on('keypress', '#percentage', function (ev) {
                var regex = new RegExp("^[0-9.]+$");
                var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
                if (regex.test(str)) {
                    return true;
                }
                ev.preventDefault();
                return false;
            });


            /** Interview Schedule Edit Start **/
            $(".edit").click(function () {

                var form = $("#interviewschedule");
                form.parsley().destroy();
                var index = $("#grid1").jqGrid('getGridParam', 'selrow');
                var interview_id = $("#grid1").jqGrid('getCell', index, 'interview_id');
                var candidate_id = $("#grid1").jqGrid('getCell', index, 'resume_id');

                var name_of_the_candidate = $("#grid1").jqGrid('getCell', index, 'name_of_the_candidate');
                var description_name = $("#grid1").jqGrid('getCell', index, 'job_description_name');
                var department = $("#grid1").jqGrid('getCell', index, 'department');
                var desc_name = $("#grid1").jqGrid('getCell', index, 'description_name');
                var remarks = $("#grid1").jqGrid('getCell', index, 'remarks');
                var interview_date = $("#grid1").jqGrid('getCell', index, 'interview_date');
                var interview_process = $("#grid1").jqGrid('getCell', index, 'interview');
                var job_title = $("#grid1").jqGrid('getCell', index, 'job_title');

                if (index) {
                    $('#name_of_the_candidate').select2('val', [name_of_the_candidate]);
                    $('#job_description_name').select2('val', [description_name]);
                    $('#remarks').val(remarks);
                    $('#interview_date').val(interview_date);
                    $('#edit_id').val(interview_id);

                }
                else {
                    showCustomAlerts('Please Select a Row', 'info');
                }

            });

            $(document).on('click', '.updatestatus', function () {
                var index = $("#grid1").jqGrid('getGridParam', 'selrow');
                var interview_id = $("#grid1").jqGrid('getCell', index, 'interview_id');
                var candidate_id = $("#grid1").jqGrid('getCell', index, 'resume_id');
                var name_of_the_candidate = $("#grid1").jqGrid('getCell', index, 'name_of_the_candidate');
                var description_name = $("#grid1").jqGrid('getCell', index, 'description_name');
                var description_id = $("#grid1").jqGrid('getCell', index, 'description_id');
                var interview_process = $("#grid1").jqGrid('getCell', index, 'interview');
                var desc_name = $("#grid1").jqGrid('getCell', index, 'description_name');
                var department = $("#grid1").jqGrid('getCell', index, 'department');
                var remarks = $("#grid1").jqGrid('getCell', index, 'remarks');
                var interview_date = $("#grid1").jqGrid('getCell', index, 'interview_date');
                var job_title = $("#grid1").jqGrid('getCell', index, 'job_title');
                if (index) {

                    $('#myModal').modal('show');
                    $('#myModal').width("100%");
                    $('#can_name').val(name_of_the_candidate);
                    $('#cand_name').val(candidate_id);
                    $('#description_name').val(description_name);
                    $('#description_id').val(description_id);
                    $('#remarks').val(remarks);
                    $('#interview_date').val(interview_date);
                    $('#update_interview_id').val(interview_id);
                    var form = $("#updateselection");
                    form.parsley().destroy();

                    var condition = 'department_line_id=' + department;
                    $(".department_id").jCombo("{{ URL::to('jcomboform?table=m_department_lines_t:department_line_id:sub_department_name') }}&parent=" + condition + '&orderby=sub_department_name asc', { selected_value: department });

                    var condition1 = 'job_title_id=' + job_title;
                    $(".job_title").jCombo("{{ URL::to('jcomboform?table=m_job_title:job_title_id:job_title_name') }}&parent=" + condition1 + '&orderby=job_title_name asc', { selected_value: job_title });

                    interview_process = (interview_process.replace('["', ''));
                    interview_process = (interview_process.replace('"]', ''));
                    //  interview_process =(interview_process.replace('"',''));

                    var condition2 = ' and interview_steps_id IN ("' + interview_process + '")';

                    $(".inter_process").jCombo("{{ URL::to('jcomboform1?table=m_interview_steps:interview_steps_id:interview_process') }}&parent=" + condition2 + '&order_by=interview_process asc', { selected_value: '' });
                }
                else {
                    showCustomAlerts('Please  Select a Row', 'info');
                }

            });
            /** Interview Schedule UPDATE Status End **/


            /** Interview Schedule Clear input Start **/
            $(document).on('click', '.clear', function () {

                var form = $("#interviewschedule");
                form.parsley().destroy();

                $('#name_of_the_candidate').val('').select2();
                $('#job_description_name').val('').select2();
                $('#remarks').val('');
                $('#interview_date').val('');
                $('#edit_id').val('');

            });
            /** Interview Schedule Clear input End **/

            /** Interview Schedule Date Of Join Start **/
            $('.date_of_join').css('display', 'none');
            /** Interview Schedule Process Status Start **/
            $(document).on('change', '.process_status', function () {
                var process_status = $('.process_status').select2('val');
                if (process_status == 3) {
                    $('.select_status').css("display", "none");
                    $('.selection_status').removeAttr("required", "false");
                    $('.date_of_join').css("display", "none");
                }
                else {
                    $('.select_status').css("display", "block");
                    $('.selection_status').attr("required", "true");
                }
            });
            /** Interview Schedule Process Status eND **/

            $(document).on('change', '.selection_status', function () {

                var selection_status = $('.selection_status').select2('val');

                if (selection_status == 1 || selection_status == 1) {
                    $('.date_of_join').css('display', 'block');
                    $('#date_of_joining').attr('required', 'true');
                    $('selection_status').attr('selected', 'selected');
                }
                else if (selection_status == "" || selection_status == null || selection_status == 2) {
                    $('.date_of_join').css('display', 'none');
                    $('#date_of_joining').removeAttr('required', 'false');
                    $('selection_status').attr('selected', 'selected');
                }

            });

            /** Interview Schedule Date Of Join End **/

            /** Interview Schedule Save Start **/
            $(document).on('click', '.save_form', function () {
                var url = "{{url('scheduleinterviewsave')}}";

                var form = $('#interviewschedule');
                form.parsley().validate();
                var form = $('#interviewschedule');
                form.parsley().validate();
                if (form.parsley().isValid()) {

                    var data = $('#interviewschedule').serialize();
                    $.post(url, data, function (data) {

                        var data = $.trim(data);
                        if (data == 1) {
                            showCustomAlert('Scheduled Interview  Successfully', 'success');
                            <?php if ($pageUrl == "searchcandidate" || $pageUrl == "resumecollection") { ?>
                            var indexurl = "{{URL::to($pageUrl)}}";
                            window.location.href = indexurl;
                            <?php } else { ?>
                            $("#grid1")[0].triggerToolbar();
                            $(".ajaxLoading").hide();
                            $('.clear').trigger('click');
                            $('.name_of_the_candidate').val('').select2();
                            $('.job_description_name').val('').select2();
                            $('#interviewschedule')[0].reset();
                            var indexurl = "{{URL::to('resumecollection')}}";
                            window.location.href = indexurl;
                            <?php } ?>
                            setTimeout(function () {
                                $("#interviewschedule").parsley().destroy();
                            }, 300);

                            $(".ajaxLoading").hide();
                        }
                        else {

                            showCustomAlert('Scheduled Updated Successfully', 'success');
                            $("#interviewschedule").parsley().destroy();

                            <?php if ($pageUrl == "searchcandidate" || $pageUrl == "resumecollection") { ?>
                            <?php } else { ?>
                            $("#grid1")[0].triggerToolbar();
                            $(".ajaxLoading").hide();
                            $('.clear').trigger('click');
                            <?php }?>
                            $('#interviewschedule')[0].reset();
                            $('#name_of_the_candidate').val('').select();
                            $('#interviewschedule').val('').select();

                            setTimeout(function () {
                                $("#interviewschedule").parsley().destroy();
                            }, 300);


                            $(".ajaxLoading").hide();
                        }

                    });

                }
            });
            /** Interview Schedule Save End **/

            /** Interview Schedule Save Popup  Start **/
            $(document).on('click', '.save_popup', function () {
                var url = "{{url('saveselectionstatus')}}";
                var data = $('#updateselection').serialize();
                var form = $('#updateselection');
                form.parsley().validate();
                var form = $('#updateselection');
                form.parsley().validate();
                if (form.parsley().isValid()) {
                    $.post(url, data, function (data1) {
                        change_date();
                        if (data1 == 1) {
                            showCustomAlert('Interview Process Updated  Successfully', 'success');
                            $("#grid1")[0].triggerToolbar();
                            $('#interviewschedule')[0].reset();
                            $('.close').trigger('click');
                        }
                        else {
                            showCustomAlert('Scheduled Updated Successfully', 'success');
                            $("#grid1")[0].triggerToolbar();
                            $('#interviewschedule')[0].reset();
                            $('#name_of_the_candidate').val('').select();
                            $('#interviewschedule').val('').select();
                            $('.close').trigger('click');
                        }

                    });
                }
            });
            /** Interview Schedule Save Popup End **/

            /** Interview Schedule Save Delete Start **/

            $(document).on('click', '.delete', function (e) {
                e.preventDefault();

                var index = jQuery("#grid1").jqGrid('getGridParam', 'selrow');
                var interview_id = $("#grid1").jqGrid('getCell', index, 'interview_id');

                if (interview_id) {
                    swal({
                        title: "Are you sure?",
                        text: "You want to delete!",
                        type: "warning",

                        showCancelButton: !0,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes",
                        cancelButtonText: "No",
                        closeOnCancel: !1
                    }, function (e) {
                        if (e == true) {
                            $.get('deleteschedule/delete?del_id=' + interview_id, function (data) {

                                if (data == 1) {
                                    setTimeout(function () {
                                        showCustomAlerts('Cannot Be Delete.Which is in Approved State or Used in Some Where', 'info');
                                        location.reload();

                                    }, 2000);

                                }
                                else if (data == 2) {
                                    showCustomAlert('Interview Details Deleted Successfully', 'info');
                                    location.reload();
                                }
                            });
                        }
                        else {
                            location.reload();
                            $('.apply').css('display', 'none');
                            swal("Cancelled");
                        }
                    });
                    $('.apply').css('display', 'none');
                }
                else {
                    showCustomAlerts('Please Select a Row', 'info');


                }


            });


            /** Interview Schedule Save Delete End **/

            /** Interview Schedule Date Of Joining **/
            $('#date_of_joining').datepicker({ format: 'yyyy-mm-dd', autoClose: true });
        });

        $(document).on('keypress', '#description_name', function (ev) {

            var regex = new RegExp("^[a-z,A-Z.]+$");
            var str = String.fromCharCode(!ev.charCode ? ev.which : ev.charCode);
            if (regex.test(str)) {
                return true;
            }
            ev.preventDefault();
            return false;
        });


    </script>


@endpush