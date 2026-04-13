@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Month And Zone Wise</h3>

    <!-- tabs header -->
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="text-muted fw-bold">Last Updated At: <span
                    class="text-primary fw-bold">{{ $last_update[0]->created_at }}</span></h6>
            <h6 class="text-muted">Data Upto: <span class="text-primary fw-bold">{{ $last_data }}</span></h6>
            <a href="{{ url($pageModule) }}" class="btn btn-outline-primary fw-bold">Tabs</a>
        </div>
    </div>

    <!-- Search Card -->
    <div class="card shadow-lg rounded-4 border-0 p-4 mb-4">
        <form action="{{ url('primarysalesandtarget') }}" method="get" id="searchForm">
            <div class="row g-3 align-items-end">

                <!-- Zone -->
                <div class="col-md-3">
                    <label for="zone" class="form-label">Zone</label>
                    <select name="zone" id="zone" class="form-select select2 text-center zone">
                        <option value="">-- please select --</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->zone }}">{{ $zone->zone }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Region -->
                <div class="col-md-3">
                    <label for="region" class="form-label">Region</label>
                    <select name="region" id="region" class="form-select select2 text-center region">
                        <option value="">-- please select --</option>
                        @foreach($regions as $region)
                            <option value="{{ $region->region }}">{{ $region->region }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- State -->
                <div class="col-md-3">
                    <label for="state" class="form-label">State</label>
                    <select name="state" id="state" class="form-select select2 text-center state">
                        <option value="">-- please select --</option>
                        @foreach($states as $state)
                            <option value="{{ $state->state }}">{{ $state->state }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Area -->
                <div class="col-md-3">
                    <label for="area" class="form-label">Area</label>
                    <select name="area" id="area" class="form-select select2 text-center area">
                        <option value="">-- please select --</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->area }}">{{ $area->area }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="category" class="form-label">Category</label>
                    <select name="category" id="category" class="form-select select2 text-center category">
                        <option value="">-- please select --</option>
                        @foreach($categorys as $category)
                            <option value="{{ $category->division }}">{{ $category->division }}</option>
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
                <div class="col-6 text-center mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Search
                    </button>
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
    <!---  month Wise Sale value -->
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
                        <?php elseif (request('state') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('state') }}</th>
                        <?php endif; ?>
                        <?php if (request('area') != ''): ?>
                        <th class="align text-white bg-danger text-center" style="background:#9b900d">{{ request('area') }}</th>
                        <?php endif; ?>
                         <?php if (request('category') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('category') }}</th>
                        <?php endif; ?>
                        <th colspan="6" class="text-white bg-danger text-center">MONTH WISE SALES AND TARGET UPTO
                            {{ $mon_yr }}</th>

                    </tr>
                    <tr>
                        <th colspan="1" class="align text-white bg-secondary text-center"></th>
                        <th colspan="1" class="align text-white bg-secondary text-center">Sale</th>
                        <th colspan="1" class="align text-white bg-secondary text-center">Sale</th>
                        <th colspan="1" class="align text-white bg-secondary text-center">Target</th>
                        <th colspan="2" class="align text-white bg-secondary text-center"></th>

                    </tr>
                    <tr>
                        <th class="align table-warning text-center">Month</th>
                        <th class="align table-warning text-center">{{ $pre_fy_year }}</th>
                        <th class="align table-warning text-center">{{ $cur_fy_year}}</th>
                        <th class="align table-warning text-center">{{ $cur_fy_year}}</th>
                        <th class="align table-warning text-center">Growth</th>
                        <th class="align table-warning text-center">Trg Vs Ach %</th>
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

    foreach ($month_wise as $value) {
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
            $zoneData[$Zone]['target_cur_fy'] += $value->target;

        }

        // Update division totals
        $divisionTotals['totalPrimaryDis_pre_fy'] += $value->f_year == $pre_fy_year ? $value->sale : 0;
        $divisionTotals['totalPrimaryDis_cur_fy'] += $value->f_year == $cur_fy_year ? $value->sale : 0;
        $divisionTotals['totaltarget_cur_fy'] += $value->f_year == $cur_fy_year ? $value->target : 0;

    }

    foreach ($zoneData as $Zone => $data) {
    ?>
                    <tr>
                        <td class="sticky-col" style="text-align:center;"><?php    echo $Zone; ?></td>
                        <td class="rupee-value" data-original="<?php    echo $data['primary_dis_pre_fy']; ?>">
                            <?php    echo $data['primary_dis_pre_fy']; ?></td>
                        <td class="rupee-value" data-original="<?php    echo $data['primary_dis_cur_fy']; ?>">
                            <?php    echo $data['primary_dis_cur_fy']; ?></td>
                        <td class="rupee-value" data-original="<?php    echo $data['target_cur_fy']; ?>">
                            <?php    echo $data['target_cur_fy']; ?></td>
                        <?php    if ($data['primary_dis_pre_fy'] != 0 && $data['primary_dis_cur_fy'] != 0) { ?>
                        <td style=" text-align: center;">
                            <?php        echo round(($data['primary_dis_cur_fy'] / $data['primary_dis_pre_fy'] - 1) * 100, 0); ?>%
                        </td>
                        <?php    } elseif ($data['primary_dis_pre_fy'] != 0 && $data['primary_dis_cur_fy'] == 0) { ?>
                        <td style=" text-align: center;">-100%</td>
                        <?php    } else { ?>
                        <td style=" text-align: center;">0%</td>
                        <?php    } ?>

                        <?php 
    if ($data['target_cur_fy'] != 0 && $data['primary_dis_cur_fy'] != 0) { ?>
                        <td
                            style=" text-align: center; <?php        echo getBackgroundColor($data['primary_dis_cur_fy'], $data['target_cur_fy']); ?>">
                            <?php        echo round(($data['primary_dis_cur_fy'] / $data['target_cur_fy']) * 100, 0); ?>%
                        </td>
                        <?php    } else { ?>
                        <td style=" text-align: center; background: #c96fc6;">0%</td>
                        <?php    } ?>

                    </tr>
                    <?php } ?>
                </tbody>

                <!-- Add the total row for all products -->
                <tfoot>
                    <tr class="table-danger text-center fw-bold">
                        <td class="sticky-col" style="text-align:center;">Total</td>
                        <td class="rupee-value" data-original="<?php echo $divisionTotals['totalPrimaryDis_pre_fy']; ?>">
                            <?php echo $divisionTotals['totalPrimaryDis_pre_fy']; ?></td>
                        <td class="rupee-value" data-original="<?php echo $divisionTotals['totalPrimaryDis_cur_fy']; ?>">
                            <?php echo $divisionTotals['totalPrimaryDis_cur_fy']; ?></td>
                        <td class="rupee-value" data-original="<?php echo $divisionTotals['totaltarget_cur_fy']; ?>">
                            <?php echo $divisionTotals['totaltarget_cur_fy']; ?></td>

                        <!-- Conditional Logic -->
                        <?php if (request('zone') != '' || request('region') != '' || request('state') != '') { ?>

                        <!-- Growth Calculation -->
                        <td>
                            <?php
        $totalSalesCurFY = $divisionTotals['totalPrimaryDis_cur_fy'];
        $totalSalesPreFY = $divisionTotals['totalPrimaryDis_pre_fy'];

        $totalGrowth = ($totalSalesPreFY != 0)
            ? round((($totalSalesCurFY / $totalSalesPreFY) - 1) * 100, 0)
            : (($totalSalesCurFY != 0) ? 100 : 0);

        echo $totalGrowth . '%';
                ?>
                        </td>

                        <!-- Target vs Achievement Calculation -->
                        <td>
                            <?php
        $totalTargetCurFY = $divisionTotals['totaltarget_cur_fy'];

        $targetAchievement = ($totalTargetCurFY != 0)
            ? round(($totalSalesCurFY / $totalTargetCurFY) * 100, 0)
            : (($totalSalesCurFY != 0) ? 100 : 0);

        echo $targetAchievement . '%';
                ?>
                        </td>

                        <?php } else { ?>

                        <!-- Alternative Growth Calculation -->
                        <td>
                            <?php
        $commonMonths = array_filter(
            array_intersect(array_keys($zoneData), array_column($month_wise, 'month')),
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

                        <!-- Alternative Target vs Achievement Calculation -->
                        <td>
                            <?php
        $totalSalesCommonMonthsCurFY = 0;
        $totalSalesCommonMonthsPreFY = 0;

        foreach ($commonMonths as $month) {
            $totalSalesCommonMonthsCurFY += $zoneData[$month]['primary_dis_cur_fy'];
            $totalSalesCommonMonthsPreFY += $zoneData[$month]['target_cur_fy'];
        }

        $target = ($totalSalesCommonMonthsPreFY != 0)
            ? round((($totalSalesCommonMonthsCurFY / $totalSalesCommonMonthsPreFY)) * 100, 0)
            : 0;

        echo $target . '%';
                ?>
                        </td>

                        <?php } ?>

                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- END -->



    <!-- month wise summary chart -->
    <div class="card shadow-lg rounded-4 border-0 p-3 mb-4">
        <h5 class="chart_tittle">Month Wise Growth And Achivement UPTO - {{ $mon_yr }}</h5>
        <canvas id="Monthchart" style="max-height:460px"></canvas>

    </div>

    <!---  zone Wise Sale value -->
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
                        <?php if (request('area') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('area') }}</th>
                        <?php endif; ?>
                       <?php if (request('category') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('category') }}</th>
                        <?php endif; ?>
                        <th colspan="6" class="text-white bg-danger text-center">ZONE WISE SALES AND TARGET UPTO
                            {{ $mon_yr }}</th>

                    </tr>
                    <tr>
                        <th colspan="1" class="align text-white bg-secondary text-center"></th>
                        <th colspan="1" class="align text-white bg-secondary text-center">Sale</th>
                        <th colspan="1" class="align text-white bg-secondary text-center">Sale</th>
                        <th colspan="1" class="align text-white bg-secondary text-center">Target</th>
                        <th colspan="2" class="align text-white bg-secondary text-center"></th>

                    </tr>
                    <tr>
                        <th class="align table-warning text-center">Zone</th>
                        <th class="align table-warning text-center">{{ $pre_fy_year }}</th>
                        <th class="align table-warning text-center">{{ $cur_fy_year}}</th>
                        <th class="align table-warning text-center">{{ $cur_fy_year}}</th>
                        <th class="align table-warning text-center">Growth</th>
                        <th class="align table-warning text-center">Trg Vs Ach %</th>
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

    foreach ($all_ind_sal as $value) {
        $Zone = $value->zone;

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
            $zoneData[$Zone]['target_cur_fy'] += $value->target;

        }

        // Update division totals
        $divisionTotals['totalPrimaryDis_pre_fy'] += $value->f_year == $pre_fy_year ? $value->sale : 0;
        $divisionTotals['totalPrimaryDis_cur_fy'] += $value->f_year == $cur_fy_year ? $value->sale : 0;
        $divisionTotals['totaltarget_cur_fy'] += $value->f_year == $cur_fy_year ? $value->target : 0;

    }

    foreach ($zoneData as $Zone => $data) {
    ?>
                    <tr>
                        <td class="sticky-col"><?php    echo $Zone; ?></td>
                        <td class="rupee-value" data-original="<?php    echo $data['primary_dis_pre_fy']; ?>">
                            <?php    echo $data['primary_dis_pre_fy']; ?></td>
                        <td class="rupee-value" data-original="<?php    echo $data['primary_dis_cur_fy']; ?>">
                            <?php    echo $data['primary_dis_cur_fy']; ?></td>
                        <td class="rupee-value" data-original="<?php    echo $data['target_cur_fy']; ?>">
                            <?php    echo $data['target_cur_fy']; ?></td>
                        <?php    if ($data['primary_dis_pre_fy'] != 0) { ?>
                        <td><?php        echo round(($data['primary_dis_cur_fy'] / $data['primary_dis_pre_fy'] - 1) * 100, 0); ?>%
                        </td> <?php    } else { ?>
                        <td>0</td> <?php    } ?>
                        <?php    if ($data['target_cur_fy'] != 0) { ?>
                        <td
                            style=" text-align: center; <?php        echo getBackgroundColor($data['primary_dis_cur_fy'], $data['target_cur_fy']); ?>">
                            <?php        echo round(($data['primary_dis_cur_fy'] / $data['target_cur_fy']) * 100, 0); ?>%
                        </td> <?php    } else { ?>
                        <td style="text-align: center;background: #c96fc6;">0</td> <?php    } ?>
                    </tr>
                    <?php } ?>
                </tbody>
                <!-- Add the total row for all products -->
                <tfoot>
                    <tr class="table-danger text-center fw-bold">
                        <td class="sticky-col">Total</td>
                        <td class="rupee-value" data-original="<?php echo $divisionTotals['totalPrimaryDis_pre_fy']; ?>">
                            <?php echo $divisionTotals['totalPrimaryDis_pre_fy']; ?></td>
                        <td class="rupee-value" data-original="<?php echo $divisionTotals['totalPrimaryDis_cur_fy']; ?>">
                            <?php echo $divisionTotals['totalPrimaryDis_cur_fy']; ?></td>
                        <td class="rupee-value" data-original="<?php echo $divisionTotals['totaltarget_cur_fy']; ?>">
                            <?php echo $divisionTotals['totaltarget_cur_fy']; ?></td>
                        <?php  if ($divisionTotals['totalPrimaryDis_pre_fy'] != 0) { ?>
                        <td><?php    echo round(($divisionTotals['totalPrimaryDis_cur_fy'] / $divisionTotals['totalPrimaryDis_pre_fy'] - 1) * 100, 0); ?>%
                        </td><?php } else { ?>
                        <td>0</td> <?php } ?>
                        <?php  if ($divisionTotals['totaltarget_cur_fy'] != 0) { ?>
                        <td
                            style=" text-align: center; <?php    echo getBackgroundColor($divisionTotals['totalPrimaryDis_cur_fy'], $divisionTotals['totaltarget_cur_fy']); ?>">
                            <?php    echo round(($divisionTotals['totalPrimaryDis_cur_fy'] / $divisionTotals['totaltarget_cur_fy']) * 100, 0); ?>%
                        </td><?php } else { ?>
                        <td>0</td> <?php } ?>
                    </tr>
                    <tfoot>
            </table>
        </div>
    </div>

    <!-- END -->



    <!-- machine wise summary chart -->
    <div class="card shadow-lg rounded-4 border-0 p-3 mb-4">
        <h5 class="chart_tittle">Zone Wise Growth And Achivement UPTO - {{ $mon_yr }}</h5>
        <canvas id="myChart" style="max-height:460px"></canvas>

    </div>
    <!-- end -->
