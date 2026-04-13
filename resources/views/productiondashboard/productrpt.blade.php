@extends('layouts.header')
@section('content')

    <h3 class="text-danger">Employee Working Detail Report</h3>

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
                <a href="productionformancelogreport" class="btn btn-outline-warning w-100">Employee Performance Report</a>
            </div>
            <div class="col-12 col-md-3">
                <a href="productionproductdashboard" class="btn btn-info w-100">Product Based Report</a>
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
        <div class="container-fluid">
            <form action="{{ url('productionproductdashboard') }}" method="get" id="searchForm">
                <div class="row g-3 align-items-center mb-3">

                    <!-- Product Group -->
                    <div class="col-md-3">
                        <label for="pro_type" class="form-label">Product Type</label>
                        <select name="pro_type" id="pro_type" class="form-select pro_type select2 w-100">
                            {!! $pro_type !!}
                        </select>
                    </div>

                    <!-- Product Name -->
                    <div class="col-md-3">
                        <label for="pro_name" class="form-label">Product Name</label>
                        <select name="pro_name" id="pro_name" class="form-select pro_name select2 w-100">
                            {!! $pro_name !!}
                        </select>
                    </div>

                    <!-- From Date -->
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">From Date</label>
                        <input type="text" class="form-control start_date1" id="start_date" name="start_date" required>
                    </div>

                    <!-- To Date -->
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">To Date</label>
                        <input type="text" class="form-control end_date1" id="end_date" name="end_date" required>
                    </div>
                </div>

                <!-- Search Button -->
                <div class="row">
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary" id="searchButton">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- table 1 -->
    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table1" class="table table-bordered table-striped table-hover w-100">
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
                        <th class="sticky-col">Employee Name</th>
                        <th class="sticky-col">Product Name</th>
                        <?php    foreach ($displayedMonths as $month): ?>
                        <th>Work Hrs</th>
                        <th>Idle Hrs</th>
                        <?php    endforeach; ?>
                        <th>Total Work Hrs</th>
                        <th>Total Idle Hrs</th>
                    </tr>
                </thead>
                <tbody>
                    <?php    $categories = array_unique(array_column($product_summary, 'qa_assigned_name')); ?>
                    <?php    $types = array_unique(array_column($product_summary, 'product_name')); ?>
                    <?php    $grandTotalRunningHrs = 0; ?>
                    <?php    $columnTotals = array_fill(0, count($displayedMonths) * 2, 0); ?>

                    <?php    foreach ($categories as $category) { ?>
                    <?php        $typesWithCategoryData = array_unique(array_column(array_filter($product_summary, function ($value) use ($category) {
                return $value->qa_assigned_name == $category;
            }), 'product_name')); ?>
                    <?php        foreach ($typesWithCategoryData as $type) { ?>
                    <tr>
                        <td style="text-decoration: underline;cursor: pointer;" class="sticky-col Empname">
                            <b><?= $category ?></b></td>
                        <td class="sticky-col-2 Product"><?= $type ?></td>
                        <?php            $totalRunningHrs = 0; ?>
                        <?php            $totalIdleHrs = 0; ?>
                        <?php            $colIndex = 0; ?>
                        <?php            foreach ($displayedMonths as $month) { ?>
                        <?php                $foundData = false; ?>
                        <?php                foreach ($product_summary as $value) { ?>
                        <?php                    if (
                            $value->qa_assigned_name == $category &&
                            $value->product_name == $type &&
                            $value->yr_month == $month
                        ) { ?>
                        <td><?= formatSecondsToTime(timeToSeconds($value->wrk_hrs)) ?></td>
                        <td><?= formatSecondsToTime(timeToSeconds($value->idle_hrs)) ?></td>
                        <?php                        /* Update totals for each row */ ?>
                        <?php                        $totalRunningHrs += timeToSeconds($value->wrk_hrs); ?>
                        <?php                        $totalIdleHrs += timeToSeconds($value->idle_hrs); ?>
                        <?php                        /* Update column totals */ ?>
                        <?php                        $columnTotals[$colIndex] += timeToSeconds($value->wrk_hrs); ?>
                        <?php                        $columnTotals[$colIndex + 1] += timeToSeconds($value->idle_hrs); ?>
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

                    <!-- End of Grand Total Row -->

                </tfoot>
            </table>
        </div>
        <?php } ?>
    </div>

    <!-- table 2 -->
    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table2" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <?php if (empty($machine_summary)) { ?>
                    <p class="nodata">No records available.</p>
                    <?php } else { ?>

                    <tr class="sticky-row">
                        <th colspan="1" class="text-center text-white bg-danger">Yr_Month</th>
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
                    <?php    $grandTotalRunningHrs = 0; ?>
                    <?php    $columnTotals = array_fill(0, count($displayedMonths) * 2, 0); ?>

                    <?php    foreach ($categories as $category) { ?>
                    <tr>
                        <td style="text-decoration: underline;cursor: pointer;" class="sticky-col Machinename">
                            <b><?= $category ?></b></td>
                        <?php        $totalRunningHrs = 0; ?>
                        <?php        $totalIdleHrs = 0; ?>
                        <?php        $colIndex = 0; ?>
                        <?php        foreach ($displayedMonths as $month) { ?>
                        <?php            $foundData = false; ?>
                        <?php            foreach ($machine_summary as $value) { ?>
                        <?php                if (
                        $value->machine_name == $category &&
                        $value->yr_month == $month
                    ) { ?>
                        <td><?= formatSecondsToTime(timeToSeconds($value->wrk_hrs)) ?></td>
                        <td><?= formatSecondsToTime(timeToSeconds($value->idle_hrs)) ?></td>
                        <?php                    /* Update totals for each row */ ?>
                        <?php                    $totalRunningHrs += timeToSeconds($value->wrk_hrs); ?>
                        <?php                    $totalIdleHrs += timeToSeconds($value->idle_hrs); ?>
                        <?php                    /* Update column totals */ ?>
                        <?php                    $columnTotals[$colIndex] += timeToSeconds($value->wrk_hrs); ?>
                        <?php                    $columnTotals[$colIndex + 1] += timeToSeconds($value->idle_hrs); ?>
                        <?php                    $foundData = true; ?>
                        <?php                } ?>
                        <?php            } ?>
                        <?php            if (!$foundData) { ?>
                        <td>-</td>
                        <td>-</td>
                        <?php            } ?>
                        <?php            $colIndex += 2; ?>
                        <?php        } ?>
                        <!-- row Total -->
                        <td><?= formatSecondsToTime($totalRunningHrs) ?></td>
                        <td><?= formatSecondsToTime($totalIdleHrs) ?></td>
                    </tr>
                    <?php    } ?>
                </tbody>
                <tfoot>
                    <tr class="fw-bold">
                        <!-- Grand Total Row -->
                        <td>Total</td>
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
        </div>
        <?php } ?>
    </div>

    <!-- Table 3 -->

    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table3" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <?php if (empty($target_summary)) { ?>
                    <p class="nodata">No records available.</p>
                    <?php } else { ?>

                    <tr class="sticky-row">
                        <th colspan="4" class="text-center text-white bg-danger">Yr_Month</th>
                        <?php    $displayedMonths = array(); ?>
                        <?php    foreach ($target_summary as $value): ?>
                        <?php        $monthYear = $value->yr_month ?>
                        <?php        if (!in_array($monthYear, $displayedMonths)): ?>
                        <th colspan="5" class="text-center text-white bg-secondary"><?= $monthYear; ?></th>
                        <?php            $displayedMonths[] = $monthYear; ?>
                        <?php        endif; ?>
                        <?php    endforeach; ?>
                        <th colspan="1" class="text-center text-white bg-secondary">Total</th>
                    </tr>
                    <tr class="sticky-row2">
                        <th class="sticky-col">Machine Name</th>
                        <th class="sticky-col">Employee Name</th>
                        <th class="sticky-col">Product Name</th>
                        <th class="sticky-col">Process</th>
                        <?php    foreach ($displayedMonths as $month): ?>
                        <th>Qty</th>
                        <th>Target</th>
                        <th>Work Hrs</th>
                        <th>Std Hrs</th>
                        <th>Hrs Diff</th>
                        <?php    endforeach; ?>
                        <th>Total Work Hrs</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $machines = array_unique(array_column($target_summary, 'machine_name')); 
                        ?>
                    <?php    foreach ($machines as $machine) { ?>
                    <?php 
                            $empnames = array_unique(array_column(array_filter($target_summary, function ($value) use ($machine) {
                return $value->machine_name == $machine;
            }), 'job_assigned_name')); 
                            ?>
                    <?php        foreach ($empnames as $names) { ?>
                    <?php 
                                $products = array_unique(array_column(array_filter($target_summary, function ($value) use ($machine, $names) {
                    return $value->machine_name == $machine && $value->job_assigned_name == $names;
                }), 'product_name')); 
                                ?>
                    <?php            foreach ($products as $product_name) { ?>
                    <?php 
                                    $processes = array_unique(array_column(array_filter($target_summary, function ($value) use ($machine, $names, $product_name) {
                        return $value->machine_name == $machine && $value->job_assigned_name == $names && $value->product_name == $product_name;
                    }), 'process_name')); 
                                    ?>
                    <?php                foreach ($processes as $process_name) { ?>
                    <tr>
                        <td class="sticky-col"><?= $machine ?></td>
                        <td class="sticky-col"><?= $names ?></td>
                        <td class="sticky-col"><?= $product_name ?></td>
                        <td class="sticky-col"><?= $process_name ?></td>
                        <?php 
                                            $totalRunningHrs = 0; 
                                            ?>
                        <?php                    foreach ($displayedMonths as $month) { ?>
                        <?php 
                                                $qty = '-';
                            $target = '-';
                            $wrk_hrs = '-';
                            $std_hrs = '-';
                            $diff_hrs = '-';
                            foreach ($target_summary as $value) {
                                if ($value->machine_name == $machine && $value->job_assigned_name == $names && $value->product_name == $product_name && $value->process_name == $process_name && $value->yr_month == $month) {
                                    $qty = $value->empqty;
                                    $target = $value->range_to;
                                    $wrk_hrs = $value->wrk_hrs;
                                    $std_hrs = $value->std_hrs;
                                    $diff_hrs = $value->hrs_diff;
                                    $totalRunningHrs += timeToSeconds($wrk_hrs);
                                    break;
                                }
                            }
                                                ?>
                        <td><?= $qty ?></td>
                        <td><?= $target ?></td>
                        <td><?= $wrk_hrs ?></td>
                        <td><?= $std_hrs ?></td>
                        <td><?= $diff_hrs ?></td>
                        <?php                    } ?>
                        <td><?= formatSecondsToTime($totalRunningHrs) ?></td>
                    </tr>
                    <?php                } ?>
                    <?php            } ?>
                    <?php        } ?>
                    <?php    } ?>
                </tbody>
            </table>
        </div>
        <?php } ?>
    </div>
    <!-- pop up -->
    <div class="modal fade" id="EmpprodctModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-white">Employee Product Type Wise Work Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6>Employee Name: <strong><span id="employee"></span></strong></h6>
                        </div>
                        <div class="col-md-6">
                            <h6>Product: <strong><span id="product"></span></strong></h6>
                        </div>
                    </div>
                    <div style="height: 450px; overflow-y: auto;">
                        <div id="loadingText" style="display: none; text-align: center;">Loading...</div>
                        <div id="employeprotable"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="MachineModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white">Machine Wise Work Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <h6>Machine Name: <strong><span id="machine_name"></span></strong></h6>
                    </div>
                    <div style="height: 450px; overflow-y: auto;">
                        <div id="machinetable"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')

    <script>

        // popup       
        $(document).ready(function () {
            $('.Empname').click(function () {
                // Show the modal
                $('#EmpprodctModal').modal('show');

                // Log the HTML of the clicked row
                var row = $(this).closest('tr');
                console.log("Row clicked: ", row.html());

                // Attempt to fetch the type from the correct column
                var employee = row.find('.sticky-col.Empname').text().trim();
                var product = row.find('.sticky-col-2.Product').text().trim();

                // Ensure the type is fetched correctly
                if (employee) {
                    // Placeholder dates - replace with actual data
                    var startDate = "{{ request('start_date') }}";
                    var endDate = "{{ request('end_date') }}";

                    // Update the type in the modal
                    $('#product').text(product);
                    $('#employee').text(employee);

                    // Build the query string
                    var params = $.param({
                        start_date: startDate,
                        end_date: endDate,
                        emp_name: employee,
                        product: product
                    });

                    // Build the full URL for the AJAX request
                    var url = "{{ route('productionprotype') }}" + "?" + params;

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


        // modal for product wrk hrs rpt

        $(document).ready(function () {
            $('.Machinename').click(function () {
                // Show the modal
                $('#MachineModal').modal('show');

                // Log the HTML of the clicked row
                var row = $(this).closest('tr');

                // Attempt to fetch the type from the correct column
                var machine = row.find('.sticky-col.Machinename').text().trim();

                // Ensure the type is fetched correctly
                if (machine) {
                    // Placeholder dates - replace with actual data
                    var pro_type = "{{ request('pro_type') }}";
                    var pro_name = "{{ request('pro_name') }}";

                    var startDate = "{{ request('start_date') }}";
                    var endDate = "{{ request('end_date') }}";

                    // Update the type in the modal
                    $('#machine_name').text(machine);

                    // Build the query string
                    var params = $.param({
                        start_date: startDate,
                        end_date: endDate,
                        machine: machine,
                        pro_type: pro_type,
                        pro_name: pro_name
                    });

                    // Build the full URL for the AJAX request
                    var url = "{{ route('productionmachinepopup') }}" + "?" + params;

                    // AJAX request to fetch data
                    $.get(url, function (data) {
                        // Update HTML content with received data
                        $('#machinetable').html(data);
                    });
                } else {
                    console.log("Type not found.");
                }
            });

            // Close modal button to close the sidebar
            $('#closeButton').click(function () {
                $('#MachineModal').modal('hide');
            });

            // Clear modal content when it is hidden
            $('#MachineModal').on('hidden.bs.modal', function () {
                $('#machinetable').html('');

            });
        });
        $(document).ready(function () {

            $('#Table1').DataTable({
                dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
                buttons: [
                    {
                        extend: 'colvis',
                        text: '<i class="bi bi-layout-three-columns"></i> Columns',
                        className: 'btn bg-primary btn-sm',
                        postfixButtons: ['colvisRestore'] // adds "Restore Columns" button
                    },
                    { extend: 'excelHtml5', title: "Product Wise Work Hours Report", exportOptions: { columns: ':visible' } }
                ]
            });

            $('#Table2').DataTable({
                dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
                buttons: [
                    {
                        extend: 'colvis',
                        text: '<i class="bi bi-layout-three-columns"></i> Columns',
                        className: 'btn bg-primary btn-sm',
                        postfixButtons: ['colvisRestore'] // adds "Restore Columns" button
                    },
                    { extend: 'excelHtml5', title: "Machine Wise Work Hours Report", exportOptions: { columns: ':visible' } }
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
                    { extend: 'excelHtml5', title: "Target Wise Work Report", exportOptions: { columns: ':visible' } }
                ]
            });
        });

        // trigger change
        $(document).ready(function () {

            var product_type = "{{ request('pro_type') }}";
            var Pro_name = "{{ request('pro_name') }}";
            var startDate = "{{ request('start_date') }}";
            var endDate = "{{ request('end_date') }}";

            $('.pro_name').select2();
            $('#pro_name').val(Pro_name).trigger('change');

            $('.pro_type').select2();
            $('#pro_type').val(product_type).trigger('change');
            $('#start_date').val(startDate);
            $('#end_date').val(endDate);

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