@extends('layouts.header')
@section('content')

<h3 class="text-danger">Overall Employee Report</h3>

<div class="container mt-4">
    <div class="row g-3 mb-3">
        <div class="col-12 col-md-3">
            <a href="operationanalyserpt" class="btn btn-outline-success w-100">Analyse Report</a>
        </div>
        <div class="col-12 col-md-3">
            <a href="overalloperationperformancereport" class="btn btn-primary w-100">Overall Employee Report</a>
        </div>
        <div class="col-12 col-md-3">
            <a href="operationperformancelogreport" class="btn btn-outline-warning w-100">Employee Performance Report</a>
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

<!--pop ops -->
<!-- Employee Product Wise Work Report Modal -->
<div class="modal fade" id="empModalMachine" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">Employee Product Wise Work Report</h5>
        <button id="closeButton" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row mb-3">
          <div class="col-md-6">
            <h6>Employee Name: <strong><span id="employee"></span></strong></h6>
          </div>
          <div class="col-md-6">
            <h6>Machine Name: <strong>{{ $machine[0]->machine_name }}</strong></h6>
          </div>
        </div>
        <div style="height: 450px; overflow-y: auto;">
          <div id="employeeMachrstable"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Employee Day Wise Work Report Modal -->
<div class="modal fade" id="empModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title">Employee Day Wise Work Report</h5>
        <button id="closeButton" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row mb-3 justify-content-end">
          <div class="col-md-8 text-center">
            <h6>Employee Name: <strong><span id="employeeName"></span></strong></h6>
          </div>
        </div>
        <div style="height: 450px; overflow-y: auto;">
          <div id="employeeHoursTable"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Employee Process Wise Work Report Modal -->
<div class="modal fade" id="EmprocessModal" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title">Employee Process Wise Work Report</h5>
        <button id="closeButton" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row mb-3">
          <div class="col-md-6">
            <h6>Employee Name: <strong><span id="employee_pname"></span></strong></h6>
          </div>
          <div class="col-md-6">
            <h6>Type: <strong><span id="type"></span></strong></h6>
          </div>
        </div>
        <div style="height: 450px; overflow-y: auto;">
          <div id="employeeprocesstable"></div>
        </div>
      </div>
    </div>
  </div>
</div>



	<div class="card shadow-lg rounded-4 border-0 p-4">
    <form action="{{ url('overalloperationperformancereport') }}" method="get" id="searchForm">
        <div class="row g-4">

            <!-- Supplier Name -->
            <div class="col-md-4">
                <label for="machine_name" class="form-label fw-semibold">Machine Name</label>
                <select name="machine_name" id="machine_name" class="form-select machine_name select2" required>
                    {!! $machine_name !!}
                </select>
            </div>

            <!-- From Date -->
            <div class="col-md-4">
                <label for="start_date" class="form-label fw-semibold">From Date</label>
                <input type="text" class="form-control start_date1" id="start_date" name="start_date" required placeholder="YYYY-MM-DD">
            </div>

            <!-- To Date -->
            <div class="col-md-4">
                <label for="end_date" class="form-label fw-semibold">To Date</label>
                <input type="text" class="form-control end_date1" id="end_date" name="end_date" required placeholder="YYYY-MM-DD">
            </div>

            <!-- Search Button -->
            <div class="col-12 text-center mt-3">
                <button type="submit" class="btn btn-primary px-5">Search</button>
            </div>

        </div>
    </form>
</div>

<!-- machine wise -->
<div class="card shadow-lg rounded-4 border-0 p-4">
	 <div class="col-md-12">
		 <h5 class="text-danger text-center">Overall Employee Machine Wise Work Hours Report - <b class="text-primary">{{ $machine[0]->machine_name }}</b></h5> 
	</div>
