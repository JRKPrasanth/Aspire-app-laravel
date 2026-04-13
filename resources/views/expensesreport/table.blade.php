@extends('layouts.header')
@section('content')
    <h3 class="text-danger mb-4"> Expense Detail Report </h3>
    @include('layouts.breadcrumb')


    <div class="card shadow-lg rounded-4 border-0">

        <div class="card-body p-4">
            <form method="post" action="" id="job_card_reprot" class="needs-validation" novalidate
                enctype="multipart/form-data">
                @csrf

                <!-- First Row: Date Inputs -->
                <div class="row g-4 mb-3">
                    <div class="col-md-2"></div>
                    <div class="col-md-4">
                        <label for="start_date" class="form-label fw-semibold">From Date</label>
                        <input type="text" class="form-control start_date" id="start_date" name="start_date" required
                            autocomplete="off">
                        <div class="invalid-feedback">Please select a start date.</div>
                    </div>

                    <div class="col-md-4">
                        <label for="end_date" class="form-label fw-semibold">To Date</label>
                        <input type="text" class="form-control end_date" id="end_date" name="end_date" required
                            autocomplete="off">
                        <div class="invalid-feedback">Please select an end date.</div>
                    </div>
                </div>

                <!-- Second Row: Centered Search Button -->
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button type="button" class="btn btn-success bg-gradient px-4 report_search" id="report_search">
                            <i class="bi bi-search-heart me-1"></i> Search
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3"></div>
            <div class="table-responsive">
                <table id="ReportTbl" class="table table-striped table-bordered">
                    <thead>
                        <tr class="table-warning">
                            <th class="freeze">Expense No</th>
                            <th>Expense Date</th>
                            <th>Created Date</th>
                            <th>Updated Date</th>
                            <th>Expense Type</th>
                            <th>Employee Code</th>
                            <th>Employee Name</th>
                            <th>Employee Type</th>
                            <th>Supplier Code</th>
                            <th>Supplier Name</th>
                            <th>Supplier GST NO</th>
                            <th>Customer Code</th>
                            <th>Customer Name</th>
                            <th>Customer GST No</th>
                            <th>Reference No</th>
                            <th>Bill Date</th>
                            <th>Reverse Charge</th>
                            <th>Tds Applicable</th>
                            <th>Tds Account</th>
                            <th>Tds Percentage</th>
                            <th>Tds Amount</th>
                            <th>Round Off</th>
                            <th>Expense Account Name</th>
                            <th>Expense Account Structure</th>
                            <th>Expense Amount</th>
                            <th>HSN Code</th>
                            <th>Tax Group</th>
                            <th>CGST</th>
                            <th>SGST</th>
                            <th>IGST</th>
                            <th>Tax Amount</th>
                            <th>Expense Total</th>
                            <th>Remarks</th>

                        </tr>
                        <tr class="table-danger">
                            <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                                    style="display:none;">Expense No</span></th>
                            <th><input type="text" class="column-search" placeholder="Search"><span
                                    style="display:none;">Expense
                                    Date</span></th>
                            <th><input type="text" class="column-search" placeholder="Search"><span
                                    style="display:none;">Created
                                    Date</span></th>
                            <th><input type="text" class="column-search" placeholder="Search"><span
                                    style="display:none;">Updated
                                    Date</span></th>
                            <th><input type="text" class="column-search" placeholder="Search"><span
                                    style="display:none;">Expense
                                    Type</span></th>
                            <th><input type="text" class="column-search" placeholder="Search"><span
                                    style="display:none;">Employee
                                    Code</span></th>
                            <th><input type="text" class="column-search" placeholder="Search"><span
                                    style="display:none;">Employee
                                    Name</span></th>
                            <th><input type="text" class="column-search" placeholder="Search"><span
                                    style="display:none;">Employee
                                    Type</span></th>
                            <th><input type="text" class="column-search" placeholder="Search"><span
                                    style="display:none;">Supplier
                                    Code</span></th>
                            <th><input type="text" class="column-search" placeholder="Search"><span
                                    style="display:none;">Supplier
                                    Name</span></th>
                            <th><input type="text" class="column-search" placeholder="Search"><span
                                    style="display:none;">Supplier GST
                                    NO</span></th>
                            <th><input type="text" class="column-search" placeholder="Search"><span
                                    style="display:none;">Customer
                                    Code</span></th>
                            <th><input type="text" class="column-search" placeholder="Search"><span
                                    style="display:none;">Customer
                                    Name</span></th>
                            <th><input type="text" class="column-search" placeholder="Search"><span
                                    style="display:none;">Customer GST
                                    No</span></th>
                            <th><input type="text" class="column-search" placeholder="Search"><span
                                    style="display:none;">Reference
                                    No</span></th>
                            <th><input type="text" class="column-search" placeholder="Search"><span
                                    style="display:none;">Bill
                                    Date</span></th>
                            <th><input type="text" class="column-search" placeholder="Search"><span
                                    style="display:none;">Reverse
                                    Charge</span></th>
                            <th><input type="text" class="column-search" placeholder="Search"><span
                                    style="display:none;">Tds
                                    Applicable</span></th>
                            <th><input type="text" class="column-search" placeholder="Search"><span
                                    style="display:none;">Tds
                                    Account</span></th>
                            <th><input type="text" class="column-search" placeholder="Search"><span
                                    style="display:none;">Tds
                                    Percentage</span></th>
                            <th><input type="text" class="column-search" placeholder="Search"><span
                                    style="display:none;">Tds
                                    Amount</span></th>
                            <th><input type="text" class="column-search" placeholder="Search"><span
                                    style="display:none;">Round
                                    Off</span></th>
                            <th><input type="text" class="column-search" placeholder="Search"><span
                                    style="display:none;">Expense
                                    Account Name</span></th>
                            <th><input type="text" class="column-search" placeholder="Search"><span
                                    style="display:none;">Expense
                                    Account Structure</span></th>
                            <th><input type="text" class="column-search" placeholder="Search"><span
                                    style="display:none;">Expense
                                    Amount</span></th>
                            <th><input type="text" class="column-search" placeholder="Search"><span
                                    style="display:none;">HSN
                                    Code</span></th>
                            <th><input type="text" class="column-search" placeholder="Search"><span
                                    style="display:none;">Tax
                                    Group</span></th>
                            <th><input type="text" class="column-search" placeholder="Search"><span
                                    style="display:none;">CGST</span>
                            </th>
                            <th><input type="text" class="column-search" placeholder="Search"><span
                                    style="display:none;">SGST</span>
                            </th>
                            <th><input type="text" class="column-search" placeholder="Search"><span
                                    style="display:none;">IGST</span>
                            </th>
                            <th><input type="text" class="column-search" placeholder="Search"><span
                                    style="display:none;">Tax
                                    Amount</span></th>
                            <th><input type="text" class="column-search" placeholder="Search"><span
                                    style="display:none;">Expense
                                    Total</span></th>
                            <th><input type="text" class="column-search" placeholder="Search"><span
                                    style="display:none;">Remarks</span>
                            </th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr class="table-info fw-bold">
                            <th class="freeze">PAGE TOTAL</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr class="table-success fw-bold">
                            <th class="freeze">GRAND TOTAL</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>

                    <tbody>
                        <!-- Your dynamic row data goes here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
