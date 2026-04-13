@extends('layouts.header')
@section('content')

    <h3 class="text-danger">Purchase Dashboard</h3>

    <div class="container mt-4">
        <!-- First row of buttons -->
        <div class="row g-3 mb-3">
            <div class="col-12 col-md-3">
                <a href="purchasedashboard" class="btn btn-outline-success w-100">Supplier Based</a>
            </div>
            <div class="col-12 col-md-3">
                <a href="purchasedashboardproduct" class="btn btn-outline-primary w-100">Product Based</a>
            </div>
            <div class="col-12 col-md-3">
                <a href="purchasedashboardqty-val" class="btn btn-info w-100">Top Rating Qty & Value</a>
            </div>
            <div class="col-12 col-md-3">
                <a href="purchasedashboardpricetrend" class="btn btn-outline-secondary w-100">Product Price Trend</a>
            </div>
            <div class="col-12 col-md-3">
                <a href="purchaseproductexceptionrpt" class="btn btn-outline-primary w-100">Price Trend Exception</a>
            </div>
            <div class="col-12 col-md-3">
                <a href="purchaseproductmostspend" class="btn btn-outline-dark w-100">Most Spend Value Products</a>
            </div>
            <div class="col-12 col-md-3">
                <a href="rejectedpuritems" class="btn btn-outline-danger w-100">Rejected Products List</a>
            </div>
            <div class="col-12 col-md-3">
                <a href="consumptionquantityreport" class="btn btn-outline-warning w-100">Consumption Quantity Report</a>
            </div>

            <div class="col-12 col-md-3">
                <a href="movementreport" class="btn btn-outline-secondary w-100">Movement Report</a>
            </div>

            <div class="col-12 col-md-3">
                <a href="purchase-supplier-summary" class="btn btn-outline-primary w-100">New Supplier Report</a>
            </div>

            <div class="col-12 col-md-3">
                <a href="purchase-product-summary" class="btn btn-outline-info w-100">New Purchase Product</a>
            </div>

            <div class="col-12 col-md-3">
                <a href="productissuedelay" class="btn btn-outline-primary w-100">Pack Material Delay Issue Report</a>
            </div>

        </div>
    </div>

    <!-- drop down -->

    <div class="card shadow-lg rounded-4 border-0 p-4">
        <form action="{{ url('purchasedashboardqty-val') }}" method="get" id="searchForm">
            <div class="row g-4">

                <!-- Supplier Name -->
                <div class="col-md-4">
                    <label for="product_name" class="form-label fw-semibold">Product Group</label>
                    <select name="product_name" id="product_name" class="form-select select2" required>
                        {!! $pro_group !!}
                    </select>
                </div>

                <!-- From Date -->
                <div class="col-md-4">
                    <label for="start_date" class="form-label fw-semibold">From Date</label>
                    <input type="text" class="form-control start_date1" id="start_date" name="start_date" required
                        placeholder="YYYY-MM-DD">
                </div>

                <!-- To Date -->
                <div class="col-md-4">
                    <label for="end_date" class="form-label fw-semibold">To Date</label>
                    <input type="text" class="form-control end_date1" id="end_date" name="end_date" required
                        placeholder="YYYY-MM-DD">
                </div>

                <!-- Search Button -->
                <div class="col-12 text-center mt-3">
                    <button type="submit" class="btn btn-primary px-5">Search</button>
                </div>

            </div>
        </form>
    </div>

    <!-- end -->

    <!-- product based -->
    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table1" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <tr>
                        <?php if (request('start_date') != ''): ?>
                        <th class="text-center text-white" style="background:#c54444">{{ request('start_date')}} To
                            {{ request('end_date')}}</th>
                        <?php endif; ?>
                        <th colspan="12" class="text-center text-white" style="background:#47647c;">TOP RATING QTY & VALUE
                        </th>
                    </tr>

                    <!-- Second header row with 'qty' and 'value' columns under each month -->
                    <tr style="background:#c32323 !important;">

                        <th class="text-center bg-danger text-white">Product Name</th>
                        <th class="text-center bg-danger text-white">Product Qty</th>
                        <th class="text-center bg-danger text-white">Total Value (Rs)</th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($top_qty as $value) { ?>
                    <tr>
                        <td style="text-align:center;">{{$value->concatenated_product}}</td>
                        <td style="text-align: center;">{{$value->p_qty}}</td>
                        <td style="text-align: center;">{{$value->subtotal}}</td>

                    </tr>
                    <?php } ?>
                </tbody>

            </table>
        </div>
    </div>

@endsection
@push('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

    <script>

        $(document).on('change', '.product_group', function () {
            var prdgroup = $('.product_group').select2('val');
            if (prdgroup != '') {
                $(".product_name").jCombo("{{ URL::to('jcomboform?table=m_products_t:product_id:concatenated_product') }}&parent=product_group_id=" + prdgroup + "&order_by=concatenated_product asc", {
                    selected_value: ""
                });
            }
            console.log(prdgroup);

        });

        $(document).ready(function () {
            $('#Table1').DataTable({
                
                  scrollX: true,
                  scrollY: "50vh",
                dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
                buttons: [
                    {
                        extend: 'colvis',
                        text: '<i class="bi bi-layout-three-columns"></i> Columns',
                        className: 'btn bg-primary btn-sm',
                        postfixButtons: ['colvisRestore'] // adds "Restore Columns" button
                    },
                    { extend: 'excelHtml5', title: "Top_rating_qty&value", exportOptions: { columns: ':visible' } }
                ]
            });
        });

        $(document).ready(function () {
            var productName = "{{ request('product_name') }}";
            var productGroup = "{{ request('product_group') }}";
            var startDate = "{{ request('start_date') }}";
            var endDate = "{{ request('end_date') }}";

            $('.product_name').select2();
            $('#product_name').val(productName).trigger('change');
            $('.product_group').select2();
            $('#product_group').val(productGroup).trigger('change');

            $('#start_date').val(startDate);
            $('#end_date').val(endDate);

        });

    </script>
@endpush