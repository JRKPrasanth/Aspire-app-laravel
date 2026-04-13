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
                <a href="purchasedashboardqty-val" class="btn btn-outline-info w-100">Top Rating Qty & Value</a>
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
                <a href="rejectedpuritems" class="btn btn-danger w-100">Rejected Products List</a>
            </div>
            <div class="col-12 col-md-3">
                <a href="consumptionquantityreport" class="btn btn-outline-warning w-100">Consumption Quantity Report</a>
            </div>

            <div class="col-12 col-md-3">
                <a href="movementreport" class="btn btn-outline-secondary w-100">Movement Report</a>
            </div>

            <div class="col-12 col-md-3">
                <a href="purchase-supplier-summary" class="btn btn-outline-info w-100">New Supplier Report</a>
            </div>

            <div class="col-12 col-md-3">
                <a href="purchase-product-summary" class="btn btn-outline-primary w-100">New Purchase Product</a>
            </div>

            <div class="col-12 col-md-3">
                <a href="productissuedelay" class="btn btn-outline-primary w-100">Pack Material Delay Issue Report</a>
            </div>

        </div>
    </div>
    <!-- drop down -->
    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="container-fluid">
            <form action="{{ url('rejectedpuritems') }}" method="get" id="searchForm">
                <div class="row g-3 align-items-end mb-3">
                    <!-- From Date -->
                    <div class="col-md-4">
                        <label for="start_date" class="form-label">From Date</label>
                        <input type="text" class="form-control start_date1" id="start_date" name="start_date" required>
                    </div>

                    <!-- To Date -->
                    <div class="col-md-4">
                        <label for="end_date" class="form-label">To Date</label>
                        <input type="text" class="form-control end_date1" id="end_date" name="end_date" required>
                    </div>

                    <!-- Search Button -->
                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- end -->

    <!-- product based -->
    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table1" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <tr>
                        <th colspan="8" class="text-center text-white" style="background:#47647c;">Rejected/Replacement
                            Products</th>
                    </tr>

                    <tr style="background:#c32323 !important;">

                        <th class="text-center bg-danger text-white">Product Name</th>
                        <th class="text-center bg-danger text-white">Supplier Name</th>
                        <th class="text-center bg-danger text-white">Type</th>
                        <th class="text-center bg-danger text-white">Reason</th>
                        <th class="text-center bg-danger text-white">Month</th>
                        <th class="text-center bg-danger text-white">Qty</th>
                        <th class="text-center bg-danger text-white">Reject/Replace Qty</th>
                        <th class="text-center bg-danger text-white">Value (Rs)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($price_trend as $value) { ?>
                    <tr>
                        <td style="text-align:center;">{{$value->pro_name}}</td>
                        <td style="text-align: center;">{{$value->supplier_name}}</td>
                        <td style="text-align: center;">{{$value->status}}</td>
                        <td style="text-align: center;">{{$value->reason}}</td>
                        <td style="text-align: center;">{{$value->yr_month}}</td>
                        <td style="text-align: center;">{{$value->total_box_qty}}</td>
                        <td style="text-align: center;">{{$value->reject_qty}}</td>
                        <td style="text-align: center;">{{$value->value}}</td>
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


        $(document).ready(function () {
            $('#Table1').DataTable({
                dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
                buttons: [
                    {
                        extend: 'colvis',
                        text: '<i class="bi bi-layout-three-columns"></i> Columns',
                        className: 'btn bg-primary btn-sm',
                        postfixButtons: ['colvisRestore'] // adds "Restore Columns" button
                    },
                    { extend: 'excelHtml5', title: "Product_price_trend", exportOptions: { columns: ':visible' } }
                ]
            });
        });

        $(document).ready(function () {

            var startDate = "{{ request('start_date') }}";
            var endDate = "{{ request('end_date') }}";

            $('#start_date').val(startDate);
            $('#end_date').val(endDate);

        });

    </script>
@endpush