@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Person Target Vs Achivement</h3>

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
        <form action="{{ url('misreport') }}" method="get" id="searchForm">
            <div class="row g-3">

                <!-- HQ -->
                <div class="col-md-4">
                    <label for="region" class="col-form-label">HQ</label>
                    <select name="region" id="region" class="form-select region select2 text-center">
                        <option value="">-- please select --</option>
                        @foreach($regions as $region)
                            <option value="{{ $region->hq_name }}">{{ $region->hq_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Area -->
                <div class="col-md-4">
                    <label for="area" class="col-form-label">Area</label>
                    <select name="area" id="area" class="form-select area select2 text-center">
                        <option value="">-- please select --</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->area }}">{{ $area->area }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Manager -->
                <div class="col-md-4">
                    <label for="manager" class="col-form-label">Manager</label>
                    <select name="manager" id="manager" class="form-select manager select2 text-center">
                        <option value="">-- please select --</option>
                        @foreach($managers as $manager)
                            <option value="{{ $manager->name }}">{{ $manager->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Person Name -->
                <div class="col-md-4">
                    <label for="person" class="col-form-label">Person Name</label>
                    <select name="person" id="person" class="form-select person select2 text-center">
                        <option value="">-- please select --</option>
                        @foreach($persons as $person)
                            <option value="{{ $person->field_force_name }}">{{ $person->field_force_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Month -->
                <div class="col-md-4">
                    <label for="date_select" class="col-form-label">Month</label>
                    <input type="month" name="date_select" id="date_select" class="form-control" autocomplete="off"
                        style="border-radius: 5px;">
                </div>

                <!-- Submit Button -->
                <div class="col-md-2 d-flex align-items-end justify-content-end">
                    <button type="submit" class="btn btn-primary w-100" id="searchButton"><i class="bi bi-search"></i>
                        Search</button>
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
    <!-- end -->
    <!--tables-->
    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table1" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <tr>
                        <?php if (request('person') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('person') }}</th>
                        <?php endif; ?>
                        <?php if (request('region') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('region') }}</th>
                        <?php endif; ?>
                        <?php if (request('manager') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('manager') }}</th>
                        <?php endif; ?>
                        <?php if (request('area') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('area') }}</th>
                        <?php endif; ?>
                        <th colspan="7" class="align text-white bg-danger text-center">TEAM WISE SALES AND TARGET UPTO -
                            {{ $mon_yr }}</th>

                    </tr>
                    <tr>
                        <th colspan="3" class="align text-white bg-secondary text-center"></th>
                        <th colspan="1" class="align text-white bg-secondary text-center">Purchase</th>
                        <th colspan="2" class="align text-white bg-secondary text-center">Target</th>
                        <th colspan="3" class="align text-white bg-secondary text-center"></th>

                    </tr>
                    <tr>

                        <th class="align bg-warning">Currrent Reporting MGR</th>
                        <th class="align bg-warning">HQ Name</th>
                        <th class="align bg-warning">Field Force Name</th>
                        <th class="align bg-warning"><span class="text-warning" style="font-size:1px;">Purchase </span>Till
                            {{ $till_mon }}</th>
                        <th class="align bg-warning"><span class="text-warning" style="font-size:1px;">Target </span>Till
                            {{ $till_mon }}</th>
                        <th class="align bg-warning"><span class="text-warning" style="font-size:1px;">Target
                            </span>{{ $till }}</th>
                        <th class="align bg-warning">Trg Vs Ach %</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($all_ind_sal as $value) { ?>
                    <tr>


                        <td>{{ $value->name }}</td>
                        <td>{{ $value->hq_name }}</td>
                        <td>{{ $value->person_name }}</td>
                        <td class="rupee-value" data-original="{{ $value->sale }}">{{ $value->sale }}</td>
                        <td class="rupee-value" data-original="{{ $value->target }}">{{ $value->target }}</td>
                        <td class="rupee-value" data-original="{{ $value->mon_target }}">{{ $value->mon_target }}</td>
                        <td <?php    echo getBackgroundColor($value->sale, $value->target); ?>>
                            <?php
        if ($value->target != 0) {
            echo round(($value->sale / $value->target) * 100, 0) . '%';
        } else {
            echo '0';
        }
            ?>
                        </td>
                    </tr>
                    <?php } ?>

                    <?php
    // Initialize variables to hold the sum of sales, targets, and monthly targets
    $total_sales = 0;
    $total_targets = 0;
    $total_monthly_targets = 0;

    // Iterate through each row of data
    foreach ($all_ind_sal as $value) {
        // Add sales and targets to the totals
        $total_sales += $value->sale;
        $total_targets += $value->target;
        $total_monthly_targets += $value->mon_target;
    }
    ?>
                </tbody>
                <!-- Add the grand total row after iterating through the data -->
                <tfoot class="table-danger">
                    <tr class="sticky-foot fw-bold">

                        <td>Total</td>
                        <td></td>
                        <td></td>
                        <td class="rupee-value" data-original="<?php echo $total_sales; ?>"><?php echo $total_sales; ?></td>
                        <td class="rupee-value" data-original="<?php echo $total_targets; ?>"><?php echo $total_targets; ?>
                        </td>
                        <td class="rupee-value" data-original="<?php echo $total_monthly_targets; ?>">
                            <?php echo $total_monthly_targets; ?></td>
                        <!-- Calculate and display the Trg Vs Ach % for the grand total -->
                        <td>
                            <?php
    if ($total_targets != 0) {
        echo round(($total_sales / $total_targets) * 100, 0) . '%';
    } else {
        echo '0%';
    }
            ?>
                        </td>

                    </tr>
                </tfoot>
            </table>
        </div>
    </div>


    <!-- distributor sale -->
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-lg rounded-4 border-0 p-4">
                <div class="table-responsive" style="overflow-x: auto;">
                    <table id="Table2" class="table table-bordered table-striped table-hover w-100">
                        <thead>
                            <tr>
                                <?php if (request('person') != ''): ?>
                                <th class="align text-white bg-danger text-center">{{ request('person') }}</th>
                                <?php endif; ?>
                                <?php if (request('region') != ''): ?>
                                <th class="align text-white bg-danger text-center">{{ request('region') }}</th>
                                <?php endif; ?>
                                <?php if (request('manager') != ''): ?>
                                <th class="align text-white bg-danger text-center">{{ request('manager') }}</th>
                                <?php endif; ?>
                                <?php if (request('area') != ''): ?>
                                <th class="align text-white bg-danger text-center">{{ request('area') }}</th>
                                <?php endif; ?>
                                <th colspan="4" class="align text-white bg-danger text-center">MONTH WISE STOCKIST PURCHASE
                                    UPTO - {{ $mon_yr }}</th>

                            </tr>

                            <tr>
                                <th class="align text-white bg-secondary text-center">Month</th>
                                <th class="align text-white bg-secondary text-center">{{ $pre_fy_year }}</th>
                                <th class="align text-white bg-secondary text-center">{{ $cur_fy_year}}</th>
                                <th class="align text-white bg-secondary text-center">Growth</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
    $zoneData = [];
    $divisionTotals = [
        'totalPrimaryDis_pre_fy' => 0,
        'totalPrimaryDis_cur_fy' => 0,
        'totaltarget_cur_fy' => 0,
    ];

    foreach ($month_wise_stock as $value) {
        $Zone = $value->month;

        if (!isset($zoneData[$Zone])) {
            $zoneData[$Zone] = [

                'primary_dis_pre_fy' => 0,
                'primary_dis_cur_fy' => 0,
                'target_cur_fy' => 0,

            ];
        }

        if ($value->f_year == $pre_fy_year) {
            $zoneData[$Zone]['primary_dis_pre_fy'] += $value->sale;

        } elseif ($value->f_year == $cur_fy_year) {

            $zoneData[$Zone]['primary_dis_cur_fy'] += $value->sale;

        }

        // Update division totals
        $divisionTotals['totalPrimaryDis_pre_fy'] += $value->f_year == $pre_fy_year ? $value->sale : 0;
        $divisionTotals['totalPrimaryDis_cur_fy'] += $value->f_year == $cur_fy_year ? $value->sale : 0;


    }

    foreach ($zoneData as $Zone => $data) {
        ?>
                            <tr>
                                <td class="sticky-col"><?php    echo $Zone; ?></td>
                                <td class="rupee-value" data-original="<?php    echo $data['primary_dis_pre_fy']; ?>">
                                    <?php    echo $data['primary_dis_pre_fy']; ?></td>
                                <td class="rupee-value" data-original="<?php    echo $data['primary_dis_cur_fy']; ?>">
                                    <?php    echo $data['primary_dis_cur_fy']; ?></td>

                                <?php    if ($data['primary_dis_pre_fy'] != 0 && $data['primary_dis_cur_fy'] != 0) { ?>
                                <td><?php        echo round(($data['primary_dis_cur_fy'] / $data['primary_dis_pre_fy'] - 1) * 100, 0); ?>%
                                </td>
                                <?php    } else { ?>
                                <td>0</td> <?php    } ?>

                            </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot class="table-danger">

                            <!-- Add the total row for all products -->
                            <tr class="sticky-foot fw-bold">
                                <td class="sticky-col">Total</td>
                                <td class="rupee-value"
                                    data-original="<?php echo $divisionTotals['totalPrimaryDis_pre_fy']; ?>">
                                    <?php echo $divisionTotals['totalPrimaryDis_pre_fy']; ?></td>
                                <td class="rupee-value"
                                    data-original="<?php echo $divisionTotals['totalPrimaryDis_cur_fy']; ?>">
                                    <?php echo $divisionTotals['totalPrimaryDis_cur_fy']; ?></td>
                                <!-- for present month total calculate purpose -->
                                <td>
                                    <?php
    // Filter common months with non-zero sales in both financial years
    $commonMonths = array_filter(
        array_intersect(array_keys($zoneData), array_column($month_wise_stock, 'month')),
        function ($month) use ($zoneData) {
            return ($zoneData[$month]['primary_dis_pre_fy'] != 0 && $zoneData[$month]['primary_dis_cur_fy'] != 0);
        }
    );
    $totalSalesCommonMonthsCurFY = 0;
    $totalSalesCommonMonthsPreFY = 0;

    foreach ($commonMonths as $month) {
        $totalSalesCommonMonthsCurFY += $zoneData[$month]['primary_dis_cur_fy'];
        $totalSalesCommonMonthsPreFY += $zoneData[$month]['primary_dis_pre_fy'];
    }

    $totalGrowth = ($totalSalesCommonMonthsPreFY != 0)
        ? round((($totalSalesCommonMonthsCurFY / $totalSalesCommonMonthsPreFY) - 1) * 100, 0)
        : 0;

    echo $totalGrowth . '%';
            ?>
                                </td>
                                <!-- end -->

                            </tr>

                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- distributor sale -->
        <div class="col-md-6">
            <div class="card shadow-lg rounded-4 border-0 p-4">
                <div class="table-responsive" style="overflow-x: auto;">
                    <table id="Table3" class="table table-bordered table-striped table-hover w-100">
                        <thead>
                            <tr>
                                <?php if (request('person') != ''): ?>
                                <th class="align text-white bg-danger text-center">{{ request('person') }}</th>
                                <?php endif; ?>
                                <?php if (request('region') != ''): ?>
                                <th class="align text-white bg-danger text-center">{{ request('region') }}</th>
                                <?php endif; ?>
                                <?php if (request('manager') != ''): ?>
                                <th class="align text-white bg-danger text-center">{{ request('manager') }}</th>
                                <?php endif; ?>
                                <?php if (request('area') != ''): ?>
                                <th class="align text-white bg-danger text-center">{{ request('area') }}</th>
                                <?php endif; ?>
                                <th colspan="4" class="align text-white bg-danger text-center">MONTH WISE DISTRIBUTOR SALE
                                    UPTO - {{ $mon_yr }}</th>

                            </tr>

                            <tr>
                                <th class="align text-white bg-secondary text-center">Month</th>
                                <th class="align text-white bg-secondary text-center">{{ $pre_fy_year }}</th>
                                <th class="align text-white bg-secondary text-center">{{ $cur_fy_year}}</th>
                                <th class="align text-white bg-secondary text-center">Growth</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
    $zoneData = [];
    $divisionTotals = [
        'totalPrimaryDis_pre_fy' => 0,
        'totalPrimaryDis_cur_fy' => 0,
        'totaltarget_cur_fy' => 0,
    ];

    foreach ($month_wise_dist as $value) {
        $Zone = $value->month;

        if (!isset($zoneData[$Zone])) {
            $zoneData[$Zone] = [

                'primary_dis_pre_fy' => 0,
                'primary_dis_cur_fy' => 0,
                'target_cur_fy' => 0,

            ];
        }

        if ($value->f_year == $pre_fy_year) {
            $zoneData[$Zone]['primary_dis_pre_fy'] += $value->sale;

        } elseif ($value->f_year == $cur_fy_year) {

            $zoneData[$Zone]['primary_dis_cur_fy'] += $value->sale;

        }

        // Update division totals
        $divisionTotals['totalPrimaryDis_pre_fy'] += $value->f_year == $pre_fy_year ? $value->sale : 0;
        $divisionTotals['totalPrimaryDis_cur_fy'] += $value->f_year == $cur_fy_year ? $value->sale : 0;


    }

    foreach ($zoneData as $Zone => $data) {
        ?>
                            <tr>
                                <td class="sticky-col"><?php    echo $Zone; ?></td>
                                <td class="rupee-value" data-original="<?php    echo $data['primary_dis_pre_fy']; ?>">
                                    <?php    echo $data['primary_dis_pre_fy']; ?></td>
                                <td class="rupee-value" data-original="<?php    echo $data['primary_dis_cur_fy']; ?>">
                                    <?php    echo $data['primary_dis_cur_fy']; ?></td>

                                <?php    if ($data['primary_dis_pre_fy'] != 0 && $data['primary_dis_cur_fy'] != 0) { ?>
                                <td><?php        echo round(($data['primary_dis_cur_fy'] / $data['primary_dis_pre_fy'] - 1) * 100, 0); ?>%
                                </td>
                                <?php    } else { ?>
                                <td>0</td> <?php    } ?>

                            </tr>
                            <?php } ?>
                        </tbody>

                        <tfoot class="table-danger">
                            <!-- Add the total row for all products -->
                            <tr class="sticky-foot" style="background:#ffbef7;font-weight: 600;">
                                <td class="sticky-col">Total</td>
                                <td class="rupee-value"
                                    data-original="<?php echo $divisionTotals['totalPrimaryDis_pre_fy']; ?>">
                                    <?php echo $divisionTotals['totalPrimaryDis_pre_fy']; ?></td>
                                <td class="rupee-value"
                                    data-original="<?php echo $divisionTotals['totalPrimaryDis_cur_fy']; ?>">
                                    <?php echo $divisionTotals['totalPrimaryDis_cur_fy']; ?></td>
                                <!-- for present month total calculate purpose -->
                                <td>
                                    <?php
    // Filter common months with non-zero sales in both financial years
    $commonMonths = array_filter(
        array_intersect(array_keys($zoneData), array_column($month_wise_dist, 'month')),
        function ($month) use ($zoneData) {
            return ($zoneData[$month]['primary_dis_pre_fy'] != 0 && $zoneData[$month]['primary_dis_cur_fy'] != 0);
        }
    );
    $totalSalesCommonMonthsCurFY = 0;
    $totalSalesCommonMonthsPreFY = 0;

    foreach ($commonMonths as $month) {
        $totalSalesCommonMonthsCurFY += $zoneData[$month]['primary_dis_cur_fy'];
        $totalSalesCommonMonthsPreFY += $zoneData[$month]['primary_dis_pre_fy'];
    }

    $totalGrowth = ($totalSalesCommonMonthsPreFY != 0)
        ? round((($totalSalesCommonMonthsCurFY / $totalSalesCommonMonthsPreFY) - 1) * 100, 0)
        : 0;

    echo $totalGrowth . '%';
            ?>
                                </td>
                                <!-- end -->

                            </tr>
                        </tfoot>

                    </table>
                </div>
            </div>
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


                buttons: [
                    {
                        extend: 'excelHtml5',
                        filename: 'TEAM WISE SALES AND TARGET',
                        exportOptions: {
                            format: {
                                header: function (data, columnIdx) {
                                    var header1 = $('#Table1 thead tr:eq(0) th').eq(columnIdx).text();
                                    var header3 = $('#Table1 thead tr:eq(2) th').eq(columnIdx).text();

                                    return header1 + '\n' + header3;
                                }
                            }
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        filename: 'TEAM WISE SALES AND TARGET',
                        exportOptions: {
                            format: {
                                header: function (data, columnIdx) {
                                    // Concatenate headers with line breaks
                                    var header1 = $('#Table1 thead tr:eq(0) th').eq(columnIdx).text();
                                    var header3 = $('#Table1 thead tr:eq(2) th').eq(columnIdx).text();

                                    return header1 + '\n' + header3;
                                }
                            }
                        }
                    }
                ]
            });

            $('#Table2').DataTable({

                order:false, 
                pageLength: 20,                       
                buttons: [
                    {
                        extend: 'excelHtml5',
                        filename: 'MONTH WISE STOCKIST SALE',
                        exportOptions: {
                            format: {
                                header: function (data, columnIdx) {
                                    var header1 = $('#Table2 thead tr:eq(0) th').eq(columnIdx).text();
                                    var header3 = $('#Table2 thead tr:eq(2) th').eq(columnIdx).text();

                                    return header1 + '\n' + header3;
                                }
                            }
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        filename: 'MONTH WISE STOCKIST SALE',
                        exportOptions: {
                            format: {
                                header: function (data, columnIdx) {
                                    // Concatenate headers with line breaks
                                    var header1 = $('#Table2 thead tr:eq(0) th').eq(columnIdx).text();
                                    var header3 = $('#Table2 thead tr:eq(2) th').eq(columnIdx).text();

                                    return header1 + '\n' + header3;
                                }
                            }
                        }
                    }
                ]
            });

            $('#Table3').DataTable({

                order:false, 
                pageLength: 20,
                buttons: [
                    {
                        extend: 'excelHtml5',
                        filename: 'MONTH WISE DISTRIBUTOR SALE',
                        exportOptions: {
                            format: {
                                header: function (data, columnIdx) {
                                    var header1 = $('#Table3 thead tr:eq(0) th').eq(columnIdx).text();
                                    var header3 = $('#Table3 thead tr:eq(2) th').eq(columnIdx).text();

                                    return header1 + '\n' + header3;
                                }
                            }
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        filename: 'MONTH WISE DISTRIBUTOR SALE',
                        exportOptions: {
                            format: {
                                header: function (data, columnIdx) {
                                    // Concatenate headers with line breaks
                                    var header1 = $('#Table3 thead tr:eq(0) th').eq(columnIdx).text();
                                    var header3 = $('#Table3 thead tr:eq(2) th').eq(columnIdx).text();

                                    return header1 + '\n' + header3;
                                }
                            }
                        }
                    }
                ]
            });

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
        // search alert
        document.getElementById('searchForm').addEventListener('submit', function (event) {
            var zone = document.getElementById('zone').value;
            var region = document.getElementById('region').value;
            var manager = document.getElementById('manager').value;
            var area = document.getElementById('area').value;
            var startDate = document.getElementById('date_select').value;

            if (zone === '' && region === '' && startDate === '' && manager === '' && area === '') {
                alert('Please select Hq,Area or Month Before Searching.');
                event.preventDefault();
            }
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