@endsection
@push('scripts')

    <!-- Include jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

    <script>


        $(document).ready(function () {

            var startDate = "{{ request('date_select') }}";
            $('#date_select').val(startDate);


        });

        $(document).ready(function () {

            $('#Table1').DataTable({
                dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
                order: false,
                pageLength: 20,
                scrollCollapse: true,
                buttons: [
                    {
                        extend: 'excelHtml5',
                        filename: 'primary_sale_and_target_zone',
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
                        filename: 'primary_sale_and_target_zone',
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
                dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
                order: false,
                pageLength: 20,
                scrollCollapse: true,
                buttons: [
                    {
                        extend: 'excelHtml5',
                        filename: 'MONTH WISE SALES AND TARGET',
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
                        filename: 'MONTH WISE SALES AND TARGET',
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
            var state = document.getElementById('state').value;
            var Area = document.getElementById('area').value;
            var Cat = document.getElementById('category').value;
            var startDate = document.getElementById('date_select').value;

            if (zone === '' && region === '' && startDate === '' && state === '' && Area === '' && Cat === '') {
                alert('Please select Zone, Region,Area or Month Before Searching.');
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


        function generateRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        // Assuming $this->data['prim_sumchart'] is a JSON-encoded string
        const monthData = {!! $month_chart !!};

        var xValues = monthData.map(function (data) {
            return data.month;
        });

        var growthValues = monthData.map(function (data) {
            return parseFloat(data.growth.replace('%', '')) || 0;
        });

        var achiveValues = monthData.map(function (data) {
            return parseFloat(data.achive.replace('%', '')) || 0;
        });

        // Generate random color for each dataset
        var growthColor = generateRandomColor();
        var achiveColor = generateRandomColor();

        var datasets = [
            {
                label: 'Growth',
                backgroundColor: growthColor,
                data: growthValues
            },
            {
                label: 'Trg vs Ach',
                backgroundColor: achiveColor,
                data: achiveValues
            }
        ];

        new Chart("Monthchart", {
            type: "bar",
            data: {
                labels: xValues,
                datasets: datasets
            },
            options: {
                hover: {
                    animationDuration: 0
                },
                animation: {
                    duration: 1,
                    onComplete: function () {
                        var chartInstance = this.chart,
                            ctx = chartInstance.ctx;

                        ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, 'bold ' + Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'bottom';
                        ctx.fillStyle = 'black';
                        this.data.datasets.forEach(function (dataset, i) {
                            var meta = chartInstance.controller.getDatasetMeta(i);
                            meta.data.forEach(function (bar, index) {
                                var data = dataset.data[index];
                                data = data + '%';
                                ctx.fillText(data, bar._model.x, bar._model.y - 5);
                            });
                        });
                    }
                },
                legend: {
                    display: true
                },
                tooltips: {
                    callbacks: {
                        label: function (tooltipItem, data) {
                            var dataset = data.datasets[tooltipItem.datasetIndex];
                            var currentValue = dataset.data[tooltipItem.index];
                            return dataset.label + ": " + currentValue + "%";
                        }
                    }
                }
            }
        });

        // Assuming $this->data['prim_sumchart'] is a JSON-encoded string
        const productData = {!! $prim_sumchart !!};

        var xValues = productData.map(function (data) {
            return data.zone;
        });

        var growthValues = productData.map(function (data) {
            return parseFloat(data.growth.replace('%', '')) || 0;
        });

        var achiveValues = productData.map(function (data) {
            return parseFloat(data.achive.replace('%', '')) || 0;
        });

        // Generate random color for each dataset
        var growthColor = generateRandomColor();
        var achiveColor = generateRandomColor();

        var datasets = [
            {
                label: 'Growth',
                backgroundColor: growthColor,
                data: growthValues
            },
            {
                label: 'Trg vs Ach',
                backgroundColor: achiveColor,
                data: achiveValues
            }
        ];

        new Chart("myChart", {
            type: "bar",
            data: {
                labels: xValues,
                datasets: datasets
            },
            options: {
                hover: {
                    animationDuration: 0
                },
                animation: {
                    duration: 1,
                    onComplete: function () {
                        var chartInstance = this.chart,
                            ctx = chartInstance.ctx;

                        ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, 'bold ' + Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'bottom';
                        ctx.fillStyle = 'black';
                        this.data.datasets.forEach(function (dataset, i) {
                            var meta = chartInstance.controller.getDatasetMeta(i);
                            meta.data.forEach(function (bar, index) {
                                var data = dataset.data[index];
                                data = data + '%';
                                ctx.fillText(data, bar._model.x, bar._model.y - 5);
                            });
                        });
                    }
                },
                legend: {
                    display: true
                },
                tooltips: {
                    callbacks: {
                        label: function (tooltipItem, data) {
                            var dataset = data.datasets[tooltipItem.datasetIndex];
                            var currentValue = dataset.data[tooltipItem.index];
                            return dataset.label + ": " + currentValue + "%";
                        }
                    }
                }
            }
        });


        // convert money inr to k and l purpose  
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