<div class="table-responsive" style="overflow-x: auto;">
		<table id="Table1" class="table table-bordered table-striped table-hover w-100">
        <thead>
              <?php if (empty($machine_summary)) { ?>
							<p class="nodata">No records available.</p>
						<?php } else { ?>	
        <tr class="sticky-row">
                        <th colspan="3" class="text-center text-white bg-danger">Yr_Month</th>
                        <?php $displayedMonths = array(); ?>
                        <?php $sno = 1; ?> 
                        <?php foreach ($machine_summary as $value) : ?>
                            <?php $monthYear = $value->yr_month ?>
                            <?php if (!in_array($monthYear, $displayedMonths)) : ?>
                                <th colspan="1" class="text-center text-white bg-secondary"><?= $monthYear; ?></th>
                                <?php $displayedMonths[] = $monthYear; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <th colspan="1" class="text-center text-white bg-secondary">Total</th>
                    </tr>
                    <tr class="sticky-row2">
                        <th class="text-center sticky-col">Sno</th>
                        <th class="text-center sticky-col">Employee Name</th>
                        <th class="text-center sticky-col">Type</th>
                        <?php foreach ($displayedMonths as $month) : ?>
                            <th class="text-center">Work Hrs</th>
                        <?php endforeach; ?>
                        <th colspan="1" class="text-center">Total Work Hrs</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $categories = array_unique(array_column($machine_summary, 'job_assigned_name')); ?>
                    <?php $types = array_unique(array_column($machine_summary, 'type')); ?>
                    <?php $grandTotalRunningHrs =  0; ?>
                    <?php $columnTotals = array_fill(0, count($displayedMonths) * 2, 0); ?>

                    <?php foreach ($categories as $category) { ?>
                        <?php $typesWithCategoryData = array_unique(array_column(array_filter($machine_summary, function ($value) use ($category) {
                            return $value->job_assigned_name == $category;
                        }), 'type')); ?>
                        <?php foreach ($typesWithCategoryData as $type) { ?>
                            <tr>
                                <td class="sticky-col"><?= $sno; ?></td>
                                <td style="text-decoration: underline;cursor: pointer;" class="sticky-col MachineEmp"><b><?= $category ?></b></td>
                                <td class="sticky-col-2"><?= $type ?></td>
                                <?php $totalRunningHrs = $totalIdleHrs = 0; ?>
                                <?php $colIndex = 0; ?>
                                <?php foreach ($displayedMonths as $month) { ?>
                                    <?php $foundData = false; ?>
                                    <?php foreach ($machine_summary as $value) { ?>
                                        <?php if (
                                            $value->job_assigned_name == $category &&
                                            $value->type == $type &&
                                            $value->yr_month == $month
                                        ) { ?>
                                            <td ><?= formatSecondsToTime(timeToSeconds($value->wrk_hrs)) ?></td>

                                            <?php /* Update totals for each row */ ?>
                                            <?php $totalRunningHrs += timeToSeconds($value->wrk_hrs); ?>

                                            <?php /* Update column totals */ ?>
                                            <?php $columnTotals[$colIndex] += timeToSeconds($value->wrk_hrs); ?>
                                            <?php $foundData = true; ?>
                                        <?php } ?>
                                    <?php } ?>
                                    <?php if (!$foundData) { ?>
                                        <td >-</td>
                                    <?php } ?>
                                    <?php $colIndex += 2; ?>
                                <?php } ?>
                                <!-- row Total -->
                                <td><?= formatSecondsToTime($totalRunningHrs) ?></td>
                            </tr>
                            <?php  $sno++;  ?>
                        <?php } ?>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr class="fw-bold">
                    <!-- Grand Total Row -->
                    <td>Total</td>
                    <td></td>
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

                        $colIndex += 1;
                    }
                    ?>
                    <td><?= formatSecondsToTime($grandTotalRunningHrs) ?></td>
                    </tr>
                    <!-- End of Grand Total Row -->
                    </tfoot>
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
		<table id="Table2" class="table table-bordered table-striped table-hover w-100">
        <thead>
              <?php if (empty($extra_hrs_cal)) { ?>
							<p class="nodata">No records available.</p>
						<?php } else { ?>	
                            <tr class="sticky-row">
                        <th colspan="2" class="text-center text-white bg-danger">Yr_Month</th>
                        <?php $displayedMonths = array(); ?>
                        <?php foreach ($extra_hrs_cal as $value) : ?>
                            <?php $monthYear = $value->yr_month ?>
                            <?php if (!in_array($monthYear, $displayedMonths)) : ?>
                                <th colspan="4" class="text-center text-white bg-secondary"><?= $monthYear; ?></th>
                                <?php $displayedMonths[] = $monthYear; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <th colspan="4" class="text-center text-white bg-secondary">Total</th>
                    </tr>
                    <tr class="sticky-row2">
                        <th class="text-center sticky-col">Sno</th>
                        <th class="text-center sticky-col">Employee Name</th>
                        <?php foreach ($displayedMonths as $month) : ?>
                            <th class="text-center">Work Hrs</th>
                            <th class="text-center">Qty</th>
                            <th class="text-center">Idle Hrs</th>
                            <th class="text-center">Extra Hrs</th>
                        <?php endforeach; ?>
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
                            <td style="text-decoration: underline;cursor: pointer;" class="sticky-col viewEmployee"><b><?= $category ?></b></td>
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
                                        <td ><?= formatSecondsToTime(max(0, timeToSeconds($value->extra_hrs))) ?></td>
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
                                    <td >-</td>
                                <?php }
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
                    <tr class="fw-bold">
                        <td>Total</td>
                        <td></td>
                        <?php
                        $colIndex = 0;

                        foreach ($columnTotals as $total) {
                            if ($colIndex % 4 == 0) { // Work Hrs
                                $grandTotalRunningHrs += $total; ?>
                                <td><?= formatSecondsToTime($total) ?></td>
                            <?php }

                            if ($colIndex % 4 == 1) { // Job Qty
                                $grandTotalJobQty += $total; ?>
                                <td><?= $total ?></td>
                            <?php }

                            if ($colIndex % 4 == 2) { // Idle Hrs
                                $grandTotalIdleHrs += $total; ?>
                                <td><?= formatSecondsToTime($total) ?></td>
                            <?php }

                            if ($colIndex % 4 == 3) { // Extra Hrs
                                $grandTotalExtraHrs += $total; ?>
                                <td><?= formatSecondsToTime($total) ?></td>
                            <?php }
                            $colIndex++;
                        }
                        ?>
                        <td><?= formatSecondsToTime($grandTotalRunningHrs) ?></td>
                        <td><?= $grandTotalJobQty ?></td>
                        <td><?= formatSecondsToTime($grandTotalIdleHrs) ?></td>
                        <td><?= formatSecondsToTime($grandTotalExtraHrs) ?></td>
                    </tr>
                </tfoot>
        </table>
	      <?php } ?>	
