@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Receipt Details</h3>
    @include('layouts.breadcrumb')
    <button type='button' id="directrecipt" class='btn btn-primary directrecipt mt-2'>Direct Receipt</button>


    <div class="card shadow-lg rounded-4 border-0">
        <div class="container mt-4 table-responsive">
            <table id="ReciptTbl" class="table table-bordered table-striped w-100">
                <thead>
                    <tr class="table-warning">
                        <th>Actions</th>
                        <th>Receipt Number</th>
                        <th>Receipt Source </th>
                        <th>Receipt Date</th>
                        <th>Receipt Type</th>
                        <th>Customer Name</th>
                        <th>Invoice Number</th>
                        <th>Invoice Amount</th>
                        <th>Receipt Amount</th>
                        <th>Remarks</th>
                        <th>Bank Name</th>
                        <th>Bank Narration</th>
                        <th>Bank Date</th>
                        <th>Cheque Cancel Date</th>
                        <th>Cheque Status</th>
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
                <tbody>
                </tbody>
            </table>
        </div>
    </div>


    <!-- Cheque Cancellation Modal -->
    <div class="modal fade" id="chequecan" tabindex="-1" aria-labelledby="chequecanLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg rounded-3">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="chequecanLabel"><i class="bi bi-x-circle-fill me-2"></i>Cheque Cancellation
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6"><strong>Receipt Number:</strong> <span class="receipt_number"></span></div>
                        <div class="col-md-6 text-end"><strong>Receipt Date:</strong> <span class="receipt_date"></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6"><strong>Receipt Type:</strong> <span class="receipt_type_id"></span></div>
                        <div class="col-md-6 text-end"><strong>Customer Name:</strong> <span class="customer_name"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>Receipt Reference:</strong> <span class="receipt_reference"></span>
                        </div>
                        <div class="col-md-6 text-end"><strong>Cheque Number:</strong> <span class="cheque_no"></span></div>
                    </div>
                </div>

                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success confirmed" id="confirm">
                        <i class="bi bi-check-circle me-1"></i> Confirm
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- Cheque Bounce/Reverse Modal -->
    <div class="modal fade" id="chequeboun" tabindex="-1" aria-labelledby="chequebounLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg rounded-3">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="chequebounLabel"><i class="bi bi-exclamation-triangle-fill me-2"></i>Cheque
                        Bounce / Reverse</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6"><strong>Receipt Number:</strong> <span class="receipt_number"></span></div>
                        <div class="col-md-6 text-end"><strong>Receipt Date:</strong> <span class="receipt_date"></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6"><strong>Receipt Type:</strong> <span class="receipt_type_id"></span></div>
                        <div class="col-md-6 text-end"><strong>Customer Name:</strong> <span class="customer_name"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>Receipt Reference:</strong> <span class="receipt_reference"></span>
                        </div>
                        <div class="col-md-6 text-end"><strong>Cheque Number:</strong> <span class="cheque_no"></span></div>
                    </div>
                </div>

                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success bounceconfirm" id="bounceconfirm">
                        <i class="bi bi-check-circle me-1"></i> Confirm
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>



