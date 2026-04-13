@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Sales Enquiry</h3>
    @include('layouts.breadcrumb')
    <div id="toolbar-container" class="mb-3 mt-2"></div>

    <div class="card shadow-lg rounded-4 border-0">
        <div class="container mt-4">
            <div class="table-responsive">
                <table id="EnquiryTbl" class="table table-bordered table-striped w-100">
                    <thead>
                        <tr class="table-warning">
                            <th>Enquiry No</th>
                            <th>Enquiry Date</th>
                            <th>Enquiry Type</th>
                            <th>Enquiry Status</th>
                            <th>Customer Name</th>
                            <th>Save Status</th>
                            <th>Organization</th>
                            <th>Actions</th>
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
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>

                        </tr>
                    </thead>
                    <tbody>
                        {{-- DataTable will populate via AJAX --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- popups   -->

    <!-- Closing Inquiry Modal -->
    <div class="modal fade" id="myModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg rounded-4 border-0">
                <div class="modal-header bg-warning bg-gradient text-dark">
                    <h5 class="modal-title">Closing Inquiry Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <h5 class="fw-semibold">Do you want to close this inquiry?</h5>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" id="yes" class="btn btn-danger px-4" value="yes">Yes</button>
                    <button type="button" id="cancel" class="btn btn-secondary px-4" data-bs-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Configuration Modal -->
    <div class="modal fade" id="config_modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow rounded-4 border-0">
                <div class="modal-header bg-info bg-gradient text-white">
                    <h5 class="modal-title">Configuration</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="form_body"></div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-primary save ok px-4">OK</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Contact Modal -->
    <div class="modal fade" id="contactModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content shadow-lg rounded-4 border-0">
                <div class="modal-header bg-primary bg-gradient text-white">
                    <h5 class="modal-title">Contact</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <form method="POST" action="" class="form-horizontal" id="enquirymail" enctype="multipart/form-data"
                    data-parsley-validate>
                    <div class="modal-body">

                        <!-- Customer Info -->
                        <div class="mb-3">
                            <label class="form-label">Customer Name</label>
                            <input type="text" name="customer_name" class="form-control customer_name" readonly>
                            <input type="hidden" name="so_inquiry_hdr_id" class="so_inquiry_hdr_id">
                        </div>

                        <!-- Contact Table -->
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th></th>
                                        <th>Contact Person</th>
                                        <th>Contact Number</th>
                                        <th>Contact Email</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="mcontent2"></tbody>
                            </table>
                        </div>

                        <!-- CC Field -->
                        <div class="mb-3">
                            <label for="cc" class="form-label">CC</label>
                            <input type="text" name="cc[]" class="form-control cc">
                        </div>

                        <!-- Message -->
                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea name="msg" class="msg tinymce form-control" rows="6">
            Thanks and Regards,
            Dispatch Department,
            JRKS,
            Kundrathur.
                        </textarea>
                            <input type="hidden" name="hdr_id" class="msg hdr_id">
                        </div>

                        <!-- Attachments -->
                        <div class="mb-3">
                            <label class="form-label">Attachments</label>
                            <input type="file" name="email_attachment[]" class="form-control" multiple>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" name="attchment" class="form-check-input attchment" id="attachPdf">
                            <label class="form-check-label" for="attachPdf">Attach PDF</label>
                        </div>

                        <div class="preview mb-3"></div>

                        <!-- PDF Preview -->
                        <iframe id="iframepdf" width="100%" height="400" class="d-none border rounded"></iframe>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-success sendmail px-4" id="sentmail_id">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>




@endsection
@push('scripts')

    <script>


        // button purpose
        // Add create button purpose
        $(document).ready(function () {
            if (window.toolbarButtons?.some(btn => btn.attr.id === 'create_standard')) {
                $('#toolbar-container').append(`
                        <button class="btn btn-primary create_standard me-2">Create Standard
                           <i class="bi bi-plus-circle"></i> 
                        </button>
                      `);
            }

            if (window.toolbarButtons?.some(btn => btn.attr.id === 'create_labour')) {
                $('#toolbar-container').append(`
                        <button class="btn btn-success create_labour me-2">Create Labour
                           <i class="bi bi-plus-circle"></i> 
                        </button>
                      `);
            }

        });


        // Add create button purpose
        $(document).ready(function () {
            if (window.toolbarButtons?.some(btn => btn.attr.id === 'create')) {
                $('#toolbar-container').append(`
                            <button class="btn btn-info text-white px-4 create me-2">Create
                              <i class="bi bi-plus-circle"></i> 
                            </button>
                          `);
            }
        });

        var inquiry_status = '<?php echo $inquiry_status; ?>';
        var pageMethod = '<?php echo $pageMethod; ?>';

        // data table funcrion	
        $(document).ready(function () {

            var table = $('#EnquiryTbl').DataTable({
                processing: true,
                serverSide: true,
                ajax: "getSalesinquiryData?status=" + inquiry_status + "&pageMethod=" + pageMethod,
                order: [[1, 'desc']],
                columns: [

                    { data: 'inquiry_no', name: 'inquiry_no' },
                    { data: 'inquiry_date', name: 'inquiry_date' },
                    { data: 'inquiry_type', name: 'inquiry_type' },
                    { data: 'inquiry_status', name: 'inquiry_status' },
                    { data: 'customer_name', name: 'customer_name' },
                    { data: 'savestatus', name: 'savestatus' },
                    { data: 'organization_id', name: 'organization_id' },

                    {
                        data: 'so_inquiry_hdr_id',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',

                        render: function (data, type, row) {
                            let buttons = '';
                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                                buttons += `
                      <button class="btn btn-sm btn-warning view-btn" data-id="${row.so_inquiry_hdr_id}">
                        <i class="bi bi-eye"></i>
                      </button>`;
                            }

                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                                buttons += `
                      <button class="btn btn-sm btn-primary edit-btn" data-id="${row.so_inquiry_hdr_id}" data-status="${row.inquiry_status}">
                        <i class="bi bi-pencil"></i>
                      </button>`;
                            }

                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'closeinquiry')) {
                                buttons += `
                      <button class="btn btn-sm btn-success close-btn" data-id="${row.so_inquiry_hdr_id}" data-status="${row.inquiry_status}"
                              data-bs-toggle="tooltip" 
                            data-bs-placement="top" 
                            title="Close Enquiry">
                       <i class="bi bi-calendar-x"></i>
                      </button>`;
                            }

                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                                buttons += `
                      <button class="btn btn-sm btn-danger delete-btn" data-id="${row.so_inquiry_hdr_id}">
                        <i class="bi bi-trash"></i>
                      </button>`;
                            }
                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'copyinquiry')) {
                                buttons += `
                      <button class="btn btn-sm btn-primary copy-btn" data-id="${row.so_inquiry_hdr_id}">
                       Copy
                      </button>`;
                            }
                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'convert')) {
                                buttons += `
                      <button class="btn btn-sm btn-success convert-btn" data-id="${row.so_inquiry_hdr_id}">
                       Convert
                      </button>`;
                            }
                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'convertorder')) {
                                buttons += `
                      <button class="btn btn-sm btn-success convert1-btn" data-id="${row.so_inquiry_hdr_id}">
                       Convert
                      </button>`;
                            }
                            return buttons;
                        }
                    }
                ]
            });

            // Individual column search
            $('#EnquiryTbl thead').on('keyup change', ".column-search", function () {
                var colIndex = $(this).parent().index();
                table.column(colIndex).search(this.value).draw();
            });
        });


        //create button

        $(document).on('click', '.create_standard', function () {
            var enqtype = "STANDARD";
            var url = "{{ url('salesinquirycreate') }}/0/" + enqtype;
            var red_url = "{{ url('salesinquiry') }}";
            window.location.replace(url);
        });

        $(document).on('click', '.create_labour', function () {
            var enqtype = "LABOUR";
            var url = "{{ url('salesinquirycreate') }}/0/" + enqtype;
            var red_url = "{{ url('salesinquiry') }}";
            window.location.replace(url);
        });

        //edit

        $(document).on('click', '.edit-btn', function () {


            const id = $(this).data('id');
            const status = $(this).data('status');

            if (status != "INITIATED") {
                var url = "{{ url('salesinquirycreate') }}";
                var editUrl = url + '/' + id;
                window.location.replace(editUrl);
            }
            else {
                showCustomAlert("Approved or Submitted Inquiry Cannot Be Edit!!!", "warning");
            }

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
                    url: "{{ url('salesinquirydelete') }}/" + deleteId,
                    type: "GET",
                    success: function (data) {
                        if (data == '0') {
                            $('#globalDeleteModal').modal('hide');
                            showCustomAlert('Deleted Successfully', 'success');
                            $('#EnquiryTbl').DataTable().ajax.reload();
                        }
                        if (data == '2') {
                            $('#globalDeleteModal').modal('hide');
                            showCustomAlert("You Cant't delete , Purchase Enquiry Used in SomeWhere", 'info');
                            $('#EnquiryTbl').DataTable().ajax.reload();
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


        //view
        $(document).on('click', '.view-btn', function () {

            const id = $(this).data('id');


            <?php if ($pageMethod == "salesinquiry") { ?>
            window.location.replace('salesinquiryview/' + id);
            <?php } else { ?>
            window.location.replace('copysalesinquiryview/' + id);
            <?php } ?>

        });

        // close enquiry
        $(document).on('click', '.close-btn', function () {

            const id = $(this).data('id');
            const inquiry_status = $(this).data('status');

            if (inquiry_status != "COMPLETED" && inquiry_status != "CANCELLED" && inquiry_status != "DRAFT" && inquiry_status != "CLOSED") {
                $('#myModal').modal('show');
                $('#yes').click(function () {
                    var val = $(this).val();
                    if (val == 'yes') {
                        $.get("{{URL::to('salesinquiryupdatestatus')}}/" + id, function (data) {
                            showCustomAlert("Sales Inquiry Closed Successfully", "success");
                            location.reload();

                        });
                    }
                });
            } else {
                showCustomAlert("Please select Initiated Records", "info");
            }

        });

        // copy
        $(document).on('click', '.copy-btn', function () {

            const id = $(this).data('id');


            var url = "{{ url('salesinquirystatus')}}/" + id;
            $.get(url, function (data) {
                var data = $.trim(data);
                var red_url = "{{ url('salesinquiry') }}";
                var data = $.trim(data);
                if (data == 'SAVE')
                    window.location.replace('copysalesinquiry/' + id);
                else
                    showCustomAlert(" Please Save Enquiry First. Which is in " + data + " Status!!!", "info");
            });

        });


        // convert

        $(document).on('click', '.convert-btn', function () {

            const id = $(this).data('id');

            var url = "{{ url('salesinquirystatus')}}/" + id;
            $.get(url, function (data) {
                var data = $.trim(data);
                var red_url = "{{ url('salesinquiry') }}";
                var data = $.trim(data);
                if (data == 'SAVE')
                    window.location.replace('salesenquiryconvert/' + id);
                else
                    showCustomAlert(" Please Save Enquiry First. Which is in " + data + " Status!!!", "info");
            });

        });


        // convert sales order

        $(document).on('click', '.convert1-btn', function () {

            const id = $(this).data('id');

            var url = "{{ url('salesinquirystatus')}}/" + id;
            $.get(url, function (data) {
                var data = $.trim(data);
                var red_url = "{{ url('salesinquiry') }}";
                var data = $.trim(data);
                if (data == 'SAVE')
                    window.location.replace('salesorderfromenquiry/' + id);
                else
                    showCustomAlert(" Please Save Enquiry First. Which is in " + data + " Status!!!", "info");
            });

        });


        //


        $(document).ready(function () {

            $('.config').click(function (e) {
                var type = $(this).data('value')
                var btn = $(".showcolumn").val();
                var arrayval = $('.columnhide').val().split(",");
                $.get('getshowcolumns?type=' + type, function (data) {
                    var html = '';
                    $.each(data, function (index, val) {

                        var check = '';

                        if ($.inArray(index, arrayval) != '-1') {
                            check = 'checked';
                        }
                        else {
                            check = '';
                        }

                        html += "<input type='checkbox'  class='checkboxtext' name='columns[]' " + check + " value='" + index + "'>&nbsp&nbsp&nbsp" + val + "&nbsp&nbsp&nbsp<br>";
                    });
                    html += "<input type='hidden' name='type' value='" + type + "'>";
                    $("#form_body").html(html);
                });
                /** Sales Inquiry Show Column End **/

                $('#config_modal').modal('show');
            });
            /** Sales Inquiry Save Start **/
            $('.save').click(function (e) {
                $('#config_modal').modal('hide');

            });


            /**  Sales Inquiry Print Start */
            $('.print').on('click', function () {

                var id = $('#grid1').jqGrid('getGridParam', 'selrow');
                var cellvalue = $('#grid1').jqGrid('getCell', id, 'so_inquiry_hdr_id');
                var inquiry_status = $('#grid1').jqGrid('getCell', id, 'inquiry_status');

                if (id) {
                    if (inquiry_status == "INITIATED") {
                        var url = "salesinquiryprint";
                        var print_url = url + '/' + cellvalue;
                        window.open(print_url, '_blank');
                    }
                    else {
                        notyMsg('info', 'Initiated Inquiry only Print');
                    }
                } else {

                    notyMsg('info', 'Please Select Row');
                }
            });



            /**  Sales Inquiry OK Start **/
            $('.ok').click(function () {
                var defcols = $('.gridcolumns').val();
                var arrayval = $('.gridcolumns').val().split(",");
                var columnhide = [];
                $('input[name="columns[]"]:checked').each(function () {
                    var val = $(this).val();
                    jQuery("#grid1").jqGrid('showCol', [val]);
                    //$(".showcolumn").val('2');
                    $('#btnviewdetails').text('Hide Column');

                    var columns = this.value;
                    columnhide.push(columns);
                });
                var url = "{{URL::to('showcoloumnsave')}}";
                $.get(url + '?type=' + columnhide, function (data) {
                    notyMsg('success', 'Successfully!!!');

                });

                $('input[name="columns[]"]:not(:checked)').each(function () {
                    var val = $(this).val();
                    if ($.inArray(val, arrayval) != '-1') {

                    }
                    else {
                        jQuery("#grid1").jqGrid('hideCol', [val]);
                    }
                    $('#btnviewdetails').text('Hide Column');
                });

                $(".columnhide").val(columnhide);
            });


            /**  Sales Inquiry Convert to order Data Start **/
            $(".convertorder").click(function () {

                var index = $("#grid1").jqGrid('getGridParam', 'selrow');
                var quoteid = $("#grid1").jqGrid('getCell', index, 'so_inquiry_hdr_id');
                if (index) {
                    //alert();
                    var url = "{{ url('salesinquirystatus')}}/" + quoteid;
                    $.get(url, function (data) {
                        var data = $.trim(data);
                        var red_url = "{{ url('salesinquiry') }}";
                        var data = $.trim(data);
                        if (data == 'SAVE')
                            window.location.replace('salesorderfromenquiry/' + quoteid);

                        else

                            notyMsg('warning', "<i class='fa fa-exclamation-circle' style='font-size:16px'></i> Please Save Enquiry First. Which is in " + data + " Status!!!");
                    });
                }
                else {
                    notyMsg('info', "Please Select Row");
                }
            });



            /** Sales Copy Inquiry Email Data Start **/
            $('#email').on('click', function () {
                var index = $("#grid1").jqGrid('getGridParam', 'selrow');
                if (index) {
                    var postatus = $("#grid1").jqGrid('getCell', index, 'dispatch_status');
                    if (postatus != "INITIATED" && postatus != "CANCELLED" && postatus != "DRAFT") {
                        var index = $("#grid1").jqGrid('getGridParam', 'selrow');
                        var id = $("#grid1").jqGrid('getCell', index, 'so_inquiry_hdr_id');
                        var sup_id = $("#grid1").jqGrid('getCell', index, 'customer_id');
                        // alert(sup_id);
                        var sup_name = $("#grid1").jqGrid('getCell', index, 'customer_name');
                        //alert(id);
                        // var suid = $("#grid1").jqGrid ('getCell', index, 'sub_id');
                        $('.mcontent2').html('');
                        $('.customer_name').val(sup_name);
                        $('.so_inquiry_hdr_id').val(id);
                        var url_print = '{{URL::to("customermaildetails")}}/' + sup_id;
                        $.get(url_print, function (data) {
                            if (data == "") {
                                notyMsg('info', "No Email Contact For Current Customer...Please Add Email First...");
                            }
                            else {

                                $.each(data, function (key) {

                                    $('.mcontent2').append('<tr class="cont_row">\n\
                                <td><input type="checkbox" name="check_mail" class="check_mail mail_name"  value="' + data[key][3] + '" data-id="' + data[key].supplier_site_id + '" data-value="' + data[key].supplier_site_id + '" required></td>\n\
                                <td><input type="text" name="contact_person[]" class="form-control" value="' + data[key][1] + '" readonly/></td>\n\
                                <td><input type="text" name="contact_number[]" class="form-control" value="' + data[key][2] + '" readonly/></td>\n\
                                <td><input type="text" name="email_id[]" class="form-control" value="' + data[key][3] + '" readonly/></td>\n\
                                </tr>');

                                    $(".check_mail").click(function () {

                                        var check = $(this).is(":checked");
                                        var index = $(this).closest('tr').index();

                                        var att_check = $(".attchment").parent('[class*="icheckbox"]').hasClass("checked");

                                        if (att_check) {

                                        }

                                        if (check == true) {

                                            var id = $(this).data('value');

                                            $('.cont_row input:not(.check_mail)').attr('disabled', 'disabled');
                                            $('.cont_row:eq(' + index + ') input').removeAttr('disabled');

                                        }
                                        else {
                                            notyMsg('info', "Please Check Any Email Contact First...");
                                            $('.cont_row input').removeAttr('disabled');

                                        }

                                    });

                                });

                            }

                        });

                        $('#contactModal').modal('show');
                    }
                    else {
                        notyMsg("info", "Please Select Approved Only");
                    }
                }
                else {
                    notyMsgs('info', 'Please Select Row');
                }

            });
            /** Sales Copy Inquiry Email Data End **/

            /** Sales Copy Inquiry Contact Pop model Data Start **/
            $('#contactModal').on('shown.bs.modal', function () {

                var index = $("#grid1").jqGrid('getGridParam', 'selrow');
                var id = $("#grid1").jqGrid('getCell', index, 'po_hdr_id');


                $('.sendmail').click(function () {
                    var id = $("#grid1").jqGrid('getCell', index, 'so_inquiry_hdr_id');
                    var suid = $("#grid1").jqGrid('getCell', index, 'customer_id');
                    var check = $('.mail_name').is(":checked");
                    var att_check = $(".attchment").is(":checked");
                    // var mail=$("input[name='check_mail']:checked").val();
                    var mail = [];
                    $(':checkbox:checked').each(function (i) {
                        mail[i] = $(this).val();
                    });
                    var mail1 = mail.filter(function (v) { return v !== '' });
                    console.log(mail1);
                    var cc = $('.cc').val();
                    var msg = $('.msg').val();
                    if (check == true) {
                        if (att_check == true) {
                            var url = "{{ URL::to('salesinquiryprint') }}/" + id + '?mail=' + mail1 + '&cc=' + cc + '&msg=' + msg;
                            $.get(url, function (data) {
                                notyMsg('info', "Mail Send Successfully");
                                location.reload();

                            });
                            filesave();
                        } else {
                            notyMsg('info', "Please Check Attach Pdf");
                        }
                    }
                    else {
                        notyMsg('info', "Please Check Contact");
                    }
                });

                function filesave() {
                    var form_data = new FormData(document.getElementById('enquirymail'));

                    $.ajax({
                        url: "{{URL::to('salesinquiryfilesave')}}",
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


                                }, true);
                            }
                            return xhr;

                        }
                    }).done(function (data) {

                    });
                }




                $(".attchment").on("ifUnchecked", function () {
                    $('#iframepdf').attr('src', '');
                    $('#iframepdf').hide();
                    $('.highlight').removeClass('highlight');
                });

            });


            /** Sales Copy Inquiry Attchment Data Start **/
            $(document).on('click', '.attchment', function () {
                var index = $("#grid1").jqGrid('getGridParam', 'selrow');
                var id = $("#grid1").jqGrid('getCell', index, 'so_inquiry_hdr_id');
                var inquiry_no = $("#grid1").jqGrid('getCell', index, 'inquiry_no');
                var url = "{{ URL::to('salesinquiryprint') }}/" + id + '?mails=mails';
                $.get(url, function (data) {
                    $('#iframepdf').attr('src', "uploads/salesenquiry/S_" + inquiry_no + ".pdf");
                    $('#iframepdf').show();
                });

            });
            /** Sales Copy Inquiry Attchment Data End **/



        });



    </script>

@endpush