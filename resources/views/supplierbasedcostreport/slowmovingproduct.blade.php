@extends('layouts.header')
@section('content')
    <h3 class="text-danger"> Slow Moving Product </h3>
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
                        <label for="start_date" class="form-label fw-semibold">As On Date</label>
                        <input type="text" class="form-control start_date" id="start_date" name="start_date" required
                            autocomplete="off">
                        <div class="invalid-feedback">Please select a start date.</div>
                    </div>
                    <div class="col-md-4">
                        <label for="end_date" class="form-label fw-semibold">Product Group Name</label>
                        <select id="product_group_id" name='product_group_id' rows='5'
                            class='form-control product_group_id select2' tabindex="1" data-show-subtext="true"
                            data-live-search="true" required>
                            {!! $product_group_id !!}
                        </select>
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

                            <th>Product Group Name</th>
                            <th>Category Name</th>
                            <th>Sub-Category Name</th>
                            <th>

                                Product Name
                            </th>
                            <th>

                                Batch Number
                            </th>
                            <th>

                                Locator Name
                            </th>
                            <th>

                                Locator Code
                            </th>
                            <th>

                                Mfg Date
                            </th>
                            <th>

                                Exp Date
                            </th>
                            <th>

                                Last Trx Date
                            </th>

                        </tr>

                        <tr class="table-danger">

                            <th>
                                <input type="text" class="column-search" placeholder="Search" />
                                <span style="display: none;">Product Group Name</span>
                            </th>
                            <th>
                                <input type="text" class="column-search" placeholder="Search" />
                                <span style="display: none;">Product Category Name</span>
                            </th>
                            <th>
                                <input type="text" class="column-search" placeholder="Search" />
                                <span style="display: none;">Sub-Category Name</span>
                            </th>
                            <th>
                                <input type="text" class="column-search" placeholder="Search" />
                                <span style="display: none;">Product Name</span>
                            </th>
                            <th>
                                <input type="text" class="column-search" placeholder="Search" />
                                <span style="display: none;">Batch Number</span>
                            </th>
                            <th>
                                <input type="text" class="column-search" placeholder="Search" />
                                <span style="display: none;">Locator Name</span>
                            </th>
                            <th>
                                <input type="text" class="column-search" placeholder="Search" />
                                <span style="display: none;">Locator Code</span>
                            </th>
                            <th>
                                <input type="text" class="column-search" placeholder="Search" />
                                <span style="display: none;">Mfg Date</span>
                            </th>
                            <th>
                                <input type="text" class="column-search" placeholder="Search" />
                                <span style="display: none;">Exp Date</span>
                            </th>
                            <th>
                                <input type="text" class="column-search" placeholder="Search" />
                                <span style="display: none;">Last Trx Date</span>
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
                    url: "{{ url('getslowmovingproduct') }}",
                    type: "GET",
                    data: function (d) {
                        d.start_date = $('#start_date').val();
                        d.product_group_id = $('#product_group_id').val();
                    }
                },
                columns: [
                    { data: 'product_group', name: 'product_group' },
                    { data: 'product_category', name: 'product_category' },
                    { data: 'product_subcategory', name: 'product_subcategory' },
                    { data: 'concatenated_product', name: 'concatenated_product' },
                    { data: 'batch_number', name: 'batch_number' },
                    { data: 'locator_name', name: 'locator_name' },
                    { data: 'locator_code', name: 'locator_code' },
                    { data: 'manufacturer_date', name: 'manufacturer_date' },
                    { data: 'product_expire_date', name: 'product_expire_date' },
                    { data: 'last_transaction_date', name: 'last_transaction_date' }
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


            // Column search
            $('#ReportTbl thead').on('keyup change', ".column-search", function () {
                var index = $(this).closest('th').index();
                table.column(index).search(this.value).draw();
            });
        });
    </script>

@endpush