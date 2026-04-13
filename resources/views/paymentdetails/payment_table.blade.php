@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Payments Details</h3>
    @include('layouts.breadcrumb')
    <?php error_reporting(0); ?>
    <style>
        .select2-container--open {
            z-index: 200000 !important;
        }
    </style>


    <div class="row mb-3 mt-2">
        <div class="col-md-12 d-flex flex-wrap align-items-center gap-2">


            <!-- Payment Cancellation -->
            <button type="button" id="chequecancellation" class="btn btn-primary px-4 d-flex align-items-center">
                <i class="fa fa-ban me-2"></i> Payment Cancellation
            </button>

            <!-- Payment Advice -->
            <button type="button" id="paymentadvice" class="btn btn-danger text-white px-4 d-flex align-items-center">
                <i class="fa fa-file-invoice-dollar me-2"></i> Payment Advice
            </button>

            <!-- Cheque -->
            <button type="button" id="cheque" class="btn btn-success text-white px-4 d-flex align-items-center">
                <i class="fa fa-file-signature me-2"></i> Cheque
            </button>

            <!-- Voucher -->
            <button type="button" id="voucher" class="btn btn-secondary text-white px-4 d-flex align-items-center">
                <i class="fa fa-file-alt me-2"></i> Voucher
            </button>


        </div>
    </div>

    <div class="card shadow-lg rounded-4 border-0">
        <div class="container mt-4 table-responsive">
            <table id="AccTbl" class="table table-bordered table-striped w-100">
                <thead>
                    <tr class="table-warning">
                        <th><input type="checkbox" id="select-all"></th>
                        <th>Action</th>
                        <th>Payment Number</th>
                        <th>Payment Date</th>
                        <th>Payment Type</th>
                        <th>Cheque Number</th>
                        <th>Payment Source</th>
                        <th>UTR Number</th>
                        <th>Invoice Number</th>
                        <th>Payment Amount</th>
                        <th>Bank Name</th>
                        <th>Employee Name</th>
                        <th>Customer Name</th>
                        <th>Supplier Name</th>
                        <th>Supplier Favouring Name</th>
                        <th>Status</th>
                        <th>Supplier Bank Name</th>
                        <th>Bank Date</th>
                        <th>Account Name</th>
                        <th>Cancel Status</th>
                        <th>Attachment</th>
                    </tr>

                    <tr class="table-danger">
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
                <tbody></tbody>
            </table>
        </div>
    </div>


    <!-- Payment Print Modal -->
    <div class="modal fade" id="chequecan" tabindex="-1" aria-labelledby="chequecanLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="chequecanLabel">Payment Print</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row gy-2">
                        <div class="col-md-6">
                            <p><b>Payment Number:</b> <span class="payment_number"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><b>Payment Date:</b> <span class="payment_date"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><b>Payment Type:</b> <span class="payment_type"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><b>Cheque Number:</b> <span class="cheque_no"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><b>Payment Reference:</b></p>
                        </div>
                        <div class="col-md-6">
                            <p><b>Supplier:</b> <span class="supplier"></span></p>
                            <p><b>Customer:</b> <span class="customer"></span></p>
                            <p><b>Emp Name:</b> <span class="emp_name"></span></p>
                        </div>
                        <div class="col-md-6">
                            <p><b>Payment Amount:</b> <span class="payamount"></span></p>
                        </div>
                        <input type="hidden" class="payment_id">
                    </div>
                </div>

                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success" id="confirm">Confirm</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Cheque Print Modal -->
    <div class="modal fade" id="chequeprint" tabindex="-1" aria-labelledby="chequeprintLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="chequeprintLabel">Cheque Print</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" class="payment_id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Select Account:</label>
                            <select class="form-select account_name_type select2">
                                <option value="">--Please Select--</option>
                                <option value="account_Name">Account Name</option>
                                <option value="favouring_name">Favouring Name</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">A/c Payee:</label>
                            <select class="form-select acpayee select2">
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success" id="ok">OK</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- UTR Number Update Modal -->
    <div class="modal fade" id="PaymentRefModal" tabindex="-1" aria-labelledby="PaymentRefModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="PaymentRefModalLabel">UTR Number Update</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="d-flex justify-content-between mb-3">
                        <b>Payment Number:</b> <span class="payment_number"></span>
                        <b>Payment Date:</b> <span class="payment_date"></span>
                    </div>
                    <input type="hidden" class="payment_id">
                    <div class="mb-3">
                        <label class="form-label fw-bold">UTR Number:</label>
                        <input type="text" class="form-control payment_reference" placeholder="Enter UTR Number">
                    </div>
                </div>

                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success paymentref_save" id="updateClose">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Challan Details Update Modal -->
    <div class="modal fade" id="ChallanRefModal" tabindex="-1" aria-labelledby="ChallanRefModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="ChallanRefModalLabel">Challan Details Update</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="d-flex justify-content-between mb-3">
                        <b>Payment Number:</b> <span class="payment_number1"></span>
                        <b>Payment Date:</b> <span class="payment_date1"></span>
                    </div>

                    <input type="hidden" class="payment_id">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Challan Number</label>
                            <input type="text" class="form-control challan_num" placeholder="Enter Challan Number">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Challan Date</label>
                            <input type="text" class="form-control start_date challan_date">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Challan Amount</label>
                            <input type="text" class="form-control challan_amt" placeholder="Enter Amount">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">BSR Code</label>
                            <input type="text" class="form-control bsr_code" placeholder="Enter BSR Code">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Account Name</label>
                            <select name="account_id" id="account_id" class="form-select account_id select2">
                                {!! $ledger !!}
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Month For</label>
                            <input type="month" class="form-control date_select">
                        </div>
                    </div>
                </div>

                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success challanref_save" id="updateClose">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


