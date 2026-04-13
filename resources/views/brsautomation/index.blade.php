@extends('layouts.header')
@section('content')
    <h3 class="text-danger"> BRS Pending - Automation </h3>
    @include('layouts.breadcrumb')

    <button class="btn btn-primary mt-2 px-4" id="brs_update"> BRS Update </button>



    <div class="card shadow-lg rounded-4 border-0">
        <div class="container mt-4 table-responsive">
            <table id="AccTbl" class="table table-bordered table-striped">
                <thead>
                    <tr class="table-warning">
                        <th>Actions</th>
                        <th>Status</th>
                        <th>Ref Number</th>
                        <th>Receipt/Payment Date</th>
                        <th>Supp/Cus/Emp Name</th>
                        <th>Bank Name</th>
                        <th>Mode Of Payment</th>
                        <th>Receipt/Payment Amount</th>
                        <th>Narration</th>
                        <th>Bank Date</th>
                        <th>Bank Amount</th>
                        <th>Bank Narration</th>
                        <th>Type</th>

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
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>




    <div class="modal fade" id="brsModal" tabindex="-1" aria-labelledby="brsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-4">

                <!-- Modal Header -->
                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="modal-title fw-bold" id="brsModalLabel">
                        <i class="fa fa-university me-2"></i>BRS Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body py-4">
                    <form id="brs_update_form" data-parsley-validate>
                        <input type="hidden" class="invoice_id" name="invoice_id" value="">

                        <!-- Bank Details -->
                        <h6 class="fw-bold text-primary mb-3"><i class="fa fa-bank me-2"></i>Bank Details</h6>
                        <div class="row g-4 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Bank Date</label>
                                <input type="text" class="form-control bank_date" id="bank_date" name="bank_date" readonly>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Bank Amount</label>
                                <input type="text" class="form-control bank_amt" id="bank_amt" name="bank_amt" readonly>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Bank Narration</label>
                                <input type="text" class="form-control bank_remark" id="bank_remark" name="bank_remark"
                                    readonly>
                            </div>
                        </div>

                        <!-- Aspire Details -->
                        <h6 class="fw-bold text-primary mb-3"><i class="fa fa-file-text me-2"></i>Aspire Details</h6>
                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Aspire Date</label>
                                <input type="text" class="form-control aspire_date" id="aspire_date" name="aspire_date"
                                    readonly>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Aspire Amount</label>
                                <input type="text" class="form-control aspire_amt" id="aspire_amt" name="aspire_amt"
                                    readonly>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Aspire Narration</label>
                                <input type="text" class="form-control aspire_remark" id="aspire_remark"
                                    name="aspire_remark" readonly>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12 emppopup"></div>
                        </div>
                    </form>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success px-4" id="brs_update_val">
                        <i class="fa fa-save me-1"></i>Update
                    </button>
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i>Close
                    </button>
                </div>

            </div>
        </div>
    </div>


