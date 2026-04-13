@extends('layouts.header')
@section('content')

    <h2 class="text-danger">Purchase Dashboard</h2>

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
            <a href="purchase-supplier-summary" class="btn btn-outline-secondary w-100">New Supplier Report</a>
        </div>
		
		<div class="col-12 col-md-3">
            <a href="purchase-product-summary" class="btn btn-outline-primary w-100">New Purchase Product</a>
        </div>
		
		<div class="col-12 col-md-3">
            <a href="productissuedelay" class="btn btn-danger w-100">Pack Material Delay Issue Report</a>
        </div>
		
    </div>
</div>

<div class="container-fluid mt-3">

        <div class="card card-body shadow-lg rounded-4 border-0">
            <form method="GET" action="{{ route('productissuedelay') }}" class="mb-4">
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

<!-- end -->

            {{-- 🔹 Data Table --}}
            <div class="card shadow-lg rounded-4 border-0 p-4">
                <table id="Table1" class="table table-bordered table-striped table-hover w-100">
                    <thead>
                        <tr>
                            <th class="align text-white bg-success text-center">S.No</th>
                            <th class="align text-white bg-success text-center">Job No</th>
                            <th class="align text-white bg-success text-center">Job Date</th>
                            <th class="align text-white bg-success text-center">Product</th>
                            <th class="align text-white bg-success text-center">Issued Qty</th>
                            <th class="align text-white bg-success text-center">Issue Updated</th>
                            <th class="align text-white bg-success text-center">Status</th>
                            <th class="align text-white bg-success text-center">GRN Received Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @forelse($productissuedelay as $row)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $row->job_no }}</td>
                                <td>{{ $row->job_date }}</td>
                                <td>{{ $row->concatenated_product }}</td>
                                <td class="text-end">{{ number_format($row->mtl_issue_qty, 2) }}</td>
                                <td>{{ $row->updated_at }}</td>
                                <td>
                                    @if($row->sts == 'Issued in Delay')
                                        <span class="badge bg-danger">{{ $row->sts }}</span>
                                    @else
                                        <span class="badge bg-success">{{ $row->sts }}</span>
                                    @endif
                                </td>
                                <td>{{ $row->rec_dt ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">No records found for selected period</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>


@endsection
@push('scripts')


<script>
	
    $(document).ready(function () {
		
        $('#Table1').DataTable({

        });

    });

    $(document).ready(function () {
        var startDate = "{{ request('start_date') }}";
        var endDate = "{{ request('end_date') }}";

        $('#start_date').val(startDate);
        $('#end_date').val(endDate);

    });

</script>


<!-- end -->

@endpush