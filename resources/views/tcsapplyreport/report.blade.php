@extends('layouts.header')
@section('content')
    <h3 class="text-danger">TCS Applicable Report</h3>
    @include('layouts.breadcrumb')

    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body p-4">
            <form action="{{ url('tcsapplyreport') }}" method="get" id="searchForm">
                @csrf
                <div class="row g-4 mb-3">
                    <div class="col-md-2"></div>
                    <div class="col-md-4">
                        <label for="start_date" class="form-label fw-semibold">From Date</label>
                        <input type="text" class="form-control start_date1" id="start_date" name="start_date" required
                            autocomplete="off">
                        <div class="invalid-feedback">Please select a start date.</div>
                    </div>

                    <div class="col-md-4">
                        <label for="end_date" class="form-label fw-semibold">To Date</label>
                        <input type="text" class="form-control end_date1" id="end_date" name="end_date" required
                            autocomplete="off">
                        <div class="invalid-feedback">Please select an end date.</div>
                    </div>
                </div>

                <!-- Second Row: Centered Search Button -->
                <div class="row">
                    <div class="col-md-12 text-center mt-2">
                        <button type="submit" class="btn btn-primary px-4 report_search" id="report_search">
                            <i class="bi bi-search-heart me-1"></i> Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="row text-center g-3 bg-light">

        <div class="col-md-4">
            <div class="p-2">
                <h6 class="text-muted mb-1">From</h6>
                <h5 class="fw-bold text-primary">{{ $s_date }}</h5>
            </div>
        </div>

        <div class="col-md-4">
            <div class="p-2">
                <h6 class="text-muted mb-1">To</h6>
                <h5 class="fw-bold text-success">{{ $e_date }}</h5>
            </div>
        </div>

        <div class="col-md-4">
            <div class="p-2">
                <h6 class="text-muted mb-1">Threshold Limit</h6>
                <h5 class="fw-bold text-danger">₹ 5,000,000</h5>
            </div>
        </div>

    </div>


    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table1" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <tr>
                        <th class="text-center text-white bg-danger">Customer Name</th>
                        <th class="text-center text-white bg-danger">Zone</th>
                        <th class="text-center text-white bg-danger">Invoice Grand Total</th>
                        <th class="text-center text-white bg-danger">TCS Applicable</th>
                </thead>
                <tbody>
                    <?php foreach ($tcs_apply as $value) { ?>
                    <tr>
                        <td>{{$value->customer_name}}</td>
                        <td>{{$value->zone}}</td>
                        <td>{{ number_format($value->total, 2) }}</td>
                        <td>{{ number_format($value->tcs_apply_amt, 2) }}</td>

                    </tr>
                    <?php } ?>
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

            var startDate = "{{ request('start_date') }}";
            var endDate = "{{ request('end_date') }}";

            $('#start_date').val(startDate);
            $('#end_date').val(endDate);

        });

    </script>

@endpush