@endsection
@push('scripts')

    <script>

        $(document).ready(function () {
            // Initialize DataTable
            var table = $('#AccTbl').DataTable({
                processing: true,
                serverSide: false,
                scrollX: true,
                scrollY: "50vh",
                orderCellsTop: true,
                ajax: "{{ route('getbrsData') }}",
                columns: [
                    {
                        data: 'id',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function (data, type, row) {
                            return `<input type="checkbox" class="row-select" value="${row.id}" data-type="${row.type}" data-bankid="${row.bankstmt_id}" />`;
                        }
                    },
                    {
                        data: 'status',
                        className: 'text-center',
                        render: function (data) {
                            let color = '';
                            if (data === 'MATCH') color = 'green';
                            else if (data === 'PARTIAL MATCH') color = 'blue';
                            else color = 'red';
                            return `<span style="color:${color}; font-weight:bold;">${data}</span>`;
                        }
                    },
                    { data: 'number', className: 'text-center' },
                    { data: 'date', className: 'text-center' },
                    { data: 'name', className: 'text-center' },
                    { data: 'bank_name', className: 'text-center' },
                    { data: 'payment_mode', className: 'text-center' },
                    { data: 'amount', className: 'text-center' },
                    { data: 'narration', className: 'text-center' },
                    { data: 'bank_date', className: 'text-center' },
                    { data: 'bank_amount', className: 'text-center' },
                    { data: 'bank_narration', className: 'text-center' },
                    { data: 'type', className: 'text-center' },
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

            // ========= Multi-row Receipt =========
            $("#receiptinv").click(function () {
                let selectedRows = $(".row-select:checked");
                if (selectedRows.length === 0) {
                    showCustomAlert("Please Select Row", 'info');
                    return;
                }

                let invoice_ids = [];
                let sales_ids = [];

                selectedRows.each(function () {
                    let rowData = table.row($(this).closest('tr')).data();
                    if (rowData.invoice_hdr_id) invoice_ids.push(rowData.invoice_hdr_id);
                    if (rowData.sales_hdr_id) sales_ids.push(rowData.sales_hdr_id);
                });

                if (invoice_ids.length > 0) {
                    window.location.replace('receiptforinvoicecrt/' + invoice_ids.join(',') + '/0/0');
                } else {
                    showCustomAlert("Please Select Row", 'info');
                }
            });

            // ========= BRS Update Modal =========
            $(document).on('click', '#brs_update', function () {
                let selectedRows = $(".row-select:checked");
                if (selectedRows.length === 0) {
                    showCustomAlert("Please Select a Row", 'info');
                    return;
                }

                let allMatchOrPartial = true;
                let firstRowData = table.row(selectedRows[0].closest('tr')).data();

                selectedRows.each(function () {
                    let rowData = table.row($(this).closest('tr')).data();
                    if (rowData.status === 'MISMATCH') allMatchOrPartial = false;
                });

                if (!allMatchOrPartial) {
                    showCustomAlert("Please Select Match AND Partial Match Records Only", 'info');
                    return;
                }

                // Populate modal with first selected row
                $("#bank_date").val(firstRowData.bank_date);
                $("#bank_amt").val(firstRowData.bank_amount);
                $("#bank_remark").val(firstRowData.bank_narration);
                $("#aspire_date").val(firstRowData.date);
                $("#aspire_amt").val(firstRowData.amount);
                $("#aspire_remark").val(firstRowData.narration);

                $('#brsModal').modal('show');
            });

            // ========= BRS Update Save =========
            $(document).on('click', '#brs_update_val', function () {
                let selectedRows = $(".row-select:checked");
                if (selectedRows.length === 0) {
                    showCustomAlert("Please select at least one record.", 'info');
                    return;
                }

                let types = [], bank_ids = [], ids = [], allMatch = true;

                selectedRows.each(function () {
                    let rowData = table.row($(this).closest('tr')).data();
                    types.push(rowData.type);
                    bank_ids.push(rowData.bankstmt_id);
                    ids.push(rowData.id);
                    if (rowData.status !== 'MATCH') allMatch = false;
                });

                if (!allMatch) {
                    showCustomAlert("Only Status MATCH records are allowed for BRS Update.", "info");
                    return;
                }

                let uniqueTypes = [...new Set(types)];
                if (uniqueTypes.length > 1) {
                    showCustomAlert("Can't BRS Update for Different Types", "info");
                    return;
                }

                $.ajax({
                    url: "{{ URL::to('brsupdatesave') }}",
                    type: "POST",
                    data: {
                        id: ids.join(','),
                        type: uniqueTypes[0],
                        bank_id: bank_ids.join(','),
                        _token: "{{ csrf_token() }}"
                    },
                    dataType: "json",
                    success: function (data) {
                        showCustomAlert(data.message, data.status);
                        table.ajax.reload();
                    },
                    error: function () {
                        showCustomAlert("Something went wrong. Please try again.", 'error');
                    }
                });
            });
        });


    </script>

@endpush