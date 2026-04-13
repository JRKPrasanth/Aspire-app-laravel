@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Sales And Purchase Trend</h3>

    <!-- tabs header -->
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="text-muted fw-bold">Last Updated At: <span
                    class="text-primary fw-bold">{{ $last_update[0]->created_at }}</span></h6>
            <h6 class="text-muted">Data Upto: <span class="text-primary fw-bold">{{ $last_data }}</span></h6>
            <a href="{{ url($pageModule) }}" class="btn btn-outline-primary fw-bold">Tabs</a>
        </div>
    </div>
    <!-- end -->

    <div class="card shadow-lg rounded-4 border-0 p-4 mb-4">
        <div class="container-fluid mb-4">
            <form action="{{ url('salesandtargetmtstrend') }}" method="get" id="searchForm">
                <div class="row g-3 align-items-end">

                    <!-- Zone -->
                    <div class="col-md-3">
                        <label class="form-label">Zone</label>
                        <select name="zone" id="zone" class="form-select zone select2 text-center">
                            <option value="">-- please select --</option>
                            @foreach($zones as $zone)
                                <option value="{{ $zone->zone }}">{{ $zone->zone }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Region -->
                    <div class="col-md-3">
                        <label class="form-label">Region</label>
                        <select name="region" id="region" class="form-select region select2 text-center">
                            <option value="">-- please select --</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->region }}">{{ $region->region }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- State -->
                    <div class="col-md-3">
                        <label class="form-label">State</label>
                        <select name="state" id="state" class="form-select state select2 text-center">
                            <option value="">-- please select --</option>
                            @foreach($states as $state)
                                <option value="{{ $state->state }}">{{ $state->state }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- HQ Name -->
                    <div class="col-md-3">
                        <label class="form-label">Hq Name</label>
                        <select name="hq_name" id="hq_name" class="form-select hq_name select2 text-center">
                            <option value="">-- please select --</option>
                            @foreach($hq_names as $hq_name)
                                <option value="{{ $hq_name->hq_name }}">{{ $hq_name->hq_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Distributor -->
                    <div class="col-md-3">
                        <label class="form-label">Distributor</label>
                        <select name="dist_name" id="dist_name" class="form-select dist_name select2 text-center">
                            <option value="">-- please select --</option>
                            @foreach($distributors as $distributor)
                                <option value="{{ $distributor->name }}">{{ $distributor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Stockist -->
                    <div class="col-md-3">
                        <label class="form-label">Stockist</label>
                        <select name="stock" id="stock" class="form-select select2 stock text-center">
                            <option value="">-- please select --</option>
                            @foreach($stockists as $stock)
                                <option value="{{ $stock->stockist_dist_name }}">{{ $stock->stockist_dist_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Month -->
                    <div class="col-md-3">
                        <label class="form-label">Month</label>
                        <input type="month" name="date_select" id="date_select" class="form-control date_select"
                            autocomplete="off">
                    </div>

                    <!-- Area -->
                    <div class="col-md-3">
                        <label class="form-label">Area</label>
                        <select name="area" id="area" class="form-select select2 area text-center">
                            <option value="">-- please select --</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->area }}">{{ $area->area }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Search Button -->
                    <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn btn-primary px-5"> <i class="bi bi-search"></i> Search</button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <!-- Status Legend -->
    <div class="card shadow-lg rounded-4 border-0 p-3 mb-4">
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <div class="px-3 py-2 text-white rounded" style="background:#c96fc6; width: 160px;">0% - 50% → <strong>VERY
                    POOR</strong></div>
            <div class="px-3 py-2 text-white rounded" style="background:#df9f29; width: 160px;">51% - 75% →
                <strong>POOR</strong></div>
            <div class="px-3 py-2 text-dark rounded" style="background:#e1e1e1; width: 160px;">75% - 85% → <strong>SUB
                    STANDARD</strong></div>
            <div class="px-3 py-2 text-white rounded" style="background:#0bb921; width: 160px;">85% - 100% →
                <strong>GOOD</strong></div>
        </div>
    </div>

    <!-- Value Format Buttons -->
    <div class="text-center mb-4">
        <button class="btn btn-primary fw-bold me-2" id="btnThousand">Show in Thousands</button>
        <button class="btn btn-success fw-bold me-2" id="btnLakhs">Show in Lakhs</button>
        <button class="btn btn-danger fw-bold" id="btnReset">Reset</button>
    </div>


    <?php
    function getBackgroundColor($value1, $value2)
    {

        if ($value2 != 0) {
            $percentage = ($value1 / $value2) * 100;

            if ($percentage >= 0 && $percentage <= 50) {
                return 'background: #c96fc6;';
            } elseif ($percentage > 50 && $percentage <= 75) {
                return 'background: #df9f29;';
            } elseif ($percentage > 75 && $percentage <= 85) {
                return 'background: #e1e1e1;';
            } elseif ($percentage > 85 && $percentage <= 100) {
                return 'background: #00f100;';
            } else {
                return 'background: #28b916;';
            }
        }
    }
    ?>
    <!---  purchase stock value -->
    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table1" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <tr>
                        <?php if (request('zone') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('zone') }}</th>
                        <?php endif; ?>
                        <?php if (request('state') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('state') }}</th>
                        <?php endif; ?>
                        <?php if (request('stock') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('stock') }}</th>
                        <?php endif; ?>
                        <?php if (request('region') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('region') }}</th>
                        <?php endif; ?>
                        <?php if (request('hq_name') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('hq_name') }}</th>
                        <?php endif; ?>
                        <?php if (request('dist_name') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('dist_name') }}</th>
                        <?php endif; ?>
                        <?php if (request('area') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('area') }}</th>
                        <?php endif; ?>
                        <th colspan="20" class="align text-white bg-danger text-center">TEAM WISE DISTRIBUTOR SALE TREND
                            UPTO - {{ $mon_yr }}</th>
                    </tr>

                    <tr>
                        <th class="align text-white bg-secondary text-center">Zone</th>
                        <th class="align text-white bg-secondary text-center">Region</th>
                        <th class="align text-white bg-secondary text-center">State</th>
                        <th class="align text-white bg-secondary text-center">HQ Name</th>
                        <th class="align text-white bg-secondary text-center">F_Year</th>

                        <?php $displayedMonths = []; ?>
                        <?php foreach ($all_ind_sal as $value) {
        $monthYear = $value->month;

        if (!in_array($monthYear, $displayedMonths)) {
            $displayedMonths[] = $monthYear; ?>
                        <th class="align text-white bg-secondary text-center"><?php        echo $monthYear; ?></th>
                        <?php    }
    } ?>
                        <th class="align text-white bg-secondary text-center">Grand Total</th>
                    </tr>
                </thead>

                <?php
    $monthlySales = []; // Array to store sales data for each month

    foreach ($all_ind_sal as $value) {
        // Store sales data for each month
        $monthlySales[$value->name][$value->f_year][$value->zone][$value->region][$value->state][$value->month] = $value->sale;
    }
        ?>

                <tbody>
                    <?php
    foreach ($monthlySales as $name => $financialYears) {
        foreach ($financialYears as $fYear => $zones) {
            foreach ($zones as $zone => $regions) {
                foreach ($regions as $region => $states) {
                    foreach ($states as $state => $monthData) {
                        echo '<tr>';
                        echo '<td >' . $zone . '</td>';
                        echo '<td >' . $region . '</td>';
                        echo '<td >' . $state . '</td>';
                        echo '<td class="sticky-col" >' . $name . '</td>';
                        echo '<td class="sticky-col-1" >' . $fYear . '</td>';

                        $rowTotal = 0; // Initialize row total

                        foreach ($displayedMonths as $month) {
                            $saleValue = isset($monthData["$month"]) ? $monthData["$month"] : 0;
                            echo '<td class="rupee-value" data-original="' . $saleValue . '" >' . $saleValue . '</td>';

                            // Update the row total
                            $rowTotal += $saleValue;
                        }

                        // Display the row total in the last column
                        echo '<td class="rupee-value" data-original="' . $rowTotal . '" >' . $rowTotal . '</td>';

                        echo '</tr>';
                    }
                }
            }
        }
    }
        ?>
                </tbody>
            </table>
        </div>
    </div>


    <!--- Sales stock value -->
    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table2" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <tr>
                        <?php if (request('zone') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('zone') }}</th>
                        <?php endif; ?>
                        <?php if (request('state') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('state') }}</th>
                        <?php endif; ?>
                        <?php if (request('stock') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('stock') }}</th>
                        <?php endif; ?>
                        <?php if (request('region') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('region') }}</th>
                        <?php endif; ?>
                        <?php if (request('hq_name') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('hq_name') }}</th>
                        <?php endif; ?>

                        <?php if (request('dist_name') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('dist_name') }}</th>
                        <?php endif; ?>
                        <?php if (request('area') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('area') }}</th>
                        <?php endif; ?>
                        <th colspan="20" class="align text-white bg-danger text-center">TEAM WISE STOCKIST PURCHASE TREND
                            UPTO - {{ $mon_yr }}</th>
                    </tr>

                    <tr>
                        <th class="align text-white bg-secondary text-center">Zone</th>
                        <th class="align text-white bg-secondary text-center">Region</th>
                        <th class="align text-white bg-secondary text-center">State</th>
                        <th class="align text-white bg-secondary text-center">HQ Name</th>
                        <th class="align text-white bg-secondary text-center">F_Year</th>
                        <?php $displayedMonths = []; ?>
                        <?php foreach ($sales_trend as $value) {
        $monthYear = $value->month;
        if (!in_array($monthYear, $displayedMonths)) {
            $displayedMonths[] = $monthYear; ?>
                        <th class="align text-white bg-secondary text-center"><?php        echo $monthYear; ?></th>
                        <?php    }
    } ?>

                        <th class="align text-white bg-secondary text-center">Grand Total</th>
                    </tr>
                </thead>

                <?php
    $monthlySales = []; // Array to store sales data for each month

    foreach ($sales_trend as $value) {
        // Store sales data for each month
        $monthlySales[$value->name][$value->f_year][$value->zone][$value->region][$value->state][$value->month] = $value->sale;
    }
        ?>

                <tbody>
                    <?php
    foreach ($monthlySales as $name => $financialYears) {
        foreach ($financialYears as $fYear => $zones) {
            foreach ($zones as $zone => $regions) {
                foreach ($regions as $region => $states) {
                    foreach ($states as $state => $monthData) {
                        echo '<tr>';
                        echo '<td >' . $zone . '</td>';
                        echo '<td >' . $region . '</td>';
                        echo '<td >' . $state . '</td>';
                        echo '<td class="sticky-col" >' . $name . '</td>';
                        echo '<td class="sticky-col-1" >' . $fYear . '</td>';

                        $rowTotal = 0; // Initialize row total

                        foreach ($displayedMonths as $month) {
                            $saleValue = isset($monthData["$month"]) ? $monthData["$month"] : 0;
                            echo '<td class="rupee-value" data-original="' . $saleValue . '"  >' . $saleValue . '</td>';

                            // Update the row total
                            $rowTotal += $saleValue;
                        }

                        // Display the row total in the last column
                        echo '<td class="rupee-value" data-original="' . $rowTotal . '" >' . $rowTotal . '</td>';

                        echo '</tr>';
                    }
                }
            }
        }
    }
        ?>
                </tbody>
            </table>
        </div>
    </div>

    <!--- distributor stock value -->
    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table3" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <tr>
                        <?php if (request('zone') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('zone') }}</th>
                        <?php endif; ?>
                        <?php if (request('state') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('state') }}</th>
                        <?php endif; ?>
                        <?php if (request('stock') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('stock') }}</th>
                        <?php endif; ?>
                        <?php if (request('region') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('region') }}</th>
                        <?php endif; ?>
                        <?php if (request('hq_name') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('hq_name') }}</th>
                        <?php endif; ?>

                        <?php if (request('dist_name') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('dist_name') }}</th>
                        <?php endif; ?>
                        <?php if (request('area') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('area') }}</th>
                        <?php endif; ?>
                        <th colspan="20" class="align text-white bg-danger text-center">STOCKIST WISE SALES TREND UPTO -
                            {{ $mon_yr }}</th>
                    </tr>

                    <tr>
                        <th class="align text-white bg-secondary text-center">Zone</th>
                        <th class="align text-white bg-secondary text-center">Region</th>
                        <th class="align text-white bg-secondary text-center">State</th>
                        <th class="align text-white bg-secondary text-center">Stockist Name</th>
                        <th class="align text-white bg-secondary text-center">F_Year</th>
                        <?php $displayedMonths = []; ?>
                        <?php foreach ($sales_trend_dis as $value) {
        $monthYear = $value->month;
        if (!in_array($monthYear, $displayedMonths)) {
            $displayedMonths[] = $monthYear; ?>
                        <th class="align text-white bg-secondary text-center"><?php        echo $monthYear; ?></th>
                        <?php    }
    } ?>
                        <th class="align text-white bg-secondary text-center">Grand Total</th>
                    </tr>
                </thead>

                <?php
    $monthlySales = []; // Array to store sales data for each month

    foreach ($sales_trend_dis as $value) {
        // Store sales data for each month
        $monthlySales[$value->name][$value->f_year][$value->zone][$value->region][$value->state][$value->month] = $value->sale;
    }
        ?>

                <tbody>
                    <?php
    foreach ($monthlySales as $name => $financialYears) {
        foreach ($financialYears as $fYear => $zones) {
            foreach ($zones as $zone => $regions) {
                foreach ($regions as $region => $states) {
                    foreach ($states as $state => $monthData) {
                        echo '<tr>';
                        echo '<td >' . $zone . '</td>';
                        echo '<td >' . $region . '</td>';
                        echo '<td >' . $state . '</td>';
                        echo '<td class="sticky-col" >' . $name . '</td>';
                        echo '<td class="sticky-col-1" >' . $fYear . '</td>';

                        $rowTotal = 0; // Initialize row total

                        foreach ($displayedMonths as $month) {
                            $saleValue = isset($monthData["$month"]) ? $monthData["$month"] : 0;
                            echo '<td class="rupee-value" data-original="' . $saleValue . '" >' . $saleValue . '</td>';

                            // Update the row total
                            $rowTotal += $saleValue;
                        }

                        // Display the row total in the last column
                        echo '<td class="rupee-value" data-original="' . $rowTotal . '" >' . $rowTotal . '</td>';

                        echo '</tr>';
                    }
                }
            }
        }
    }
        ?>
                </tbody>
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
                        filename: 'TEAM WISE STOCKIST SALE TREND',
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
                        filename: 'TEAM WISE STOCKIST SALE TREND',
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
            $('#Table2').DataTable({
                scrollX: true,
                scrollY: "50vh",
                buttons: [
                    {
                        extend: 'excelHtml5',
                        filename: 'TEAM WISE STOCKIST PURCHASE TREND',
                        exportOptions: {
                            format: {
                                header: function (data, columnIdx) {
                                    var header1 = $('#Table2 thead tr:eq(0) th').eq(columnIdx).text();
                                    var header3 = $('#Table2 thead tr:eq(1) th').eq(columnIdx).text();

                                    return header1 + '\n' + header3;
                                }
                            }
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        filename: 'TEAM WISE STOCKIST PURCHASE TREND',
                        exportOptions: {
                            format: {
                                header: function (data, columnIdx) {
                                    // Concatenate headers with line breaks
                                    var header1 = $('#Table2 thead tr:eq(0) th').eq(columnIdx).text();
                                    var header3 = $('#Table2 thead tr:eq(1) th').eq(columnIdx).text();

                                    return header1 + '\n' + header3;
                                }
                            }
                        }
                    }
                ]
            });
            $('#Table3').DataTable({
                scrollX: true,
                scrollY: "50vh",
                buttons: [
                    {
                        extend: 'excelHtml5',
                        filename: 'DISTRIBUTOR WISE SALES TREND',
                        exportOptions: {
                            format: {
                                header: function (data, columnIdx) {
                                    var header1 = $('#Table3 thead tr:eq(0) th').eq(columnIdx).text();
                                    var header3 = $('#Table3 thead tr:eq(1) th').eq(columnIdx).text();

                                    return header1 + '\n' + header3;
                                }
                            }
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        filename: 'DISTRIBUTOR WISE SALES TREND',
                        exportOptions: {
                            format: {
                                header: function (data, columnIdx) {
                                    // Concatenate headers with line breaks
                                    var header1 = $('#Table3 thead tr:eq(0) th').eq(columnIdx).text();
                                    var header3 = $('#Table3 thead tr:eq(1) th').eq(columnIdx).text();

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
            var Stock = document.getElementById('stock').value;
            var State = document.getElementById('state').value;
            var distributor = document.getElementById('dist_name').value;
            var hq_name = document.getElementById('hq_name').value;
            var area = document.getElementById('area').value;

            if (zone === '' && region === '' && startDate === '' && Stock === '' && State === '' && distributor === '' && hq_name === '' && area === '') {
                alert('Please select Zone, Region,area or Month Before Searching.');
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