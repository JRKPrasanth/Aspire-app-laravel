@extends('layouts.header')
@section('content')
    <h3 class="text-danger">TDS Report</h3>
    @include('layouts.breadcrumb')

    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="card-body">
            <div class="col-md-12">
                <form action="{{ url('tdstcsoutput') }}" method="get" id="searchForm">
                    <div class="row g-3 align-items-center">

                        <!-- Ledger Name -->
                        <div class="col-lg-4 col-md-6">
                            <label for="account_id" class="form-label fw-semibold">Ledger Name</label>
                            <select name="account_id" id="account_id" class="form-select select2" required>
                                {!! $ledger !!}
                            </select>
                        </div>

                        <!-- Start Date -->
                        <div class="col-lg-3 col-md-6">
                            <label for="start_date" class="form-label fw-semibold">Start Date</label>
                            <div class="input-group">
                                <input type="text" class="form-control start_date1" id="start_date" name="start_date"
                                    placeholder="YYYY-MM-DD" autocomplete="off" required>
                            </div>
                        </div>

                        <!-- End Date -->
                        <div class="col-lg-3 col-md-6">
                            <label for="end_date" class="form-label fw-semibold">End Date</label>
                            <div class="input-group">
                                <input type="text" class="form-control end_date1" id="end_date" name="end_date"
                                    placeholder="YYYY-MM-DD" autocomplete="off" required>
                            </div>
                        </div>

                        <!-- Search Button -->
                        <div class="col-lg-2 col-md-6 d-flex align-items-end mt-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search me-1"></i> Search
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table1" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <tr>
                        <th colspan="12" class="align text-white bg-danger text-center">{{ $account }}</th>

                    </tr>
                    <tr>
                        <th class="align text-white bg-secondary text-center">S. No</th>
                        <th class="align text-white bg-secondary text-center">Period</th>
                        <th class="align text-white bg-secondary text-center">Month-Yr</th>
                        <th class="align text-white bg-secondary text-center">Name of the Deductee</th>
                        <th class="align text-white bg-secondary text-center">PAN</th>
                        <th class="align text-white bg-secondary text-center">Invoice Ref.No</th>
                        <th class="align text-white bg-secondary text-center">Invoice Date</th>
                        <th class="align text-white bg-secondary text-center">Date of Deduction</th>
                        <th class="align text-white bg-secondary text-center">Taxable Amount</th>
                        <th class="align text-white bg-secondary text-center">TDS Amount</th>
                        <th class="align text-white bg-secondary text-center">TDS %</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $key = 1;
    $total_taxable_amt = 0;
    $total_tds_amt = 0;
                ?>
                    <?php foreach ($tcs_report as $value) {
        $total_taxable_amt += $value->taxable_amt;
        $total_tds_amt += $value->tds_amt;
                ?>
                    <tr>
                        <td>{{ $key }}</td>
                        <td>{{ $value->period }}</td>
                        <td>{{ $value->yr_month }}</td>
                        <td>{{ $value->name }}</td>
                        <td>{{ $value->pan }}</td>
                        <td>{{ $value->ref_no }}</td>
                        <td>{{ $value->date }}</td>
                        <td>{{ $value->entry_date }}</td>
                        <td>{{ number_format($value->taxable_amt, 2) }}</td>
                        <td>{{ number_format($value->tds_amt, 2) }}</td>
                        <td>{{ $value->percentage }}</td>
                    </tr>
                    <?php  
                    $key++;
    } ?>

                </tbody>
                <tfoot>
                    <tr class="sticky-row fw-bold">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Grand Total</td>
                        <td>{{ number_format($total_taxable_amt, 2) }}</td>
                        <td>{{ number_format($total_tds_amt, 2) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>

        </div>
    </div>



@endsection
@push('scripts')

    <script>

        $(document).ready(function () {

            $('#Table1').DataTable({
                  scrollX: true,
                  scrollY: "50vh",
            });
        });


        $(document).ready(function () {
            var account = "{{ request('account_id') }}";
            var startDate = "{{ request('start_date') }}";
            var endDate = "{{ request('end_date') }}";

            $('.account_id').select2();
            $('#account_id').val(account).trigger('change');

            $('#start_date').val(startDate);
            $('#end_date').val(endDate);


        });

    </script>

@endpush