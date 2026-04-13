@extends('layouts.header')
@section('content')

    <h3 class="text-danger"> Trial Balance Report </h3>
    @include('layouts.breadcrumb')

    <div class="card shadow-lg border-0 rounded-4 mb-4">
        <div class="card-header bg-success bg-gradient text-white fw-semibold">
            <i class="bi bi-funnel me-2"></i>Filter Options
        </div>
        <div class="card-body">
            <form method="get" action="{{ url('trialbalancenew') }}" id="job_card_reprot" class="needs-validation"
                novalidate enctype="multipart/form-data">
                @csrf

                <div class="row g-4">
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

                <div class="d-flex justify-content-center mt-4">
                    <button type="submit" class="btn bg-success bg-gradient text-white px-4 report_search"
                        id="report_search">
                        <i class="bi bi-search-heart me-1"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </div>


    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table1" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <tr>
                        <th class="text-white bg-danger text-center">Account</th>
                        <th class="text-white bg-danger text-center">Code</th>
                        <th class="text-white bg-danger text-center">FS</th>
                        <th class="text-white bg-danger text-center">Opening Balance</th>
                        <th class="text-white bg-danger text-center">Debit Amount</th>
                        <th class="text-white bg-danger text-center">Credit Amount</th>
                        <th class="text-white bg-danger text-center">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
    $opening_total = 0;
    $debit_total = 0;
    $credit_total = 0;
    $balance_total = 0;
                ?>
                    <?php foreach ($trial_balance as $value) {
        $opening_total += $value->opening_balance;
        $debit_total += $value->debit;
        $credit_total += $value->credit;
        $balance_total += $value->balance;
                ?>
                    <tr>
                        <td class="sticky-col viewBalance"
                            style="text-align:center;text-decoration: underline;cursor: pointer;color: #ff0000;">
                            {{$value->concatenated_segments}}
                        </td>
                        <td>{{$value->account_id}}</td>
                        <td>{{$value->FS}}</td>
                        <td>{{ number_format($value->opening_balance, 2) }}</td>
                        <td>{{ number_format($value->debit, 2) }}</td>
                        <td>{{ number_format($value->credit, 2) }}</td>
                        <td>{{ number_format($value->balance, 2) }}</td>
                    </tr>
                    <?php } ?>
                </tbody>

                <tfoot class="table-danger fw-bold">
                    <tr>
                        <td colspan='3'>Grand Total</td>
                        <td>{{ number_format($opening_total, 2) }}</td>
                        <td>{{ number_format($debit_total, 2) }}</td>
                        <td>{{ number_format($credit_total, 2) }}</td>
                        <td>{{ number_format($balance_total, 2) }}</td>
                    </tr>
                </tfoot>

            </table>
        </div>
    </div>

    <!-- popup-->
    <!-- Modal -->
    <div class="modal fade" id="trialModal" tabindex="-1" aria-labelledby="trialModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content rounded-4 shadow">

                <!-- Modal Header -->
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="trialModalLabel">Trial Balance Details</h5>
                    <button id="closeButton" type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <h5>Account Name: <strong><span id="accountName"></span></strong></h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="border rounded p-3" style="height: 490px; overflow-y: auto;">
                                <div id="Deatilstable"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>




@endsection
@push('scripts')

    <script>

        $(document).ready(function () {
            $('#Table1').DataTable({
            });
        });

        // modal for 
        $(document).ready(function () {

            $('.viewBalance').click(function () {
                // Show the modal
                $('#trialModal').modal('show');

                // Get account name from the clicked cell
                var accountName = $(this).text().trim();
                var startDate = "{{ request('start_date') }}";
                var endDate = "{{ request('end_date') }}";

                // Update the account name in the modal
                $('#accountName').text(accountName);

                // AJAX request to fetch data
                $.get("{{ route('trialbalpopup') }}?accountName=" + encodeURIComponent(accountName)
                    + "&start_date1=" + startDate
                    + "&end_date1=" + endDate, function (data) {
                        $('#Deatilstable').html(data);
                    });
            });

            // Close modal button
            document.getElementById("closeButton").addEventListener("click", function () {
                $('#trialModal').modal('hide');
            });

            // Clear modal content when hidden
            $('#trialModal').on('hidden.bs.modal', function () {
                $('#Deatilstable').html('');
                $('#accountName').text('');
            });

        });

        $(document).ready(function () {
            var startDate = "{{ request('start_date') }}";
            var endDate = "{{ request('end_date') }}";

            $('.start_date1').val(startDate);
            $('.end_date1').val(endDate);

        });

    </script>
@endpush