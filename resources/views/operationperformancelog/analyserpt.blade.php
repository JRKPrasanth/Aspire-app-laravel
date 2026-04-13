@extends('layouts.header')
@section('content')

    <h3 class="text-danger">Operation Analyse Report</h3>

    <div class="container mt-4">
        <div class="row g-3 mb-3">
            <div class="col-12 col-md-3">
                <a href="operationanalyserpt" class="btn btn-success w-100">Analyse Report</a>
            </div>
            <div class="col-12 col-md-3">
                <a href="overalloperationperformancereport" class="btn btn-outline-primary w-100">Overall Employee
                    Report</a>
            </div>
            <div class="col-12 col-md-3">
                <a href="operationperformancelogreport" class="btn btn-outline-warning w-100">Employee Performance
                    Report</a>
            </div>
            <div class="col-12 col-md-3">
                <a href="operationprobasereport" class="btn btn-outline-info w-100">Product Based Report</a>
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

    <!--date wise work hrs popup-->
    <!-- Modal -->
    <div class="modal fade" id="empModal" tabindex="-1" aria-labelledby="empModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content shadow-lg rounded-4 border-0">

                <!-- Modal Header -->
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="empModalLabel">Employee Day Wise Work Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="closeButton"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body px-4 py-3">

                    <div class="row mb-3">
                        <div class="col text-center">
                            <h5>Employee Name: <strong><span id="employeeName" class="text-dark"></span></strong></h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="border rounded-3 overflow-auto" style="height: 450px;">
                                <div id="employeeHoursTable" class="p-3">
                                    <!-- Table will be injected here -->
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!--end-->
    <!--date machine work hrs popup-->
    <!-- Modal -->
    <div class="modal fade" id="analyzeModal" tabindex="-1" aria-labelledby="analyzeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content shadow-lg rounded-4 border-0">

                <!-- Modal Header -->
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="analyzeModalLabel">Employee Product Wise Work Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="closeButton"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body px-4 py-3">

                    <!-- Info Row -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="mb-2">Product Type: <strong><span id="prd_type" class="text-dark"></span></strong>
                            </h6>
                        </div>
                        <div class="col-md-4">
                            <h6 class="mb-2">Batch: <strong><span id="batch" class="text-dark"></span></strong></h6>
                        </div>
                        <div class="col-md-2">
                            <h6 class="mb-2">Unit: <strong><span id="unit" class="text-dark"></span></strong></h6>
                        </div>
                    </div>

                    <!-- Data Table Container -->
                    <div class="border rounded-3 overflow-auto" style="height: 450px;">
                        <div id="analyzetable" class="p-3">
                            <!-- Dynamic table content -->
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!--end-->

    <!--drop down-->
    <div class="card shadow-lg rounded-4 border-0 p-4">
        <form action="{{ url('operationanalyserpt') }}" method="get" id="searchForm">
            <div class="row g-3">

                <div class="col-md-3">
                    <label for="pro_varient" class="form-label">Product Variant</label>
                    <select name='pro_varient[]' id='pro_varient' class='form-select pro_varient select2' multiple>
                        {!! $pro_varient !!}
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="pro_type" class="form-label">Product Type</label>
                    <select name='pro_type' id='pro_type' class='form-select pro_type select2'>
                        {!! $pro_type !!}
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="pro_name" class="form-label">Product Name</label>
                    <select name='pro_name' id='pro_name' class='form-select pro_name select2'>
                        {!! $pro_name !!}
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="process_name" class="form-label">Process Level</label>
                    <select name='process_name' id='process_name' class='form-select process_name select2'>
                        {!! $process_name !!}
                    </select>
                </div>

                <div class="col-md-3 offset-md-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="text" id="start_date" name="start_date" class="form-control start_date1" required>
                </div>

                <div class="col-md-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="text" id="end_date" name="end_date" class="form-control end_date1" required>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100" id="searchButton">Search</button>
                </div>

            </div>
        </form>
    </div>


    <div class="card shadow-lg rounded-4 border-0 p-4">
                        <div class="col-md-12">
            <h5 class="text-danger text-center">Type Wise Batch Report</h5>
        </div>
        <?php if (empty($product_summary)) { ?>
        <p class="nodata">No records available.</p>
        <?php } else { ?>
        <?php
        // Calculate the total number of product types
        $total_product_types = count(array_unique(array_column($product_summary, 'prd_type')));

        // Initialize displayedMonths and totals
        $displayedMonths = array();
        $grand_totals = ['qty' => 0, 'act_qty' => 0];
        $monthly_totals = [];

        // Calculate totals per month
        foreach ($product_summary as $value) {
            if (!in_array($value->yr_month, $displayedMonths)) {
                $displayedMonths[] = $value->yr_month;
            }
            if (!isset($monthly_totals[$value->yr_month])) {
                $monthly_totals[$value->yr_month] = ['qty' => 0, 'act_qty' => 0];
            }
            $monthly_totals[$value->yr_month]['qty'] += $value->empqty;
            // for single tablet count purpose
            //  dd(request('pro_varient'));
            if (in_array("13", request('pro_varient'))) {

                $monthly_totals[$value->yr_month]['act_qty'] += round(($value->pack_name * $value->empqty) * 0.62 / 1000, 1);

            } else {

                $monthly_totals[$value->yr_month]['act_qty'] += round(($value->pack_name * $value->empqty) / 1000, 1);
            }


        }
        //dd($displayedMonths);
        foreach ($displayedMonths as $month) {
            $grand_totals['qty'] += $monthly_totals[$month]['qty'];
            $grand_totals['act_qty'] += $monthly_totals[$month]['act_qty'];

        }
                    ?>
        <div class="row">
            <div class="col-md-4">

                <p class="text-danger"><b>Total : <?= $total_product_types ?></b></p>
            </div>
            <div class="col-md-4">
                <p class="text-primary"><b>Total Unit: <?= $grand_totals['qty'] ?></b></p>
            </div>
            <div class="col-md-4">
                <p class="text-danger"><b>Total KG/LTR: <?= $grand_totals['act_qty'] ?></b></p>
            </div>
        </div>



        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table1" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <tr class="sticky-row">
                        <th colspan="3" class="text-center text-white bg-danger"
                            style="text-align: center; background: #b3d1ff">Yr_Month</th>
                        <?php    foreach ($displayedMonths as $monthYear): ?>
                        <th colspan="2" class="text-center text-white bg-secondary"><?= $monthYear; ?></th>
                        <?php    endforeach; ?>
                        <th colspan="2" class="text-center text-white bg-secondary">Total</th>
                    </tr>
                    <tr class="sticky-row2 table-warning">
                        <th class="text-center">Product Type</th>
                        <th class="text-center">Batch No</th>
                        <th class="text-center">Unit</th>
                        <?php    foreach ($displayedMonths as $month): ?>
                        <th class="text-center">Actual Qty</th>
                        <th class="text-center">Qty in kgs/ltrs</th>
                        <?php    endforeach; ?>
                        <th class="text-center">Total Qty</th>
                        <th class="text-center">Total Qty kg/ltr</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                            $prd_types = array_unique(array_column($product_summary, 'prd_type')); 
                            ?>
                    <?php    foreach ($prd_types as $prd_type) { ?>
                    <?php 
                                $batchs = array_unique(array_column(array_filter($product_summary, function ($value) use ($prd_type) {
                return $value->prd_type == $prd_type;
            }), 'batch_no')); 
                                ?>
                    <?php        foreach ($batchs as $batch) { ?>
                    <?php 
                                    $units = array_unique(array_column(array_filter($product_summary, function ($value) use ($prd_type, $batch) {
                    return $value->prd_type == $prd_type && $value->batch_no == $batch;
                }), 'pack_name')); 
                                    ?>
                    <?php            foreach ($units as $unit) { ?>
                    <tr>
                        <td style="text-decoration: underline;cursor: pointer;" class="sticky-col prd_type">
                            <b><?= $prd_type ?></b>
                        </td>
                        <td class="sticky-col batch"><?= $batch ?></td>
                        <td class="sticky-col unit"><?= $unit ?></td>

                        <?php 
                                            $row_total_qty = 0;
                    $row_total_act_qty = 0;
                    foreach ($displayedMonths as $month) {
                        $qty = '-';
                        $act_qty = '-';

                        foreach ($product_summary as $value) {
                            if ($value->prd_type == $prd_type && $value->batch_no == $batch && $value->pack_name == $unit && $value->yr_month == $month) {
                                $qty = $value->empqty;
                                if (in_array("13", request('pro_varient'))) {

                                    $act_qty = round(($value->pack_name * $value->empqty) * 0.62 / 1000, 1);

                                } else {

                                    $act_qty = round(($value->pack_name * $value->empqty) / 1000, 1);
                                }

                                $row_total_qty += $qty;
                                $row_total_act_qty += $act_qty;
                                break;
                            }
                        }
                                            ?>
                        <td><?= $qty ?></td>
                        <td><?= $act_qty ?></td>
                        <?php                } ?>
                        <td> <?= $row_total_qty ?> </td>
                        <td> <?= $row_total_act_qty ?> </td>
                    </tr>
                    <?php            } ?>
                    <?php        } ?>
                    <?php    } ?>
                </tbody>
                <tfoot>
                    <tr style="background:#ffc6c6;">
                        <th>Grand Total</th>
                        <th></th>
                        <th></th>
                        <?php    foreach ($displayedMonths as $month) { ?>
                        <th><?= $monthly_totals[$month]['qty'] ?></th>
                        <th><?= $monthly_totals[$month]['act_qty'] ?></th>
                        <?php    } ?>
                        <th><?= $grand_totals['qty'] ?></th>
                        <th><?= $grand_totals['act_qty'] ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php } ?>
    </div>


    <!-- duration analyse-->
    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="col-md-12">
            <h5 class="text-danger text-center">Type Wise Duration Report</h5>
        </div>
        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table2" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <tr class="sticky-row">
                        <th colspan="3" class="text-center text-white bg-danger">Yr_Month</th>
                        <?php if (empty($duration_summary)) { ?>
                        <p class="nodata">No records available.</p>
                        <?php } else { ?>
                        <?php 
                                // Initialize $displayedMonths
        $displayedMonths = array();
        foreach ($duration_summary as $value) {
            if (!in_array($value->yr_month, $displayedMonths)) {
                $displayedMonths[] = $value->yr_month;
            }
        }
        foreach ($displayedMonths as $monthYear): ?>
                        <th colspan="4" class="text-center text-white bg-secondary"><?= $monthYear; ?></th>
                        <?php    endforeach; ?>
                    </tr>
                    <tr class="sticky-row2 table-warning">
                        <th class="text-center">Product Type</th>
                        <th class="text-center">Batch No</th>
                        <th class="text-center">Unit</th>
                        <?php    foreach ($displayedMonths as $month): ?>
                        <th class="text-center">Batch Start Date</th>
                        <th class="text-center">Batch End Date</th>
                        <th class="text-center">Duration</th>
                        <th class="text-center">Remarks</th>
                        <?php    endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                            $prd_types = array_unique(array_column($duration_summary, 'prd_type')); 
                            ?>
                    <?php    foreach ($prd_types as $prd_type) { ?>
                    <?php 
                                $batchs = array_unique(array_column(array_filter($duration_summary, function ($value) use ($prd_type) {
                return $value->prd_type == $prd_type;
            }), 'batch_no')); 
                                ?>
                    <?php        foreach ($batchs as $batch) { ?>
                    <?php 
                                    $units = array_unique(array_column(array_filter($duration_summary, function ($value) use ($prd_type, $batch) {
                    return $value->prd_type == $prd_type && $value->batch_no == $batch;
                }), 'pack_name')); 
                                    ?>
                    <?php            foreach ($units as $unit) { ?>
                    <tr>
                        <td class="sticky-col"><?= $prd_type ?></td>
                        <td class="sticky-col-1"><?= $batch ?></td>
                        <td class="sticky-col-2"><?= $unit ?></td>

                        <?php                foreach ($displayedMonths as $month) { ?>
                        <?php 
                                                    $batchs = '-';
                        $batche = '-';
                        $duration = '-';
                        $remark = '-';

                        foreach ($duration_summary as $value) {
                            if ($value->prd_type == $prd_type && $value->batch_no == $batch && $value->pack_name == $unit && $value->yr_month == $month) {
                                $batchs = $value->batch_sdate;
                                $batche = $value->batch_edate;
                                $duration = $value->duration;
                                $remark = $value->remark;
                                break;
                            }
                        }
                                                    ?>
                        <td><?= $batchs ?></td>
                        <td><?= $batche ?></td>
                        <td><?= $duration ?></td>
                        <td><?= $remark ?></td>
                        <?php                } ?>
                    </tr>
                    <?php            } ?>
                    <?php        } ?>
                    <?php    } ?>
                </tbody>
            </table>
            <?php } ?>
        </div>
    </div>
    <!-- extra hours calculation -->

    <div class="card shadow-lg rounded-4 border-0 p-4">
                <div class="col-md-12">
            <h5 class="text-danger text-center">Overall Employee Hours Report</h5>
        </div>
        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table3" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <tr class="sticky-row">
                        <th colspan="2" class="text-center text-white bg-danger">Yr_Month</th>
                        <?php if (empty($extra_hrs_cal)) { ?>
                        <p class="nodata">No records available.</p>
                        <?php } else { ?>
                        <?php    $displayedMonths = array(); ?>
                        <?php    foreach ($extra_hrs_cal as $value): ?>
                        <?php        $monthYear = $value->yr_month ?>
                        <?php        if (!in_array($monthYear, $displayedMonths)): ?>
                        <th colspan="4" class="text-center text-white bg-secondary"><?= $monthYear; ?></th>
                        <?php            $displayedMonths[] = $monthYear; ?>
                        <?php        endif; ?>
                        <?php    endforeach; ?>
                        <th colspan="4" class="text-center text-white bg-secondary">Total</th>
                    </tr>
                    <tr class="sticky-row2">
                        <th class="text-center">Sno</th>
                        <th class="text-center">Employee Name</th>
                        <?php    foreach ($displayedMonths as $month): ?>
                        <th class="text-center">Work Hrs</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Idle Hrs</th>
                        <th class="text-center">Extra Hrs</th>
                        <?php    endforeach; ?>
                        <th class="text-center">Total Work Hrs</th>
                        <th class="text-center">Total Qty</th>
                        <th class="text-center">Total Idle Hrs</th>
                        <th class="text-center">Total Extra Hrs</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                            $categories = array_unique(array_column($extra_hrs_cal, 'prd_type'));
        $grandTotalRunningHrs = $grandTotalIdleHrs = $grandTotalExtraHrs = $grandTotalJobQty = 0;
        $columnTotals = array_fill(0, count($displayedMonths) * 4, 0);
        $sno = 1;

        foreach ($categories as $category) { ?>
                    <tr>
                        <td class="sticky-col"><?= $sno; ?></td>
                        <td style="text-decoration: underline;cursor: pointer;" class="sticky-col viewEmployee">
                            <b><?= $category ?></b>
                        </td>
                        <?php 
                                    $totalRunningHrs = $totalQty = $totalIdleHrs = $totalExtraHrs = 0;
            $colIndex = 0;

            foreach ($displayedMonths as $month) {
                $foundData = false;
                foreach ($extra_hrs_cal as $value) {
                    if ($value->prd_type == $category && $value->yr_month == $month) { ?>
                        <td><?= formatSecondsToTime(timeToSeconds($value->wrk_hrs)) ?></td>
                        <td><?= htmlspecialchars($value->job_qty) ?></td>
                        <td><?= formatSecondsToTime(timeToSeconds($value->idle_hrs)) ?></td>
                        <td><?= formatSecondsToTime(max(0, timeToSeconds($value->extra_hrs))) ?></td>
                        <?php 
                                                // Update totals for the row
                        $totalRunningHrs += timeToSeconds($value->wrk_hrs);
                        $totalQty += is_numeric($value->job_qty) ? $value->job_qty : 0;
                        $totalIdleHrs += timeToSeconds($value->idle_hrs);
                        $totalExtraHrs += max(0, timeToSeconds($value->extra_hrs));

                        // Update column totals
                        $columnTotals[$colIndex] += timeToSeconds($value->wrk_hrs);
                        $columnTotals[$colIndex + 1] += is_numeric($value->job_qty) ? $value->job_qty : 0;
                        $columnTotals[$colIndex + 2] += timeToSeconds($value->idle_hrs);
                        $columnTotals[$colIndex + 3] += max(0, timeToSeconds($value->extra_hrs));

                        $foundData = true;
                    }
                }
                if (!$foundData) { ?>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <?php            }
                $colIndex += 4;
            } ?>
                        <td><?= formatSecondsToTime($totalRunningHrs) ?></td>
                        <td><?= $totalQty ?></td>
                        <td><?= formatSecondsToTime($totalIdleHrs) ?></td>
                        <td><?= formatSecondsToTime($totalExtraHrs) ?></td>
                    </tr>
                    <?php 
                                $sno++;
        } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td><b>Total</b></td>
                        <td></td>
                        <?php
        $colIndex = 0;

        foreach ($columnTotals as $total) {
            if ($colIndex % 4 == 0) { // Work Hrs
                $grandTotalRunningHrs += $total; ?>
                        <td><b><?= formatSecondsToTime($total) ?></b></td>
                        <?php        }

            if ($colIndex % 4 == 1) { // Job Qty
                $grandTotalJobQty += $total; ?>
                        <td><b><?= $total ?></b></td>
                        <?php        }

            if ($colIndex % 4 == 2) { // Idle Hrs
                $grandTotalIdleHrs += $total; ?>
                        <td><b><?= formatSecondsToTime($total) ?></b></td>
                        <?php        }

            if ($colIndex % 4 == 3) { // Extra Hrs
                $grandTotalExtraHrs += $total; ?>
                        <td><b><?= formatSecondsToTime($total) ?></b></td>
                        <?php        }
            $colIndex++;
        }
                                ?>
                        <td><b><?= formatSecondsToTime($grandTotalRunningHrs) ?></b></td>
                        <td><b><?= $grandTotalJobQty ?></b></td>
                        <td><b><?= formatSecondsToTime($grandTotalIdleHrs) ?></b></td>
                        <td><b><?= formatSecondsToTime($grandTotalExtraHrs) ?></b></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php } ?>
    </div>