@push('scripts')

    <script>

        $(document).ready(function () {

            $('#ReportTbl').DataTable({
                processing: true,
                serverSide: false,
                scrollX: true,
                scrollY: "50vh",
                orderCellsTop: true,
                ajax: {
                    url: "{{ url('getExpenseData') }}",
                    type: "GET",
                    data: function (d) {
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                    }
                },
                columns: [
                    { class: 'freeze', data: "expense_no" },
                    { data: "expense_date" },
                    { data: "created_at" },
                    { data: "updated_at" },
                    { data: "expense_type" },
                    { data: "employee_number" },
                    { data: "first_name" },
                    { data: "emp_type" },
                    { data: "supplier_number" },
                    { data: "supplier_name" },
                    { data: "gst_number" },
                    { data: "customer_number" },
                    { data: "customer_name" },
                    { data: "gst_no" },
                    { data: "invoice" },
                    { data: "bill_date" },
                    { data: "reverse_charge" },
                    { data: "tds_applicable" },
                    { data: "concatenated_segments" },
                    { data: "tds_prcnt" },
                    { data: "tdsamount" },
                    { data: "round_off" },
                    { data: "accountname" },
                    { data: "accountstructure" },
                    { data: "expense_line_amount" },
                    { data: "classification_code" },
                    { data: "tax_group_name" },
                    { data: "cgst" },
                    { data: "sgst" },
                    { data: "igst" },
                    { data: "taxamount" },
                    { data: "expense_amount" },
                    { data: "remarks" }


                ],

                footerCallback: function () {

                    let api = this.api();

                    let num = function (i) {
                        return typeof i === 'string'
                            ? i.replace(/,/g, '') * 1
                            : typeof i === 'number'
                                ? i
                                : 0;
                    };

                    // -------------------------
                    // PAGE TOTAL (visible rows)
                    // -------------------------
                    let pageTdsamt = api.column(20, { page: 'current' }).data()
                        .reduce((a, b) => num(a) + num(b), 0);

                    let pageRound = api.column(21, { page: 'current' }).data()
                        .reduce((a, b) => num(a) + num(b), 0);

                    let pageExplne = api.column(24, { page: 'current' }).data()
                        .reduce((a, b) => num(a) + num(b), 0);

                    let pageSgst = api.column(27, { page: 'current' }).data()
                        .reduce((a, b) => num(a) + num(b), 0);

                    let pageCgst = api.column(28, { page: 'current' }).data()
                        .reduce((a, b) => num(a) + num(b), 0);

                    let pageIgst = api.column(29, { page: 'current' }).data()
                        .reduce((a, b) => num(a) + num(b), 0);

                    let pageTaxamt = api.column(30, { page: 'current' }).data()
                        .reduce((a, b) => num(a) + num(b), 0);

                    let pageExpamt = api.column(31, { page: 'current' }).data()
                        .reduce((a, b) => num(a) + num(b), 0);

                    // -------------------------
                    // GRAND TOTAL (ALL rows)
                    // -------------------------
                    let grandTdsamt = api.column(20).data()
                        .reduce((a, b) => num(a) + num(b), 0);

                    let grandRound = api.column(21).data()
                        .reduce((a, b) => num(a) + num(b), 0);

                    let grandExplne = api.column(24).data()
                        .reduce((a, b) => num(a) + num(b), 0);

                    let grandSgst = api.column(27).data()
                        .reduce((a, b) => num(a) + num(b), 0);

                    let grandCgst = api.column(28).data()
                        .reduce((a, b) => num(a) + num(b), 0);

                    let grandIgst = api.column(29).data()
                        .reduce((a, b) => num(a) + num(b), 0);

                    let grandTaxamt = api.column(30).data()
                        .reduce((a, b) => num(a) + num(b), 0);

                    let grandExpamt = api.column(31).data()
                        .reduce((a, b) => num(a) + num(b), 0);

                    // PAGE TOTAL row (1st footer row)
                    $(api.column(20).footer()).closest('tfoot').find('tr:eq(0) th:eq(20)')
                        .html(pageTdsamt.toFixed(2));
                    $(api.column(21).footer()).closest('tfoot').find('tr:eq(0) th:eq(21)')
                        .html(pageRound.toFixed(2));
                    $(api.column(24).footer()).closest('tfoot').find('tr:eq(0) th:eq(24)')
                        .html(pageExplne.toFixed(2));
                    $(api.column(27).footer()).closest('tfoot').find('tr:eq(0) th:eq(27)')
                        .html(pageSgst.toFixed(2));
                    $(api.column(28).footer()).closest('tfoot').find('tr:eq(0) th:eq(28)')
                        .html(pageCgst.toFixed(2));
                    $(api.column(29).footer()).closest('tfoot').find('tr:eq(0) th:eq(29)')
                        .html(pageIgst.toFixed(2));
                    $(api.column(30).footer()).closest('tfoot').find('tr:eq(0) th:eq(30)')
                        .html(pageTaxamt.toFixed(2));
                    $(api.column(31).footer()).closest('tfoot').find('tr:eq(0) th:eq(31)')
                        .html(pageExpamt.toFixed(2));

                    // GRAND TOTAL row (2nd footer row)
                    $(api.column(20).footer()).closest('tfoot').find('tr:eq(1) th:eq(20)')
                        .html(grandTdsamt.toFixed(2));
                    $(api.column(21).footer()).closest('tfoot').find('tr:eq(1) th:eq(21)')
                        .html(grandRound.toFixed(2));
                    $(api.column(24).footer()).closest('tfoot').find('tr:eq(1) th:eq(24)')
                        .html(grandExplne.toFixed(2));
                    $(api.column(27).footer()).closest('tfoot').find('tr:eq(1) th:eq(27)')
                        .html(grandSgst.toFixed(2));
                    $(api.column(28).footer()).closest('tfoot').find('tr:eq(1) th:eq(28)')
                        .html(grandCgst.toFixed(2));
                    $(api.column(29).footer()).closest('tfoot').find('tr:eq(1) th:eq(29)')
                        .html(grandIgst.toFixed(2));
                    $(api.column(30).footer()).closest('tfoot').find('tr:eq(1) th:eq(30)')
                        .html(grandTaxamt.toFixed(2));
                    $(api.column(31).footer()).closest('tfoot').find('tr:eq(1) th:eq(31)')
                        .html(grandExpamt.toFixed(2));
                },

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

            // Trigger search
            $('.report_search').on('click', function () {
                $('#ReportTbl').DataTable().ajax.reload();
            });

        });

    </script>
@endpush