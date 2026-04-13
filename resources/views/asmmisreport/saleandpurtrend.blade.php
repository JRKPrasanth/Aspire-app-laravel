@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Sales And Purchase Trend</h3>

    <!-- tabs header -->
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="text-muted fw-bold">Last Updated At: <span
                    class="text-danger fw-bold">{{ $last_update[0]->created_at }}</span></h6>
            <h6 class="text-muted">Data Upto: <span class="text-danger fw-bold">{{ $last_data }}</span></h6>
            <a href="{{ url($pageModule) }}" class="btn btn-outline-danger fw-bold">Tabs</a>
        </div>
    </div>
    <!-- end -->

    <div class="card shadow-lg rounded-4 border-0 p-4 mb-4">
        <form action="{{ url('misreportsaleandpurc') }}" method="get" id="searchForm">
            <div class="row g-3">

                <!-- HQ -->
                <div class="col-md-3">
                    <label for="region" class="col-form-label">HQ</label>
                    <select name="region" id="region" class="form-select region select2 text-center">
                        <option value="">-- please select --</option>
                        @foreach($regions as $region)
                            <option value="{{ $region->hq_name }}">{{ $region->hq_name }}</option>
                        @endforeach
                    </select>
                </div>


                <!-- Manager -->
                <div class="col-md-3">
                    <label for="manager" class="col-form-label">Manager</label>
                    <select name="manager" id="manager" class="form-select manager select2 text-center">
                        <option value="">-- please select --</option>
                        @foreach($managers as $manager)
                            <option value="{{ $manager->name }}">{{ $manager->name }}</option>
                        @endforeach
                    </select>
                </div>


                <!-- Month -->
                <div class="col-md-3">
                    <label for="date_select" class="col-form-label">Month</label>
                    <input type="month" name="date_select" id="date_select" class="form-control date_select"
                        autocomplete="off" style="border-radius: 5px;">
                </div>

                <!-- Submit Button -->
                <div class="col-md-2 d-flex align-items-end justify-content-end">
                    <button type="submit" class="btn btn-primary w-100" id="searchButton"><i class="bi bi-search"></i>
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
    <!-- end -->

    <!--- distributor stock value -->
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

                        <?php if (request('manager') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('manager') }}</th>
                        <?php endif; ?>
                        <th colspan="15" class="align text-white bg-danger text-center">DISTRIBUTOR WISE SALES TREND UPTO -
                            {{ $mon_yr }}</th>
                    </tr>
                    <tr>
                        <th class="align text-white bg-secondary text-center">Distributor Name</th>
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
        $monthlySales[$value->name][$value->f_year][$value->month] = $value->sale;
    }
        ?>

              <tbody>