@endsection
@push('scripts')

    <script>

        $(document).ready(function () {

            var table = $('#AccTbl').DataTable({
                processing: true,
                serverSide: false,
                scrollX: true,
                scrollY: "50vh",
                orderCellsTop: true,
                ajax: "getpaymentdetail",
                columns: [
                    {
                        data: 'payment_id',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function (data) {
                            return `<input type="checkbox" class="row-checkbox" value="${data}">`;
                        }
                    },
                    {
                        data: 'payment_id',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',

                        render: function (data, type, row) {
                            let buttons = '';
                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                                buttons += `
                <button class="btn btn-sm btn-warning view-btn" data-id="${row.payment_id}">
                  <i class="bi bi-eye"></i>
                </button>`;
                            }

                           if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                                buttons += `
                <button class="btn btn-sm btn-primary edit-btn" data-id="${row.payment_id}">
                  <i class="bi bi-pencil"></i>
                </button>`;
                            }


                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'payrefupdate')) {
                                buttons += `
                <button class="btn btn-sm btn-primary utr-btn" data-id="${row.payment_id}"
                data-bs-toggle="tooltip" 
                data-bs-placement="top" 
                title="Update UTR Number">
                  <i class="bi bi-plus"></i>
                </button>`;
                            }

                            buttons += `
              <button class="btn btn-sm btn-success chellan-btn" data-id="${row.payment_id}"
          data-bs-toggle="tooltip" 
          data-bs-placement="top" 
          title="Update Chellan Detail">
                <i class="fa fa-pen-to-square"></i>
              </button>`;
                            return buttons;
                        }
                    },

                    { data: 'payment_number' },
                    { data: 'payment_date' },
                    { data: 'payment_type_id' },
                    { data: 'cheque_no' },
                    { data: 'payment_source' },
                    { data: 'payment_reference' },
                    { data: 'bill_number' },
                    { data: 'payment_amount' },
                    { data: 'bank_name' },
                    { data: 'first_name' },
                    { data: 'customer_name' },
                    { data: 'supplier_name' },
                    { data: 'favouring_name' },
                    { data: 'batch_status' },
                    { data: 'supplier_bank_id' },
                    { data: 'bank_date' },
                    { data: 'supplier_account_name' },
                    { data: 'cancel_status' },
                    { data: 'attachfile_name' },

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
                }
            });

            $('#select-all').on('click', function () {
                var rows = table.rows({ 'search': 'applied' }).nodes();
                $('input[type="checkbox"].row-checkbox', rows).prop('checked', this.checked);
            });


            $('#AccTbl tbody').on('change', '.row-checkbox', function () {
                if (!this.checked) {
                    var el = $('#select-all').get(0);
                    if (el && el.checked && ('indeterminate' in el)) {
                        el.indeterminate = true;
                    }
                }
            });
        });



        //view function
        $(document).on('click', '.view-btn', function () {
            const id = $(this).data('id');
            var url = "paymentsindex";
            window.location.replace('paymentreferenceview/' + id + '?return=' + url);
        });

        //view function
        $(document).on('click', '.edit-btn', function () {
            const id = $(this).data('id');
            window.location.replace('paymentsedit/' + id);
        });

        // UTR number update
        $(document).on('click', '.utr-btn', function () {
            var payment_id = $(this).data('id');
            var table = $('#AccTbl').DataTable();
            var rowData = table.row($(this).closest('tr')).data();

            var payment_number = rowData.payment_number || '';
            var payment_date = rowData.payment_date || '';

            // Fill modal data
            $(".payment_number").html(payment_number);
            $(".payment_date").html(payment_date);
            $(".payment_id").val(payment_id);

            // Show modal
            $("#PaymentRefModal").modal('show');

            // Fetch payment reference
            var url = "{{ URL::to('paymentreference') }}?id=" + payment_id;
            $.get(url, function (data) {
                $('.payment_reference').val(data.payment_reference);
            });

        });



        $(document).on('click', '.paymentref_save', function () {
            var id = $(".payment_id").val();
            var payment_reference = $(".payment_reference").val();
            $.get("paymentreferenceupdate?id=" + id + "&payment_reference=" + payment_reference, function (data) {
                if ($.trim(data) == '1') {
                    showCustomAlert("UTR Number Updated Successfully", "success")
                    $("#PaymentRefModal").modal('hide');
                    $('#AccTbl').DataTable().ajax.reload();
                } else {

                    showCustomAlert("UTR Number Updated Successfully", "success");
                    $("#PaymentRefModal").modal('hide');
                    $('#AccTbl').DataTable().ajax.reload();
                }
            });
        });


        // 	chellan update
        //challan detail entry - vignesh m

        $(document).on('click', '.chellan-btn', function () {
            var payment_id = $(this).data('id'); // Get ID from the button attribute
            var table = $('#AccTbl').DataTable();
            var rowData = table.row($(this).closest('tr')).data(); // Get full row data

            var payment_number = rowData.payment_number || '';
            var payment_date = rowData.payment_date || '';

            // Fill modal fields
            $(".payment_number1").html(payment_number);
            $(".payment_date1").html(payment_date);
            $(".payment_id").val(payment_id);

            // Show the modal
            $("#ChallanRefModal").modal('show');

            // Fetch challan details
            var url = "{{ URL::to('paymentreference') }}?id=" + payment_id;
            $.get(url, function (data) {
                $('.challan_num').val(data.challan_num);
                $('.challan_date').val(data.challan_date);
                $('.challan_amt').val(data.challan_amt);
                $('.bsr_code').val(data.bsr_code);
                $('.account_id').val(data.account_id);
                $('.date_select').val(data.date_select);
            });

        });


        // payment Advice	
        $("#paymentadvice").on('click', function () {
            var selectedIds = [];
            // Loop through all checked checkboxes in DataTable
            $('#AccTbl tbody input.row-checkbox:checked').each(function () {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length > 0) {
                // Join all IDs (comma separated) for the URL
                var url = "{{ url('paymentadvice') }}/" + selectedIds.join(',');
                window.open(url, '_blank'); // Open in a new tab
            } else {
                showCustomAlert("Please choose payments data", "info");
            }
        });


        // Payment cancellation	
        $("#chequecancellation").click(function () {
            var table = $('#AccTbl').DataTable();
            var selectedRow = $('#AccTbl tbody input.row-checkbox:checked').first().closest('tr');
            var rowData = table.row(selectedRow).data();

            if (rowData) {
                var payment_id = rowData.payment_id;
                var payment_number = rowData.payment_number || '';
                var payment_type_id = rowData.payment_type_id || '';
                var payment_date = rowData.payment_date || '';
                var supplier = rowData.supplier_name || '';
                var customer = rowData.customer_name || '';
                var emp_name = rowData.first_name || '';
                var cancel_status = rowData.cancel_status || '';
                var bank_date = rowData.bank_date || '';
                var cheque_no = rowData.cheque_no || '';
                var payment_reference = rowData.payment_reference || '';
                var cheque_cancel_status = rowData.cheque_cancel_status || '';
                var payamount = rowData.payment_amount || '';

                // Validation and modal show
                if (cancel_status !== "Cancelled" && bank_date === '') {
                    $(".payment_number").html(payment_number);
                    $(".payment_type_id").html(payment_type_id);
                    $(".payment_date").html(payment_date);
                    $(".supplier").html(supplier);
                    $(".customer").html(customer);
                    $(".emp_name").html(emp_name);
                    $(".payment_reference").html(payment_reference);
                    $(".cheque_no").html(cheque_no);
                    $(".payamount").html(payamount);
                    $(".payment_type").html(payment_type_id);
                    $(".cheque_cancel_status").html(cheque_cancel_status);
                    $(".payment_id").val(payment_id);

                    $("#chequecan").modal('show');
                }
                else if (bank_date !== '') {
                    showCustomAlert("BRS Payment Cannot be Cancelled", "error");
                }
                else {
                    showCustomAlert("Already Payment Cancelled!!!", "info");
                }
            }
            else {
                showCustomAlert("Please select a payment row first", "info");
            }
        });

        $('#confirm').on('click', function () {
            var table = $('#AccTbl').DataTable();
            var selectedRow = $('#AccTbl tbody input.row-checkbox:checked').first().closest('tr');
            var rowData = table.row(selectedRow).data();

            if (rowData) {
                var payment_id = rowData.payment_id;
                var payment_date = rowData.payment_date;

                var url = "{{ url('getPaycanclconfirm') }}/" + payment_id + "?payment_date=" + payment_date;
                $.get(url, function (data) {
                    if (data == 1) {
                        showCustomAlert("Payment Cancelled Successfully", "success");
                        table.ajax.reload(null, false); // reloads DataTable without resetting pagination
                    }
                    $("#chequecan").modal('hide');
                });
            }
            else {
                showCustomAlert("Please Select a Row", "info");
            }
        });


        // Cheque

        $('#cheque').on('click', function () {
            var table = $('#AccTbl').DataTable();
            var selectedRow = $('#AccTbl tbody input.row-checkbox:checked').first().closest('tr');
            var rowData = table.row(selectedRow).data();

            if (rowData) {
                var payment_id = rowData.payment_id;
                var payment_type = rowData.payment_type_id;

                if (payment_type === "CHEQUE") {
                    $("#chequeprint").modal('show');

                    // Avoid multiple bindings
                    $('#ok').off('click').on('click', function () {
                        var account_name = $('.account_name_type').val();
                        var acpayee = $('.acpayee').val();
                        $("#chequeprint").modal('hide');
                        var url = "{{ url('getPaymentcheque') }}/" + payment_id +
                            '?account_type_name=' + encodeURIComponent(account_name) +
                            '&acpayee=' + encodeURIComponent(acpayee);
                        window.open(url, '_blank');
                    });
                }
                else {
                    showCustomAlert("No Cheque For This Payment!!!", "info");
                }
            }
            else {
                showCustomAlert("Please Select a Payment Row", "info");
            }
        });

        // Voucher
        $('#voucher').on('click', function () {
            var selectedIds = [];

            // Collect all checked payment IDs
            $('#AccTbl tbody input.row-checkbox:checked').each(function () {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length > 0) {
                var url = "{{ url('getPaymentvoucher') }}/" + selectedIds.join(',');
                window.open(url, '_blank');
            }
            else {
                showCustomAlert("Please Choose Payments Data", "info");
            }
        });

        // 	chellan update
        //challan detail entry - vignesh m

        $(document).on('click', '.chellan-btn', function () {
            var payment_id = $(this).data('id'); // Get ID from the button attribute
            var table = $('#AccTbl').DataTable();
            var rowData = table.row($(this).closest('tr')).data(); // Get full row data

            var payment_number = rowData.payment_number || '';
            var payment_date = rowData.payment_date || '';

            // Fill modal fields
            $(".payment_number1").html(payment_number);
            $(".payment_date1").html(payment_date);
            $(".payment_id").val(payment_id);

            // Show the modal
            $("#ChallanRefModal").modal('show');

            // Fetch challan details
            var url = "{{ URL::to('paymentreference') }}?id=" + payment_id;
            $.get(url, function (data) {
                $('.challan_num').val(data.challan_num);
                $('.challan_date').val(data.challan_date);
                $('.challan_amt').val(data.challan_amt);
                $('.bsr_code').val(data.bsr_code);
                $('.account_id').val(data.account_id);
                $('.date_select').val(data.date_select);
            });

        });


        //challan detail entry save - vignesh m
        $(document).on('click', '.challanref_save', function () {

            var id = $(".payment_id").val();
            var challan_num = $(".challan_num").val();
            var challan_date = $(".challan_date").val();
            var challan_amt = $(".challan_amt").val();
            var bsr_code = $(".bsr_code").val();
            var date_select = $(".date_select").val();
            var account_id = $(".account_id").val();

            $.get("challanrefupdate?id=" + id + "&challan_num=" + challan_num + "&challan_date=" + challan_date + "&challan_amt=" + challan_amt + "&bsr_code=" + bsr_code + "&account_id=" + account_id + "&date_select=" + date_select, function (data) {
                if ($.trim(data) == '1') {
                    showCustomAlert("Challan Detail Updated Successfully", 'success');
                    $("#ChallanRefModal").modal('hide');
                    $('#AccTbl').DataTable().ajax.reload();
                } else {
                    showCustomAlert("Challan Detail Updated Successfully", 'success');
                    $("#ChallanRefModal").modal('hide');
                    $('#AccTbl').DataTable().ajax.reload();
                }
            });
        });

    </script>

@endpush