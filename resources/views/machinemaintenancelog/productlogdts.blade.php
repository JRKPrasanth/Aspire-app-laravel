@extends('layouts.header')
@section('content')
    <style>
        table th,
        table td {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 150px;
        }

        table th:hover,
        table td:hover {
            overflow: visible;
            white-space: normal;
            z-index: 1000;
            position: relative;
        }
    </style>
    <h3 class="text-danger">Maintenance Product Details</h3>

    <div class="container mt-4">
        <div class="row g-3 mb-3">
            <div class="col-12 col-md-4">
                <a href="machinemintenancelogreport" class="btn btn-outline-success w-100">Machine Dashboard</a>
            </div>
            <div class="col-12 col-md-4">
                <a href="productmaintenancelogreport" class="btn btn-primary w-100">Product Dashboard</a>
            </div>
        </div>
    </div>

    <?php
    function timeToSeconds($time)
    {
        list($hours, $minutes, $seconds) = explode(":", $time);
        return $hours * 3600 + $minutes * 60 + $seconds;
    }

    function formatSecondsToTime($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;

        return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
    }
                    ?>

    <div class="card shadow-lg rounded-4 border-0 p-4">
        <form action="{{ url('productmaintenancelogreport') }}" method="get" id="searchForm">
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label for="product_type" class="form-label">Product Type</label>
                    <select name="product_type" id="product_type" class="form-select select2 product_type">
                        <option value="">-- please select --</option>
                        @foreach($pro_type as $type)
                            <option value="{{ $type->product_type_id }}">{{ $type->product_type }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="product_varient" class="form-label">Product Variant</label>
                    <div class="input-group">
                        <select name="product_varient" id="product_varient" class="form-select select2 product_varient">
                            {!! $product_varient !!}
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="unit" class="form-label">Pack</label>
                    <select name="unit" id="unit" class="form-select select2 unit" style="pointer-events: none;">
                        {!! $unit !!}
                    </select>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label for="product_id" class="form-label">Product Name</label>
                    <div class="input-group">
                        <select name="product_id" id="product_id" class="form-select select2 product_id">
                            {!! $product_id !!}
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="text" class="form-control start_date1" id="start_date" name="start_date"
                        placeholder="YYYY-MM-DD" required>
                </div>

                <div class="col-md-4">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="text" class="form-control end_date1" id="end_date" name="end_date" placeholder="YYYY-MM-DD"
                        required>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12 text-center mt-4">
                    <button type="submit" class="btn btn-primary px-4" id="searchButton">Search</button>
                </div>
            </div>
        </form>
    </div>


    <!-- type wise summary chart -->
    <div class='row'>
        <div class='col-md-6'>
            <div class="card shadow-lg rounded-4 border-0 p-4">
                <p class="nodata fw-bold">Type Wise Summary Chart (Production and Operation)</p>
                <div class="dash_charrt">
                    <canvas id="typeChart"></canvas>
                    <p class="nodata" id="typeChart1"></p>
                </div>
            </div>
        </div>
        <!-- type summary chart (preventive and breakdown)-->
        <div class='col-md-6'>
            <div class="card shadow-lg rounded-4 border-0 p-4">
                <p class="nodata fw-bold">Category Summary Chart (Total Running Hours)</p>
                <div class="dash_charrt">
                    <canvas id="primChart"></canvas>
                    <p class="nodata" id="primChart1"></p>
                </div>
            </div>
        </div>
    </div>
    <!--  table 1-->
    <div class="card shadow-lg rounded-4 border-0 p-4">
        <h4 class="text-danger">Type Wise Summary Report</h4>
        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table1" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <?php if (empty($prim_summary)) { ?>
                    <p class="nodata">No records available.</p>
                    <?php } else { ?>

                    <thead>
                        <tr>
                            <th colspan="2" class="text-center text-white bg-danger">Particulars</th>
                            <?php    $displayedMonths = []; ?>
                            <?php    foreach ($prim_summary as $value) {
            $monthYear = $value->log_month;
            if (!in_array($monthYear, $displayedMonths)) {
                $displayedMonths[] = $monthYear; ?>
                            <th colspan="4" class="text-center text-white bg-secondary"><?php            echo $monthYear; ?>
                            </th>
                            <?php        }
        } ?>
                            <th colspan="4" class="text-center text-white bg-secondary">Total</th>
                        </tr>
                        <tr>
                            <th class="freeze text-center text-white bg-secondary">Department</th>
                            <th class="text-center text-white bg-secondary">Product</th>
                            <?php    foreach ($displayedMonths as $month) { ?>
                            <th class="table-primary">Qty</th>
                            <th class="table-primary">Running Hrs</th>
                            <th class="table-primary">OPT P/H(M)</th>
                            <th class="table-primary">Idle Hrs</th>
                            <?php    } ?>
                            <th class="table-warning">Total Qty</th>
                            <th class="table-warning">Total Running Hrs</th>
                            <th class="table-warning">Total OPT P/H(M)</th>
                            <th class="table-warning">Total Idle Hrs</th>
                        </tr>
                    </thead>
                <tbody>
                    <?php    $departments = array_unique(array_column($prim_summary, 'process_dept')); ?>
                    <?php    $machines = array_unique(array_column($prim_summary, 'machine_name')); ?>
                    <?php    $grandTotalQty = $grandTotalRunningHrs = $grandTotalIdleHrs = $grandtotaloptHrs = 0; ?>
                    <?php    $columnTotals = array_fill(0, count($displayedMonths) * 4, 0); ?>

                    <?php    foreach ($departments as $department) { ?>
                    <?php        foreach ($machines as $machine) { ?>
                    <tr>
                        <td class="freeze"><?= $department ?></td>
                        <td class="sticky-col1"><?= $machine ?></td>
                        <?php            $totalQty = $totalRunningHrs = $totaloptHrs = $totalIdleHrs = 0; ?>
                        <?php            $colIndex = 0; ?>
                        <?php            foreach ($displayedMonths as $month) { ?>
                        <?php                $foundData = false; ?>
                        <?php                foreach ($prim_summary as $value) { ?>
                        <?php                    if (
                            $value->process_dept == $department &&
                            $value->machine_name == $machine &&
                            $value->log_month == $month
                        ) { ?>
                        <td><?= $value->total_quantity ?></td>
                        <td><?= formatSecondsToTime(timeToSeconds($value->total_running_hours)) ?></td>
                        <td><?= $value->opt_hrs ?></td>
                        <td><?= formatSecondsToTime(timeToSeconds($value->total_idle_hours)) ?></td>
                        <?php                        /* Update totals for each row */ ?>
                        <?php                        $totalQty += $value->total_quantity; ?>
                        <?php                        $totalRunningHrs += timeToSeconds($value->total_running_hours); ?>
                        <?php                        $totaloptHrs += $value->opt_hrs; ?>
                        <?php                        $totalIdleHrs += timeToSeconds('200:00:00') - timeToSeconds($value->total_running_hours); ?>
                        <?php                        /* Update column totals */ ?>
                        <?php                        $columnTotals[$colIndex] += $value->total_quantity; ?>
                        <?php                        $columnTotals[$colIndex + 1] += timeToSeconds($value->total_running_hours); ?>
                        <?php                        $columnTotals[$colIndex + 2] = round(($columnTotals[$colIndex] / $columnTotals[$colIndex + 1] * 3600), 0); ?>
                        <?php                        $columnTotals[$colIndex + 3] = (200 * 3600) - $columnTotals[$colIndex + 1]; ?>
                        <?php                        $foundData = true; ?>
                        <?php                    } ?>
                        <?php                } ?>
                        <?php                if (!$foundData) { ?>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <?php                } ?>
                        <?php                $colIndex += 4; ?>
                        <?php            } ?>
                        <!-- row Total -->
                        <td><?= $totalQty ?></td>
                        <td><?= formatSecondsToTime($totalRunningHrs) ?></td>
                        <td><?php            if ($totalRunningHrs > 0) {
                    echo round(($totalQty / $totalRunningHrs * 3600), 0);
                } else {
                    echo '0';
                } ?>
                        </td>
                        <td><?= formatSecondsToTime($totalIdleHrs) ?></td>

                    </tr>
                    <?php        } ?>
                    <?php    } ?>
                    <!-- Grand Total Row -->
                    <tr class="fw-bold">
                        <td class="freeze">Grand Total</td>
                        <td></td>
                        <?php
        $colIndex = 0;
        $grandTotalQty = $grandTotalRunningHrs = $grandtotaloptHrs = $grandTotalIdleHrs = 0;

        foreach ($columnTotals as $total) {
            if ($colIndex % 4 == 0) {
                $grandTotalQty += $total;
                        ?>
                        <td><?= $total ?></td>
                        <?php
            }
            if ($colIndex % 4 == 1) {
                $grandTotalRunningHrs += $total;
                        ?>
                        <td><?= formatSecondsToTime($total) ?></td>
                        <?php
            }



            if ($colIndex % 4 == 2) {
                if ($grandTotalRunningHrs != 0) {
                    $grandtotaloptHrs = round(($grandTotalQty / $grandTotalRunningHrs * 3600), 0);
                                   ?>
                        <td><?= $total ?></td>
                        <?php
                } else {
                    echo '';


                }
            }


            if ($colIndex % 4 == 3) {

                $grandTotalIdleHrs += $total;
                        ?>
                        <td><?= formatSecondsToTime($total) ?></td>

                        <?php
            }
            $colIndex += 1;
        }
                ?>



                        <td><?= $grandTotalQty ?></td>
                        <td><?= formatSecondsToTime($grandTotalRunningHrs) ?></td>
                        <td><?= $grandTotalRunningHrs != 0 ? round(($grandTotalQty / $grandTotalRunningHrs * 3600), 0) : 0 ?>
                        </td>
                        <td><?= formatSecondsToTime(timeToSeconds('200:00:00') * count($displayedMonths) - $grandTotalRunningHrs) ?>
                        </td>
                    </tr>
                    <tr>
                    </tr>
                    <!-- End of Grand Total Row -->

                </tbody>
            </table>
        </div>
        <?php } ?>
    </div>

    <!-- END-->
    <!-- table 2-->

    <div class="card shadow-lg rounded-4 border-0 p-4">
        <h4 class="text-danger">Category Wise Summary Report</h4>
        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table2" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <?php if (empty($type_summary)) { ?>
                    <p class="nodata">No records available.</p>
                    <?php } else { ?>

                    <thead>
                        <tr>
                            <th colspan="1" class="text-center text-white bg-danger">Particulars</th>
                            <?php    $displayedMonths = array(); ?>
                            <?php    foreach ($type_summary as $value): ?>
                            <?php        $monthYear = $value->log_month ?>
                            <?php        if (!in_array($monthYear, $displayedMonths)): ?>
                            <th colspan="4" class="text-center text-white bg-secondary"><?= $monthYear; ?></th>
                            <?php            $displayedMonths[] = $monthYear; ?>
                            <?php        endif; ?>
                            <?php    endforeach; ?>
                            <th colspan="4" class="text-center text-white bg-secondary">Total</th>
                        </tr>
                        <tr>
                            <th class="freeze text-center bg-secondary text-white">Category</th>
                            <?php    foreach ($displayedMonths as $month): ?>
                            <th class="table-primary">Qty</th>
                            <th class="table-primary">Running Hrs</th>
                            <th class="table-primary">OPT P/H(M)</th>
                            <th class="table-primary">Idle Hrs</th>
                            <?php    endforeach; ?>
                            <th class="table-warning">Total Qty</th>
                            <th class="table-warning">Total Running Hrs</th>
                            <th class="table-warning">Total OPT Hrs</th>
                            <th class="table-warning">Total Idle Hrs</th>
                        </tr>
                    </thead>
                <tbody>
                    <?php    $categories = array_unique(array_column($type_summary, 'category')); ?>
                    <?php    $grandTotalQty = $grandTotalRunningHrs = $grandTotalIdleHrs = $grandtotaloptHrs = 0; ?>
                    <?php    $columnTotals = array_fill(0, count($displayedMonths) * 4, 0); ?>

                    <?php    foreach ($categories as $category): ?>
                    <tr>
                        <td class="freeze"><?= $category ?></td>
                        <?php        $totalQty = $totalRunningHrs = $totalIdleHrs = $totaloptHrs = 0; ?>
                        <?php        $colIndex = 0; ?>
                        <?php        foreach ($displayedMonths as $month): ?>
                        <?php            $foundData = false; ?>
                        <?php            foreach ($type_summary as $value): ?>
                        <?php                if ($value->category == $category && $value->log_month == $month): ?>
                        <td><?= $value->total_quantity ?></td>
                        <td><?= formatSecondsToTime(timeToSeconds($value->total_running_hours)) ?></td>
                        <td><?= $value->opt_hrs ?></td>
                        <td><?= formatSecondsToTime(timeToSeconds($value->total_idle_hours)) ?></td>
                        <?php                    /* Update totals for each row */ ?>
                        <?php                    $totalQty += $value->total_quantity; ?>
                        <?php                    $totalRunningHrs += timeToSeconds($value->total_running_hours); ?>
                        <?php                    $totaloptHrs += $value->opt_hrs; ?>
                        <?php                    $totalIdleHrs += timeToSeconds($value->total_idle_hours); ?>
                        <?php                    /* Update column totals */ ?>
                        <?php                    $columnTotals[$colIndex] += $value->total_quantity; ?>
                        <?php                    $columnTotals[$colIndex + 1] += timeToSeconds($value->total_running_hours); ?>
                        <?php                    $columnTotals[$colIndex + 2] = round(($columnTotals[$colIndex] / $columnTotals[$colIndex + 1] * 3600), 0); ?>
                        <?php                    $columnTotals[$colIndex + 3] = (200 * 3600) - $columnTotals[$colIndex + 1]; ?>
                        <?php                    $foundData = true; ?>
                        <?php                endif; ?>
                        <?php            endforeach; ?>
                        <?php            if (!$foundData): ?>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <?php            endif; ?>
                        <?php            $colIndex += 4; ?>
                        <?php        endforeach; ?>
                        <td><?= $totalQty ?></td>
                        <td><?= formatSecondsToTime($totalRunningHrs) ?></td>
                        <td><?php        if ($totalRunningHrs > 0) {
                echo round(($totalQty / $totalRunningHrs * 3600), 0);
            } else {
                echo '0';
            } ?>
                        </td>
                        <td><?= formatSecondsToTime($totalIdleHrs) ?></td>
                    </tr>
                    <?php    endforeach; ?>

                    <!-- Grand Total Row -->
                    <tr class="fw-bold">
                        <td class="freeze">Grand Total</td>
                        <?php
        $colIndex = 0;
        $grandTotalQty = $grandTotalRunningHrs = $grandtotaloptHrs = $grandTotalIdleHrs = 0;

        foreach ($columnTotals as $total) {
            if ($colIndex % 4 == 0) {
                $grandTotalQty += $total;
                        ?>
                        <td><?= $total ?></td>
                        <?php
            }
            if ($colIndex % 4 == 1) {
                $grandTotalRunningHrs += $total;
                        ?>
                        <td><?= formatSecondsToTime($total) ?></td>
                        <?php
            }
            if ($colIndex % 4 == 2) {
                if ($grandTotalRunningHrs != 0) {
                    $grandtotaloptHrs = round(($grandTotalQty / $grandTotalRunningHrs * 3600), 0);
                                   ?>
                        <td><?= $total ?></td>
                        <?php
                } else {
                    echo '0';


                }
            }
            if ($colIndex % 4 == 3) {
                $grandTotalIdleHrs += $total;
                        ?>
                        <td><?= formatSecondsToTime($total) ?></td>
                        <?php
            }
            $colIndex += 1;
        }
                ?>



                        <td><?= $grandTotalQty ?></td>
                        <td><?= formatSecondsToTime($grandTotalRunningHrs) ?></td>
                        <td><?= round(($grandTotalQty / $grandTotalRunningHrs * 3600), 0)  ?></td>
                        <td><?= formatSecondsToTime(timeToSeconds('200:00:00') * count($displayedMonths) - $grandTotalRunningHrs) ?>
                        </td>
                    </tr>
                    <tr>
                    </tr>
                    <!-- End of Grand Total Row -->
                </tbody>
            </table>
        </div>
        <?php } ?>
    </div>
    <!-- end -->

    <!-- prim summary chart -->
    <div class='row'>
        <div class='col-md-6'>
            <div class="card shadow-lg rounded-4 border-0 p-4">
                <p class="nodata fw-bold">Product Wise Chart (Total Running Hours) </p>
                <div class="dash_charrt">
                    <canvas id="proChart"></canvas>
                    <p class="nodata" id="proChart1"></p>
                </div>
            </div>
        </div>

        <div class='col-md-6'>
            <div class="card shadow-lg rounded-4 border-0 p-4">
                <p class="nodata fw-bold">Product Wise Chart (Total Qty)</p>
                <div class="dash_charrt">
                    <canvas id="myChart"></canvas>
                    <p class="nodata" id="myChart1"></p>
                </div>
            </div>
        </div>
        <!-- end -->
    </div>

    <!-- END -->

    <!-- table 3-->
    <div class="card shadow-lg rounded-4 border-0 p-4">
        <h4 class="text-danger">Category Wise Summary Report</h4>
        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table3" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <?php if (empty($product_summary)) { ?>
                    <p class="nodata">No records available.</p>
                    <?php } else { ?>

                    <thead>
                        <tr class="sticky-row">
                            <th colspan="1" class="text-center text-white bg-danger">Particulars</th>
                            <?php    $displayedMonths = array(); ?>
                            <?php    foreach ($product_summary as $value): ?>
                            <?php        $monthYear = $value->log_month; ?>
                            <?php        if (!in_array($monthYear, $displayedMonths)): ?>
                            <th colspan="4" class="text-center text-white bg-secondary"><?= $monthYear; ?></th>
                            <?php            $displayedMonths[] = $monthYear; ?>
                            <?php        endif; ?>
                            <?php    endforeach; ?>
                            <th colspan="4" class="text-center text-white bg-secondary">Total</th>
                        </tr>
                        <tr class="sticky-row2">
                            <th class="freeze text-center text-white bg-secondary">Product Name</th>
                            <?php    foreach ($displayedMonths as $month): ?>
                            <th class="table-primary">Qty</th>
                            <th class="table-primary">Running Hrs</th>
                            <th class="table-primary">OPT P/H(M)</th>
                            <th class="sticky-row2 table-primary">Idle Hrs</th>
                            <?php    endforeach; ?>
                            <th class="table-warning">Total Qty</th>
                            <th class="table-warning">Total Running Hrs</th>
                            <th class="table-warning">Total OPT Hrs</th>
                            <th class="table-warning">Total Idle Hrs</th>
                        </tr>
                    </thead>
                <tbody>
                    <?php    $products = array_unique(array_column($product_summary, 'product_name')); ?>
                    <?php    $grandTotalQty = $grandTotalRunningHrs = $grandTotalIdleHrs = $grandtotaloptHrs = 0; ?>
                    <?php    $columnTotals = array_fill(0, count($displayedMonths) * 4, 0); ?>

                    <?php    foreach ($products as $product): ?>
                    <tr>
                        <td class="freeze"><?= $product ?></td>

                        <?php        $monthlyTotals = array('qty' => 0, 'running_hrs' => 0, 'idle_hrs' => 0, 'opt_hrs' => 0); ?>
                        <?php        $colIndex = 0; ?>
                        <?php        foreach ($displayedMonths as $month): ?>
                        <?php            $foundData = false; ?>
                        <?php            foreach ($product_summary as $value): ?>
                        <?php                if ($value->product_name == $product && $value->log_month == $month): ?>
                        <td><?= $value->total_quantity ?></td>
                        <td><?= formatSecondsToTime(timeToSeconds($value->total_running_hours)) ?></td>
                        <td><?= $value->opt_hrs ?></td>
                        <td><?= formatSecondsToTime(timeToSeconds($value->total_idle_hours)) ?></td>
                        <?php                    /* Update monthly totals */ ?>
                        <?php                    $monthlyTotals['qty'] += $value->total_quantity; ?>
                        <?php                    $monthlyTotals['running_hrs'] += timeToSeconds($value->total_running_hours); ?>
                        <?php                    $monthlyTotals['opt_hrs'] += $value->opt_hrs; ?>
                        <?php                    $monthlyTotals['idle_hrs'] += timeToSeconds($value->total_idle_hours); ?>
                        <?php                    /* Update column totals */ ?>
                        <?php                    $columnTotals[$colIndex] += $value->total_quantity; ?>
                        <?php                    $columnTotals[$colIndex + 1] += timeToSeconds($value->total_running_hours); ?>
                        <?php                    $columnTotals[$colIndex + 2] = round(($columnTotals[$colIndex] / $columnTotals[$colIndex + 1] * 3600), 0); ?>
                        <?php                    $columnTotals[$colIndex + 3] = (200 * 3600) - $columnTotals[$colIndex + 1]; ?>
                        <?php                    $foundData = true; ?>
                        <?php                endif; ?>
                        <?php            endforeach; ?>
                        <?php            if (!$foundData): ?>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <?php            endif; ?>
                        <?php            $colIndex += 4; ?>
                        <?php        endforeach; ?>
                        <!-- Monthly Total -->
                        <td><?= $monthlyTotals['qty'] ?></td>
                        <td><?=formatSecondsToTime($monthlyTotals['running_hrs']) ?></td>
                        <td><?php        if ($monthlyTotals['running_hrs'] > 0) {
                echo round(($monthlyTotals['qty'] / $monthlyTotals['running_hrs'] * 3600), 0);
            } else {
                echo '0';
            } ?>
                        </td>
                        <td><?= formatSecondsToTime($monthlyTotals['idle_hrs']) ?></td>
                    </tr>
                    <?php    endforeach; ?>

                    <!-- Grand Total Row -->
                    <tr class="fw-bold">
                        <td class="freeze">Grand Total</td>
                        <?php
        $colIndex = 0;
        $grandTotalQty = $grandTotalRunningHrs = $grandtotaloptHrs = $grandTotalIdleHrs = 0;

        foreach ($columnTotals as $total) {
            if ($colIndex % 4 == 0) {
                $grandTotalQty += $total;
                        ?>
                        <td><?= $total ?></td>
                        <?php
            }
            if ($colIndex % 4 == 1) {
                $grandTotalRunningHrs += $total;
                        ?>
                        <td><?= formatSecondsToTime($total) ?></td>
                        <?php
            }
            if ($colIndex % 4 == 2) {
                if ($grandTotalRunningHrs != 0) {
                    $grandtotaloptHrs = round(($grandTotalQty / $grandTotalRunningHrs * 3600), 0);
                                   ?>
                        <td><?= $total ?></td>
                        <?php
                } else {
                    echo '0';


                }
            }
            if ($colIndex % 4 == 3) {
                $grandTotalIdleHrs += $total;
                        ?>
                        <td><?= formatSecondsToTime($total) ?></td>
                        <?php
            }
            $colIndex += 1;
        }
                ?>



                        <td><?= $grandTotalQty ?></td>
                        <td><?= formatSecondsToTime($grandTotalRunningHrs) ?></td>
                        <td><?= round(($grandTotalQty / $grandTotalRunningHrs * 3600), 0)  ?></td>
                        <td><?= formatSecondsToTime(timeToSeconds('200:00:00') * count($displayedMonths) - $grandTotalRunningHrs) ?>
                        </td>
                    </tr>
                    <tr>
                    </tr>
                    <!-- End of Grand Total Row -->

                </tbody>
            </table>
        </div>
        <?php } ?>
    </div>

    <!-- end -->

    <!-- table 4-->
    <div class="card shadow-lg rounded-4 border-0 p-4">
        <h4 class="text-danger">Machine Wise Summary Report</h4>
        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table4" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <?php if (empty($machine_summary)) { ?>
                    <p class="nodata">No records available.</p>
                    <?php } else { ?>

                    <thead>
                        <tr class="sticky-row">
                            <th colspan="1" class="text-center text-white bg-danger">Particulars</th>
                            <?php    $displayedMonths = array(); ?>
                            <?php    foreach ($machine_summary as $value): ?>
                            <?php        $monthYear = $value->log_month; ?>
                            <?php        if (!in_array($monthYear, $displayedMonths)): ?>
                            <th colspan="4" class="text-center text-white bg-secondary"><?= $monthYear; ?></th>
                            <?php            $displayedMonths[] = $monthYear; ?>
                            <?php        endif; ?>
                            <?php    endforeach; ?>
                            <th colspan="4" class="text-center text-white bg-secondary">Total</th>
                        </tr>
                        <tr class="sticky-row2">
                            <th class="text-center text-white bg-secondary freeze">Machine Name</th>
                            <?php    foreach ($displayedMonths as $month): ?>
                            <th class="table-primary">Qty</th>
                            <th class="table-primary">Running Hrs</th>
                            <th class="table-primary">OPT P/H(M)</th>
                            <th class="sticky-row2 table-primary">Idle Hrs</th>
                            <?php    endforeach; ?>
                            <th class="table-warning">Total Qty</th>
                            <th class="table-warning">Total Running Hrs</th>
                            <th class="table-warning">Total OPT Hrs</th>
                            <th class="table-warning">Total Idle Hrs</th>
                        </tr>
                    </thead>
                <tbody>
                    <?php    $products = array_unique(array_column($machine_summary, 'machine_name')); ?>
                    <?php    $grandTotalQty = $grandTotalRunningHrs = $grandTotalIdleHrs = $grandtotaloptHrs = 0; ?>
                    <?php    $columnTotals = array_fill(0, count($displayedMonths) * 4, 0); ?>

                    <?php    foreach ($products as $product): ?>
                    <tr>
                        <td class="freeze"><?= $product ?></td>

                        <?php        $monthlyTotals = array('qty' => 0, 'running_hrs' => 0, 'idle_hrs' => 0, 'opt_hrs' => 0); ?>
                        <?php        $colIndex = 0; ?>
                        <?php        foreach ($displayedMonths as $month): ?>
                        <?php            $foundData = false; ?>
                        <?php            foreach ($machine_summary as $value): ?>
                        <?php                if ($value->machine_name == $product && $value->log_month == $month): ?>
                        <td><?= $value->total_quantity ?></td>
                        <td><?= formatSecondsToTime(timeToSeconds($value->total_running_hours)) ?></td>
                        <td><?= $value->opt_hrs ?></td>
                        <td><?= formatSecondsToTime(timeToSeconds($value->total_idle_hours)) ?></td>
                        <?php                    /* Update totals for each row */ ?>
                        <?php                    $monthlyTotals['qty'] += $value->total_quantity; ?>
                        <?php                    $monthlyTotals['running_hrs'] += timeToSeconds($value->total_running_hours); ?>
                        <?php                    $monthlyTotals['opt_hrs'] += $value->opt_hrs; ?>
                        <?php                    $monthlyTotals['idle_hrs'] += timeToSeconds($value->total_idle_hours); ?>
                        <?php                    /* Update column totals */ ?>
                        <?php                    $columnTotals[$colIndex] += $value->total_quantity; ?>
                        <?php                    $columnTotals[$colIndex + 1] += timeToSeconds($value->total_running_hours); ?>
                        <?php                    $columnTotals[$colIndex + 2] = round(($columnTotals[$colIndex] / $columnTotals[$colIndex + 1] * 3600), 0); ?>
                        <?php                    $columnTotals[$colIndex + 3] = (200 * 3600) - $columnTotals[$colIndex + 1]; ?>
                        <?php                    $foundData = true; ?>
                        <?php                endif; ?>
                        <?php            endforeach; ?>
                        <?php            if (!$foundData): ?>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <?php            endif; ?>
                        <?php            $colIndex += 4; ?>
                        <?php        endforeach; ?>
                        <!-- Monthly Total -->
                        <td><?= $monthlyTotals['qty'] ?></td>
                        <td><?=formatSecondsToTime($monthlyTotals['running_hrs']) ?></td>
                        <td><?php        if ($monthlyTotals['running_hrs'] != 0) {
                echo round(($monthlyTotals['qty'] / $monthlyTotals['running_hrs'] * 3600), 0);
            } else {
                echo '0';
            } ?>
                        </td>
                        <td><?= formatSecondsToTime($monthlyTotals['idle_hrs']) ?></td>
                    </tr>
                    <?php    endforeach; ?>

                    <!-- Grand Total Row -->
                    <tr class="fw-bold">
                        <td class="freeze">Grand Total</td>
                        <?php
        $colIndex = 0;
        $grandTotalQty = $grandTotalRunningHrs = $grandtotaloptHrs = $grandTotalIdleHrs = 0;

        foreach ($columnTotals as $total) {
            if ($colIndex % 4 == 0) {
                $grandTotalQty += $total;
                        ?>
                        <td><?= $total ?></td>
                        <?php
            }
            if ($colIndex % 4 == 1) {
                $grandTotalRunningHrs += $total;
                        ?>
                        <td><?= formatSecondsToTime($total) ?></td>
                        <?php
            }
            if ($colIndex % 4 == 2) {
                if ($grandTotalRunningHrs != 0) {
                    $grandtotaloptHrs = round(($grandTotalQty / $grandTotalRunningHrs * 3600), 0);
                                   ?>
                        <td><?= $total ?></td>
                        <?php
                } else {
                    echo '0';


                }
            }
            if ($colIndex % 4 == 3) {
                $grandTotalIdleHrs += $total;
                        ?>
                        <td><?= formatSecondsToTime($total) ?></td>
                        <?php
            }
            $colIndex += 1;
        }
                ?>



                        <td><?= $grandTotalQty ?></td>
                        <td><?= formatSecondsToTime($grandTotalRunningHrs) ?></td>
                        <td><?= round(($grandTotalQty / $grandTotalRunningHrs * 3600), 0)  ?></td>
                        <td><?= formatSecondsToTime(timeToSeconds('200:00:00') * count($displayedMonths) - $grandTotalRunningHrs) ?>
                        </td>
                    </tr>
                    <tr>
                    </tr>
                    <!-- End of Grand Total Row -->

                </tbody>
            </table>
        </div>
        <?php } ?>
    </div>
    <!-- machine wise summary chart -->
    <div class="card shadow-lg rounded-4 border-0 p-4">
        <p class="nodata fw-bold">Machine Wise Chart (Total Running Hours) </p>
        <div class="dash_charrt">
            <canvas id="machineChart" style="max-height:460px"></canvas>
            <p class="nodata" id="machineChart1"></p>
        </div>
    </div>
    <!-- end -->

    <!--  table 5-->
    <div class="card shadow-lg rounded-4 border-0 p-4">
        <h4 class="text-danger">Pack Wise Summary Report</h4>
        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table5" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <?php if (empty($unit_summary)) { ?>
                    <p class="nodata">No records available.</p>
                    <?php } else { ?>

                    <thead>
                        <tr class="sticky-row">
                            <th colspan="1" class="text-center text-white bg-danger">Particulars</th>
                            <?php    $displayedMonths = array(); ?>
                            <?php    foreach ($unit_summary as $value): ?>
                            <?php        $monthYear = $value->log_month; ?>
                            <?php        if (!in_array($monthYear, $displayedMonths)): ?>
                            <th colspan="4" class="text-center text-white bg-secondary"><?= $monthYear; ?></th>
                            <?php            $displayedMonths[] = $monthYear; ?>
                            <?php        endif; ?>
                            <?php    endforeach; ?>
                            <th colspan="4" class="text-center text-white bg-secondary">Total</th>
                        </tr>
                        <tr class="sticky-row2">
                            <th class="text-center text-white bg-secondary freeze">Pack</th>
                            <?php    foreach ($displayedMonths as $month): ?>
                            <th class="table-primary">Qty</th>
                            <th class="table-primary">Running Hrs</th>
                            <th class="table-primary">OPT P/H(M)</th>
                            <th class="sticky-row2 table-primary">Idle Hrs</th>
                            <?php    endforeach; ?>
                            <th class="table-warning">Total Qty</th>
                            <th class="table-warning">Total Running Hrs</th>
                            <th class="table-warning">Total OPT Hrs</th>
                            <th class="table-warning">Total Idle Hrs</th>
                        </tr>
                    </thead>
                <tbody>
                    <?php    $products = array_unique(array_column($unit_summary, 'product_name')); ?>
                    <?php    $grandTotalQty = $grandTotalRunningHrs = $grandTotalIdleHrs = $grandtotaloptHrs = 0; ?>
                    <?php    $columnTotals = array_fill(0, count($displayedMonths) * 4, 0); ?>

                    <?php    foreach ($products as $product): ?>
                    <tr>
                        <td class="freeze"><?= $product ?></td>

                        <?php        $monthlyTotals = array('qty' => 0, 'running_hrs' => 0, 'idle_hrs' => 0, 'opt_hrs' => 0); ?>
                        <?php        $colIndex = 0; ?>
                        <?php        foreach ($displayedMonths as $month): ?>
                        <?php            $foundData = false; ?>
                        <?php            foreach ($unit_summary as $value): ?>
                        <?php                if ($value->product_name == $product && $value->log_month == $month): ?>
                        <td><?= $value->total_quantity ?></td>
                        <td><?= formatSecondsToTime(timeToSeconds($value->total_running_hours)) ?></td>
                        <td><?= $value->opt_hrs ?></td>
                        <td><?= formatSecondsToTime(timeToSeconds($value->total_idle_hours)) ?></td>
                        <?php                    /* Update totals for each row */ ?>
                        <?php                    $monthlyTotals['qty'] += $value->total_quantity; ?>
                        <?php                    $monthlyTotals['running_hrs'] += timeToSeconds($value->total_running_hours); ?>
                        <?php                    $monthlyTotals['opt_hrs'] += $value->opt_hrs; ?>
                        <?php                    $monthlyTotals['idle_hrs'] += timeToSeconds($value->total_idle_hours); ?>
                        <?php                    /* Update column totals */ ?>
                        <?php                    $columnTotals[$colIndex] += $value->total_quantity; ?>
                        <?php                    $columnTotals[$colIndex + 1] += timeToSeconds($value->total_running_hours); ?>
                        <?php                    $columnTotals[$colIndex + 2] = round(($columnTotals[$colIndex] / $columnTotals[$colIndex + 1] * 3600), 0); ?>
                        <?php                    $columnTotals[$colIndex + 3] = (200 * 3600) - $columnTotals[$colIndex + 1]; ?>
                        <?php                    $foundData = true; ?>
                        <?php                endif; ?>
                        <?php            endforeach; ?>
                        <?php            if (!$foundData): ?>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <?php            endif; ?>
                        <?php            $colIndex += 4; ?>
                        <?php        endforeach; ?>
                        <!-- Monthly Total -->
                        <td><?= $monthlyTotals['qty'] ?></td>
                        <td><?=formatSecondsToTime($monthlyTotals['running_hrs']) ?></td>
                        <td><?php        if ($monthlyTotals['running_hrs'] > 0) {
                echo round(($monthlyTotals['qty'] / $monthlyTotals['running_hrs'] * 3600), 0);
            } else {
                echo '0';
            } ?>
                        </td>
                        <td><?= formatSecondsToTime($monthlyTotals['idle_hrs']) ?></td>
                    </tr>
                    <?php    endforeach; ?>
                    <!-- Grand Total Row -->
                    <tr class="fw-bold">
                        <td class="freeze">Grand Total</td>
                        <?php
        $colIndex = 0;
        $grandTotalQty = $grandTotalRunningHrs = $grandtotaloptHrs = $grandTotalIdleHrs = 0;

        foreach ($columnTotals as $total) {
            if ($colIndex % 4 == 0) {
                $grandTotalQty += $total;
                        ?>
                        <td><?= $total ?></td>
                        <?php
            }
            if ($colIndex % 4 == 1) {
                $grandTotalRunningHrs += $total;
                        ?>
                        <td><?= formatSecondsToTime($total) ?></td>
                        <?php
            }
            if ($colIndex % 4 == 2) {
                if ($grandTotalRunningHrs != 0) {
                    $grandtotaloptHrs = round(($grandTotalQty / $grandTotalRunningHrs * 3600), 0);
                                   ?>
                        <td><?= $total ?></td>
                        <?php
                } else {
                    echo '0';


                }
            }
            if ($colIndex % 4 == 3) {
                $grandTotalIdleHrs += $total;
                        ?>
                        <td><?= formatSecondsToTime($total) ?></td>
                        <?php
            }
            $colIndex += 1;
        }
                ?>

                        <td><?= $grandTotalQty ?></td>
                        <td><?= formatSecondsToTime($grandTotalRunningHrs) ?></td>
                        <td> <?= $grandTotalRunningHrs != 0 ? round(($grandTotalQty / $grandTotalRunningHrs * 3600), 0) : 0 ?>
                        </td>
                        <td><?= formatSecondsToTime(timeToSeconds('200:00:00') * count($displayedMonths) - $grandTotalRunningHrs) ?>
                        </td>
                    </tr>
                    <tr>
                    </tr>
                    <!-- End of Grand Total Row -->

                </tbody>
            </table>
        </div>
        <?php } ?>
    </div>
    <!-- end -->
    <!-- unit  wise summary chart -->
    <div class='row'>
        <div class='col-md-6'>
            <div class="card shadow-lg rounded-4 border-0 p-4">
                <p class="nodata fw-bold">Pack Wise Chart Report (Total Running Hours) </p>
                <div class="dash_charrt">
                    <canvas id="unitChart"></canvas>
                    <p class="nodata" id="unitChart1"></p>
                </div>
            </div>
        </div>
        <div class='col-md-6'>
            <div class="card shadow-lg rounded-4 border-0 p-4">
                <p class="nodata fw-bold">Pack Wise Chart Report (Total Qty)</p>
                <div class="dash_charrt">
                    <canvas id="uChart"></canvas>
                    <p class="nodata" id="uChart1"></p>
                </div>
            </div>
        </div>
    </div>
    <!-- end -->