<?php
foreach ($monthlySales as $name => $financialYears) {
    foreach ($financialYears as $fYear => $monthData) {

        echo '<tr>';

        // Distributor Name
        echo '<td class="sticky-col">' . htmlspecialchars($name) . '</td>';

        // Financial Year
        echo '<td class="sticky-col-1">' . htmlspecialchars($fYear) . '</td>';

        $rowTotal = 0;

        // Monthly values
        foreach ($displayedMonths as $month) {
            $saleValue = $monthData[$month] ?? 0;

            echo '<td class="rupee-value" data-original="' . $saleValue . '">'
                . number_format($saleValue, 2) .
                '</td>';

            $rowTotal += $saleValue;
        }

        // Grand Total
        echo '<td class="rupee-value fw-bold" data-original="' . $rowTotal . '">'
            . number_format($rowTotal, 2) .
            '</td>';

        echo '</tr>';
    }
}
?>
</tbody>


            </table>
        </div>
    </div>

    <!--- stockist trend -->
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
                        <th colspan="15" class="align text-white bg-danger text-center">STOCKIST WISE SALES TREND UPTO -
                            {{ $mon_yr }}</th>
                    </tr>

                    <tr>
                        <th class="align text-white bg-secondary text-center">HQ Name</th>
                        <th class="align text-white bg-secondary text-center">Stockist Name</th>
                        <th class="align text-white bg-secondary text-center">F_Year</th>
                        <?php $displayedMonths = []; ?>
                        <?php foreach ($stock_trend_stk as $value) {
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

    foreach ($stock_trend_stk as $value) {
        // Store sales data for each month
        $monthlySales[$value->hq_name][$value->name][$value->f_year][$value->month] = $value->sale;
    }
        ?>

                <tbody>
                    <?php
    foreach ($monthlySales as $hq_name => $names) {
        foreach ($names as $name => $financialYears) {
            foreach ($financialYears as $fYear => $monthData) {

                echo '<td class="sticky-col" >' . $hq_name . '</td>';
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
        ?>
                </tbody>
            </table>
        </div>
    </div>
    <!--- Sales stock value -->
    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table3" class="table table-bordered table-striped table-hover w-100">
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
                        <th colspan="15" class="align text-white bg-danger text-center">TEAM WISE STOCKIST PURCHASE TREND
                            UPTO - {{ $mon_yr }}</th>
                    </tr>

                    <tr>
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
        $monthlySales[$value->name][$value->f_year][$value->month] = $value->sale;
    }
        ?>

                <tbody>
                    <?php
    foreach ($monthlySales as $name => $financialYears) {
        foreach ($financialYears as $fYear => $monthData) {

            echo '<tr>';

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
        ?>
                </tbody>
            </table>
        </div>
    </div>

    <!---  purchase stock value -->
    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table4" class="table table-bordered table-striped table-hover w-100">
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
                        <th colspan="15" class="align text-white bg-danger text-center">TEAM WISE STOCKIST SALE TREND UPTO -
                            {{ $mon_yr }}</th>
                    </tr>

                    <tr>
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
        $monthlySales[$value->name][$value->f_year][$value->month] = $value->sale;
    }
        ?>

                <tbody>
                    <?php
    foreach ($monthlySales as $name => $financialYears) {
        foreach ($financialYears as $fYear => $monthData) {

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

            $('#Table3').DataTable({
                scrollX: true,
                scrollY: "50vh",

                buttons: [
                    {
                        extend: 'excelHtml5',
                        filename: 'TEAM WISE STOCKIST PURCHASE TREND',
                        exportOptions: {
                            format: {
                                header: function (data, columnIdx) {
                                    var header1 = $('#Table3 thead tr:eq(0) th').eq(columnIdx).text();
                                    var header2 = $('#Table3 thead tr:eq(1) th').eq(columnIdx).text();
                                    var header3 = $('#Table3 thead tr:eq(2) th').eq(columnIdx).text();

                                    return header1 + '\n' + (header2 ? header2 + '\n' : '') + header3;
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
                                    var header1 = $('#Table3 thead tr:eq(0) th').eq(columnIdx).text();
                                    var header2 = $('#Table3 thead tr:eq(1) th').eq(columnIdx).text();
                                    var header3 = $('#Table3 thead tr:eq(2) th').eq(columnIdx).text();

                                    return header1 + '\n' + (header2 ? header2 + '\n' : '') + header3;
                                }
                            }
                        }
                    }
                ]
            });
            $('#Table4').DataTable({
                scrollX: true,
                scrollY: "50vh",

                buttons: [
                    {
                        extend: 'excelHtml5',
                        filename: 'TEAM WISE STOCKIST SALE TREND',
                        exportOptions: {
                            format: {
                                header: function (data, columnIdx) {
                                    var header1 = $('#Table4 thead tr:eq(0) th').eq(columnIdx).text();
                                    var header2 = $('#Table4 thead tr:eq(1) th').eq(columnIdx).text();
                                    var header3 = $('#Table4 thead tr:eq(2) th').eq(columnIdx).text();

                                    return header1 + '\n' + (header2 ? header2 + '\n' : '') + header3;
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
                                    var header1 = $('#Table4 thead tr:eq(0) th').eq(columnIdx).text();
                                    var header2 = $('#Table4 thead tr:eq(1) th').eq(columnIdx).text();
                                    var header3 = $('#Table4 thead tr:eq(2) th').eq(columnIdx).text();

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