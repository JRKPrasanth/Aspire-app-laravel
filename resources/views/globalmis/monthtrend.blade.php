@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Month Wise Sale Trend</h3>

    <!-- tabs header -->
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="text-muted fw-bold">Last Updated At: <span
                    class="text-primary fw-bold">{{ $last_update[0]->created_at }}</span></h6>
            <h6 class="text-muted">Data Upto: <span class="text-primary fw-bold">{{ $last_data }}</span></h6>
            <a href="{{ url($pageModule) }}" class="btn btn-outline-primary fw-bold">Tabs</a>
        </div>
    </div>

    <div class="card shadow-lg rounded-4 border-0 p-4 mb-4">
        <form action="{{ url('globalmisreportsaletrend') }}" method="get" id="searchForm">
            <div class="row g-3">

                <!-- Zone -->
                <div class="col-md-3">
                    <label for="zone" class="form-label">Zone</label>
                    <select name="zone" id="zone" class="form-select zone select2 text-center">
                        <option value="">-- please select --</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->zone }}">{{ $zone->zone }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Region -->
                <div class="col-md-3">
                    <label for="region" class="form-label">Region</label>
                    <select name="region" id="region" class="form-select region select2 text-center">
                        <option value="">-- please select --</option>
                        @foreach($regions as $region)
                            <option value="{{ $region->region }}">{{ $region->region }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Month -->
                <div class="col-md-3">
                    <label for="date_select" class="form-label">Month</label>
                    <input type="month" name="date_select" id="date_select" class="form-control" autocomplete="off">
                </div>

                <!-- Submit Button -->
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100" id="searchButton"> <i class="bi bi-search"></i>
                        Search</button>
                </div>

            </div>
        </form>
    </div>

    <!-- Value Format Buttons -->
    <div class="text-center mb-4">
        <button class="btn btn-primary fw-bold me-2" id="btnThousand">Show in Thousands</button>
        <button class="btn btn-success fw-bold me-2" id="btnLakhs">Show in Lakhs</button>
        <button class="btn btn-danger fw-bold" id="btnReset">Reset</button>
    </div>

    <!-- END -->

    <!---  purchase stock value -->
    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table1" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <tr>
                        <?php if (request('zone') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('zone') }}</th>
                        <?php endif; ?>
                        <?php if (request('region') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('region') }}</th>
                        <?php endif; ?>
                        <?php if (request('division') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('division') }}</th>
                        <?php endif; ?>
                        <th colspan="13" class="align text-white bg-danger text-center">MONTH WISE SALE TREND</th>

                    </tr>

                    <tr>

                        <th class="align text-white bg-secondary text-center">Zone</th>
                        <th class="align text-white bg-secondary text-center">Region</th>

                        <?php $displayedMonths = []; ?>
                        <?php foreach ($month_sample as $value) {
        $monthYear = $value->month_y;
        if (!in_array($monthYear, $displayedMonths)) {
            $displayedMonths[] = $monthYear; ?>
                        <th class="align text-white bg-secondary text-center"><?php        echo $monthYear; ?></th>
                        <?php    }
    } ?>
                        <th class="align text-white bg-secondary text-center">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
    $totalStock = 0;
    $monthlySales = []; // Array to store sales data for each month
    $grandTotal = 0;
    foreach ($month_sample as $value) {
        $totalStock += $value->sale;

        // Store sales data for each month
        $monthlySales[$value->f_year][$value->zone][$value->region][$value->month_y] = $value->sale;
    }

    // Loop through unique F_Year, HQ Name, Zone, Region, State combinations
    foreach ($monthlySales as $fYear => $divisions) {
        foreach ($divisions as $division => $sfg_product_names) {
            foreach ($sfg_product_names as $sfg_product_name => $monthData) {

                echo '<tr>';
                echo '<td >' . $division . '</td>';
                echo '<td class="sticky-col"  >' . $sfg_product_name . '</td>';
                $rowTotal = 0; // Initialize row total
                foreach ($displayedMonths as $month) {
                    $saleValue = isset($monthData["$month"]) ? $monthData["$month"] : 0;
                    echo '<td class="rupee-value" data-original="' . $saleValue . '"  >' . $saleValue . '</td>';
                    $rowTotal += $saleValue;
                }
                echo '<td class="rupee-value" data-original="' . $rowTotal . '" >' . $rowTotal . '</td>';
                echo '</tr>';

                $grandTotal += $rowTotal;
            }
        }

    }
        ?>
                </tbody>
                <tfoot class="table-danger">
                    <?php
    // Add Grand Total Row
    echo "<tr class='fw-bold'>";
    echo "<td  class='sticky-col1'>Total</td>";
    echo "<td  class='sticky-col1'></td>";
    foreach ($displayedMonths as $month) {
        $totalSamples = 0;

        foreach ($monthlySales as $fYear => $divisions) {
            foreach ($divisions as $division => $sfg_product_names) {
                foreach ($sfg_product_names as $sfg_product_name => $monthData) {
                    $totalSamples += isset($monthData["$month"]) ? $monthData["$month"] : 0;
                }
            }
        }

        echo "<td >{$totalSamples}</td>";
    }
    echo "<td >{$grandTotal}</td>";
    echo "</tr>";
        ?>
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
                        filename: 'MONTH WISE SALE TREND',
                        exportOptions: {
                            format: {
                                header: function (data, columnIdx) {
                                    var header1 = $('#Table1 thead tr:eq(0) th').eq(columnIdx).text();
                                    var header3 = $('#Table1 thead tr:eq(1) th').eq(columnIdx).text();

                                    return header1 + '\n' + header3;
                                }
                            }
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        filename: 'MONTH WISE SALE TREND',
                        exportOptions: {
                            format: {
                                header: function (data, columnIdx) {
                                    // Concatenate headers with line breaks
                                    var header1 = $('#Table1 thead tr:eq(0) th').eq(columnIdx).text();
                                    var header3 = $('#Table1 thead tr:eq(1) th').eq(columnIdx).text();

                                    return header1 + '\n' + header3;
                                }
                            }
                        }
                    }
                ]
            });
        });

        // search alert
        document.getElementById('searchForm').addEventListener('submit', function (event) {
            var zone = document.getElementById('zone').value;
            var region = document.getElementById('region').value;
            var startDate = document.getElementById('date_select').value;

            if (zone === '' && region === '' && startDate === '') {
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
    </script>
    <!-- end  -->
@endpush