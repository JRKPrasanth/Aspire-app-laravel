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
                <a href="rejectedpuritems" class="btn btn-outline-danger w-100">Rejected Products List</a>
            </div>
            <div class="col-12 col-md-3">
                <a href="consumptionquantityreport" class="btn btn-outline-warning w-100">Consumption Quantity Report</a>
            </div>

            <div class="col-12 col-md-3">
                <a href="movementreport" class="btn btn-outline-secondary w-100">Movement Report</a>
            </div>

            <div class="col-12 col-md-3">
                <a href="purchase-supplier-summary" class="btn btn-success w-100">New Supplier Report</a>
            </div>

            <div class="col-12 col-md-3">
                <a href="purchase-product-summary" class="btn btn-outline-secondary w-100">New Purchase Product</a>
            </div>

            <div class="col-12 col-md-3">
                <a href="productissuedelay" class="btn btn-outline-primary w-100">Pack Material Delay Issue Report</a>
            </div>

        </div>
    </div>

    <div class="container mt-3">
        <div class="card card-body shadow-lg rounded-4 border-0">
            <form method="GET" action="{{ route('suppliersummary') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-3">
                        <label>Start Date</label>
                        <input type="text" name="start_date" class="form-control start_date1"
                            value="{{ $start_date ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <label>End Date</label>
                        <input type="text" name="end_date" class="form-control end_date1" value="{{ $end_date ?? '' }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search-heart me-1"></i>
                            Search</button>
                    </div>
                </div>
            </form>
        </div>

        @if(isset($suppliersummary))
            <div class="card shadow-lg border-0 rounded-4 p-4 mb-3">

                <div class="d-flex flex-wrap align-items-center gap-4">

                    <h5 class="fw-bold text-primary mb-0 d-flex align-items-center">
                        <i class="bi bi-truck me-2"></i> New Supplier Report
                    </h5>

                    <div class="d-flex align-items-center">
                        <span class="fw-semibold text-dark">Total Active Suppliers:</span>
                        <span class="badge bg-success ms-2 px-3 py-2">
                            {{ $suppliersummary->total_suppliers }}
                        </span>
                    </div>

                    <div class="d-flex align-items-center">
                        <span class="fw-semibold text-dark">
                            New Suppliers ({{ $start_date }} → {{ $end_date }}):
                        </span>
                        <span class="badge bg-info text-dark ms-2 px-3 py-2">
                            {{ $suppliersummary->new_suppliers_count }}
                        </span>
                    </div>

                    @if(empty($suppliersummary->new_supplier_names))
                        <div class="alert alert-secondary mb-0 py-1 px-3 d-flex align-items-center rounded-3">
                            <i class="bi bi-exclamation-circle me-2"></i>
                        </div>
                    @endif

                    @if(empty($suppliersummary->created_user))
                        <div class="alert alert-secondary mb-0 py-1 px-3 d-flex align-items-center rounded-3">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            <strong>No new suppliers during this period.</strong>
                        </div>
                    @endif

                </div>

            </div>



            <div class="card shadow-lg rounded-4 border-0 p-4">
                <table id="Table1" class="table table-bordered table-striped table-hover w-100">
                    <thead>
                        <tr>
                            <th class="align text-white bg-danger text-center">Sl.No</th>
                            <th class="align text-white bg-danger text-center">Supplier Name</th>
                            <th class="align text-white bg-danger text-center">Created Date</th>
                            <th class="align text-white bg-danger text-center">Created User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @forelse($newsupplier as $item)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $item->supplier_name }}</td>
                                <td>{{ $item->created_date }}</td>
                                <td>{{ $item->first_name }}</td>
                            </tr>
                        @empty
                        <tr>
                            <td class="text-center text-muted" colspan="4">No suppliers found for selected period.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
        @endif
        </div>
    </div>

@endsection
@push('scripts')

    <script>
        $(document).ready(function () {
            $('#Table1').DataTable({

            });
        });
    </script>

@endpush