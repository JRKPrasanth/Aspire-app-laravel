@extends('layouts.header')
@section('content')
    <h3 class="text-danger"> Debit/Credit Note </h3>
    @include('layouts.breadcrumb')

    <div class="d-flex align-items-center justify-content-between mb-3 mt-1">
        <div id="toolbar-container" class="create"></div>

        <button class="btn btn-primary text-white px-4 ms-2" id="credit_update">
            Credit Update
        </button>
    </div>



    <div class="card shadow-lg rounded-4 border-0">
        <div class="container mt-4 table-responsive">
            <table id="AccTbl" class="table table-bordered table-striped w-100">
                <thead>
                    <tr class="table-warning">
                        <th><input type="checkbox" id="select-all"></th>
                        <th style="width: 8% !important;">Actions</th>
                        <th>Debit/Credit No</th>
                        <th>Source</th>
                        <th>Reference</th>
                        <th>Ref_Inv_no</th>
                        <th>Debit/Credit Date</th>
                        <th>Debit/Credit Type</th>
                        <th>Debit/Credit Status</th>
                        <th>Debit/Credit Amount</th>
                        <th>Supplier Name</th>
                        <th>Customer Name</th>
                        <th>Remarks</th>
                        <th>Credit Taken</th>
                        <th>Credit Taken Month</th>
                        <th>Created By</th>
                        <th>Company Name</th>
                    </tr>

                    <tr class="table-danger">
                        <th></th> <!-- Empty for checkbox -->
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




    <div class="modal fade" id="debitcreditrefno" tabindex="-1" aria-labelledby="debitcreditrefnoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg">

                <!-- Modal Header -->
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="debitcreditrefnoLabel">Debit/Credit Ref Number Update</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span><strong>Debit/Credit Number:</strong> <span class="debitcredit_no"></span></span>
                        <span><strong>Date:</strong> <span class="debitcredit_date"></span></span>
                    </div>

                    <form id="debitcredit_ref_form">
                        <input type="hidden" class="debitcredit_id">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="buyer_refno" class="form-label">Debit/Credit Ref Number</label>
                                <input type="text" class="form-control buyer_refno" id="buyer_refno"
                                    placeholder="Enter Ref Number">
                            </div>
                            <div class="col-md-6">
                                <label for="buyer_refdate" class="form-label">Debit/Credit Ref Date</label>
                                <input type="date" class="form-control buyer_refdate" id="buyer_refdate">
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success debitref_save">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>


    <!-- Credit Update Modal -->
    <div class="modal fade" id="creditModal" tabindex="-1" aria-labelledby="creditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="creditModalLabel">Credit Update Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <form id="credit_update_form" data-parsley-validate>
                        <input type="hidden" class="invoice_id" value="">

                        <div class="mb-3">
                            <label class="form-label"><strong>Credit Taken:</strong></label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="credit_taken" id="credit_taken_yes"
                                    value="Yes">
                                <label class="form-check-label" for="credit_taken_yes">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="credit_taken" id="credit_taken_no"
                                    value="No">
                                <label class="form-check-label" for="credit_taken_no">No</label>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="credit_date" class="col-sm-4 col-form-label">Date</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control credit_date start_date" id="credit_date"
                                    name="credit_date" placeholder="Select date">
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="text-center">
                            <button type="button" class="btn btn-success" id="credit_update_val">Update</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')

    <script>

        // Add create button purpose
        $(document).ready(function () {
            if (window.toolbarButtons?.some(btn => btn.attr.id === 'create')) {
                $('#toolbar-container').append(`
                  <button class="btn btn-success text-white px-4 create me-2">Create
                    <i class="bi bi-plus-circle"></i> 
                  </button>
                `);
            }
        });

        $(document).ready(function () {

            var status = "{{$status}}";

            var table = $('#AccTbl').DataTable({
                processing: true,
                serverSide: false,
                scrollX: true,
                scrollY: "50vh",
                orderCellsTop: true,
                ajax: "getdebitcreditData?status=" + status,
                columns: [
                    {
                        data: 'debitcredit_id',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function (data) {
                            return `<input type="checkbox" class="row-checkbox" value="${data}">`;
                        }
                    },
                    {
                        data: 'debitcredit_id',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',

                        render: function (data, type, row) {
                            let buttons = '';
                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                                buttons += `
            <button class="btn btn-sm btn-warning view-btn" data-id="${row.debitcredit_id}" data-status="${row.debitcredit_status}">
              <i class="bi bi-eye"></i>
            </button>`;
                            }

                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'edit-btn')) {
                                buttons += `
            <button class="btn btn-sm btn-primary edit-btn" data-id="${row.debitcredit_id}" data-status="${row.debitcredit_status}">
              <i class="bi bi-pencil"></i>
            </button>`;
                            }

                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'print')) {
                                buttons += `
            <button class="btn btn-sm btn-success print-btn" data-id="${row.debitcredit_id}" data-status="${row.debitcredit_status}">
              <i class="bi bi-printer"></i>
            </button>`;
                            }
                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'approval')) {
                                buttons += `
            <button class="btn btn-sm btn-success approve-btn" data-id="${row.debitcredit_id}" data-status="${row.debitcredit_status}">
              Approve
            </button>`;
                            }

                            if (window.toolbarButtons?.some(btn => btn.attr.id === 'docrefno')) {
                                buttons += `
            <button class="btn btn-sm btn-secondary docrefno-btn" data-id="${row.debitcredit_id}" data-status="${row.debitcredit_status}"
            data-number="${row.debitcredit_no}"
            data-date="${row.debitcredit_date}"
            data-bs-toggle="tooltip" 
            data-bs-placement="top" 
            title="Other Doc ref No">
             <i class="bi bi-clipboard-check"></i>
            </button>`;

                            }


                            return buttons;
                        }
                    },

                    { data: 'debitcredit_no', name: 'debitcredit_no', className: 'text-center' },
                    { data: 'source_type', name: 'source_type', className: 'text-center' },
                    { data: 'reference_no', name: 'reference_no', className: 'text-center' },
                    { data: 'invoice_no', name: 'invoice_no', className: 'text-center' },
                    { data: 'debitcredit_date', name: 'debitcredit_date', className: 'text-center' },
                    { data: 'debitcredit_type', name: 'debitcredit_type', className: 'text-center' },
                    { data: 'debitcredit_status', name: 'debitcredit_status', className: 'text-center' },
                    { data: 'debitcredit_amount', name: 'debitcredit_amount', className: 'text-center' },
                    { data: 'supplier_id', name: 'supplier_id', className: 'text-center' },
                    { data: 'customer_name', name: 'customer_name', className: 'text-center' },
                    { data: 'remarks', name: 'remarks', className: 'text-center' },
                    { data: 'credit_taken', name: 'credit_taken', className: 'text-center' },
                    { data: 'credit_date', name: 'credit_date', className: 'text-center' },
                    { data: 'username', name: 'username', className: 'text-center' },
                    { data: 'company_name', name: 'company_name', className: 'text-start', width: '350px' },

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


            $('#AccTbl thead').on('keyup change', ".column-search", function () {
                var colIndex = $(this).parent().index();
                table.column(colIndex).search(this.value).draw();
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



        // Create

        $(".create").click(function () {
            var url = "{{  URL::to('debitcreditnotecreate')}}";
            window.location.replace(url);
        });


        /* Edit Function*/
        $(document).on('click', '.edit-btn', function () {
            const id = $(this).data('id');
            const status = $(this).data('status');


            if (status != "APPROVED") {
                window.location.replace('debitcreditnotecreate/' + id);
            }
            else {
                showCustomAlert("Approved Data Cant Edit", "error");
            }
        });


        /* Purpose For approve Function*/
        $(document).on('click', '.approve-btn', function () {

            const id = $(this).data('id');
            const status = $(this).data('status');

            window.location.replace('debitcreditapproval/' + id + '/' + status);

        });


        //view function
        $(document).on('click', '.view-btn', function () {
            const id = $(this).data('id');
            const url = "{{ url('debitcreditview') }}/" + id;
            window.location.href = url;
        });

        //Print function
        $(document).on('click', '.print-btn', function () {
            const id = $(this).data('id');
            const url = "{{ url('debitprint') }}/" + id;
            window.open(url, '_blank');
        });



        // Credit update
        $('#credit_update').on('click', function () {
            var table = $('#AccTbl').DataTable();
            var selectedIds = [];
            var notApproved = false;

            // Loop through checked rows
            $('#AccTbl tbody input.row-checkbox:checked').each(function () {
                var rowData = table.row($(this).closest('tr')).data();
                if (rowData) {
                    selectedIds.push(rowData.debitcredit_id);
                    if (rowData.debitcredit_status !== "APPROVED") {
                        notApproved = true;
                    }
                }
            });

            if (selectedIds.length === 0) {
                showCustomAlert("Please select at least one row", "info");
                return;
            }

            if (notApproved) {
                showCustomAlert("Please select Approved rows only", "info");
                return;
            }

            // Show modal and set selected IDs
            $('#creditModal').modal('show');
            $('.debitcredit_id').val(selectedIds.join(','));
        });


        // Handle Credit Update modal submit
        $(document).on('click', '#credit_update_val', function () {
            var creditdebit_id = $('.creditdebit_id').val();
            var url = "{{ URL::to('crdrcreditupdate') }}/" + creditdebit_id;
            var formdata = $('#credit_update_form').serialize();

            $.post(url, formdata, function (data) {
                var status = data.status;
                var msg = data.message;

                showCustomAlert(msg, "success");

                // Reload table or redirect
                var red_url = "{{ URL::to('debitcreditnote') }}";
                window.location.href = red_url;
            }).fail(function () {
                showCustomAlert("Error updating expense credit.", "error");
            });
        });


        //  file

        $(document).on('click', '.docrefno-btn', function () {

            const debitcredit_id = $(this).data('id');
            const debitcredit_status = $(this).data('status');

            // Retrieve row data from DataTable (safer for date/no)
            const table = $('#AccTbl').DataTable();
            const rowData = table.row($(this).closest('tr')).data();

            const debitcredit_no = rowData?.debitcredit_no || '';
            const debitcredit_date = rowData?.debitcredit_date || '';

            // Only allow APPROVED expenses

            $(".debitcredit_no").text(debitcredit_no);
            $(".debitcredit_date").text(debitcredit_date);
            $(".debitcredit_id").val(debitcredit_id);

            // Show modal first
            $("#debitcreditrefno").modal('show');


            // Fetch attachment status
            const url = "{{ URL::to('debitcreditrefno') }}?id=" + debitcredit_id;
            $.get(url, function (data) {
                $('.buyer_refno').val(data.buyer_refno);
                $('.buyer_refdate').val(data.buyer_refdate);
            }).fail(function () {
                $('.buyer_refno').text('Error checking attachment');
            });

        });


        $(document).on('click', '.debitref_save', function () {
            var id = $(".debitcredit_id").val();
            var buyer_refno = $(".buyer_refno").val();
            var buyer_refdate = $(".buyer_refdate").val();
            $.get("debitcreditrefnoupdate?id=" + id + "&buyer_refno=" + buyer_refno + "&buyer_refdate=" + buyer_refdate, function (data) {
                if ($.trim(data) == '1') {
                    showCustomAlert("Other Document Ref  Number Updated Successfully", "success");

                    $("#debitcreditrefno").modal('hide');
                } else {

                    showCustomAlert("Other Document Ref Number Updated Successfully", "success");
                }
            });
        });

    </script>

@endpush