@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    <script>

        // ON Change
        function loadDropdown($element, url, selected = "") {

            $.ajax({
                url: url,
                type: "GET",
                success: function (response) {

                    let data = response;

                    if (typeof data === "string") {
                        try { data = JSON.parse(data); }
                        catch (e) { console.error("Invalid JSON:", response); return; }
                    }

                    $element.empty().append('<option value="">-- Select --</option>');

                    $.each(data, function (i, item) {
                        let sel = (item.val == selected) ? "selected" : "";
                        $element.append(`<option value="${item.val}" ${sel}>${item.option_name}</option>`);
                    });

                    $element.trigger("change.select2");
                }
            });
        }

        $(document).on('change', '.product_type', function () {

            let prdgroup = $('.product_type').val();

            if (prdgroup !== "") {

                let url = "{{ URL::to('jcomboproduct?table=m_product_variants_t') }}" +
                    "&type_id=" + prdgroup;

                loadDropdown($(".product_varient"), url, "");
            }
        });

        $(document).on('change', '.product_varient', function () {

            let prdgroup = $('.product_type').val();
            let var_id = $('.product_varient').val();

            if (prdgroup !== "") {

                let url = "{{ URL::to('jcomboproductvar?table=i_product_packs') }}" +
                    "&type_id=" + prdgroup +
                    "&var_id=" + var_id;

                loadDropdown($(".unit"), url, "");
            }
        });

        $(document).on('change', '.unit', function () {

            let prdgroup = $('.product_type').val();
            let var_id = $('.product_varient').val();
            let pack_id = $('.unit').val();

            if (var_id !== "") {

                let url = "{{ URL::to('jcomboproductunit?table=m_products_t') }}" +
                    "&type_id=" + prdgroup +
                    "&var_id=" + var_id +
                    "&pack_id=" + pack_id;

                loadDropdown($(".product_id"), url, "");

                $("#unit").prop('disabled', false);
            }
        });

        $(document).on('change', '.product_type', function () {

            let prdgroup = $('.product_type').val();

            if (prdgroup !== "") {

                let url = "{{ URL::to('jcomboproductname?table=m_products_t') }}" +
                    "&type_id=" + prdgroup;

                loadDropdown($(".product_id"), url, "");
            }
        });

        $(document).on('change', '.product_varient', function () {

            let prdgroup = $('.product_type').val();
            let var_id = $('.product_varient').val();

            if (prdgroup !== "") {

                let url = "{{ URL::to('jcomboprodt?table=m_products_t') }}" +
                    "&type_id=" + prdgroup +
                    "&var_id=" + var_id;

                loadDropdown($(".product_id"), url, "");
            }
        });



        $(document).ready(function () {
            var proType = "{{ request('product_type') }}";
            var proVariet = "{{ request('product_varient') }}";
            var proUnit = "{{ request('unit') }}";
            var proName = "{{ request('product_id') }}";
            var startDate = "{{ request('start_date') }}";
            var endDate = "{{ request('end_date') }}";

            $('.product_type').select2();
            $('#product_type').val(proType).trigger('change');

            $('.product_varient').select2();
            $('#product_varient').val(proVariet).trigger('change');

            $('.unit').select2();
            $('#unit').val(proUnit).trigger('change');

            $('.product_id').select2();
            $('#product_id').val(proName).trigger('change');

            $('#start_date').val(startDate);
            $('#end_date').val(endDate);

        });



        // color generator

        function generateRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }
        // end

        // Chart
        const chartData = {!! $prim_sumchart !!};

        if (chartData.length == 0) {
            document.getElementById("typeChart1").innerHTML = "No Data available.";
        } else {
            var names = chartData.map(item => item.name);
            var uniqueNames = [...new Set(names)];

            var months = chartData.map(item => item.month);
            var uniqueMonths = [...new Set(months)];

            var barColors = uniqueNames.map(name => generateRandomColor());

            new Chart("typeChart", {
                type: "bar", // Change this to "bar" for a bar chart or "column" for a column chart
                data: {
                    labels: uniqueMonths,
                    datasets: uniqueNames.map((name, index) => ({
                        label: name,
                        backgroundColor: barColors[index],
                        data: uniqueMonths.map(month => {
                            var dataPoint = chartData.find(item => item.name === name && item.month === month);
                            return dataPoint ? dataPoint.value : 0;
                        })
                    }))
                },
                options: {
                    "hover": {
                        "animationDuration": 0
                    },
                    "animation": {
                        "duration": 1,
                        "onComplete": function () {
                            var chartInstance = this.chart,
                                ctx = chartInstance.ctx;

                            ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultColor, '#000 ' + Chart.defaults.global.defaultFontSize, 'bold ' + Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'bottom';
                            ctx.fillStyle = 'black';
                            this.data.datasets.forEach(function (dataset, i) {
                                var meta = chartInstance.controller.getDatasetMeta(i);
                                var offset = 5; // Adjust this value as needed

                                meta.data.forEach(function (bar, index) {
                                    var data = dataset.data[index];
                                    var yPos = bar._model.y - offset;

                                    // Check if the value will overlap with the previous bar
                                    if (index > 0 && yPos < meta.data[index - 1]._model.y) {
                                        yPos = meta.data[index - 1]._model.y + offset;
                                    }

                                    ctx.fillText(data, bar._model.x, yPos);
                                });
                            });
                        }
                    },
                    legend: {
                        display: true
                    },
                    title: {
                        display: true,
                        text: "Total Running Hours"
                    },
                    scales: {
                        xAxes: [{
                            stacked: true // Change this to false for a column chart
                        }],
                        yAxes: [{
                            stacked: true // Change this to false for a column chart
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            label: function (tooltipItem, data) {
                                var dataset = data.datasets[tooltipItem.datasetIndex];
                                var currentValue = dataset.data[tooltipItem.index];
                                return dataset.label + ": " + currentValue + "Hrs";
                            }
                        }
                    }
                }
            });
        }


        //category chart

        const primchartData = {!! $type_sumchart !!};

        if (primchartData.length == 0) {
            document.getElementById("primChart1").innerHTML = "No Data available.";
        } else {
            var variant = primchartData.map(item => item.name);
            var yValues = primchartData.map(item => item.value);
            var months = primchartData.map(item => item.month);
            var barColors = Array.from({ length: months.length }, () => generateRandomColor());

            new Chart("primChart", {
                type: "bar",
                data: {
                    labels: months,
                    datasets: [
                        {
                            label: "Total Running Hours",
                            backgroundColor: barColors,
                            data: yValues
                        },

                    ]
                },
                options: {
                    "hover": {
                        "animationDuration": 0
                    },
                    "animation": {
                        "duration": 1,
                        "onComplete": function () {
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
                                    ctx.fillText(data, bar._model.x, bar._model.y - 5);
                                });
                            });
                        }
                    },
                    legend: {
                        display: true
                    },
                    title: {
                        display: true,
                        text: "Total Running Hours"
                    },
                    tooltips: {
                        callbacks: {
                            label: function (tooltipItem, data) {
                                var dataset = data.datasets[tooltipItem.datasetIndex];
                                var total = dataset.data.reduce(function (previousValue, currentValue) {
                                    return previousValue + currentValue;
                                });
                                var currentValue = dataset.data[tooltipItem.index];
                                return variant[tooltipItem.index] + ": " + currentValue + "Hrs";
                            }
                        }
                    }
                }
            });
        }

        //end

        // Product Summary chart
        const productData = {!! $product_sumchart !!};

        if (productData.length == 0) {
            document.getElementById("proChart1").innerHTML = "No Data available.";
        } else {
            const uniqueMonths = [...new Set(productData.map(item => item.month))];
            const uniqueProducts = [...new Set(productData.map(item => item.name))];

            var datasets = uniqueProducts.map((product, index) => {
                var productValues = productData
                    .filter(item => item.name === product)
                    .map(item => item.value);

                var barColor = generateRandomColor();

                return {
                    label: product,
                    backgroundColor: barColor,
                    data: uniqueMonths.map(month => {
                        var dataPoint = productData.find(item => item.month === month && item.name === product);
                        return dataPoint ? dataPoint.value : 0;
                    })
                };
            });

            new Chart("proChart", {
                type: "bar",
                data: {
                    labels: uniqueMonths,
                    datasets: datasets
                },
                options: {

                    legend: {
                        display: true
                    },
                    title: {
                        display: true,
                        text: "Total Running Hours"
                    },
                    tooltips: {
                        callbacks: {
                            label: function (tooltipItem, data) {
                                var dataset = data.datasets[tooltipItem.datasetIndex];
                                var currentValue = dataset.data[tooltipItem.index];
                                return dataset.label + ": " + currentValue + "Hrs";
                            }
                        }
                    }
                }
            });
        }

        //end
        // product line chart

        const lineData = {!! $product_linechart !!};

        if (lineData.length == 0) {
            document.getElementById("proChart1").innerHTML = "No Data available.";
        } else {
            const uniqueMonths = [...new Set(lineData.map(item => item.month))];
            const uniqueProducts = [...new Set(lineData.map(item => item.name))];

            var datasets = uniqueProducts.map((product, index) => {
                var productValues = lineData
                    .filter(item => item.name === product)
                    .map(item => item.value);

                var barColor = generateRandomColor();

                return {
                    label: product,
                    backgroundColor: barColor,
                    data: uniqueMonths.map(month => {
                        var dataPoint = lineData.find(item => item.month === month && item.name === product);
                        return dataPoint ? dataPoint.value : 0;
                    })
                };
            });

            new Chart("myChart", {
                type: "bar",
                data: {
                    labels: uniqueMonths,
                    datasets: datasets
                },
                options: {

                    legend: {
                        display: true
                    },
                    title: {
                        display: true,
                        text: "Total Qty"
                    },
                    tooltips: {
                        callbacks: {
                            label: function (tooltipItem, data) {
                                var dataset = data.datasets[tooltipItem.datasetIndex];
                                var currentValue = dataset.data[tooltipItem.index];
                                return dataset.label + ": " + currentValue;
                            }
                        }
                    }
                }
            });
        }


        // machine wise chart

        const breakData = {!! $machine_sumchart !!};

        if (breakData.length == 0) {
            document.getElementById("machineChart1").innerHTML = "No Data available.";
        } else {
            const uniqueMonths = [...new Set(breakData.map(item => item.month))];
            const uniqueMachines = [...new Set(breakData.map(item => item.name))];

            var datasets = uniqueMachines.map((machine, index) => {
                var machineValues = breakData
                    .filter(item => item.name === machine)
                    .map(item => item.value);

                var barColor = generateRandomColor();

                return {
                    label: machine,
                    backgroundColor: barColor,
                    data: uniqueMonths.map(month => {
                        var dataPoint = breakData.find(item => item.month === month && item.name === machine);
                        return dataPoint ? dataPoint.value : 0;
                    })
                };
            });

            new Chart("machineChart", {
                type: "bar",
                data: {
                    labels: uniqueMonths,
                    datasets: datasets
                },
                options: {

                    legend: {
                        display: true
                    },
                    title: {
                        display: true,
                        text: "Total Running Hours"
                    },
                    tooltips: {
                        callbacks: {
                            label: function (tooltipItem, data) {
                                var dataset = data.datasets[tooltipItem.datasetIndex];
                                var currentValue = dataset.data[tooltipItem.index];
                                return dataset.label + ": " + currentValue + "Hrs";
                            }
                        }
                    }
                }
            });
        }

        //end
        // unit wise chart  total running hrs
        const prevenData = {!! $unit_chart !!};

        if (prevenData.length == 0) {
            document.getElementById("unitChart1").innerHTML = "No Data available.";
        } else {
            var names = prevenData.map(item => item.name);
            var uniqueNames = [...new Set(names)];

            var months = prevenData.map(item => item.month);
            var uniqueMonths = [...new Set(months)];

            var barColors = uniqueNames.map(name => generateRandomColor());

            new Chart("unitChart", {
                type: "bar", // Change this to "bar" for a bar chart or "column" for a column chart
                data: {
                    labels: uniqueMonths,
                    datasets: uniqueNames.map((name, index) => ({
                        label: name,
                        backgroundColor: barColors[index],
                        data: uniqueMonths.map(month => {
                            var dataPoint = prevenData.find(item => item.name === name && item.month === month);
                            return dataPoint ? dataPoint.value : 0;
                        })
                    }))
                },
                options: {

                    legend: {
                        display: true
                    },
                    title: {
                        display: true,
                        text: "Total Running Hours"
                    },
                    scales: {
                        xAxes: [{
                            stacked: true // Change this to false for a column chart
                        }],
                        yAxes: [{
                            stacked: true // Change this to false for a column chart
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            label: function (tooltipItem, data) {
                                var dataset = data.datasets[tooltipItem.datasetIndex];
                                var currentValue = dataset.data[tooltipItem.index];
                                return dataset.label + ": " + currentValue + "Hrs";
                            }
                        }
                    }
                }
            });
        }

        const UnitData = {!! $unit_chart !!};

        if (UnitData.length == 0) {
            document.getElementById("uChart1").innerHTML = "No Data available.";
        } else {
            var names = UnitData.map(item => item.name);
            var uniqueNames = [...new Set(names)];

            var months = UnitData.map(item => item.month);
            var uniqueMonths = [...new Set(months)];

            var barColors = uniqueNames.map(name => generateRandomColor());

            new Chart("uChart", {
                type: "bar", // Change this to "bar" for a bar chart or "column" for a column chart
                data: {
                    labels: uniqueMonths,
                    datasets: uniqueNames.map((name, index) => ({
                        label: name,
                        backgroundColor: barColors[index],
                        data: uniqueMonths.map(month => {
                            var dataPoint = UnitData.find(item => item.name === name && item.month === month);
                            return dataPoint ? dataPoint.qty : 0;
                        })
                    }))
                },
                options: {

                    legend: {
                        display: true
                    },
                    title: {
                        display: true,
                        text: "Total Qty"
                    },
                    scales: {
                        xAxes: [{
                            stacked: true // Change this to false for a column chart
                        }],
                        yAxes: [{
                            stacked: true // Change this to false for a column chart
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            label: function (tooltipItem, data) {
                                var dataset = data.datasets[tooltipItem.datasetIndex];
                                var currentValue = dataset.data[tooltipItem.index];
                                return dataset.label + ": " + currentValue + "Qty";
                            }
                        }
                    }
                }
            });
        }

    </script>

@endpush