@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Distributor Wise Sale Value</h3>

    <!-- tabs header -->
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="text-muted fw-bold">Last Updated At: <span
                    class="text-success fw-bold">{{ $last_update[0]->created_at }}</span></h6>
            <h6 class="text-muted">Data Upto: <span class="text-success fw-bold">{{ $last_data }}</span></h6>
            <a href="{{ url($pageModule) }}" class="btn btn-outline-success fw-bold">Tabs</a>
        </div>
    </div>

    <div class="card shadow-lg rounded-4 border-0 p-4 mb-4">
        <form action="{{ url('primarycostratiodistsaletrend') }}" method="get" id="searchForm">
            <div class="row g-3 align-items-end">

                <!-- Zone -->
                <div class="col-md-3">
                    <label for="zone" class="form-label">Zone</label>
                    <select name="zone" id="zone" class="form-select select2 zone text-center">
                        <option value="">-- please select --</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->zone }}">{{ $zone->zone }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Region -->
                <div class="col-md-3">
                    <label for="region" class="form-label">Region</label>
                    <select name="region" id="region" class="form-select select2 region text-center">
                        <option value="">-- please select --</option>
                        @foreach($regions as $region)
                            <option value="{{ $region->region }}">{{ $region->region }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- state -->
                <div class="col-md-3">
                    <label for="state" class="form-label">State</label>
                    <select name="state" id="state" class="form-select state select2 text-center">
                        <option value="">-- please select --</option>
                        @foreach($states as $state)
                            <option value="{{ $state->state }}">{{ $state->state }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Month -->
                <div class="col-md-3">
                    <label for="date_select" class="form-label">Month</label>
                    <input type="month" name="date_select" id="date_select" class="form-control date_select"
                        autocomplete="off">
                </div>

                <!-- Search Button -->
                <div class="col-12 text-center mt-3">
                    <button type="submit" class="btn btn-primary px-5"><i class="bi bi-search"></i> Search</button>
                </div>
            </div>
        </form>
    </div>

    <!-- convert money inr to k and l purpose  -->
    <div class="text-center mb-4">
        <button class="btn btn-primary fw-bold me-2" id="btnThousand">Show in Thousands</button>
        <button class="btn btn-success fw-bold me-2" id="btnLakhs">Show in Lakhs</button>
        <button class="btn btn-danger fw-bold" id="btnReset">Reset</button>
    </div>
    <!-- end -->
    <!--tables-->
    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table1" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <tr>
                        <?php if (request('zone') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('zone') }}</th>
                        <?php elseif (request('region') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('region') }}</th>
                        <?php elseif (request('state') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('state') }}</th>
                        <?php endif; ?>

                        <th colspan="13" class="align text-white bg-danger text-center">ALL INDIA - DISTRIBUTOR WISE SALE
                            VALUE - upto {{ $mon_yr}} </th>
                    </tr>

                    <tr>
                        <th class="align text-white bg-secondary text-center">Region</th>
                        <th class="align text-white bg-secondary text-center">State Consolidated</th>
                        <th class="align text-white bg-secondary text-center">Marketing Person Name</th>

                        <?php $displayedMonths = []; ?>
                        <?php foreach ($sales_trend as $value) {
        $monthYear = $value->month;
        if (!in_array($monthYear, $displayedMonths)) {
            $displayedMonths[] = $monthYear; ?>
                        <th colspan="1" class="align text-white bg-secondary text-center"><?php        echo $monthYear; ?></th>
                        <?php    }
    } ?>
                    </tr>
                </thead>

                <tbody>
                    <?php
    $grandTotal = [];
    $totalStock = 0;
    $monthlySales = [];

    foreach ($sales_trend as $value) {
        $totalStock += $value->sale;
        // Store sales data for each month
        $monthlySales[$value->f_year][$value->region][$value->state][$value->name][$value->month] = $value->sale;
    }

    // Loop through unique F_Year, Region, State, Name combinations
    foreach ($monthlySales as $fYear => $regions) {
        foreach ($regions as $region => $states) {
            foreach ($states as $state => $names) {
                foreach ($names as $name => $monthData) {
                    echo '<tr>';
                    echo '<td >' . $region . '</td>';
                    echo '<td >' . $state . '</td>';
                    echo '<td   class="sticky-col" >' . $name . '</td>';

                    foreach ($displayedMonths as $month) {
                        $saleValue = isset($monthData[$month]) ? $monthData[$month] : 0;
                        echo '<td  class="rupee-value" data-original="' . $saleValue . '" >' . $saleValue . '</td>';

                        // Update the grand total array
                        $grandTotal[$month] = isset($grandTotal[$month]) ? $grandTotal[$month] + $saleValue : $saleValue;
                    }

                    echo '</tr>';
                }
            }
        }
    }
    ?>
                </tbody>
                <tfoot>
                    <!-- Display the grand total row -->
                    <tr class="sticky-foot fw-bold table-danger">
                        <td>Total</td>
                        <td></td>
                        <td></td>
                        <?php foreach ($displayedMonths as $month): ?>
                        <td class="rupee-value" data-original="<?php    echo $grandTotal[$month]; ?>">
                            <?php    echo $grandTotal[$month]; ?></td>
                        <?php endforeach; ?>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

