@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Purchase Return</h3>
    @include('layouts.breadcrumb')
    <div id="toolbar-container" class="mb-3 mt-2"></div>

    <div class="card shadow-lg rounded-4 border-0">
        <div class="container mt-4">
            <div class="table-responsive">
                <table id="purInvTbl" class="table table-bordered table-striped w-100">
                    <thead>
                        <tr class="table-warning">

                            <th><input type="checkbox" id="select_all"></th>
                            <th>Actions</th>
                            <th class="freeze">Return Invoice Number</th>
                            <th>Return Date</th>
                            <th>Return Status</th>
                            <th>GRN Number</th>
                            <th>PO Number</th>
                            <th>Supplier Name</th>
                            <th>Invoice Number</th>
                            <th>Tax Total</th>
                            <th>Grand Total</th>
                            <th>Credit Taken</th>
                            <th>Credit Taken Month</th>
                            <th>Actions</th>

                        </tr>

                        <tr class="table-info">

                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
                            <th><input type="text" class="form-control form-control-sm column-search"
                                    placeholder="Search" /></th>
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

                        </tr>
                    </thead>
                    <tbody>
                        {{-- DataTable will populate via AJAX --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>




    <!--popups-->
    <!-- Contact Modal -->
    <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-3">

                <!-- Modal Header -->
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="contactModalLabel">Contact</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <!-- Form Start -->
                <form method="POST" action="" id="enquirymail" data-parsley-validate enctype="multipart/form-data">
                    <div class="modal-body">

                        <!-- Supplier Name -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Supplier Name</label>
                            <input type="text" name="supplier_name" class="form-control supplier_name bg-light" readonly>
                            <input type="hidden" name="return_header_id" class="return_header_id">
                        </div>

                        <!-- Contacts Table -->
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 5%;"></th>
                                        <th>Contact Person</th>
                                        <th>Contact Number</th>
                                        <th>Contact Email</th>
                                        <th style="width: 5%;"></th>
                                    </tr>
                                </thead>
                                <tbody class="mcontent2"></tbody>
                            </table>
                        </div>

                        <!-- CC Input -->
                        <div class="mb-4 row g-2 align-items-center">
                            <label for="cc" class="col-sm-1 col-form-label fw-bold">CC</label>
                            <div class="col-sm-6">
                                <input type="text" name="cc[]" class="form-control cc">
                            </div>
                        </div>

                        <!-- Message -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Message</label>
                            <textarea name="msg" class="form-control msg tinymce" rows="6">
    Thanks and Regards,
    Purchase Department,
    JRKS,
    Kundrathur.
                            </textarea>
                            <input type="hidden" name="hdr_id" class="hdr_id">
                        </div>

                        <!-- Attachments -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Add Attachments</label>
                            <input type="file" name="email_attachment[]" class="form-control email_attachment" multiple>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input attchment" name="attchment" value="">
                            <label class="form-check-label">Attach PDF</label>
                        </div>

                        <!-- Preview Area -->
                        <div class="preview mb-4"></div>

                        <!-- PDF Viewer -->
                        <iframe id="iframepdf" width="100%" height="400" class="border rounded d-none"></iframe>

                    </div>

                    <!-- Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i> Close
                        </button>
                        <button type="button" class="btn btn-success sendmail" id="sentmail_id">
                            <i class="bi bi-envelope-fill"></i> Send
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Credit Update Modal -->
    <div class="modal fade" id="creditModal" tabindex="-1" aria-labelledby="creditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-3">

                <!-- Modal Header -->
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="creditModalLabel">Credit Update Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <form method="post" action="" id="credit_update_form" data-parsley-validate>
                        <input type="hidden" class="return_header_id" value="">

                        <!-- Credit Taken -->
                        <div class="mb-4">
                            <label class="form-label fw-bold col-sm-3">Credit Taken</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="credit_taken" id="credit_yes"
                                    value="Yes">
                                <label class="form-check-label" for="credit_yes">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="credit_taken" id="credit_no" value="No">
                                <label class="form-check-label" for="credit_no">No</label>
                            </div>
                        </div>

                        <!-- Credit Date -->
                        <div class="mb-4 row align-items-center">
                            <label for="credit_date" class="col-sm-3 col-form-label fw-bold">Date</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control credit_date start_date" id="credit_date"
                                    name="credit_date" placeholder="Select date">
                            </div>
                        </div>

                        <!-- Employee Popup Area -->
                        <div class="mb-4 emppopup"></div>

                        <!-- Modal Footer -->
                        <div class="text-center">
                            <button type="button" class="btn btn-success px-4" id="credit_update_val" value="SAVE">
                                Update
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>


@endsection
@push('scripts')

    <script>

        // button purpose
        // Add create button purpose
        $(document).ready(function () {

            if (window.toolbarButtons?.some(btn => btn.attr.id === 'create')) {

                $('#toolbar-container').append(`
                <button class="btn btn-danger text-white bg-gradient returnbtn me-2"> Return
                </button>
              `);


                $('#toolbar-container').append(`
                <button class="btn btn-primary text-white bg-gradient credit_update me-2"> Credit Update 
                </button>
              `);



            }

        });


        $(document).ready(function () {

            var suppliername = "{{$suppliername}}";
            var invoiceno = "{{$invoiceno}}";
            var status = "{{$status}}";


            var table = $('#purInvTbl').DataTable({
                processing: true,
                serverSide: false,
                scrollX: true,
                scrollY: "50vh",
                orderCellsTop: true,
                ajax: "getpurchasereturnData?status=" + status,
                columns: [
                    {   // Checkbox column
                        data: 'return_header_id',
                        render: function (data) {
                            return '<input type="checkbox" class="row_checkbox" value="' + data + '">';
                        },
                        orderable: false,
                        searchable: false
                    },
                    {   // Actions column
                        data: null,
                        render: function (data, type, row) {
                            let buttons = '';

                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                                buttons += `
                                <button type="button" class="btn btn-sm btn-primary edit-btn"
                                    data-id="${row.return_header_id}"
                                    data-status="${row.p_return_status}">
                                    <i class="bi bi-pencil"></i>
                                </button>`;
                            }

                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                                buttons += `
                                <button type="button" class="btn btn-sm btn-warning view-btn"
                                    data-id="${row.return_header_id}">
                                    <i class="bi bi-eye"></i>
                                </button>`;
                            }

                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'print')) {
                                buttons += `
                                <button type="button" class="btn btn-sm btn-success print-btn"
                                    data-id="${row.return_header_id}"
                                    data-status="${row.p_return_status}">
                                    <i class="bi bi-printer"></i> 
                                </button>`;
                            }

                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'email')) {
                                buttons += `
                                <button type="button" class="btn btn-sm btn-secondary mail-btn"
                                    data-id="${row.return_header_id}"
                                    data-status="${row.p_return_status}">
                                   <i class="bi bi-envelope-check"></i>
                                </button>`;
                            }

                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'approve')) {
                                buttons += `
                                <button type="button" class="btn btn-sm btn-success approve-btn"
                                    data-id="${row.return_header_id}"
                                    data-status="${row.p_return_status}">
                                   <i class="bi bi-check2-circle"></i> Approve
                                </button>`;
                            }

                            return buttons;

                        },

                        orderable: false,
                        searchable: false
                    },
                    { class: 'freeze', data: 'return_invoice_number', name: 'return_invoice_number' },
                    { data: 'return_date', name: 'return_date' },
                    { data: 'p_return_status', name: 'p_return_status' },
                    { data: 'grn_number', name: 'grn_number' },
                    { data: 'po_number', name: 'po_number' },
                    { data: 'supplier_name', name: 'supplier_name' },
                    { data: 'bill_number', name: 'bill_number' },
                    { data: 'po_tax_total', name: 'po_tax_total' },
                    { data: 'po_grand_total', name: 'po_grand_total' },
                    { data: 'credit_taken', name: 'credit_taken' },
                    { data: 'credit_date', name: 'credit_date' },
                    { data: 'supplier_id', name: 'supplier_id', visible: false },

                ],
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
                },
                order: [[2, 'desc']]
            });

            // Select all checkboxes
            $('#select_all').on('click', function () {
                $('.row_checkbox').prop('checked', this.checked);
            });

            // Clear filter
            $(".clear").click(function () {
                table.search('').columns().search('').draw();
            });

            // Per-column search
            $('#purInvTbl thead').on('keyup change', ".column-search", function () {
                var index = $(this).closest('th').index();
                table.column(index).search(this.value).draw();
            });
        });


        // view	

        $(document).on('click', '.view-btn', function () {

            const id = $(this).data('id');
            var url = "{{$pageMethod}}";

            window.location.replace('purchasereturnview/' + id + '?return=' + url);

        });


        // edit

        $(document).on('click', '.edit-btn', function () {

            const id = $(this).data('id');
            const status = $(this).data('status');

            if (status != "INITIATED") {
                window.location.replace('purchasereturnedit/' + id);
            } else {
                showCustomAlert("Unable to edit Purchase Return", 'info');
            }

        });


        // print	

        $(document).on('click', '.print-btn', function () {

            const id = $(this).data('id');
            const status = $(this).data('status');


            if (status == "APPROVED") {
                window.open('purchasereturnprint/' + id);
            } else {
                showCustomAlert("Approved Record only able to Print", 'warning');
            }

        });


        // mail

        $(document).on('click', '.mail-btn', function () {


            const id = $(this).data('id');
            const status = $(this).data('status');

            if (status != "INITIATED" && status != "CANCELLED" && status != "DRAFT") {


                var sup_id = $(this).data('sid');
                var sup_name = $(this).data('name');



                $('.mcontent2').html('');
                $('.supplier_name').val(sup_name);
                $('.return_header_id').val(id);
                var url_print = '{{URL::to("purchasesuppliermaildetails")}}/' + sup_id;
                $.get(url_print, function (data) {
                    if (data == "") {
                        showCustomAlert("No Email Contact For Current Customer...Please Add Email First...", 'info');
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
                                    showCustomAlert("Please Check Any Email Contact First...", 'info');
                                    $('.cont_row input').removeAttr('disabled');

                                }

                            });

                        });

                    }

                });

                $('#contactModal').modal('show');
            }
            else {
                showCustomAlert("Please Select a Approved Only", 'info');
            }


        });


        // return

        $(document).on('click', '.returnbtn', function () {

            var url = "{{ url('invoicetable') }}";

            window.location.replace(url);

        });


        // credit update 

        $(document).on('click', '.credit_update', function () {
            var table = $('#purInvTbl').DataTable();
            var cellvalues = [];
            var chk = 0;

            // Loop over all checked checkboxes in the table
            $('#purInvTbl tbody input.row_checkbox:checked').each(function () {
                var row = table.row($(this).closest('tr')).data();

                if (row) {
                    cellvalues.push(row.return_header_id);

                    if (row.p_return_status !== "APPROVED") {
                        chk = 1; // Found a non-approved row
                    }
                }
            });

            if (cellvalues.length > 0) {
                if (chk === 0) {
                    $('#creditModal').modal('show');
                    $('.return_header_id').val(cellvalues.join(',')); // comma separated IDs
                } else {
                    showCustomAlert("Please Select Approved Rows Only", 'info');
                }
            } else {
                showCustomAlert("Please Select a Row", 'info');
            }
        });


        $(document).on('click', '#credit_update_val', function () {

            var return_header_id = $('.return_header_id').val();
            var url = "{{URL::to('returncreditupdate')}}/" + return_header_id;
            var formdata = $('#credit_update_form').serialize();

            $.post(url, formdata, function (data) {
                var status = data.status;
                var msg = data.message;


                showCustomAlert(msg, status);

                var red_url = "{{ URL::to('purchasereturn') }}";
                window.location.href = red_url;

            });

        });



        $('#contactModal').on('shown.bs.modal', function () {

            var id = $(this).data('id');


            $('.sendmail').click(function () {
                var id = $(this).data('id');
                var sup_id = $(this).data('sid');
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
                        var url = "{{ URL::to('purchasereturnprint') }}/" + id + '?mail=' + mail1 + '&cc=' + cc + '&msg=' + msg;
                        $.get(url, function (data) {
                            showCustomAlert("Mail Send Successfully", 'success');
                            location.reload();

                        });
                        filesave();
                    } else {
                        showCustomAlert("Please Check Attach Pdf", 'warning');
                    }
                }
                else {
                    showCustomAlert("Please Check a Contact", 'warning');
                }
            });

            function filesave() {
                var form_data = new FormData(document.getElementById('enquirymail'));
                $.ajax({
                    url: "{{URL::to('purchasefilesave')}}",
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


        // approve

        $(document).on('click', '.approve-btn', function () {

            var id = $(this).data('id');

            window.location.replace('purchasereturnapprove/' + id + '/1');

        });


        $(document).on('click', '.attchment', function () {

            var id = $(this).data('id');
            var number = $(this).data('number');

            var url = "{{URL::to('purchasereturnprint')}}/" + id + '?mails=mails';

            $.get(url, function (data) {
                $('#iframepdf').attr('src', "uploads/purchasereturn/P_" + number + ".pdf");
                $('#iframepdf').show();
            });

        });

    </script>

@endpush