</div>
</div>


<!-- employee wise  -->
<div class="card shadow-lg rounded-4 border-0 p-4">
	 <div class="col-md-12">
		 <h5 class="text-danger text-center">Overall Employee Type Wise Work Hours Report</h5> 
	</div>
<div class="table-responsive" style="overflow-x: auto;">
		<table id="Table3" class="table table-bordered table-striped table-hover w-100">
        <thead>
              <?php if (empty($product_summary)) { ?>
							<p class="nodata">No records available.</p>
						<?php } else { ?>	
                            <tr class="sticky-row">
                        <th colspan="3" class="text-center text-white bg-danger">Yr_Month</th>
                        <?php $displayedMonths = array(); ?>
                        <?php $sno = 1; ?> 
                        <?php foreach ($product_summary as $value) : ?>
                            <?php $monthYear = $value->yr_month ?>
                            <?php if (!in_array($monthYear, $displayedMonths)) : ?>
                                <th colspan="2" class="text-center text-white bg-secondary"><?= $monthYear; ?></th>
                                <?php $displayedMonths[] = $monthYear; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <th colspan="2" class="text-center text-white bg-secondary">Total</th>
                    </tr>
                    <tr class="sticky-row2">
                        <th class="sticky-col">Sno</th>
                        <th class="sticky-col">Employee Name</th>
                        <th class="sticky-col">Type</th>
                        <?php foreach ($displayedMonths as $month) : ?>
                            <th>Work Hrs</th>
                            <th >Qty</th>
                        <?php endforeach; ?>
                        <th>Total Work Hrs</th>
                        <th>Total Qty</th>
                    </tr>
                    <tbody>
                    <?php $categories = array_unique(array_column($product_summary, 'job_assigned_name')); ?>
                    <?php $grandTotalRunningHrs = $grandTotalQty = 0; ?>
                    <?php $columnTotals = array_fill(0, count($displayedMonths) * 2, 0); ?>

                    <?php foreach ($categories as $category) { ?>
                        <?php $typesWithCategoryData = array_unique(array_column(array_filter($product_summary, function ($value) use ($category) {
                            return $value->job_assigned_name == $category;
                        }), 'type')); ?>
                        <?php foreach ($typesWithCategoryData as $type) { ?>
                            <tr>
                                <td class="text-center sticky-col"><?= $sno; ?></td>
                                <td style="text-decoration: underline;cursor: pointer;" class="text-center sticky-col Emprocess"><b><?= $category ?></b></td>
                                <td class="text-center sticky-col-2 Emptype"><?= $type ?></td>
                                <?php $totalRunningHrs = $totalQty = 0; ?>
                                <?php $colIndex = 0; ?>
                                <?php foreach ($displayedMonths as $month) { ?>
                                    <?php $foundData = false; ?>
                                    <?php foreach ($product_summary as $value) { ?>
                                        <?php if (
                                            $value->job_assigned_name == $category &&
                                            $value->type == $type &&
                                            $value->yr_month == $month
                                        ) { ?>
                                            <td><?= formatSecondsToTime(timeToSeconds($value->wrk_hrs)) ?></td>
                                            <td  ><?= htmlspecialchars($value->job_qty) ?></td>
                                            <?php /* Update totals for each row */ ?>
                                            <?php $totalRunningHrs += timeToSeconds($value->wrk_hrs); ?>
                                            <?php $totalQty += is_numeric($value->job_qty) ? $value->job_qty : 0; ?>
                                            <?php /* Update column totals */ ?>
                                            <?php $columnTotals[$colIndex] += timeToSeconds($value->wrk_hrs); ?>
                                            <?php $columnTotals[$colIndex + 1] += is_numeric($value->job_qty) ? $value->job_qty : 0; ?>
                                            <?php $foundData = true; ?>
                                        <?php } ?>
                                    <?php } ?>
                                    <?php if (!$foundData) { ?>
                                        <td >-</td>
                                        <td>-</td>
                                    <?php } ?>
                                    <?php $colIndex += 2; ?>
                                <?php } ?>
                                <!-- row Total -->
                                <td><?= formatSecondsToTime($totalRunningHrs) ?></td>
                                <td><?= $totalQty ?></td>
                            </tr>
                            <?php  $sno++;  ?>
                        <?php } ?>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr class="fw-bold">
                        <!-- Grand Total Row -->
                        <td>Total</td>
                        <td></td>
                        <td></td>
                        <?php
                        $colIndex = 0;
                        foreach ($columnTotals as $index => $total) {
                            if ($index % 2 == 0) { // Work Hours
                                $grandTotalRunningHrs += $total;
                        ?>
                                <td><?= formatSecondsToTime($total) ?></td>
                        <?php
                            } else { // Qty
                                $grandTotalQty += $total;
                        ?>
                                <td><?= $total ?></td>
                        <?php
                            }
                        }
                        ?>
                        <td><?= formatSecondsToTime($grandTotalRunningHrs) ?></td>
                        <td><?= $grandTotalQty ?></td>
                    </tr>
                </tfoot>
        </table>
	      <?php } ?>	
</div>
</div>

@endsection
@push('scripts')

<script>

         $(document).ready(function() {
	// machine modal		 
        $('.MachineEmp').click(function() {
       // Show the modal
       $('#empModalMachine').modal('show');
   
        // Log the HTML of the clicked row
        var row = $(this).closest('tr');

        // Attempt to fetch the employee name from the correct column
        var employee = row.find('.sticky-col.MachineEmp').text().trim();
        // Ensure the employee name is fetched correctly
        if (employee) {
            // Placeholder dates - replace with actual data
            var startDate = "{{ request('start_date') }}";
            var endDate = "{{ request('end_date') }}";
            var machineName = "{{ request('machine_name') }}";
            // Update the employee name in the modal
            $('#employee').text(employee);
   

       // Build the query string
       var params = $.param({
           start_date: startDate,
           end_date: endDate,
           employee: employee,
		   machine :machineName

       });
   
       // Build the full URL for the AJAX request
       var url = "{{ route('employemachinewrkpopup') }}" + "?" + params;
   
       // AJAX request to fetch data
       $.get(url, function(data) {
           // Update HTML content with received data
           $('#employeeMachrstable').html(data);
       });
   } else {
       console.log("Type not found.");
   }
   });
   
   // Close modal button to close the sidebar
   $('#closeButton').click(function() {
   $('#empModalMachine').modal('hide');
   });
   
   // Clear modal content when it is hidden
   $('#empModalMachine').on('hidden.bs.modal', function () {
   $('#employeeMachrstable').html('');  
   
       });

		  // employee modal
		  
        $('.viewEmployee').click(function() {
       // Show the modal
       $('#empModal').modal('show');
   
        // Log the HTML of the clicked row
        var row = $(this).closest('tr');

        // Attempt to fetch the employee name from the correct column
        var employeeName = $(this).closest('tr').find('.sticky-col:eq(1)').text();
        // Ensure the employee name is fetched correctly
        if (employeeName) {
            // Placeholder dates - replace with actual data
            var startDate = "{{ request('start_date') }}";
            var endDate = "{{ request('end_date') }}";
            
            // Update the employee name in the modal
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
       $.get(url, function(data) {
           // Update HTML content with received data
           $('#employeeHoursTable').html(data);
       });
   } else {
       console.log("Type not found.");
   }
   });
   
   // Close modal button to close the sidebar
   $('#closeButton').click(function() {
   $('#empModal').modal('hide');
   });
   
   // Clear modal content when it is hidden
   $('#empModal').on('hidden.bs.modal', function () {
   $('#employeeHoursTable').html('');  
   
       });

	//overall emp popup		 
	        $('.Emprocess').click(function() {
       // Show the modal
       $('#EmprocessModal').modal('show');
   
        // Log the HTML of the clicked row
        var row = $(this).closest('tr');

        // Attempt to fetch the employee name from the correct column
        var employeeName = $(this).closest('tr').find('.sticky-col:eq(1)').text();
		var type = row.find('.sticky-col-2.Emptype').text().trim();
        // Ensure the employee name is fetched correctly
        if (employeeName) {
            // Placeholder dates - replace with actual data
            var startDate = "{{ request('start_date') }}";
            var endDate = "{{ request('end_date') }}";
            
            // Update the employee name in the modal
            $('#employee_pname').text(employeeName);
		   $('#type').text(type);

       // Build the query string
       var params = $.param({
           start_date: startDate,
           end_date: endDate,
           employeeName: employeeName,
		   type : type
       });
   
       // Build the full URL for the AJAX request
       var url = "{{ route('employeprocesspopup') }}" + "?" + params;
   
       // AJAX request to fetch data
       $.get(url, function(data) {
           // Update HTML content with received data
           $('#employeeprocesstable').html(data);
       });
   } else {
       console.log("Type not found.");
   }
   });
   
   // Close modal button to close the sidebar
   $('#closeButton').click(function() {
   $('#EmprocessModal').modal('hide');
   });
   
   // Clear modal content when it is hidden
   $('#EmprocessModal').on('hidden.bs.modal', function () {
   $('#employeeprocesstable').html('');  
   
       });		 
 });


    
    // tables
        $(document).ready(function() {
        
        $('#Table1').DataTable({
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
                    { extend: 'excelHtml5', title: "Overall Employee Machine Wise Work Hours Report", exportOptions: { columns: ':visible' } }
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
                    { extend: 'excelHtml5', title: "Overall Employee Hours Report", exportOptions: { columns: ':visible' } }
                ]
        });
        $('#Table3').DataTable({
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
                    { extend: 'excelHtml5', title: "Overall Employee Type Wise Work Hours Report", exportOptions: { columns: ':visible' } }
                ]
        });
    });
    
    // trigger change
         $(document).ready(function() {
             
            var machineName = "{{ request('machine_name') }}";
            var startDate = "{{ request('start_date') }}";
            var endDate = "{{ request('end_date') }}";
            
            $('.machine_name').select2();
            $('#machine_name').val(machineName).trigger('change');
        
        
            $('#start_date').val(startDate);
            $('#end_date').val(endDate);
        
        });

    </script>

@endpush