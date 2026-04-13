@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Purchase Order</h3>
    @include('layouts.breadcrumb')
    <div id="toolbar-container" class="mb-3 mt-2"></div>
    <style>
        .ui-datepicker {
            z-index: 999999 !important;
        }
    </style>
    <div class="card shadow-lg rounded-4 border-0">
        <div class="container mt-4">
            <div class="table-responsive">
                <table id="PurchaseTbl" class="table table-bordered table-striped w-100" >
                    <thead>
                        <tr class="table-warning">
                            <th>Actions</th>
                            <th>PO Number</th>
                            <th>PO Date</th>
                            <th>PO Type</th>
                            <th>Supplier Name</th>
                            <th>PO Status</th>
                            <th>Tax Total</th>
                            <th>Grand Total</th>
                            <th>Created By</th>
                            <th>Approved/Rejected By</th>
                            <th>GRN Status</th>
                            <th>Remarks</th>
                            
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
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- DataTable will populate via AJAX --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- popups -->

    <!-- Closing Order Confirmation Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="myModalLabel">Closing Order Confirmation</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="fs-5">Do you want to close the order?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" id="yes" class="btn btn-success">Yes</button>
                    <button type="button" id="cancel" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Promised Alternate Date Modal -->
    <form method="post" action="" id="saveprodateform" class="saveprodateform needs-validation" novalidate>
        <div class="modal fade" id="myModal2" tabindex="-1" aria-labelledby="myModal2Label" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="myModal2Label">Add Promised Alternate Date</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-striped w-75 mx-auto">
                            <thead>
                                <tr>
                                    <th>PO Number</th>
                                    <th>PO Date</th>
                                </tr>
                            </thead>
                            <tbody class="poaltdatetbl"></tbody>
                        </table>
                        <br>
                        <table class="table table-bordered table-striped w-75 mx-auto">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Promised Date</th>
                                    <th>Promised Alternate Date</th>
                                </tr>
                            </thead>
                            <tbody class="poaltdatetblbody"></tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success prodatesave" data-bs-dismiss="modal">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Contact Modal -->
    <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content" style="height: 550px;overflow-y: auto;">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="contactModalLabel">Contact</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form method="POST" action="" id="enquirymail" class="needs-validation" novalidate
                    enctype="multipart/form-data">
                    <div class="modal-body">
                        <!-- Supplier Info -->
                        <div class="mb-3">
                            <label class="form-label">Supplier Name</label>
                            <input type="text" name="supplier_name" class="form-control supplier_name" readonly>
                            <input type="hidden" name="po_hdr_id" class="po_hdr_id">
                        </div>

                        <!-- Contact List -->
                        <div class="table-responsive mb-3">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Contact Person</th>
                                        <th>Contact Number</th>
                                        <th>Contact Mail</th>
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
                            <textarea name="msg" class="form-control tinymce msg_editor" rows="4"></textarea>
                            <input type="hidden" name="hdr_id" class="msg hdr_id">
                        </div>

                        <!-- Attachments -->
                        <div class="mb-3">
                            <label class="form-label">Add Attachments</label>
                            <input type="file" name="email_attachment[]" class="form-control email_attachment" multiple>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="attchment" class="form-check-input attchment">
                            <label class="form-check-label">Attach PDF</label>
                        </div>

                        <!-- PDF Preview -->
                        <iframe id="iframepdf" width="100%" height="400" style="display:none"></iframe>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-success sendmail" id="sentmail_id">Send</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                <button class="btn btn-secondary create_standard me-2">Create Standard
                   <i class="bi bi-plus-circle"></i> 
                </button>
              `);
            }

            if (window.toolbarButtons?.some(btn => btn.attr.id === 'create_labour')) {
                $('#toolbar-container').append(`
                <button class="btn btn-primary create_labour me-2">Create Labour
                   <i class="bi bi-plus-circle"></i> 
                </button>
              `);
            }

        });



        // table data
        $(document).ready(function () {

            var status = "{{$status}}";
            var pocancellation = "{{$pageMethod}}";

            var table = $('#PurchaseTbl').DataTable({
                processing: true,
                serverSide: false,
                orderable: true,   
                order: [[0, 'desc']], 
                scrollX: true,
                scrollY: "50vh",
                orderCellsTop: true,  
                ajax: "getPurchaseorderData?status=" + status + "&page_status=" + pocancellation,
                columns: [

                    {

                        data: 'po_hdr_id',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {

                            if (type === 'sort' || type === 'type') {
                            return data; // IMPORTANT: return numeric id for sorting
                            }
                            
                            let buttons = '';
                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                                buttons += `
                    <button type="button" class="btn btn-sm btn-primary bg-gradient edit-btn"
                      data-id="${row.po_hdr_id}"
                      data-type="${row.po_type}"
                      data-status="${row.po_status}">
                      <i class="bi bi-pencil"></i>
                    </button>`;
                            }
                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'delete')) {
                                buttons += `
                        <button type="button" class="btn btn-sm btn-danger bg-gradient delete-btn"
                          data-id="${row.po_hdr_id}">
                          <i class="bi bi-trash"></i>
                        </button>`;
                            }
                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                                buttons += `
                        <button type="button" class="btn btn-sm btn-warning bg-gradient view-btn"
                          data-id="${row.po_hdr_id}">
                          <i class="bi bi-eye"></i>
                        </button>`;
                            }

                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'email')) {
                                buttons += `
                        <button type="button" class="btn btn-sm btn-secondary bg-gradient mail-btn"
                                data-bs-toggle="tooltip" 
                                data-bs-placement="top" 
                                title="Email"
                          data-id="${row.po_hdr_id}"
                          data-status="${row.po_status}"
                          data-name="${row.supplier_name}"
                          data-sid="${row.sub_id}">
                          <i class="bi bi-envelope"></i>
                        </button>`;
                            }

                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'print')) {
                                buttons += `
                        <button type="button" class="btn btn-sm btn-success bg-gradient print-btn"
                      data-id="${row.po_hdr_id}"
                      data-type="${row.po_type}"
                      data-status="${row.po_status}">
                          <i class="bi bi-printer"></i>
                        </button>`;
                            }

                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'date')) {
                                buttons += `
                        <button type="button" class="btn btn-sm btn-info date-btn bg-gradient text-white"
                                data-bs-toggle="tooltip" 
                                data-bs-placement="top" 
                                title="Change Promissed Date"
                          data-id="${row.po_hdr_id}"
                          data-number="${row.po_number}"
                          data-date="${row.po_date}">
                        <i class="bi bi-calendar2-check-fill"></i>
                        </button>`;
                            }
                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'close')) {
                                buttons += `
                        <button type="button" class="btn btn-sm btn-dark bg-gradient close-btn"
                          data-id="${row.po_hdr_id}"
                                  data-bs-toggle="tooltip" 
                                data-bs-placement="top" 
                                title="Close Order"
                          data-status="${row.po_status}">
                        <i class="bi bi-x"></i>
                        </button>`;
                            }
                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'copy')) {
                                buttons += `
                        <button type="button" class="btn btn-sm btn-primary copy-btn"
                          data-id="${row.po_hdr_id}">
                          <i class="bi bi-copy"></i> Copy
                        </button>`;
                            }

                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'approve')) {
                                buttons += `
                        <button type="button" class="btn btn-sm btn-success approve-btn"
                          data-id="${row.po_hdr_id}"
                          data-type="${row.po_type}"
                          data-bs-toggle="tooltip" 
                          data-bs-placement="top" 
                          title="Po Approve">
                        <i class="bi bi-check2-circle"></i>  
                        </button>`;
                            }

                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'cancelpo')) {
                                buttons += `
                        <button type="button" class="btn btn-sm btn-danger cancel-btn"
                          data-id="${row.po_hdr_id}"
                          data-type="${row.po_type}"
                          data-bs-toggle="tooltip" 
                          data-bs-placement="top" 
                          title="Po Cancel">
                          <i class="bi bi-x"></i>
                        </button>`;
                            }

                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'viewpocance')) {
                                buttons += `
                        <button type="button" class="btn btn-sm btn-primary text-white poview-btn"
                          data-id="${row.po_hdr_id}"
                          data-type="${row.po_type}"
                          data-bs-toggle="tooltip" 
                          data-bs-placement="top" 
                          title="Po View">
                         <i class="bi bi-eye"></i>
                        </button>`;
                            }

                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'amendment')) {
                                buttons += `
                        <button type="button" class="btn btn-sm btn-success amendment-btn"
                          data-id="${row.po_hdr_id}"
                          data-type="${row.po_type}"
                          data-status="${row.po_status}"
                          data-bs-toggle="tooltip" 
                          data-bs-placement="top" 
                          title="PO Amendment">
                        <i class="bi bi-pen"></i>
                        </button>`;
                            }

                            return buttons;
                        }

                    },
                    
                    { data: 'po_number', name: 'po_number' },
                    { data: 'po_date', name: 'po_date' },
                    { data: 'po_type', name: 'po_type' },
                    { data: 'supplier_name', name: 'supplier_name' },
                    { data: 'po_status', name: 'po_status' },
                    { data: 'po_tax_total', name: 'po_tax_total' },
                    { data: 'po_grand_total', name: 'po_grand_total' },
                    { data: 'first_name', name: 'first_name' },
                    { data: 'approvedby', name: 'approvedby' },
                    { data: 'sub_id', name: 'sub_id', visible: false },
                    { data: 'remarks', name: 'remarks' },
                    

                ],

                // 🔥 bind to the *cloned* header that is actually visible
                initComplete: function () {
                    var api = this.api();

                    // get the real visible header inside the scroll container
                    var $scrollHead = $(api.table().container())
                        .find('.dataTables_scrollHead thead');

                    // second header row (index 1) has the inputs
                    $scrollHead.find('tr:eq(1) th').each(function (colIndex) {
                        var th = this;
                        $('input.column-search', th).on('keyup change', function () {
                            if (api.column(colIndex).search() !== this.value) {
                                api.column(colIndex).search(this.value).draw();
                            }
                        });
                    });
                }
   
            });

        });


        //create button

        $(document).on('click', '.create_standard', function () {
            var enqtype = "STANDARD";
            var url = "{{ url('purchaseordercreate') }}/0/" + enqtype + '/2';
            var red_url = "{{ url('purchaseorder') }}";
            window.location.replace(url);
        });

        $(document).on('click', '.create_labour', function () {
            var enqtype = "LABOUR";
            var url = "{{ url('purchaselabourordercreate') }}/0/" + enqtype + '/2';
            var red_url = "{{ url('purchaseorder') }}";
            window.location.replace(url);
        });

        //edit

        $(document).on('click', '.edit-btn', function () {


            const id = $(this).data('id');
            const type = $(this).data('type');
            const status = $(this).data('status');


            if (status != "APPROVED" && status != "INITIATED" && status != "CANCELLED" && status != "CLOSED" && status != "COMPLETED") {
                window.location.replace('purchaseordercreate/' + id + '/' + type + '/2');
            }
            else {
                showCustomAlert("Purchase Order Cannot Be Edit!!!", 'warning');
            }

        });


        // delete	
        // delete function
        let deleteId = null;

        $(document).on('click', '.delete-btn', function () {
            deleteId = $(this).data('id');
            $('#globalDeleteModal').modal('show');
        });

        $('#globalConfirmDeleteBtn').on('click', function () {
            if (deleteId) {
                $.ajax({
                    url: "{{ url('purchaseorderdelete') }}/" + deleteId,
                    type: "GET",
                    success: function (data) {
                        if (data == '0') {
                            $('#globalDeleteModal').modal('hide');
                            showCustomAlert('Deleted Successfully', 'success');
                            $('#PurchaseTbl').DataTable().ajax.reload();
                        }
                        if (data == '1') {
                            $('#globalDeleteModal').modal('hide');
                            showCustomAlert("You Cant't delete , Purchase Enquiry Used in SomeWhere", 'info');
                            $('#PurchaseTbl').DataTable().ajax.reload();
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
            var url = "{{$pageMethod}}";

            window.location.replace('purchaseorderview/' + id + '/?return=' + url);

        });


        //	close order
        $(document).on('click', '.close-btn', function () {
            const id = $(this).data('id');
            const status = $(this).data('status');

            if (status !== "COMPLETED" && status !== "CANCELLED" && status !== "DRAFT") {

                // Store the selected ID in a data attribute of the modal
                $('#myModal').data('order-id', id).modal('show');

            } else {
                showCustomAlert("Please select Initiated/Approved Records", 'info');
            }
        });

        // Bind YES click event only once
        $(document).on('click', '#yes', function () {
            const id = $('#myModal').data('order-id');

            $.get("{{ URL::to('poorderupdatestatus') }}/" + id, function (data) {
                showCustomAlert("Purchase Order Closed Successfully", 'info');
                location.reload();
            });
        });


        //print

        $(document).on('click', '.print-btn', function () {


            const id = $(this).data('id');
            const type = $(this).data('type');
            const status = $(this).data('status');


            if (status == "APPROVED" || status == "COMPLETED" || status == "CLOSED") {
                window.open('poprint/' + id, '_blank');

            } else {

                showCustomAlert("Please Select Approved PO Only", 'info');
            }

        });

        // mail

        $(document).on('click', '.mail-btn', function () {


            const status = $(this).data('status');

            if (status != "INITIATED" && status != "CANCELLED" && status != "DRAFT") {

                const id = $(this).data('id');
                const po_hdr_id = $(this).data('id');
                const sid = $(this).data('sid');
                const name = $(this).data('name');

                $('.mcontent2').html('');
                $('.supplier_name').val(name);
                $('.po_hdr_id').val(po_hdr_id);
                var url_print = '{{URL::to("suppliermaildetails")}}/' + sid;

                $.get(url_print, function (data) {
                    if (data == "") {
                        showCustomAlert("No Email Contact For Current Supplier...Please Add Email First...", 'info');
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
                                if (check == true) {

                                    var id = $(this).data('value');

                                    $('.cont_row input:not(.check_mail)').attr('disabled', 'disabled');
                                    $('.cont_row:eq(' + index + ') input').removeAttr('disabled');

                                }
                                else {
                                    showCustomAlert("Please Check Any Email Contact First...", 'warning');
                                    $('.cont_row input').removeAttr('disabled');

                                }

                            });

                        });
                    }

                });

                $('#contactModal').modal('show');
            }
            else {
                showCustomAlert("Please Select Approved Only", 'warning');
            }


            // send mail
            $('.sendmail').click(function () {
                const id = $(this).data('id');
                var suid = $(this).data('sid');
                var check = $('.mail_name').is(":checked");
                var att_check = $(".attchment").is(":checked");

                var mail = [];
                $(':checkbox:checked').each(function (i) {
                    mail[i] = $(this).val();
                });
                var mail1 = mail.filter(function (v) { return v !== '' });

                var cc = $('.cc').val();
                var msg = $('.msg').val();
                if (check == true) {
                    if (att_check == true) {
                        var url = "{{ URL::to('poprint') }}/" + id + '?mail=' + mail1 + '&cc=' + cc + '&msg=' + msg;
                        $.get(url, function (data) {
                            showCustomAlert("Mail Send Successfully", 'success');
                            location.reload();

                        });
                        filesave();
                    } else {
                        showCustomAlert("Please Check Attach Pdf", 'info');
                    }
                }
                else {
                    showCustomAlert("Please Check Contact", 'info');
                }
            });


        });

        function filesave() {
            var form_data = new FormData(document.getElementById('enquirymail'));
            $.ajax({
                url: "{{URL::to('pofilesave')}}",
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

        $(document).on('click', '.attchment', function () {
            var index = $("#pogrid").jqGrid('getGridParam', 'selrow');
            var id = $("#pogrid").jqGrid('getCell', index, 'po_hdr_id');
            var po_number = $("#pogrid").jqGrid('getCell', index, 'po_number');
            // alert(po_number);
            var url = "{{ URL::to('poprint') }}/" + id + '?mails=mails';
            $.get(url, function (data) {
                $('#iframepdf').attr('src', "uploads/purchaseorder/PO_" + id + ".pdf");
                $('#iframepdf').show();
            });

        });

        $(".attchment").on("ifUnchecked", function () {
            $('#iframepdf').attr('src', '');
            $('#iframepdf').hide();
            $('.highlight').removeClass('highlight');
        });


        $(document).on('click', '.prodatesave', function () {
            var url = "{{ URL::to('alternatedatesave') }}";
            var form = $('#saveprodateform');
            form.parsley().validate();
            var formdata = $('#saveprodateform').serialize();
            $.post(url, formdata, function (data) {
                var status = data.status;
                var msg = data.message;
                showCustomAlert(msg, status);
            })
        });

        // change date
        $(document).on('click', '.date-btn', function () {

            $('#myModal2').modal('show');
            const id = $(this).data('id');
            const po_number = $(this).data('number');
            const po_date = $(this).data('date');

            var datefomdatas = "<tr><td>" + po_number + "</td><td>" + po_date + "</td></tr>";
            $('.poaltdatetbl').html(datefomdatas);
            var url = "{{URL::to('getpoaltdatedatas')}}/" + id;
            $.get(url, function (data) {
                var table = '';
                $.each(data, function (key, value) {
                    table += "<tr><td>" + value['concatenated_product'] + "</td><td>" + value['promised_date'] + "</td><td><div class='input-group date form_date col-md-12' data-date='' data-date-format='dd MM yyyy' data-link-field='dtp_input2' data-link-format='yyyy-mm-dd'><input id='dtpicker1' type='text' name='bulk_promised_alternate_date[]' class='form-control  bulk_promised_alternate_date' required><span class='input-group-addon'><span class='glyphicon glyphicon-calendar'></span></span></div> </td><input type='hidden' name='bulk_po_line_id[]' value=" + value['po_line_id'] + "><input type='hidden' name='bulk_po_hdr_id[]' value=" + value['po_hdr_id'] + "></tr>";

                });
                $('.poaltdatetblbody').html(table);
            });


        });

        $(document).on("focus", ".bulk_promised_alternate_date", function () {
            var dateToday = new Date();
            $(this).datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: "yy-mm-dd",
                minDate: dateToday,
                maxDate: +90,
                showAnim: "slideDown",
                yearRange: "-25:+0",
                appendTo: "body" // important
            }).datepicker("show"); // force display
        });


        // copy po

        $(document).on('click', '.copy-btn', function () {


            const id = $(this).data('id');

            window.location.replace('purchasecopypocreate/' + id + '/0?status=COPYPO');

        });


        //approve

        $(document).on('click', '.approve-btn', function () {

            const id = $(this).data('id');
            const type = $(this).data('type');

            window.location.replace('purchaseorderapprovcreate/' + id + '/' + type + '/1');


        });


        // cancel po

        $(document).on('click', '.cancel-btn', function () {

            const id = $(this).data('id');
            const type = $(this).data('type');

            window.location.replace('purchaseordercreate/' + id + '/' + type + '/3');

        });


        // po view

        $(document).on('click', '.poview-btn', function () {

            const id = $(this).data('id');
            const pocancellation = "pocancellation";

            window.location.replace('purchaseorderview/' + id + '?return=' + pocancellation);


        });

        // amendment po

        $(document).on('click', '.amendment-btn', function () {

            const id = $(this).data('id');
            const type = $(this).data('type');
            const status = $(this).data('status');


            window.location.replace('poamendmentcreate/' + id + '/' + type + '/5?status=POAMENDMENT');


        });


    </script>

@endpush