@endsection
@push('scripts')

    <script>

        $(document).ready(function () {

            var startDate = "{{ request('date_select') }}";
            $('#date_select').val(startDate);


        });

        $(document).ready(function () {
            $('#Table1').DataTable({
                scrollX: true,
                scrollY: "50vh",
                buttons: [
                    {
                        extend: 'excelHtml5',
                        filename: 'cost_to_sale_ DISTRIBUTOR_WISE_SALE_VALUE ',
                    },
                    {
                        extend: 'pdfHtml5',
                        filename: 'cost_to_sale_ DISTRIBUTOR_WISE_SALE_VALUE ',
                    }
                ]
            });
        });

        // search alert
        document.getElementById('searchForm').addEventListener('submit', function (event) {
            var zone = document.getElementById('zone').value;
            var region = document.getElementById('region').value;
            var startDate = document.getElementById('date_select').value;
            var State = document.getElementById('state').value;

            if (zone === '' && region === '' && startDate === '' && State === '') {
                alert('Please select Zone, Region, or Month Before Searching.');
                event.preventDefault();
            }
        });

        // calendar freeze
        document.addEventListener('DOMContentLoaded', function () {
            var today = new Date();
            var currentYear = {{ $last_yr }};
            var currentMonth = '{{ sprintf('%02d', $last_mon) }}';

            var startMonthYear = '2022-04';
            var endMonthYear = currentYear + '-' + currentMonth;

            document.getElementById('date_select').setAttribute('min', startMonthYear);
            document.getElementById('date_select').setAttribute('max', endMonthYear);
        });

        $(document).ready(function () {
            // Function to format numbers as per the selected option
            function formatNumber(number, format) {
                if (format === 'k') {
                    return (number / 1000).toFixed(1) + 'K';
                } else if (format === 'l') {
                    return (number / 100000).toFixed(1) + 'L';
                } else {
                    return number;
                }
            }

            // Event handler for the "K" button
            $('#btnThousand').on('click', function () {
                $('.rupee-value').each(function () {
                    var originalValue = parseFloat($(this).data('original'));
                    $(this).text(formatNumber(originalValue, 'k'));
                });
            });

            // Event handler for the "L" button
            $('#btnLakhs').on('click', function () {
                $('.rupee-value').each(function () {
                    var originalValue = parseFloat($(this).data('original'));
                    $(this).text(formatNumber(originalValue, 'l'));
                });
            });

            // Event handler for the "Reset" button
            $('#btnReset').on('click', function () {
                location.reload();
            });
        });




        $(document).on('change', '.zone', function () {

            var zone = $(this).val();
            var $region = $(".region");

            if (zone !== "") {

                var condition = encodeURIComponent("zone='" + zone + "'");

                var url = "{{ URL::to('jcombosecondsales') }}" +
                    "?table=sd_primarydataupload_t:region:region" +
                    "&parent=" + condition +
                    "&order_by=region asc";

                $.ajax({
                    url: url,
                    type: "GET",
                    success: function (response) {

                        let data = response;

                        // Convert string → JSON (if needed)
                        if (typeof response === "string") {
                            try {
                                data = JSON.parse(response);
                            } catch (e) {
                                console.error("Invalid JSON:", response);
                                return;
                            }
                        }

                        // Clear region dropdown
                        $region.empty().append('<option value="">-- Select Region --</option>');

                        // Populate region list
                        $.each(data, function (i, item) {
                            $region.append(
                                `<option value="${item.val}">${item.option_name}</option>`
                            );
                        });

                        // Reinitialize select2 (if used)
                        $region.trigger('change.select2');
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", error);
                    }
                });

            } else {
                $region.empty().append('<option value="">-- Select Region --</option>');
            }
        });

        $(document).on('change', '.region', function () {

            var region = $(this).val();
            var $state = $(".state");

            if (region !== "") {

                var condition = encodeURIComponent("region='" + region + "'");

                var url = "{{ URL::to('jcombosecondsales') }}" +
                    "?table=sd_primarydataupload_t:state:state" +
                    "&parent=" + condition +
                    "&order_by=state asc";

                $.ajax({
                    url: url,
                    type: "GET",
                    success: function (response) {

                        let data = response;

                        if (typeof response === "string") {
                            try {
                                data = JSON.parse(response);
                            } catch (e) {
                                console.error("Invalid JSON:", response);
                                return;
                            }
                        }

                        // Clear previous options
                        $state.empty().append('<option value="">-- Select State --</option>');

                        // Populate results
                        $.each(data, function (i, item) {
                            $state.append(
                                `<option value="${item.val}">${item.option_name}</option>`
                            );
                        });

                        // Reinitialize Select2 if required
                        $state.trigger('change.select2');
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", error);
                    }
                });

            } else {
                // Reset dropdown when region is empty
                $state.empty().append('<option value="">-- Select State --</option>');
            }
        });

    </script>
    <!-- end  -->

@endpush