@endsection
@push('scripts')

    <script>
        // modal for date wise work rpt
        $(document).ready(function () {
            $('.viewEmployee').click(function () {
                // Show the modal
                $('#empModal').modal('show');

                // Get the clicked row
                var row = $(this).closest('tr');

                // Attempt to fetch the employee name from the correct column
                var employeeName = row.find('.sticky-col:eq(1)').text().trim();
                var startDate = "{{ request('start_date') }}";
                var endDate = "{{ request('end_date') }}";

                // Set the employee name in the modal
                $('#employeeName').text(employeeName);

                // Build the query string
                var params = $.param({
                    start_date: startDate,
                    end_date: endDate,
                    employeeName: employeeName
                });

                // Build the full URL for the AJAX request
                var url = "{{ route('employewrkpopup') }}" + "?" + params;

                // AJAX request to fetch data
                $.get(url, function (data) {
                    // Update HTML content with received data
                    $('#employeeHoursTable').html(data);
                });
            });

            // Close modal button to close the sidebar
            $('#closeButton').click(function () {
                $('#empModal').modal('hide');
            });

            // Clear modal content when it is hidden
            $('#empModal').on('hidden.bs.modal', function () {
                $('#employeeHoursTable').html('');
            });
        });

        // tables
        $(document).ready(function () {

            $('#Table1').DataTable({


                scrollCollapse: true,
                pageLength: 1000,
                scrollX: true,
                scrollY: "50vh",
                dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
                buttons: [
                    {
                        extend: 'colvis',
                        text: '<i class="bi bi-layout-three-columns"></i> Columns',
                        className: 'btn bg-primary btn-sm',
                        postfixButtons: ['colvisRestore'] // adds "Restore Columns" button
                    },
                    { extend: 'excelHtml5', title: "Type Wise Batch Report", exportOptions: { columns: ':visible' } }
                ]

            });

            $('#Table2').DataTable({


                scrollCollapse: true,
           
                scrollX: true,
                scrollY: "50vh",
                dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
                buttons: [
                    {
                        extend: 'colvis',
                        text: '<i class="bi bi-layout-three-columns"></i> Columns',
                        className: 'btn bg-primary btn-sm',
                        postfixButtons: ['colvisRestore'] // adds "Restore Columns" button
                    },
                    { extend: 'excelHtml5', title: "Type Wise Duration Report", exportOptions: { columns: ':visible' } }
                ]


            });
            $('#Table3').DataTable({


                scrollCollapse: true,
                pageLength: 1000,
                scrollX: true,
                scrollY: "50vh",
                dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
                buttons: [
                    {
                        extend: 'colvis',
                        text: '<i class="bi bi-layout-three-columns"></i> Columns',
                        className: 'btn bg-primary btn-sm',
                        postfixButtons: ['colvisRestore'] // adds "Restore Columns" button
                    },
                    { extend: 'excelHtml5', title: "Overall Employee Hours", exportOptions: { columns: ':visible' } }
                ]
            });
        });

        $(document).ready(function () {
            $('.prd_type').click(function () {
                // Show the modal
                $('#analyzeModal').modal('show');

                // Log the HTML of the clicked row
                var row = $(this).closest('tr');

                // Attempt to fetch the employee name from the correct column
                var prd_type = row.find('.sticky-col.prd_type').text().trim();
                var batch = row.find('.sticky-col.batch').text().trim();
                var unit = row.find('.sticky-col.unit').text().trim();
                // Ensure the employee name is fetched correctly
                if (prd_type) {
                    // Placeholder dates - replace with actual data
                    var startDate = "{{ request('start_date') }}";
                    var endDate = "{{ request('end_date') }}";

                    // Update the employee name in the modal
                    $('#batch').text(batch);
                    $('#prd_type').text(prd_type);
                    $('#unit').text(unit);


                    // Build the query string
                    var params = $.param({
                        start_date: startDate,
                        end_date: endDate,
                        prd_type: prd_type,
                        batch: batch,
                        unit: unit
                    });

                    // Build the full URL for the AJAX request
                    var url = "{{ route('operationanalyspopup') }}" + "?" + params;

                    // AJAX request to fetch data
                    $.get(url, function (data) {
                        // Update HTML content with received data
                        $('#analyzetable').html(data);
                    });
                } else {
                    console.log("Type not found.");
                }
            });

            // Close modal button to close the sidebar
            $('#closeButton').click(function () {
                $('#analyzeModal').modal('hide');
            });

            // Clear modal content when it is hidden
            $('#analyzeModal').on('hidden.bs.modal', function () {
                $('#analyzetable').html('');

            });
        });

        // trigger change

        $(document).ready(function () {

            var product_type = "{{ request('pro_type') }}";
            var Pro_name = "{{ request('pro_name') }}";

            var Process_name = "{{ request('process_name') }}";
            var startDate = "{{ request('start_date') }}";
            var endDate = "{{ request('end_date') }}";

            $('.pro_name').select2();
            $('#pro_name').val(Pro_name).trigger('change');

            $('.pro_type').select2();
            $('#pro_type').val(product_type).trigger('change');


            $('.process_name').select2();
            $('#process_name').val(Process_name).trigger('change');

            $('#start_date').val(startDate);
            $('#end_date').val(endDate);


            var Pro_varient = {!! json_encode(request('pro_varient', [])) !!};
            $('.pro_varient').select2();
            $('#pro_varient').val(Pro_varient).trigger('change');

        });

        // loader purpose
        $(document).ready(function () {
            $('#searchForm').on('submit', function () {
                $('#loaderOverlay').show();
            });
        });

        $(window).on('load', function () {
            $('#loaderOverlay').hide();
        });


$(document).on('change', '.pro_type', function () {
    var prdgroup = $('.pro_type').val(); // or select2('val')
    
    if (prdgroup !== '') {
        var url = "{{ URL::to('jcomboform1') }}?table=m_products_t:product_id:concatenated_product"
                + "&parent=and product_type_id=" + prdgroup
                + "&order_by=concatenated_product asc";

        $.ajax({
            url: url,
            type: 'GET',
            success: function (data) {
                // Parse JSON string if needed
                if (typeof data === "string") {
                    try {
                        data = JSON.parse(data);
                    } catch (e) {
                        console.error("Invalid JSON response:", data);
                        return;
                    }
                }

                // Reset dropdown
                $('.pro_name').html('<option value="">-- Select Product --</option>');

                // Populate options
                $.each(data, function (i, item) {
                    let selected = item.val == "{{ $row->product_id ?? '' }}" ? 'selected' : '';
                    $('.pro_name').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
                });

                // Refresh select2 if used
                $('.pro_name').trigger('change.select2');
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    }

});
    </script>

@endpush