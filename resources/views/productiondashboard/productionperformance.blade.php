@extends('layouts.header')
@section('content')

    <h3 class="text-danger">Overall Employee Report</h3>

    <div class="container mt-4">
        <div class="row g-3 mb-3">
            <div class="col-12 col-md-3">
                <a href="productiondashboard" class="btn btn-outline-success w-100">Analyse Report</a>
            </div>
            <div class="col-12 col-md-3">
                <a href="overallprodctionperformancereport" class="btn btn-outline-primary w-100">Overall Employee
                    Report</a>
            </div>
            <div class="col-12 col-md-3">
                <a href="productionformancelogreport" class="btn btn-warning w-100">Employee Performance Report</a>
            </div>
            <div class="col-12 col-md-3">
                <a href="productionproductdashboard" class="btn btn-outline-info w-100">Product Based Report</a>
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
        <form action="{{ url('productionformancelogreport') }}" method="get" id="searchForm">
            <div class="row g-4">

                <!-- Supplier Name -->
                <div class="col-md-4">
                    <label for="emp_name" class="form-label fw-semibold">Employee Name</label>
                    <select name="emp_name" id="emp_name" class="form-select select2" required>
                        {!! $employee_name !!}
                    </select>
                </div>

                <!-- From Date -->
                <div class="col-md-4">
                    <label for="start_date" class="form-label fw-semibold">From Date</label>
                    <input type="text" class="form-control start_date1" id="start_date" name="start_date" required
                        autocomplete="off" placeholder="YYYY-MM-DD">
                </div>

                <!-- To Date -->
                <div class="col-md-4">
                    <label for="end_date" class="form-label fw-semibold">To Date</label>
                    <input type="text" class="form-control end_date1" id="end_date" name="end_date" required
                        autocomplete="off" placeholder="YYYY-MM-DD">
                </div>

                <!-- Search Button -->
                <div class="col-12 text-center mt-3">
                    <button type="submit" class="btn btn-primary px-5">Search</button>
                </div>

            </div>
        </form>
    </div>


    <!-- table 2-->
    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="col-md-12">
            <h5 class="text-primary text-center">Employee Product Type Wise Work Hours Report</h5>
        </div>
        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table2" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <?php if (empty($product_summary)) { ?>
                    <p class="nodata">No records available.</p>
                    <?php } else { ?>
                    <tr class="sticky-row">
                        <th colspan="2" class="text-center text-white bg-danger">Yr_Month</th>
                        <?php    $displayedMonths = array(); ?>
                        <?php    foreach ($product_summary as $value): ?>
                        <?php        $monthYear = $value->yr_month ?>
                        <?php        if (!in_array($monthYear, $displayedMonths)): ?>
                        <th colspan="2" class="text-center text-white bg-secondary"><?= $monthYear; ?></th>
                        <?php            $displayedMonths[] = $monthYear; ?>
                        <?php        endif; ?>
                        <?php    endforeach; ?>
                        <th colspan="4" class="text-center text-white bg-secondary">Total</th>
                    </tr>
                    <tr class="sticky-row2">
                        <th class="sticky-col">Pro Type</th>
                        <th class="sticky-col">Type</th>
                        <?php    foreach ($displayedMonths as $month): ?>
                        <th>Work Hrs</th>
                        <th>Idle Hrs</th>
                        <?php    endforeach; ?>
                        <th>Total Work Hrs</th>
                        <th>Total Idle Hrs</th>
                    </tr>
                </thead>
                <tbody>
                    <?php    $categories = array_unique(array_column($product_summary, 'prd_type')); ?>
                    <?php    $types = array_unique(array_column($product_summary, 'type')); ?>
                    <?php    $grandTotalRunningHrs = $grandTotalIdleHrs = 0; ?>
                    <?php    $columnTotals = array_fill(0, count($displayedMonths) * 2, 0); ?>

                    <?php    foreach ($categories as $category) { ?>
                    <?php        $typesWithCategoryData = array_unique(array_column(array_filter($product_summary, function ($value) use ($category) {
                return $value->prd_type == $category;
            }), 'type')); ?>
                    <?php        foreach ($typesWithCategoryData as $type) { ?>
                    <tr>
                        <td style="text-decoration: underline;cursor: pointer;" class="sticky-col Prowise"><?= $category ?>
                        </td>
                        <td class="sticky-col-2 protype"><?= $type ?></td>
                        <?php            $totalRunningHrs = $totalIdleHrs = 0; ?>
                        <?php            $colIndex = 0; ?>
                        <?php            foreach ($displayedMonths as $month) { ?>
                        <?php                $foundData = false; ?>
                        <?php                foreach ($product_summary as $value) { ?>
                        <?php                    if (
                            $value->prd_type == $category &&
                            $value->type == $type &&
                            $value->yr_month == $month
                        ) { ?>
                        <td><?= formatSecondsToTime(timeToSeconds($value->wrk_hrs)) ?></td>
                        <td><?= formatSecondsToTime(timeToSeconds($value->idle_hours)) ?></td>
                        <?php                        /* Update totals for each row */ ?>
                        <?php                        $totalRunningHrs += timeToSeconds($value->wrk_hrs); ?>
                        <?php                        $totalIdleHrs += timeToSeconds($value->idle_hours); ?>
                        <?php                        /* Update column totals */ ?>
                        <?php                        $columnTotals[$colIndex] += timeToSeconds($value->wrk_hrs); ?>
                        <?php                        $columnTotals[$colIndex + 1] += timeToSeconds($value->idle_hours); ?>
                        <?php                        $foundData = true; ?>
                        <?php                    } ?>
                        <?php                } ?>
                        <?php                if (!$foundData) { ?>
                        <td>-</td>
                        <td>-</td>
                        <?php                } ?>
                        <?php                $colIndex += 2; ?>
                        <?php            } ?>
                        <!-- row Total -->
                        <td><?= formatSecondsToTime($totalRunningHrs) ?></td>
                        <td><?= formatSecondsToTime($totalIdleHrs) ?></td>
                    </tr>
                    <?php        } ?>
                    <?php    } ?>
                </tbody>
                <tfoot class="fw-bold">
                    <tr>
                        <!-- Grand Total Row -->
                        <td>Total</td>
                        <td></td>
                        <?php
        $colIndex = 0;
        $grandTotalRunningHrs = $grandTotalIdleHrs = 0;

        foreach ($columnTotals as $total) {

            if ($colIndex % 2 == 0) {
                $grandTotalRunningHrs += $total;
                        ?>
                        <td><?= formatSecondsToTime($total) ?></td>
                        <?php
            }
            if ($colIndex % 2 == 1) {

                $grandTotalIdleHrs += $total;

                            ?>
                        <td><?= formatSecondsToTime(timeToSeconds('200:00:00') - $grandTotalRunningHrs) ?></td>
                        <?php

            }
            $colIndex += 1;
        }
                        ?>
                        <td><?= formatSecondsToTime($grandTotalRunningHrs) ?></td>
                        <td><?= formatSecondsToTime(timeToSeconds('200:00:00') * count($displayedMonths) - $grandTotalRunningHrs) ?>
                        </td>

                    </tr>

                    <!-- End of Grand Total Row -->

                </tfoot>
            </table>
            <?php } ?>
        </div>
    </div>

    <!-- table 3 -->
    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="col-md-12">
            <h5 class="text-dark text-center">Employee Machine Wise Work Hours Report</h5>
        </div>
        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table3" class="table table-bordered table-striped table-hover w-100">
                <thead>

                    <?php if (empty($machine_summary)) { ?>
                    <p class="nodata">No records available.</p>
                    <?php } else { ?>
                    <tr class="sticky-row">
                        <th colspan="2" class="text-center text-white bg-danger">Yr_Month</th>
                        <?php    $displayedMonths = array(); ?>
                        <?php    foreach ($machine_summary as $value): ?>
                        <?php        $monthYear = $value->yr_month ?>
                        <?php        if (!in_array($monthYear, $displayedMonths)): ?>
                        <th colspan="2" class="text-center text-white bg-secondary"><?= $monthYear; ?></th>
                        <?php            $displayedMonths[] = $monthYear; ?>
                        <?php        endif; ?>
                        <?php    endforeach; ?>
                        <th colspan="4" class="text-center text-white bg-secondary">Total</th>
                    </tr>
                    <tr class="sticky-row2">
                        <th class="sticky-col">Machine Name</th>
                        <th class="sticky-col">Type</th>
                        <?php    foreach ($displayedMonths as $month): ?>
                        <th>Work Hrs</th>
                        <th>Idle Hrs</th>
                        <?php    endforeach; ?>
                        <th>Total Work Hrs</th>
                        <th>Total Idle Hrs</th>
                    </tr>
                </thead>
                <tbody>
                    <?php    $categories = array_unique(array_column($machine_summary, 'machine_name')); ?>
                    <?php    $types = array_unique(array_column($machine_summary, 'type')); ?>
                    <?php    $grandTotalRunningHrs = $grandTotalIdleHrs = 0; ?>
                    <?php    $columnTotals = array_fill(0, count($displayedMonths) * 2, 0); ?>

                    <?php    foreach ($categories as $category) { ?>
                    <?php        $typesWithCategoryData = array_unique(array_column(array_filter($machine_summary, function ($value) use ($category) {
                return $value->machine_name == $category;
            }), 'type')); ?>
                    <?php        foreach ($typesWithCategoryData as $type) { ?>
                    <tr>
                        <td style="text-decoration: underline;cursor: pointer;" class="sticky-col Machwise"><?= $category ?>
                        </td>
                        <td class="sticky-col-2 mactype"><?= $type ?></td>
                        <?php            $totalRunningHrs = $totalIdleHrs = 0; ?>
                        <?php            $colIndex = 0; ?>
                        <?php            foreach ($displayedMonths as $month) { ?>
                        <?php                $foundData = false; ?>
                        <?php                foreach ($machine_summary as $value) { ?>
                        <?php                    if (
                            $value->machine_name == $category &&
                            $value->type == $type &&
                            $value->yr_month == $month
                        ) { ?>
                        <td><?= formatSecondsToTime(timeToSeconds($value->wrk_hrs)) ?></td>
                        <td><?= formatSecondsToTime(timeToSeconds($value->idle_hours)) ?></td>
                        <?php                        /* Update totals for each row */ ?>
                        <?php                        $totalRunningHrs += timeToSeconds($value->wrk_hrs); ?>
                        <?php                        $totalIdleHrs += timeToSeconds($value->idle_hours); ?>
                        <?php                        /* Update column totals */ ?>
                        <?php                        $columnTotals[$colIndex] += timeToSeconds($value->wrk_hrs); ?>
                        <?php                        $columnTotals[$colIndex + 1] += timeToSeconds($value->idle_hours); ?>
                        <?php                        $foundData = true; ?>
                        <?php                    } ?>
                        <?php                } ?>
                        <?php                if (!$foundData) { ?>
                        <td>-</td>
                        <td>-</td>
                        <?php                } ?>
                        <?php                $colIndex += 2; ?>
                        <?php            } ?>
                        <!-- row Total -->
                        <td><?= formatSecondsToTime($totalRunningHrs) ?></td>
                        <td><?= formatSecondsToTime($totalIdleHrs) ?></td>
                    </tr>
                    <?php        } ?>
                    <?php    } ?>
                </tbody>
                <tfoot class="fw-bold">
                    <tr>
                        <!-- Grand Total Row -->
                        <td>Total</td>
                        <td></td>
                        <?php
        $colIndex = 0;
        $grandTotalRunningHrs = $grandTotalIdleHrs = 0;

        foreach ($columnTotals as $total) {

            if ($colIndex % 2 == 0) {
                $grandTotalRunningHrs += $total;
                        ?>
                        <td><?= formatSecondsToTime($total) ?></td>
                        <?php
            }
            if ($colIndex % 2 == 1) {

                $grandTotalIdleHrs += $total;

                            ?>
                        <td><?= formatSecondsToTime(timeToSeconds('200:00:00') - $grandTotalRunningHrs) ?></td>
                        <?php

            }
            $colIndex += 1;
        }
                        ?>
                        <td><?= formatSecondsToTime($grandTotalRunningHrs) ?></td>
                        <td><?= formatSecondsToTime(timeToSeconds('200:00:00') * count($displayedMonths) - $grandTotalRunningHrs) ?>
                        </td>
                    </tr>
                    <!-- End of Grand Total Row -->
                </tfoot>
            </table>
            <?php } ?>
        </div>
    </div>

    <!-- table 4  -->
    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="col-md-12">
            <h5 class="text-danger text-center">Employee Category Wise Work Hours Report</h5>
        </div>
        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table4" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <?php if (empty($type_summary)) { ?>
                    <p class="nodata">No records available.</p>
                    <?php } else { ?>
                    <tr class="sticky-row">
                        <th colspan="2" class="text-center text-white bg-danger">Yr_Month</th>
                        <?php    $displayedMonths = array(); ?>
                        <?php    foreach ($type_summary as $value): ?>
                        <?php        $monthYear = $value->yr_month ?>
                        <?php        if (!in_array($monthYear, $displayedMonths)): ?>
                        <th colspan="2" class="text-center text-white bg-secondary"><?= $monthYear; ?></th>
                        <?php            $displayedMonths[] = $monthYear; ?>
                        <?php        endif; ?>
                        <?php    endforeach; ?>
                        <th colspan="4" class="text-center text-white bg-secondary">Total</th>
                    </tr>
                    <tr class="sticky-row2">
                        <th class="sticky-col">Category</th>
                        <th class="sticky-col">Type</th>
                        <?php    foreach ($displayedMonths as $month): ?>
                        <th>Work Hrs</th>
                        <th>Idle Hrs</th>
                        <?php    endforeach; ?>
                        <th>Total Work Hrs</th>
                        <th>Total Idle Hrs</th>
                    </tr>
                </thead>
                <tbody>
                    <?php    $categories = array_unique(array_column($type_summary, 'p_pack_name')); ?>
                    <?php    $types = array_unique(array_column($type_summary, 'type')); ?>
                    <?php    $grandTotalRunningHrs = $grandTotalIdleHrs = 0; ?>
                    <?php    $columnTotals = array_fill(0, count($displayedMonths) * 2, 0); ?>

                    <?php    $categories = array_unique(array_column($type_summary, 'p_pack_name')); ?>
                    <?php    foreach ($categories as $category) { ?>
                    <?php        $typesWithCategoryData = array_unique(array_column(array_filter($type_summary, function ($value) use ($category) {
                return $value->p_pack_name == $category;
            }), 'type')); ?>
                    <?php        foreach ($typesWithCategoryData as $type) { ?>
                    <tr>
                        <td style="text-decoration: underline;cursor: pointer;" class="sticky-col Categorywise">
                            <?= $category ?></td>
                        <td class="sticky-col-2 cateype"><?= $type ?></td>
                        <?php            $totalRunningHrs = $totalIdleHrs = 0; ?>
                        <?php            $colIndex = 0; ?>
                        <?php            foreach ($displayedMonths as $month) { ?>
                        <?php                $foundData = false; ?>
                        <?php                foreach ($type_summary as $value) { ?>
                        <?php                    if (
                            $value->p_pack_name == $category &&
                            $value->type == $type &&
                            $value->yr_month == $month
                        ) { ?>
                        <td><?= formatSecondsToTime(timeToSeconds($value->wrk_hrs)) ?></td>
                        <td><?= formatSecondsToTime(timeToSeconds($value->idle_hours)) ?></td>
                        <?php                        /* Update totals for each row */ ?>
                        <?php                        $totalRunningHrs += timeToSeconds($value->wrk_hrs); ?>
                        <?php                        $totalIdleHrs += timeToSeconds($value->idle_hours); ?>
                        <?php                        /* Update column totals */ ?>
                        <?php                        $columnTotals[$colIndex] += timeToSeconds($value->wrk_hrs); ?>
                        <?php                        $columnTotals[$colIndex + 1] += timeToSeconds($value->idle_hours); ?>
                        <?php                        $foundData = true; ?>
                        <?php                    } ?>
                        <?php                } ?>
                        <?php                if (!$foundData) { ?>
                        <td>-</td>
                        <td>-</td>
                        <?php                } ?>
                        <?php                $colIndex += 2; ?>
                        <?php            } ?>
                        <!-- row Total -->
                        <td><?= formatSecondsToTime($totalRunningHrs) ?></td>
                        <td><?= formatSecondsToTime($totalIdleHrs) ?></td>
                    </tr>
                    <?php        } ?>
                    <?php    } ?>
                </tbody>
                <tfoot>
                    <tr class="fw-bold">
                        <!-- Grand Total Row -->
                        <td>Total</td>
                        <td></td>
                        <?php
        $colIndex = 0;
        $grandTotalRunningHrs = $grandTotalIdleHrs = 0;

        foreach ($columnTotals as $total) {

            if ($colIndex % 2 == 0) {
                $grandTotalRunningHrs += $total;
                        ?>
                        <td><?= formatSecondsToTime($total) ?></td>
                        <?php
            }
            if ($colIndex % 2 == 1) {

                $grandTotalIdleHrs += $total;

                            ?>
                        <td><?= formatSecondsToTime(timeToSeconds('200:00:00') - $grandTotalRunningHrs) ?></td>
                        <?php

            }
            $colIndex += 1;
        }
                        ?>
                        <td><?= formatSecondsToTime($grandTotalRunningHrs) ?></td>
                        <td><?= formatSecondsToTime(timeToSeconds('200:00:00') * count($displayedMonths) - $grandTotalRunningHrs) ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
            <?php } ?>
        </div>
    </div>

    <!-- table 5 -->

    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="col-md-12">
            <h5 class="text-success text-center">Employee Product Wise Work Hours Report</h5>
        </div>
        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table5" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <?php if (empty($name_summary)) { ?>
                    <p class="nodata">No records available.</p>
                    <?php } else { ?>
                    <tr class="sticky-row">
                        <th colspan="2" class="text-center text-white bg-danger">Yr_Month</th>
                        <?php    $displayedMonths = array(); ?>
                        <?php    foreach ($name_summary as $value): ?>
                        <?php        $monthYear = $value->yr_month ?>
                        <?php        if (!in_array($monthYear, $displayedMonths)): ?>
                        <th colspan="2" class="text-center text-white bg-secondary"><?= $monthYear; ?></th>
                        <?php            $displayedMonths[] = $monthYear; ?>
                        <?php        endif; ?>
                        <?php    endforeach; ?>
                        <th colspan="4" class="text-center text-white bg-secondary">Total</th>
                    </tr>
                    <tr class="sticky-row2">
                        <th class="sticky-col">Product Name</th>
                        <th class="sticky-col">Type</th>
                        <?php    foreach ($displayedMonths as $month): ?>
                        <th>Work Hrs</th>
                        <th>Idle Hrs</th>
                        <?php    endforeach; ?>
                        <th>Total Work Hrs</th>
                        <th>Total Idle Hrs</th>
                    </tr>
                </thead>
                <tbody>
                    <?php    $categories = array_unique(array_column($name_summary, 'product_name')); ?>
                    <?php    $types = array_unique(array_column($name_summary, 'type')); ?>
                    <?php    $grandTotalRunningHrs = $grandTotalIdleHrs = 0; ?>
                    <?php    $columnTotals = array_fill(0, count($displayedMonths) * 2, 0); ?>

                    <?php    foreach ($categories as $category) { ?>
                    <?php        $typesWithCategoryData = array_unique(array_column(array_filter($name_summary, function ($value) use ($category) {
                return $value->product_name == $category;
            }), 'type')); ?>
                    <?php        foreach ($typesWithCategoryData as $type) { ?>
                    <tr>
                        <td style="text-decoration: underline;cursor: pointer;" class="sticky-col Productwise">
                            <?= $category ?></td>
                        <td class="sticky-col-2 Productype"><?= $type ?></td>
                        <?php            $totalRunningHrs = $totalIdleHrs = 0; ?>
                        <?php            $colIndex = 0; ?>
                        <?php            foreach ($displayedMonths as $month) { ?>
                        <?php                $foundData = false; ?>
                        <?php                foreach ($name_summary as $value) { ?>
                        <?php                    if (
                            $value->product_name == $category &&
                            $value->type == $type &&
                            $value->yr_month == $month
                        ) { ?>
                        <td><?= formatSecondsToTime(timeToSeconds($value->wrk_hrs)) ?></td>
                        <td><?= formatSecondsToTime(timeToSeconds($value->idle_hours)) ?></td>
                        <?php                        /* Update totals for each row */ ?>
                        <?php                        $totalRunningHrs += timeToSeconds($value->wrk_hrs); ?>
                        <?php                        $totalIdleHrs += timeToSeconds($value->idle_hours); ?>
                        <?php                        /* Update column totals */ ?>
                        <?php                        $columnTotals[$colIndex] += timeToSeconds($value->wrk_hrs); ?>
                        <?php                        $columnTotals[$colIndex + 1] += timeToSeconds($value->idle_hours); ?>
                        <?php                        $foundData = true; ?>
                        <?php                    } ?>
                        <?php                } ?>
                        <?php                if (!$foundData) { ?>
                        <td>-</td>
                        <td>-</td>
                        <?php                } ?>
                        <?php                $colIndex += 2; ?>
                        <?php            } ?>
                        <!-- row Total -->
                        <td><?= formatSecondsToTime($totalRunningHrs) ?></td>
                        <td><?= formatSecondsToTime($totalIdleHrs) ?></td>
                    </tr>
                    <?php        } ?>
                    <?php    } ?>
                </tbody>
                <tfoot>
                    <tr class="fw-bold">
                        <!-- Grand Total Row -->
                        <td>Total</td>
                        <td></td>
                        <?php
        $colIndex = 0;
        $grandTotalRunningHrs = $grandTotalIdleHrs = 0;

        foreach ($columnTotals as $total) {

            if ($colIndex % 2 == 0) {
                $grandTotalRunningHrs += $total;
                        ?>
                        <td><?= formatSecondsToTime($total) ?></td>
                        <?php
            }
            if ($colIndex % 2 == 1) {

                $grandTotalIdleHrs += $total;

                            ?>
                        <td><?= formatSecondsToTime(timeToSeconds('200:00:00') - $grandTotalRunningHrs) ?></td>
                        <?php

            }
            $colIndex += 1;
        }
                        ?>
                        <td><?= formatSecondsToTime($grandTotalRunningHrs) ?></td>
                        <td><?= formatSecondsToTime(timeToSeconds('200:00:00') * count($displayedMonths) - $grandTotalRunningHrs) ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
            <?php } ?>
        </div>
    </div>
    <!--pop ups -->
    <!-- Employee Type Wise Work Report Modal -->
    <div class="modal fade" id="EmptypeModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title">Employee Type Wise Work Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6>Employee Name: <strong>{{ $employee }}</strong></h6>
                        </div>
                        <div class="col-md-6">
                            <h6>Type: <strong><span id="type"></span></strong></h6>
                        </div>
                    </div>
                    <div style="height: 450px; overflow-y: auto;">
                        <div id="employeetypetable"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Employee Product Wise Work Report Modal -->
    <div class="modal fade" id="EmpproModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Employee Product Wise Work Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <h6>Employee Name: <strong>{{ $employee }}</strong></h6>
                        </div>
                        <div class="col-md-6">
                            <h6>Product Type: <strong><span id="product"></span></strong></h6>
                        </div>
                        <div class="col-md-2">
                            <h6>Type: <strong><span id="pro_type"></span></strong></h6>
                        </div>
                    </div>
                    <div style="height: 450px; overflow-y: auto;">
                        <div id="employeeprotable"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Employee Machine Wise Work Report Modal -->
    <div class="modal fade" id="EmpmachineModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title text-white">Employee Machine Wise Work Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <h6>Employee Name: <strong>{{ $employee }}</strong></h6>
                        </div>
                        <div class="col-md-6">
                            <h6>Machine Name: <strong><span id="machine"></span></strong></h6>
                        </div>
                        <div class="col-md-2">
                            <h6>Type: <strong><span id="machine_type"></span></strong></h6>
                        </div>
                    </div>
                    <div style="height: 450px; overflow-y: auto;">
                        <div id="employeemachinetable"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Employee Category Wise Work Report Modal -->
    <div class="modal fade" id="EmpcategoryModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Employee Category Wise Work Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <h6>Employee Name: <strong>{{ $employee }}</strong></h6>
                        </div>
                        <div class="col-md-6">
                            <h6>Category Name: <strong><span id="category"></span></strong></h6>
                        </div>
                        <div class="col-md-2">
                            <h6>Type: <strong><span id="category_type"></span></strong></h6>
                        </div>
                    </div>
                    <div style="height: 450px; overflow-y: auto;">
                        <div id="employecatetable"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')

    <script>

        $(document).ready(function () {
            var EmpName = "{{ request('emp_name') }}";
            var startDate = "{{ request('start_date') }}";
            var endDate = "{{ request('end_date') }}";

            $('.emp_name').select2();
            $('#emp_name').val(EmpName).trigger('change');


            $('#start_date').val(startDate);
            $('#end_date').val(endDate);

        });


        // tables

        $(document).ready(function () {

            $('#Table2').DataTable({
                dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
                buttons: [
                    {
                        extend: 'colvis',
                        text: '<i class="bi bi-layout-three-columns"></i> Columns',
                        className: 'btn bg-primary btn-sm',
                        postfixButtons: ['colvisRestore'] // adds "Restore Columns" button
                    },
                    { extend: 'excelHtml5', title: "Employee Product Type Wise Work Hours Report", exportOptions: { columns: ':visible' } }
                ]
            });

            $('#Table3').DataTable({
                dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
                buttons: [
                    {
                        extend: 'colvis',
                        text: '<i class="bi bi-layout-three-columns"></i> Columns',
                        className: 'btn bg-primary btn-sm',
                        postfixButtons: ['colvisRestore'] // adds "Restore Columns" button
                    },
                    { extend: 'excelHtml5', title: "Employee Machine Wise Work Hours Report", exportOptions: { columns: ':visible' } }
                ]
            });

            $('#Table4').DataTable({
                dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
                buttons: [
                    {
                        extend: 'colvis',
                        text: '<i class="bi bi-layout-three-columns"></i> Columns',
                        className: 'btn bg-primary btn-sm',
                        postfixButtons: ['colvisRestore'] // adds "Restore Columns" button
                    },
                    { extend: 'excelHtml5', title: "Employee Category Wise Work Hours Report", exportOptions: { columns: ':visible' } }
                ]
            });

            $('#Table5').DataTable({
                dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
                buttons: [
                    {
                        extend: 'colvis',
                        text: '<i class="bi bi-layout-three-columns"></i> Columns',
                        className: 'btn bg-primary btn-sm',
                        postfixButtons: ['colvisRestore'] // adds "Restore Columns" button
                    },
                    { extend: 'excelHtml5', title: "Employee Product Wise Work Hours Report", exportOptions: { columns: ':visible' } }
                ]
            });
        });


        // modal for product wrk hrs rpt

        $(document).ready(function () {
            $('.Prowise').click(function () {
                // Show the modal
                $('#EmpproModal').modal('show');

                // Log the HTML of the clicked row
                var row = $(this).closest('tr');
                console.log("Row clicked: ", row.html());

                // Attempt to fetch the type from the correct column
                var prodct = row.find('.sticky-col.Prowise').text().trim();
                var type = row.find('.sticky-col-2.protype').text().trim();
                // Ensure the type is fetched correctly
                if (prodct) {
                    // Placeholder dates - replace with actual data
                    var Employee = "{{ request('emp_name') }}";
                    var startDate = "{{ request('start_date') }}";
                    var endDate = "{{ request('end_date') }}";

                    // Update the type in the modal
                    $('#product').text(prodct);

                    // Build the query string
                    var params = $.param({
                        start_date: startDate,
                        end_date: endDate,
                        emp_name: Employee,
                        prodct: prodct
                    });

                    // Build the full URL for the AJAX request
                    var url = "{{ route('productionprotypepopup') }}" + "?" + params;

                    // AJAX request to fetch data
                    $.get(url, function (data) {
                        // Update HTML content with received data
                        $('#employeeprotable').html(data);
                    });
                } else {
                    console.log("Type not found.");
                }
            });

            // Close modal button to close the sidebar
            $('#closeButton').click(function () {
                $('#EmpproModal').modal('hide');
            });

            // Clear modal content when it is hidden
            $('#EmpproModal').on('hidden.bs.modal', function () {
                $('#employeeprotable').html('');

            });
        });

        // modal for machine wrk hrs rpt

        $(document).ready(function () {
            $('.Machwise').click(function () {
                // Show the modal
                $('#EmpmachineModal').modal('show');

                // Log the HTML of the clicked row
                var row = $(this).closest('tr');
                console.log("Row clicked: ", row.html());

                // Attempt to fetch the type from the correct column
                var machine = row.find('.sticky-col.Machwise').text().trim();
                var type = row.find('.sticky-col-2.mactype').text().trim();
                // Ensure the type is fetched correctly
                if (machine) {
                    // Placeholder dates - replace with actual data
                    var Employee = "{{ request('emp_name') }}";
                    var startDate = "{{ request('start_date') }}";
                    var endDate = "{{ request('end_date') }}";

                    // Update the type in the modal
                    $('#machine_type').text(type);
                    $('#machine').text(machine);

                    // Build the query string
                    var params = $.param({
                        start_date: startDate,
                        end_date: endDate,
                        emp_name: Employee,
                        machine: machine
                    });

                    // Build the full URL for the AJAX request
                    var url = "{{ route('productionmacpopup') }}" + "?" + params;

                    // AJAX request to fetch data
                    $.get(url, function (data) {
                        // Update HTML content with received data
                        $('#employeemachinetable').html(data);
                    });
                } else {
                    console.log("Type not found.");
                }
            });

            // Close modal button to close the sidebar
            $('#closeButton').click(function () {
                $('#EmpmachineModal').modal('hide');
            });

            // Clear modal content when it is hidden
            $('#EmpmachineModal').on('hidden.bs.modal', function () {
                $('#employeemachinetable').html('');

            });
        });

        // modal for category wrk hrs rpt

        $(document).ready(function () {
            $('.Categorywise').click(function () {
                // Show the modal
                $('#EmpcategoryModal').modal('show');

                // Log the HTML of the clicked row
                var row = $(this).closest('tr');
                console.log("Row clicked: ", row.html());

                // Attempt to fetch the type from the correct column
                var category = row.find('.sticky-col.Categorywise').text().trim();
                var type = row.find('.sticky-col-2.cateype').text().trim();
                // Ensure the type is fetched correctly
                if (category) {
                    // Placeholder dates - replace with actual data
                    var Employee = "{{ request('emp_name') }}";
                    var startDate = "{{ request('start_date') }}";
                    var endDate = "{{ request('end_date') }}";

                    // Update the type in the modal
                    $('#category_type').text(type);
                    $('#category').text(category);

                    // Build the query string
                    var params = $.param({
                        start_date: startDate,
                        end_date: endDate,
                        emp_name: Employee,
                        category: category
                    });

                    // Build the full URL for the AJAX request
                    var url = "{{ route('productioncatpopup') }}" + "?" + params;

                    // AJAX request to fetch data
                    $.get(url, function (data) {
                        // Update HTML content with received data
                        $('#employecatetable').html(data);
                    });
                } else {
                    console.log("Type not found.");
                }
            });

            // Close modal button to close the sidebar
            $('#closeButton').click(function () {
                $('#EmpcategoryModal').modal('hide');
            });

            // Clear modal content when it is hidden
            $('#EmpcategoryModal').on('hidden.bs.modal', function () {
                $('#employecatetable').html('');

            });
        });


        // modal for product wrk hrs rpt

        $(document).ready(function () {
            $('.Productwise').click(function () {
                // Show the modal
                $('#EmpprodctModal').modal('show');

                // Log the HTML of the clicked row
                var row = $(this).closest('tr');
                console.log("Row clicked: ", row.html());

                // Attempt to fetch the type from the correct column
                var product = row.find('.sticky-col.Productwise').text().trim();
                var type = row.find('.sticky-col-2.Productype').text().trim();
                // Ensure the type is fetched correctly
                if (product) {
                    // Placeholder dates - replace with actual data
                    var Employee = "{{ request('emp_name') }}";
                    var startDate = "{{ request('start_date') }}";
                    var endDate = "{{ request('end_date') }}";

                    // Update the type in the modal
                    $('#product_type').text(type);
                    $('#product_name').text(product);

                    // Build the query string
                    var params = $.param({
                        start_date: startDate,
                        end_date: endDate,
                        emp_name: Employee,
                        product: product
                    });

                    // Build the full URL for the AJAX request
                    var url = "{{ route('productionpropopup') }}" + "?" + params;

                    // AJAX request to fetch data
                    $.get(url, function (data) {
                        // Update HTML content with received data
                        $('#employeprotable').html(data);
                    });
                } else {
                    console.log("Type not found.");
                }
            });

            // Close modal button to close the sidebar
            $('#closeButton').click(function () {
                $('#EmpprodctModal').modal('hide');
            });

            // Clear modal content when it is hidden
            $('#EmpprodctModal').on('hidden.bs.modal', function () {
                $('#employeprotable').html('');

            });
        });



    </script>

@endpush