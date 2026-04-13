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
<h3 class="text-danger">Maintenance Machine Details</h3>

<div class="container mt-4">
    <div class="row g-3 mb-3">
        <div class="col-12 col-md-4">
            <a href="machinemintenancelogreport" class="btn btn-success w-100">Machine Dashboard</a>
        </div>
        <div class="col-12 col-md-4">
            <a href="productmaintenancelogreport" class="btn btn-outline-primary w-100">Product Dashboard</a>
        </div>
    </div>
</div>

    <?php
    function timeToSeconds($time) {
        list($hours, $minutes, $seconds) = explode(":", $time);
        return $hours * 3600 + $minutes * 60 + $seconds;
    }
    
    function formatSecondsToTime($seconds) {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;
    
        return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
    }
    ?>

<div class="card shadow-lg rounded-4 border-0 p-4">
<form action="{{ url('machinemintenancelogreport') }}" method="get" id="searchForm">
  <div class="row g-3 align-items-center mb-3">
    <div class="col-md-4">
      <label for="machine_name" class="form-label">Machine Name</label>
      <select name="machine_name" id="machine_name" class="form-select select2" required>
        {!! $machine_name !!}
      </select>
    </div>

    <div class="col-md-4">
      <label for="start_date" class="form-label">Start Date</label>
      <input type="text" class="form-control start_date1" id="start_date" name="start_date"  required placeholder="YYYY-MM-DD">
    </div>

    <div class="col-md-4">
      <label for="end_date" class="form-label">End Date</label>
      <input type="text" class="form-control end_date1" id="end_date" name="end_date"  required placeholder="YYYY-MM-DD">
    </div>
  </div>

  <div class="row">
    <div class="col-12 text-center mt-3">
      <button type="submit" class="btn btn-primary px-4">Search</button>
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
                <p class="nodata fw-bold">Type Wise Summary Chart (Preventive and Breakdown)</p>
                <div class="dash_charrt">
                    <canvas id="pieChart"></canvas>
                    <p class="nodata" id="pieChart1"></p>
                </div>
					</div>
            </div>
        </div>
        
          <!-- type summary table -->
	<div class="card shadow-lg rounded-4 border-0 p-4">
        <h4 class="text-primary">Type Wise Summary Report</h4>
        <div class="table-responsive" style="overflow-x: auto;">
		<table id="Table1" class="table table-bordered table-striped table-hover w-100">
        <thead>
              <?php if (empty($prim_summary)) { ?>
					<p class="nodata">No records available.</p>
				<?php } else { ?>	

                    <tr>
                        <th colspan="2" class="text-white text-center bg-danger">Particulars</th>
                        <?php $displayedMonths = []; ?>
                        <?php foreach ($prim_summary as $value) {
                            $monthYear = $value->log_month;
                            if (!in_array($monthYear, $displayedMonths)) {
                                $displayedMonths[] = $monthYear; ?>
                                <th colspan="4" class="text-white text-center bg-secondary"><?= $monthYear; ?></th>
                        <?php }
                        } ?>
                        <th colspan="4" class="text-white text-center bg-secondary">Total</th>
                    </tr>
                    <tr>
                        <th class="freeze bg-secondary text-white">Department</th>
                        <th class="bg-secondary text-white">Machine Name</th>
                        <?php foreach ($displayedMonths as $month) { ?>
                            <th class="table-primary">Qty</th>
                            <th class="table-primary">Run Hrs</th>
                            <th class="table-primary">OPT P/H(M)</th>
                            <th class="table-primary">Idle Hrs</th>
                        <?php } ?>
                        <th class="table-warning">Total Qty</th>
                        <th class="table-warning">Total Run Hrs</th>
                        <th class="table-warning">Total OPT Hrs</th>
                        <th class="table-warning">Total Idle Hrs</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $departments = array_unique(array_column($prim_summary, 'process_dept')); ?>
                    <?php $machines = array_unique(array_column($prim_summary, 'machine_name')); ?>
                    <?php $grandTotalQty = $grandTotalRunningHrs = $grandTotalIdleHrs = $grandtotaloptHrs = 0; ?>
                    <?php $columnTotals = array_fill(0, count($displayedMonths) * 4, 0); ?>

                    <?php foreach ($departments as $department) { ?>
                        <?php foreach ($machines as $machine) { ?>
                            <tr>
                                <td class="freeze"><?= $department ?></td>
                                <td><?= $machine ?></td>
                                <?php $totalQty = $totalRunningHrs = $totaloptHrs  = $totalIdleHrs = 0; ?>
                                <?php $colIndex = 0; ?>
                                <?php foreach ($displayedMonths as $month) { ?>
                                    <?php $foundData = false; ?>
                                    <?php foreach ($prim_summary as $value) { ?>
                                        <?php if (
                                            $value->process_dept == $department &&
                                            $value->machine_name == $machine &&
                                            $value->log_month == $month
                                        ) { ?>
                                            <td><?= $value->total_quantity ?></td>
                                            <td><?= formatSecondsToTime(timeToSeconds($value->total_running_hours)) ?></td>
                                            <td><?= $value->opt_hrs ?></td>
                                            <td><?= formatSecondsToTime(timeToSeconds($value->total_idle_hours)) ?></td>
                                            <?php /* Update totals for each row */ ?>
                                            <?php $totalQty += $value->total_quantity; ?>
                                            <?php $totalRunningHrs += timeToSeconds($value->total_running_hours); ?>
                                            <?php $totaloptHrs += $value->opt_hrs; ?>
                                            <?php $totalIdleHrs += timeToSeconds($value->total_idle_hours); ?>
                                            <?php /* Update column totals */ ?>
                                            <?php $columnTotals[$colIndex] += $value->total_quantity; ?>
                                            <?php $columnTotals[$colIndex + 1] += timeToSeconds($value->total_running_hours); ?>
                                            <?php if($columnTotals[$colIndex + 1] != 0) { ?>
                                            <?php $columnTotals[$colIndex + 2] = round(($columnTotals[$colIndex] / $columnTotals[$colIndex + 1] * 3600),0) ; ?>
                                            <?php } else { echo '';  }?>
                                            <?php $columnTotals[$colIndex + 3] = (200 * 3600) - $columnTotals[$colIndex + 1]; ?>
                                            <?php $foundData = true; ?>
                                        <?php } ?>
                                    <?php } ?>
                                    <?php if (!$foundData) { ?>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                    <?php } ?>
                                    <?php $colIndex += 4; ?>
                                <?php } ?>
                                <!-- row Total -->
                                <td><?= $totalQty ?></td>
                                <td><?= formatSecondsToTime($totalRunningHrs) ?></td>
                                <td><?php if ($totalRunningHrs > 0) { echo round(($totalQty / $totalRunningHrs * 3600), 0);  } else {   echo '';   } ?> </td>
                                <td><?= formatSecondsToTime($totalIdleHrs) ?></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                    <tr class="fw-bold">
                    <!-- Grand Total Row -->
                    <td class="freeze">Grand Total</td>
                    <td ></td>
                    <?php $colIndex = 0; ?>
                    <?php foreach ($columnTotals as $total) :
                        if ($colIndex % 4 == 0) $grandTotalQty += $total;
                        if ($colIndex % 4 == 1) $grandTotalRunningHrs += $total;
                        if ($colIndex % 4 == 2) {
                        if ($grandTotalRunningHrs != 0) {
                            $grandtotaloptHrs = round(($grandTotalQty / $grandTotalRunningHrs * 3600), 0);
                        } else {
                            echo '';
                        }
                         }
                        if ($colIndex % 4 == 3) $grandTotalIdleHrs = $total; ?>

                        <?php if ($colIndex % 4 == 1 || $colIndex % 4 == 3) : ?>
                            <td ><?= formatSecondsToTime($total) ?></td>
                        <?php else : ?>
                            <td ><?= $total ?></td>
                        <?php endif; ?>

                    <?php $colIndex += 1; ?>
                    <?php endforeach; ?>
                    <td><?= $grandTotalQty ?></td>
                    <td><?= formatSecondsToTime($grandTotalRunningHrs) ?></td>
                   <td>
                   <?= $grandTotalRunningHrs != 0 ? round(($grandTotalQty / $grandTotalRunningHrs * 3600), 0) : 0 ?></td>
                   <td><?= formatSecondsToTime(timeToSeconds('200:00:00') * count($displayedMonths) - $grandTotalRunningHrs) ?></td>
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

        <!-- prim summary chart -->
         <div class='row'>
                 <div class='col-md-6'>
					 <div class="card shadow-lg rounded-4 border-0 p-4">
              <p class="nodata fw-bold">Category Wise Chart Report </p>
                <div class="dash_charrt">
                    <canvas id="primChart"></canvas>
                     <p class="nodata" id="primChart1"></p>
                </div>
						  </div>
            </div>
                    <!-- breakdown summary chart -->
            <div class='col-md-6'>
				<div class="card shadow-lg rounded-4 border-0 p-4">
         <p class="nodata fw-bold">Breakdown Chart Report </p>
                <div class="dash_charrt">
                    <canvas id="breakChart"></canvas>
                    <p class="nodata" id="breakChart1"></p>
                </div>
         </div>
    <!-- end -->   
            </div>
         </div>
    <!-- END -->

   <!-- category wise summary table -->
<div class="card shadow-lg rounded-4 border-0 p-4">
    <h4 class="text-primary">Category  Wise Summary Report</h4>
        <div class="table-responsive" style="overflow-x: auto;">
		<table id="Table2" class="table table-bordered table-striped table-hover w-100">
        <thead>
              <?php if (empty($type_summary)) { ?>
					<p class="nodata">No records available.</p>
				<?php } else { ?>	

                    <tr>
                        <th colspan="1" class="text-white text-center bg-danger">Particulars</th>
                        <?php $displayedMonths = array(); ?>
                        <?php foreach ($type_summary as $value) : ?>
                            <?php $monthYear = $value->log_month ?>
                            <?php if (!in_array($monthYear, $displayedMonths)) : ?>
                                <th colspan="4" class="text-white text-center bg-secondary"><?= $monthYear; ?></th>
                                <?php $displayedMonths[] = $monthYear; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <th colspan="4" class="text-white text-center bg-secondary">Total</th>
                    </tr>
                    <tr>
                        <th class="freeze text-white text-center bg-secondary">Category</th>
                        <?php foreach ($displayedMonths as $month) : ?>
                            <th class="table-primary">Qty</th>
                            <th class="table-primary">Running Hrs</th>
                            <th class="table-primary">OPT P/H(M)</th>
                            <th class="table-primary">Idle Hrs</th>
                        <?php endforeach; ?>
                        <th class="table-warning">Total Qty</th>
                        <th class="table-warning">Total Running Hrs</th>
                        <th class="table-warning">Total OPT Hrs</th>
                        <th class="table-warning">Total Idle Hrs</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $categories = array_unique(array_column($type_summary, 'category')); ?>
                    <?php $grandTotalQty = $grandTotalRunningHrs = $grandTotalIdleHrs = $grandtotaloptHrs = 0; ?>
                    <?php $columnTotals = array_fill(0, count($displayedMonths) * 4, 0); ?>

                    <?php foreach ($categories as $category) : ?>
                        <tr>
                            <td class="freeze"><?= $category ?></td>
                            <?php $totalQty = $totalRunningHrs = $totalIdleHrs = $totaloptHrs = 0; ?>
                            <?php $colIndex = 0; ?>
                            <?php foreach ($displayedMonths as $month) : ?>
                                <?php $foundData = false; ?>
                                <?php foreach ($type_summary as $value) : ?>
                                    <?php if ($value->category == $category && $value->log_month == $month) : ?>
                                        <td><?= $value->total_quantity ?></td>
                                      <td><?= formatSecondsToTime(timeToSeconds($value->total_running_hours)) ?></td>
                                        <td><?= $value->opt_hrs ?></td>
                                      <td><?= formatSecondsToTime(timeToSeconds($value->total_idle_hours)) ?></td>
                                        <?php /* Update totals for each row */ ?>
                                        <?php $totalQty += $value->total_quantity; ?>
                                        <?php $totalRunningHrs += timeToSeconds($value->total_running_hours); ?>
                                        <?php $totaloptHrs += $value->opt_hrs; ?>
                                        <?php $totalIdleHrs += timeToSeconds($value->total_idle_hours); ?>
                                        <?php /* Update column totals */ ?>
                                        <?php $columnTotals[$colIndex] += $value->total_quantity; ?>
                                        <?php $columnTotals[$colIndex + 1] += timeToSeconds($value->total_running_hours); ?>
                                        <?php if($columnTotals[$colIndex + 1] != 0) { ?>
                                            <?php $columnTotals[$colIndex + 2] = round(($columnTotals[$colIndex] / $columnTotals[$colIndex + 1] * 3600),0) ; ?>
                                            <?php } else { echo ''; }?>
                                        <?php $columnTotals[$colIndex + 3] = (200 * 3600) - $columnTotals[$colIndex + 1]; ?>
                                        <?php $foundData = true; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <?php if (!$foundData) : ?>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                <?php endif; ?>
                                <?php $colIndex += 4; ?>
                            <?php endforeach; ?>
                                <td><?= $totalQty ?></td>
                                <td><?= formatSecondsToTime($totalRunningHrs) ?></td>
                                 <td><?php if ($totalRunningHrs > 0) { echo round(($totalQty / $totalRunningHrs * 3600), 0);  } else {   echo '';   } ?> </td>
                                <td><?= formatSecondsToTime($totalIdleHrs) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    
                    <!-- Grand Total Row -->
                     <tr class="fw-bold">
                     <td class="freeze">Grand Total</td>
                  
                    <?php $colIndex = 0; ?>
                    <?php foreach ($columnTotals as $total) :
                        if ($colIndex % 4 == 0) $grandTotalQty += $total;
                        if ($colIndex % 4 == 1) $grandTotalRunningHrs += $total;
                                                if ($colIndex % 4 == 2) {
                        if ($grandTotalRunningHrs != 0) {
                            $grandtotaloptHrs = round(($grandTotalQty / $grandTotalRunningHrs * 3600), 0);
                        } else {
                            echo '';
                        }
                         }
                        if ($colIndex % 4 == 3) $grandTotalIdleHrs += $total; ?>

                        <?php if ($colIndex % 4 == 1 || $colIndex % 4 == 3) : ?>
                            <td ><?= formatSecondsToTime($total) ?></td>
                        <?php else : ?>
                            <td ><?= $total ?></td>
                        <?php endif; ?>

                        <?php $colIndex += 1; ?>
                    <?php endforeach; ?>
                    <td ><?= $grandTotalQty ?></td>
                    <td ><?= formatSecondsToTime($grandTotalRunningHrs) ?></td>
                                       <td >
                   <?= $grandTotalRunningHrs != 0 ? round(($grandTotalQty / $grandTotalRunningHrs * 3600), 0) : 0 ?></td>
                    <td ><?= formatSecondsToTime(timeToSeconds('200:00:00') * count($displayedMonths) - $grandTotalRunningHrs) ?></td>
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

<!-- product wise summary table -->
<div class="card shadow-lg rounded-4 border-0 p-4">
    <h4 class="text-primary">Product   Wise Summary Report</h4>
        <div class="table-responsive" style="overflow-x: auto;">
		<table id="Table3" class="table table-bordered table-striped table-hover w-100">
        <thead>
              <?php if (empty($product_summary)) { ?>
					<p class="nodata">No records available.</p>
				<?php } else { ?>	

                    <tr class="sticky-row">
                        <th colspan="1"  class="text-white text-center bg-danger">Particulars</th>
                        <?php $displayedMonths = array(); ?>
                        <?php foreach ($product_summary as $value) : ?>
                            <?php $monthYear =  $value->log_month ?>
                            <?php if (!in_array($monthYear, $displayedMonths)) : ?>
                                <th colspan="4"  class="text-white text-center bg-secondary"><?= $monthYear; ?></th>
                                <?php $displayedMonths[] = $monthYear; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <th colspan="4" class="text-white text-center bg-secondary">Total</th>
                    </tr>
                    <tr  class="sticky-row2">
                        <th class="freeze text-white text-center bg-secondary">Product Name</th>
                        <?php foreach ($displayedMonths as $month) : ?>
                            <th class="table-primary">Qty</th>
                            <th class="table-primary">Running Hrs</th>
                             <th class="table-primary">OPT P/H(M)</th>
                            <th class="sticky-row2 table-primary">Idle Hrs</th>
                        <?php endforeach; ?>
                        <th class="table-warning">Total Qty</th>
                        <th class="table-warning">Total Running Hrs</th>
                         <th class="table-warning">Total OPT P/H(M)</th>
                        <th class="table-warning">Total Idle Hrs</th>
                    </tr>
                </thead>
               <tbody>
    <?php $products = array_unique(array_column($product_summary, 'product_name')); ?>
    <?php $grandTotalQty = $grandTotalRunningHrs = $grandTotalIdleHrs = $grandtotaloptHrs = 0; ?>
    <?php $columnTotals = array_fill(0, count($displayedMonths) * 4, 0); ?>

    <?php foreach ($products as $product) : ?>
        <tr>
            <td class="freeze"><?= $product ?></td>

            <?php $monthlyTotals = array('qty' => 0, 'running_hrs' => 0, 'idle_hrs' => 0, 'opt_hrs' => 0); ?>
            <?php $colIndex = 0; ?>
            <?php foreach ($displayedMonths as $month) : ?>
                <?php $foundData = false; ?>
                <?php foreach ($product_summary as $value) : ?>
                    <?php if ($value->product_name == $product &&  $value->log_month == $month) : ?>
                        <td><?= $value->total_quantity ?></td>
                        <td><?= formatSecondsToTime(timeToSeconds($value->total_running_hours)) ?></td>
                        <td><?= $value->opt_hrs?></td>
                        <td><?= formatSecondsToTime(timeToSeconds($value->total_idle_hours)) ?></td>
                        <?php /* Update monthly totals */ ?>
                        <?php $monthlyTotals['qty'] += $value->total_quantity; ?>
                        <?php $monthlyTotals['running_hrs'] += timeToSeconds($value->total_running_hours); ?>
                        <?php $monthlyTotals['opt_hrs'] += $value->opt_hrs; ?>
                        <?php $monthlyTotals['idle_hrs'] += timeToSeconds($value->total_idle_hours); ?>
                        <?php /* Update column totals */ ?>
                        <?php $columnTotals[$colIndex] += $value->total_quantity; ?>
                        <?php $columnTotals[$colIndex + 1] += timeToSeconds($value->total_running_hours); ?>
                        <?php if($columnTotals[$colIndex + 1] != 0) { ?>
                        <?php $columnTotals[$colIndex + 2] = round(($columnTotals[$colIndex] / $columnTotals[$colIndex + 1] * 3600),0) ; ?>
                        <?php } else { echo ''; }?>
                        <?php $columnTotals[$colIndex + 3] = (200 * 3600) - $columnTotals[$colIndex + 1]; ?>
                        <?php $foundData = true; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php if (!$foundData) : ?>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                <?php endif; ?>
                <?php $colIndex += 4; ?>
            <?php endforeach; ?>
            <!-- Monthly Total -->
            <td><?= $monthlyTotals['qty'] ?></td>
            <td><?=formatSecondsToTime( $monthlyTotals['running_hrs']) ?></td>
             <td><?php if ($monthlyTotals['running_hrs'] > 0) { echo round (( $monthlyTotals['qty'] / $monthlyTotals['running_hrs']  * 3600), 0);  } else {   echo '';   } ?> </td>
            <td><?= formatSecondsToTime($monthlyTotals['idle_hrs']) ?></td>
        </tr>
            <?php endforeach; ?>

    <!-- Grand Total Row -->
     <tr class="fw-bold">
 <td class="freeze">Grand Total</td>
                    <?php $colIndex = 0; ?>
                    <?php foreach ($columnTotals as $total) :
                        if ($colIndex % 4 == 0) $grandTotalQty += $total;
                        if ($colIndex % 4 == 1) $grandTotalRunningHrs += $total;
                        if ($colIndex % 4 == 2) {
                        if ($grandTotalRunningHrs != 0) {
                            $grandtotaloptHrs = round(($grandTotalQty / $grandTotalRunningHrs * 3600), 0);
                        } else {
                            echo '';
                        }
                         }
                        if ($colIndex % 4 == 3) $grandTotalIdleHrs += $total; ?>

                        <?php if ($colIndex % 4 == 1 || $colIndex % 4 == 3) : ?>
                            <td ><?= formatSecondsToTime($total) ?></td>
                        <?php else : ?>
                            <td ><?= $total ?></td>
                        <?php endif; ?>

                        <?php $colIndex += 1; ?>
                    <?php endforeach; ?>
                    <td ><?= $grandTotalQty ?></td>
                    <td ><?= formatSecondsToTime($grandTotalRunningHrs) ?></td>
                    <td >
                   <?= $grandTotalRunningHrs != 0 ? round(($grandTotalQty / $grandTotalRunningHrs * 3600), 0) : 0 ?></td>
                    <td ><?= formatSecondsToTime(timeToSeconds('200:00:00') * count($displayedMonths) - $grandTotalRunningHrs) ?></td>
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


    <!-- product wise summary chart -->
            <div class="card shadow-lg rounded-4 border-0 p-4">
                 
                <p class="nodata fw-bold">Product Chart Report </p>
                <div class="dash_charrt">
                    <canvas id="proChart" style="max-height:380px"></canvas>
                    <p class="nodata" id="proChart1"></p>
                </div>
            </div>
                <div class="card shadow-lg rounded-4 border-0 p-4">
                <p class="nodata fw-bold">Product Chart Report </p>
                <div class="dash_charrt">
                    <canvas id="myChart" style="max-height:380px"></canvas>
                    <p class="nodata" id="myChart1"></p>
                </div>
            </div>
     
    
           <!-- breakdown summary table -->   
		<div class="card shadow-lg rounded-4 border-0 p-4">
            <h4 class="text-primary">Breakdown Summary Report</h4>
        <div class="table-responsive" style="overflow-x: auto;">
		<table id="Table4" class="table table-bordered table-striped table-hover w-100">
        <thead>
              <?php if (empty($break_summary)) { ?>
					<p class="nodata">No records available.</p>
				<?php } else { ?>	

                    <thead>
                    <tr class="text-white text-center bg-danger">
                        <th class="text-white text-center bg-danger">Department</th>
                        <th class="text-white text-center bg-danger">Causes</th>
                        <th class="text-white text-center bg-danger">Remarks</th>
                        <th class="text-white text-center bg-danger">Qty</th>
                         <th class="text-white text-center bg-danger">Idle Hrs</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $currentMonth = null;
                    $monthlyTotalQty = $monthlyTotalRunningHrs = $monthlyTotalIdleHrs = 0;
                    $grandTotalQty = $grandTotalRunningHrs = $grandTotalIdleHrs = 0;
                    ?>
                    <?php foreach ($break_summary as $value) { ?>
                        <?php if ($currentMonth !== $value->log_month) { ?>
                            <?php if ($currentMonth !== null) { ?>
                        
                                <!-- Reset monthly totals after displaying them -->
                                <?php $monthlyTotalQty = $monthlyTotalRunningHrs = $monthlyTotalIdleHrs = 0; ?>
                            <?php } ?>

                            <?php $currentMonth = $value->log_month; ?>
                            <tr>
                                <th colspan="6" style="font-size: 14px !important; background-color: #b3d1ff;"><?php echo  $value->log_month; ?></th>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td><?= $value->process_dept ?></td>
                            <td><?= $value->machine_name ?></td>
                            <td><?= $value->request_remark ?></td>
                            <td><?= $value->total_quantity ?></td>
                            <td><?= formatSecondsToTime(timeToSeconds($value->total_idle_hours)) ?></td>
                        </tr>
                        <?php

                        $grandTotalQty += $value->total_quantity;
                        $grandTotalRunningHrs +=timeToSeconds( $value->total_running_hours);
                        $grandTotalIdleHrs += timeToSeconds($value->total_idle_hours);
                        ?>
                    <?php } ?>


                    <!-- Grand Total Row -->
                    <tr style="font-weight: bold; background: #ffc6c6 !important;">
                        <td colspan="3" >Grand Total</td>
                        <td style="font-weight: 800 !important;"><?= $grandTotalQty ?></td>
                        <td style="font-weight: 800 !important;"><?= formatSecondsToTime($grandTotalIdleHrs) ?></td>
                    </tr>
                </tbody>
    </table>
</div>
		      <?php } ?>	
</div>
    <!-- end-->                         

@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<script>

$(document).ready(function() {
    var machineName = "{{ request('machine_name') }}";
    var startDate = "{{ request('start_date') }}";
    var endDate = "{{ request('end_date') }}";
    
    $('.machine_name').select2();
    $('#machine_name').val(machineName).trigger('change');


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
    
   // type wise Summary chart

const chartData = {!! $prim_sumchart !!};
console.log(chartData);

if (chartData.length == 0) {
    document.getElementById("typeChart1").innerHTML = "No Data available.";

} else {
    var xValues = chartData.map(item => item.name);
    var dept = chartData.map(item => item.dept);
    var uniqueDepts = [...new Set(dept)];
    var yValues = chartData.map(item => item.value);
    var barColors = Array.from({ length: xValues.length }, () => generateRandomColor());

    new Chart("typeChart", {
        type: "bar",
        data: {
            labels: xValues,
            datasets: [{
                label: 'Total Running Hours',
                backgroundColor: barColors,
                data: yValues
            }]
        },
        options: {
            "hover": {
      "animationDuration": 0
    },
    "animation": {
      "duration": 1,
      "onComplete": function() {
        var chartInstance = this.chart,
          ctx = chartInstance.ctx;

        ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, 'bold ' + Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
        ctx.textAlign = 'center';
            ctx.textBaseline = 'bottom';   
            ctx.fillStyle = 'black';

        this.data.datasets.forEach(function(dataset, i) {
          var meta = chartInstance.controller.getDatasetMeta(i);
          meta.data.forEach(function(bar, index) {
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
                text: uniqueDepts.join(', ') // Join department names if there are multiple
            }
        }
    });
}

    //end
  // category chart
const primchartData = {!! $type_sumchart !!};

if (primchartData.length == 0) {
    document.getElementById("primChart1").innerHTML = "No Data available.";
} else {
    var uniqueMonths = [...new Set(primchartData.map(item => item.month))];
    var uniqueProducts = [...new Set(primchartData.map(item => item.name))];

    var datasets = uniqueProducts.map((product, index) => {
        var productValues = primchartData
            .filter(item => item.name === product)
            .map(item => item.value);

        var barColor = generateRandomColor();

        return {
            label: product,
            backgroundColor: barColor,
            data: uniqueMonths.map(month => {
                var dataPoint = primchartData.find(item => item.month === month && item.name === product);
                return dataPoint ? dataPoint.value : 0;
            })
        };
    });

    new Chart("primChart", {
        type: "bar",
        data: {
            labels: uniqueMonths,
            datasets: datasets
        },
        options: {
            "hover": {
      "animationDuration": 0
    },
    "animation": {
      "duration": 1,
      "onComplete": function() {
        var chartInstance = this.chart,
          ctx = chartInstance.ctx;

       ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, 'bold ' + Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
        ctx.textAlign = 'center';
          ctx.textBaseline = 'bottom';      
          ctx.fillStyle = 'black';

        this.data.datasets.forEach(function(dataset, i) {
          var meta = chartInstance.controller.getDatasetMeta(i);
          meta.data.forEach(function(bar, index) {
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
                        var currentValue = dataset.data[tooltipItem.index];
                        return dataset.label + ": " + currentValue + "Hrs";
                    }
                }
            }
        }
    });
}

    //end
    
  
    //end
// product  chart
 const productData = {!! $product_sumchart !!};

if (productData.length == 0) {
    document.getElementById("proChart1").innerHTML = "No Data available.";
} else {
    var uniqueMonths = [...new Set(productData.map(item => item.month))];
    var uniqueProducts = [...new Set(productData.map(item => item.name))];

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
    // preventive and break down type chart
  const prevenData = {!! $preven_chart !!};
console.log(prevenData);
if (prevenData.length == 0) {
    document.getElementById("pieChart1").innerHTML = "No Data available.";
} else {
    var names = prevenData.map(item => item.name);
    var uniqueNames = [...new Set(names)];

    var depts = prevenData.map(item => item.dept);
    var uniqueDepts = [...new Set(depts)];

    var barColors = uniqueNames.map(name => generateRandomColor());

    new Chart("pieChart", {
        type: "bar",
        data: {
            labels: uniqueNames,
            datasets: uniqueDepts.map((dept, index) => ({
                label: dept,
                backgroundColor: barColors[index],
                data: uniqueNames.map(name => {
                    var dataPoint = prevenData.find(item => item.name === name && item.dept === dept);
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
      "onComplete": function() {
        var chartInstance = this.chart,
          ctx = chartInstance.ctx;

        ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, 'bold ' + Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
        ctx.textAlign = 'center';
          ctx.textBaseline = 'bottom';  
          ctx.fillStyle = 'black';

        this.data.datasets.forEach(function(dataset, i) {
          var meta = chartInstance.controller.getDatasetMeta(i);
          meta.data.forEach(function(bar, index) {
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
            scales: {
                xAxes: [{
                    stacked: true
                }],
                yAxes: [{
                    stacked: true
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

// product pie chart
const proData = {!! $product_linechart !!};

if (proData.length == 0) {
    document.getElementById("myChart1").innerHTML = "No Data available.";
} else {
    var uniqueMonths = [...new Set(proData.map(item => item.month))];
    var uniqueProducts = [...new Set(proData.map(item => item.name))];

    var datasets = uniqueProducts.map((product, index) => {
        var productValues = proData
            .filter(item => item.name === product)
            .map(item => item.value);

        var barColor = generateRandomColor();

        return {
            label: product,
            backgroundColor: barColor,
            data: uniqueMonths.map(month => {
                var dataPoint = proData.find(item => item.month === month && item.name === product);
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
                        return dataset.label + ": " + currentValue + "Qty";
                    }
                }
            }
        }
    });
}
   
// breakdown summary chart
const breakData = {!! $break_sumchart !!};
      if (breakData.length == 0) {
    
    document.getElementById("breakChart1").innerHTML = "No Data available.";
   } else {
var BreakType = breakData.map(item => item.name);
var yValues = breakData.map(item => item.value);
var months = breakData.map(item => item.month);
var barColors = Array.from({ length: xValues.length }, () => generateRandomColor());

new Chart("breakChart", {
    type: "pie",
    data: {
        labels: BreakType,
        datasets: [{
            label: "Total Idle Hrs",
            backgroundColor: barColors,
            data: yValues
        }]
    },
    options: {
    
        legend: {
            display: true
        },
        title: {
            display: true,
            text: "Total Idle Hours"
        },
        tooltips: {
            callbacks: {
                label: function (tooltipItem, data) {
                    var dataset = data.datasets[tooltipItem.datasetIndex];
                    var total = dataset.data.reduce(function (previousValue, currentValue) {
                        return previousValue + currentValue;
                    });
                    var currentValue = dataset.data[tooltipItem.index];
                    var percentage = Math.floor(((currentValue / total) * 100) + 0.5);
                   return BreakType[tooltipItem.index] + ": " + currentValue + "Hrs";
                }
            }
        }
    }
});

   }
	
</script>

@endpush