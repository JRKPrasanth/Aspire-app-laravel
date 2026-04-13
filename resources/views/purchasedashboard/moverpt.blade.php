@extends('layouts.header')
@section('content')

    <h3 class="text-danger">Purchase Dashboard</h3>

    <div class="container mt-4">
        <!-- First row of buttons -->
        <div class="row g-3 mb-3">
            <div class="col-12 col-md-3">
                <a href="purchasedashboard" class="btn btn-outline-success w-100 ">Supplier Based</a>
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
                <a href="rejectedpuritems" class="btn btn-outline-danger w-100">Rejected Products List</a>
            </div>
            <div class="col-12 col-md-3">
                <a href="consumptionquantityreport" class="btn btn-outline-warning w-100">Consumption Quantity Report</a>
            </div>

            <div class="col-12 col-md-3">
                <a href="movementreport" class="btn btn-secondary w-100">Movement Report</a>
            </div>

            <div class="col-12 col-md-3">
                <a href="purchase-supplier-summary" class="btn btn-outline-info w-100">New Supplier Report</a>
            </div>

            <div class="col-12 col-md-3">
                <a href="purchase-product-summary" class="btn btn-outline-warning w-100">New Purchase Product</a>
            </div>

            <div class="col-12 col-md-3">
                <a href="productissuedelay" class="btn btn-outline-primary w-100">Pack Material Delay Issue Report</a>
            </div>

        </div>
    </div>

    <!-- drop down -->
    <div class="card shadow-lg rounded-4 border-0 p-4">
        <form action="{{ url('movementreport') }}" method="get" id="searchForm">
            <div class="row g-4">

                <!-- product_name -->
                <div class="col-md-4">
                    <label for="supplier_name" class="form-label fw-semibold">Product Name</label>
                    <select name="product_name" id="product_name" class="form-select product_name select2" required>
                        {!! $pro_name !!}
                    </select>
                </div>

                <!-- From Date -->
                <div class="col-md-4">
                    <label for="start_date" class="form-label fw-semibold">From Date</label>
                    <input type="text" class="form-control start_date1" id="start_date" name="start_date" required
                        autocomplete="off" placeholder="YYYY-MM-DD">
                </div>

                <!-- To Date -->
                <div class="col-md-4">
                    <label for="end_date" class="form-label fw-semibold">To Date</label>
                    <input type="text" class="form-control end_date1" id="end_date" name="end_date" required
                        autocomplete="off" placeholder="YYYY-MM-DD">
                </div>

                <!-- Search Button -->
                <div class="col-12 text-center mt-3">
                    <button type="submit" class="btn btn-primary px-5">Search</button>
                </div>

            </div>
        </form>
    </div>

    <!-- end -->

    <!-- category based -->
    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table1" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <tr>
                        <th class="text-white text-center bg-danger">Date</th>
                        <th class="text-white text-center bg-danger">Product</th>
                        <th class="text-white text-center bg-danger">Supplier</th>
                        <th class="text-white text-center bg-danger">Purchase Qty</th>
                        <th class="text-white text-center bg-danger">Unit Price</th>
                        <th class="text-white text-center bg-danger">Issue Qty</th>
                        <th class="text-white text-center bg-danger">Consumption Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_purchase = 0;
                        $total_issue = 0;
                        $total_cons = 0;
                    @endphp

                    @forelse($product_summary as $row)
                        @php
                            $total_purchase += $row->purchase_qty;
                            $total_issue += $row->issue_qty;
                            $total_cons += $row->cons_qty;
                        @endphp
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($row->trx_date)->format('d-m-Y') }}</td>
                            <td>{{ $row->concatenated_product }}</td>
                            <td>{{ $row->supplier_name ?? '-' }}</td>
                            <td>{{ number_format($row->purchase_qty, 2) }}</td>
                            <td>
                                {{ $row->unit_price ? number_format($row->unit_price, 2) : '-' }}
                            </td>
                            <td>{{ number_format($row->issue_qty, 2) }}</td>
                            <td>{{ number_format($row->cons_qty, 2) }}</td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>

                @if(count($product_summary) > 0)
                    <tfoot class="table-secondary fw-bold">
                        <tr>
                            <td colspan="3">TOTAL</td>
                            <td>{{ number_format($total_purchase, 2) }}</td>
                            <td></td>
                            <td>{{ number_format($total_issue, 2) }}</td>
                            <td>{{ number_format($total_cons, 2) }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

@endsection
@push('scripts')


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
                    { extend: 'excelHtml5', title: menuText, exportOptions: { columns: ':visible' } }
                ]
            });

        });

        $(document).ready(function () {
            var product_name = "{{ request('product_name') }}";
            var startDate = "{{ request('start_date') }}";
            var endDate = "{{ request('end_date') }}";

            $('.product_name').select2();
            $('#product_name').val(product_name).trigger('change');


            $('#start_date').val(startDate);
            $('#end_date').val(endDate);

        });

    </script>


    <!-- end -->


@endpush