@endsection
@push('scripts')


    <script>


        // data table funcrion	
        $(document).ready(function () {

            var table = $('#ReciptTbl').DataTable({
                processing: true,
                serverSide: true,
                order: [[3, 'desc']],
                scrollX: true,
                scrollY: "50vh",
                ajax: "getreceiptgridData",
                columns: [
                    {
                        data: 'receipt_id',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',

                        render: function (data, type, row) {
                            let buttons = '';

                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                                buttons += `
                    <button class="btn btn-sm btn-warning view-btn me-1" data-id="${row.receipt_id}">
                      <i class="bi bi-eye"></i>
                    </button>`;
                            }

                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'voucher')) {
                                buttons += `
                    <button class="btn btn-sm btn-success voucher-btn me-1" data-id="${row.receipt_id}" data-status="${row.cheque_cancel_status}"
                    data-bs-toggle="tooltip" 
                    data-bs-placement="top" 
                    title="Receipt Slip">
                     <i class="bi bi-align-middle"></i>
                    </button>`;
                            }


                            buttons += `
                    <button class="btn btn-sm btn-danger cancel-btn me-1" data-id="${row.receipt_id}"
                    data-bs-toggle="tooltip" 
                    data-bs-placement="top" 
                    title="Cheque Cancellation">
                    <i class="bi bi-calendar2-x"></i>
                    </button>`;

                            buttons += `
                    <button class="btn btn-sm btn-primary reverse-btn me-1" data-id="${row.receipt_id}"
                            data-bs-toggle="tooltip" 
                    data-bs-placement="top" 
                    title="Cheque Bounce/Reverse">
                   <i class="bi bi-cash"></i>
                    </button>`;

                            return buttons;
                        }
                    },

                    { data: 'receipt_number', name: 'receipt_number' },
                    { data: 'receipt_source', name: 'receipt_source' },
                    { data: 'receipt_date', name: 'receipt_date' },
                    { data: 'receipt_type_id', name: 'receipt_type_id' },
                    { data: 'customer_name', name: 'customer_name' },
                    { data: 'invoice_number', name: 'invoice_number' },
                    { data: 'invoice_grand_total', name: 'invoice_grand_total' },
                    { data: 'receipt_amount', name: 'receipt_amount' },
                    { data: 'remarks', name: 'remarks' },
                    { data: 'bank_name', name: 'bank_name' },
                    { data: 'narration', name: 'narration' },
                    { data: 'bank_date', name: 'bank_date' },
                    { data: 'cheque_cancel_status', name: 'cheque_cancel_status' },
                    { data: 'cheque_bounce_status', name: 'cheque_bounce_status' },

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

            // Individual column search
            $('#ReciptTbl thead').on('keyup change', ".column-search", function () {
                var colIndex = $(this).parent().index();
                table.column(colIndex).search(this.value).draw();
            });
        });



        // view 
        $(document).on('click', '.view-btn', function () {

            const id = $(this).data('id');

            window.location.replace('receiptsindexview/' + id);

        });

        // recipt slip 

        $(document).on('click', '.voucher-btn', function () {

            const id = $(this).data('id');
            const status = $(this).data('status');

            if (status != "cancelled") {
                var url = "{{ url('getReceiptvoucher') }}/" + id;
                window.open(url);
            } else {
                showCustomAlert("Cancelled Receipt Cannot be Print!!!  ", "error");
            }

        });


        $(".directrecipt").click(function () {
            window.location.replace('directreceiptcreate');
        });


        $(document).on('click', '.cancel-btn', function () {

            var table = $('#ReciptTbl').DataTable();
            var tr = $(this).closest('tr');
            var rowData = table.row(tr).data();

            let receipt_id = $(this).data('id');
            var receipt_number = rowData.receipt_number;
            var receipt_type_id = rowData.receipt_type_id;
            var receipt_date = rowData.receipt_date;
            var customer_name = rowData.customer_name;
            var receipt_reference = rowData.receipt_reference;
            var cheque_cancel_status = rowData.cheque_cancel_status;
            var cheque_no = rowData.cheque_no;
            var bank_date = rowData.bank_date;

            $('#chequecan').data('receipt-id', receipt_id);

            if (cheque_cancel_status == null && (bank_date == '0000-00-00' || bank_date == null)) {
                $(".receipt_number").html(receipt_number);
                $(".receipt_type_id").html(receipt_type_id);
                $(".receipt_date").html(receipt_date);
                $(".customer_name").html(customer_name);
                $(".receipt_id").val(receipt_id);
                $(".cheque_no").html(cheque_no);
                $(".receipt_reference").html(receipt_reference);
                $("#chequecan").modal('show');

            } else if (bank_date != '0000-00-00') {
                showCustomAlert("BRS Receipt Cannot be Cancelled","info");
            } else {
                showCustomAlert("This Receipt Cheque Already Cancelled !!!","warning");
            }

        });


        $('.confirmed').on('click', function () {

            var receipt_id = $('#chequecan').data('receipt-id');

            var url = "{{ url('getReceiptcanclconfirm') }}/" + receipt_id;
            $.get(url, function (data) {
                $("#chequecan").modal('hide');
                showCustomAlert('Cancellation successfully!', 'success');
                window.location.reload();
            });

        });


        // cheque reverse
        $(document).on('click', '.reverse-btn', function () {

            var table = $('#ReciptTbl').DataTable();
            var tr = $(this).closest('tr');
            var rowData = table.row(tr).data();

            let receipt_id = $(this).data('id');
            var receipt_number = rowData.receipt_number;
            var receipt_type_id = rowData.receipt_type_id;
            var receipt_date = rowData.receipt_date;
            var customer_name = rowData.customer_name;
            var receipt_reference = rowData.receipt_reference;
            var cheque_cancel_status = rowData.cheque_cancel_status;
            var cheque_no = rowData.cheque_no;
            var bank_date = rowData.bank_date;

            $('#chequeboun').data('receipt-id', receipt_id);

            if (cheque_cancel_status == null && (bank_date == '0000-00-00' || bank_date == null)) {
                $(".receipt_number").html(receipt_number);
                $(".receipt_type_id").html(receipt_type_id);
                $(".receipt_date").html(receipt_date);
                $(".customer_name").html(customer_name);
                $(".receipt_id").val(receipt_id);
                $(".cheque_no").html(cheque_no);
                $(".receipt_reference").html(receipt_reference);
                $("#chequeboun").modal('show');

            } else if (bank_date != '0000-00-00') {
                showCustomAlert("BRS Receipt Cannot be Cancelled", "info");
            } else {
                showCustomAlert("This Receipt Cheque Already Reversed !!!", "warning");
            }

        });


        $('.bounceconfirm').on('click', function () {

            var receipt_id = $('#chequeboun').data('receipt-id');

            var url = "{{ url('getReceiptbounceconfirm') }}/" + receipt_id;
            $.get(url, function (data) {
                $("#chequeboun").modal('hide');
                showCustomAlert('Reversed successfully!', 'success');
                window.location.reload();
            });

        });





    </script>

@endpush