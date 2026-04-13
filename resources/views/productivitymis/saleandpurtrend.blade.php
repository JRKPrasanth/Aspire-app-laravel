@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Productivity MIS</h3>
    <div class="container mt-4">
        <!-- First row of buttons -->
        <div class="row g-3 mb-2">
            <div class="col-12 col-md-3">
                <a href="personwiseproductivity" class="btn btn-outline-danger w-100">Person Productivity</a>
            </div>
            <div class="col-12 col-md-3">
                <a href="productivitymis" class="btn btn-primary w-100">Business Trend</a>
            </div>
            <div class="col-12 col-md-3">
                <a href="productivityhqwise" class="btn btn-outline-success w-100">HQ Wise</a>
            </div>
        </div>
    </div>

    <div class="card shadow-lg rounded-4 border-0 p-4 mb-4">
        <form action="{{ url('productivitymis') }}" method="get" id="searchForm">
            <div class="row g-3">

                <!-- State -->
                <div class="col-md-3">
                    <label for="state" class="form-label">State</label>
                    <select name="state" id="state" class="form-select state select2 text-center">
                        <option value="">-- please select --</option>
                        @foreach($states as $state)
                            <option value="{{ $state->state }}">{{ $state->state }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Manager -->
                <div class="col-md-3">
                    <label for="manager" class="form-label">Manager</label>
                    <select name="manager" id="manager" class="form-select manager select2 text-center">
                        <option value="">-- please select --</option>
                        @foreach($managers as $manager)
                            <option value="{{ $manager->name }}">{{ $manager->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- HQ -->
                <div class="col-md-3">
                    <label for="region" class="form-label">HQ</label>
                    <select name="region" id="region" class="form-select region select2 text-center">
                        <option value="">-- please select --</option>
                        @foreach($regions as $region)
                            <option value="{{ $region->hq_name }}">{{ $region->hq_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Month -->
                <div class="col-md-3">
                    <label for="date_select" class="form-label">Month</label>
                    <input type="month" name="date_select" id="date_select" class="form-control" autocomplete="off">
                </div>

                <!-- Search Button -->
                <div class="col-12 text-center mt-3">
                    <button type="submit" class="btn btn-primary px-5"><i class="bi bi-search"></i> Search</button>
                </div>

            </div>
        </form>
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


    <!--- Sales stock value -->
    <!--tables-->
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
                        <?php if (request('state') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('state') }}</th>
                        <?php endif; ?>
                        <?php if (request('manager') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('manager') }}</th>
                        <?php endif; ?>
                        <th colspan="20" class="align text-white bg-danger text-center">TEAM WISE STOCKIST PURCHASE TREND
                            UPTO - {{ $mon_yr }}</th>
                    </tr>

                    <tr>
                        <th class="align text-white bg-secondary text-center">State</th>
                        <th class="align text-white bg-secondary text-center">manager</th>
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
        $monthlySales[$value->state][$value->manager][$value->name][$value->f_year][$value->month] = $value->sale;
    }
    ?>

                <tbody>
                    <?php
    foreach ($monthlySales as $state => $managers) {
        foreach ($managers as $manager => $hqNames) {
            foreach ($hqNames as $name => $financialYears) {
                foreach ($financialYears as $fYear => $monthData) {
                    echo '<tr>';
                    echo '<td class="sticky-col" >' . htmlspecialchars($state) . '</td>';
                    echo '<td class="sticky-col" >' . htmlspecialchars($manager) . '</td>';
                    echo '<td class="sticky-col" >' . htmlspecialchars($name) . '</td>';
                    echo '<td class="sticky-col-1" >' . htmlspecialchars($fYear) . '</td>';

                    $rowTotal = 0; // Initialize row total

                    foreach ($displayedMonths as $month) {
                        $saleValue = isset($monthData[$month]) ? $monthData[$month] : 0;
                        echo '<td class="rupee-value" data-original="' . htmlspecialchars($saleValue) . '" >' . htmlspecialchars($saleValue) . '</td>';

                        // Update the row total
                        $rowTotal += $saleValue;
                    }

                    // Display the row total in the last column
                    echo '<td class="rupee-value" data-original="' . htmlspecialchars($rowTotal) . '" >' . htmlspecialchars($rowTotal) . '</td>';

                    echo '</tr>';
                }
            }
        }
    }
        ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table2" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <tr>
                        <?php if (request('zone') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('zone') }}</th>
                        <?php endif; ?>
                        <?php if (request('region') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('region') }}</th>
                        <?php endif; ?>

                        <?php if (request('manager') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('manager') }}</th>
                        <?php endif; ?>
                        <th colspan="20" class="align text-white bg-danger text-center">TEAM WISE STOCKIST SALE TREND UPTO -
                            {{ $mon_yr }}</th>
                    </tr>

                    <tr>
                        <th class="align text-white bg-secondary text-center">State</th>
                        <th class="align text-white bg-secondary text-center">Manager</th>
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
        $monthlySales[$value->state][$value->manager][$value->name][$value->f_year][$value->month] = $value->sale;
    }
        ?>

                <tbody>
                    <?php
    foreach ($monthlySales as $state => $managers) {
        foreach ($managers as $manager => $hqNames) {
            foreach ($hqNames as $name => $financialYears) {
                foreach ($financialYears as $fYear => $monthData) {

                    echo '<td class="sticky-col" >' . htmlspecialchars($state) . '</td>';
                    echo '<td class="sticky-col" >' . htmlspecialchars($manager) . '</td>';
                    echo '<td class="sticky-col" >' . htmlspecialchars($name) . '</td>';
                    echo '<td class="sticky-col-1" >' . htmlspecialchars($fYear) . '</td>';

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
                        filename: 'DISTRIBUTOR WISE SALES TREND',
                        exportOptions: {
                            format: {
                                header: function (data, columnIdx) {
                                    var header1 = $('#Table1 thead tr:eq(0) th').eq(columnIdx).text();
                                    var header2 = $('#Table1 thead tr:eq(1) th').eq(columnIdx).text();
                                    var header3 = $('#Table1 thead tr:eq(2) th').eq(columnIdx).text();

                                    return header1 + '\n' + (header2 ? header2 + '\n' : '') + header3;
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
                                    var header1 = $('#Table1 thead tr:eq(0) th').eq(columnIdx).text();
                                    var header2 = $('#Table1 thead tr:eq(1) th').eq(columnIdx).text();
                                    var header3 = $('#Table1 thead tr:eq(2) th').eq(columnIdx).text();

                                    return header1 + '\n' + (header2 ? header2 + '\n' : '') + header3;
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
                        filename: 'STOCKIST WISE SALES TREND',
                        exportOptions: {
                            format: {
                                header: function (data, columnIdx) {
                                    var header1 = $('#Table2 thead tr:eq(0) th').eq(columnIdx).text();
                                    var header2 = $('#Table2 thead tr:eq(1) th').eq(columnIdx).text();
                                    var header3 = $('#Table2 thead tr:eq(2) th').eq(columnIdx).text();

                                    return header1 + '\n' + (header2 ? header2 + '\n' : '') + header3;
                                }
                            }
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        filename: 'STOCKIST WISE SALES TREND',
                        exportOptions: {
                            format: {
                                header: function (data, columnIdx) {
                                    // Concatenate headers with line breaks
                                    var header1 = $('#Table2 thead tr:eq(0) th').eq(columnIdx).text();
                                    var header2 = $('#Table2 thead tr:eq(1) th').eq(columnIdx).text();
                                    var header3 = $('#Table2 thead tr:eq(2) th').eq(columnIdx).text();

                                    return header1 + '\n' + (header2 ? header2 + '\n' : '') + header3;
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
            var Manager = document.getElementById('manager').value;

            if (zone === '' && region === '' && startDate === '' && Manager === '') {
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
    </script>
    <!-- end  -->
@endpush