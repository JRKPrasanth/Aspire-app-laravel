@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Consumables Transaction Report</h3>
    @include('layouts.breadcrumb')


    <div class="card shadow-lg border-0 rounded-4 mb-4">
        <div class="card-header bg-primary bg-gradient text-white fw-semibold">
            <i class="bi bi-funnel me-2"></i>Filter Options
        </div>
        <div class="card-body">
            <form method="post" action="" id="job_card_reprot" class="needs-validation" novalidate
                enctype="multipart/form-data">
                @csrf

                <div class="row g-4">
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

                <div class="d-flex justify-content-center mt-4">
                    <button type="button" class="btn bg-primary bg-gradient text-white px-4 report_search"
                        id="report_search">
                        <i class="bi bi-search-heart me-1"></i> Search
                    </button>
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

                            <th>Consumable Number</th>
                            <th>Date</th>
                            <th>Product Name</th>
                            <th>Batch No</th>
                            <th>Transaction Qty</th>
                            <th>Account Name</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th>Approved By</th>
                            <th>SubInventory Name</th>
                            <th>Locator</th>
                            <th>Comments</th>

                        </tr>
                        <tr class="table-danger">
                            <th>
                                <input type="text" class="column-search" placeholder="Search" />
                                <span style="display: none;">Consumable Number</span>
                            </th>
                            <th>
                                <input type="text" class="column-search" placeholder="Search" />
                                <span style="display: none;">Date</span>
                            </th>
                            <th>
                                <input type="text" class="column-search" placeholder="Search" />
                                <span style="display: none;">Product Name</span>
                            </th>
                            <th>
                                <input type="text" class="column-search" placeholder="Search" />
                                <span style="display: none;">Batch No</span>
                            </th>
                            <th>
                                <input type="text" class="column-search" placeholder="Search" />
                                <span style="display: none;">Transaction Qty</span>
                            </th>
                            <th>
                                <input type="text" class="column-search" placeholder="Search" />
                                <span style="display: none;">Account Name</span>
                            </th>
                            <th>
                                <input type="text" class="column-search" placeholder="Search" />
                                <span style="display: none;">Status</span>
                            </th>
                            <th>
                                <input type="text" class="column-search" placeholder="Search" />
                                <span style="display: none;">Created By</span>
                            </th>

                            <th>
                                <input type="text" class="column-search" placeholder="Search" />
                                <span style="display: none;">Approved By</span>
                            </th>
                            <th>
                                <input type="text" class="column-search" placeholder="Search" />
                                <span style="display: none;">SubInventory Name</span>
                            </th>
                            <th>
                                <input type="text" class="column-search" placeholder="Search" />
                                <span style="display: none;">Locator</span>
                            </th>
                            <th>
                                <input type="text" class="column-search" placeholder="Search" />
                                <span style="display: none;">Comments</span>
                            </th>
                        </tr>
                    </thead>
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

            var table = $('#ReportTbl').DataTable({
                processing: true,
                serverSide: false,
                scrollX: true,
                scrollY: "50vh",
                orderCellsTop: true,
                ajax: {
                    url: "{{ url('getconsumablereportdata') }}",
                    type: "GET",
                    data: function (d) {
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();

                    }
                },
                columns: [
                    { data: 'consumable_number', name: 'consumable_number' },
                    { data: 'consumable_date', name: 'consumable_date' },
                    { data: 'concatenated_product', name: 'concatenated_product' },
                    { data: 'batch_no', name: 'batch_no' },
                    { data: 'qty', name: 'qty' },
                    { data: 'concatenated_segments', name: 'concatenated_segments' },
                    { data: 'status', name: 'status' },
                    { data: 'first_name', name: 'first_name' },
                    { data: 'approver_name', name: 'approver_name' },
                    { data: 'subinventory_name', name: 'subinventory_name' },
                    { data: 'locator_name', name: 'locator_name' },
                    { data: 'comments', name: 'comments' }
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

            // Trigger search
            $('.report_search').on('click', function () {
                $('#ReportTbl').DataTable().ajax.reload();
            });
        });
    </script>
@endpush