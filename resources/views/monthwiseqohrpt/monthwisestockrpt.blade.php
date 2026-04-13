@extends('layouts.header')

@section('content')

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<link rel="stylesheet" type="text/css" href="{{asset('css/dashboard.css')}}">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
<script src='https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js'></script>

<style>
    .bread {
        display: none;
    }

    body {
        background: #fff;
    }

        .ul {
            list-style: none;
            display: flex;
            gap: 16px;
        }

        .list {
            margin: 0;
            padding: 10px;
            background-color: #3996d5;
            color: #fff;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease;
            text-transform: capitalize;
            width:165px;
        }

        .list a {
            color: #fff;
            text-decoration: none;
        }

        .list:hover {
            background-color: #1c2c78;
        }
    .align {
        text-align: center;
        background: #9799e5;
    }

    .align1 {
        text-align: center;
        background: #481a73;
        color: #fff;
        border: 1px solid black !important;
    }
        .dash_charrt {
        border-radius: 10px;
        border-left: 3px solid #4e73df !important;
        border-right: 1px solid #ccc !important;
        border-bottom: 1px solid #ccc !important;
        border-top: 1px solid #ccc !important;
        margin: 30px 0;
        box-shadow: 0 .15rem 1.75rem 0 rgba(58, 59, 69, .15) !important;
    }
    .sticky-col {
  position: sticky;
  left: 0; 
  z-index: 1;
  background-color: #e1e1e1; 
}

  .sticky-coll {
  position: sticky;
  left: 120px; 
  z-index: 2; 
  background-color:#e1e1e1; 
}
  .sticky-colll {
  position: sticky;
  left: 275px; 
  z-index: 3; 
  background-color:#e1e1e1; 
}
 @media screen and (max-width: 768px) {
    .ul {
        flex-direction: column; 
        gap: 10px;
    }

    .list {
        width: 100%; 
    }
          .sticky-col {
        position: sticky;
        left: 0;
        z-index: 1;
        background-color: #b3d1ff;
    }

 }   
        .sticky-foot {
        position: sticky;
        bottom: 0;
        z-index: 1;
        background-color: #ffbef7;
    }
</style>


<h3 class="heads" style="background:#3c763d !important;">Monthwise Stock Report</h3>


        <div class="col-lg-12">
    
        <form action="{{ url('monthwiseqohrpt') }}" method="get" id="searchForm">
            <div class="col-md-4">
                <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-4">Product Group</label>
                    <div class="col-md-7">
                            <select style="width:100%" name='product_group_id' id='product_group_id' rows='5' class='form-control product_group_id select2' >
                                {!! $product_group_id !!}
                            </select>
                    </div>
                      <div class="col-md-1 showinline">
                    <span class="showspan"> <i class="fa fa-refresh reproduct_name"></i></span>
                               </div>
                </div>
        </div> 
            <div class="col-md-4">
                <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-4">Start Date</label>
                    <div class="col-md-8">
                        <div class="input-group form_date " data-date="" data-link-format="yyyy-mm-dd">
                            <input class="form-control start_date1" id="start_date" name="start_date" required type="text" value="" style="border-radius: 5px;" autocomplete="off" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-4">End Date</label>
                    <div class="col-md-8">
                        <div class="input-group form_date " data-date="" data-link-format="yyyy-mm-dd">
                            <input class="form-control end_date1" id="end_date" name="end_date" required type="text" value="" style="border-radius: 5px;" autocomplete="off" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label for="inputIsValid" class="form-control-label col-md-offset-3 col-md-2"><span style="color:red; ">
                    </span></label>
                <div class="col-md-12" style="text-align: justify;">
                    <button type="submit" class="btn search search" id="searchButton" style="left:46%;">Search</button>
                </div>
            </div>
    </form>
</div>

  <!-- monthly productivity -->
  
      <div class="col-md-12" style="margin-top: 40px;">
        <table id="Table2" class="display" style="width:100%">
            <thead>
            <tr>
            <?php if (request('zone') != ''): ?>
                <th class="align" style="background:#f9fd00">{{ request('zone') }}</th>
                <?php endif; ?>
            <?php if (request('region') != ''): ?>
                <th class="align" style="background:#f9fd00">{{ request('region') }}</th>
                <?php endif; ?>
            <?php if (request('division') != ''): ?>
                <th class="align" style="background:#f9fd00">{{ request('division') }}</th>
            <?php endif; ?>
                        <?php if (request('state') != ''): ?>
            <th class="align" style="background:#9b900d">{{ request('state') }}</th>
            <?php endif; ?>
                <th colspan="64" class="align" style="border-right: 1px solid #000;background:#fcff26 !important;text-align:left;">Monthly Productivity upto  </th>
                 </tr>
                <tr>
        <th colspan="3" class="align" style="background:#b3d1ff !important;"></th>
        <?php $Zones = []; ?>
        <?php foreach ($zone_wise as $value) {
            $zone_type = $value->month_y;
            if (!in_array($zone_type, $Zones)) {
                $Zones[] = $zone_type; ?>
                <th colspan="3" style="text-align: center;background: #b3d1ff"><?php echo $zone_type; ?></th>
        <?php }
        } ?>
     </tr>
         <tr>
        <th style="border-right: 1px solid black;" class="sticky-col">State</th>
        <th style="border-right: 1px solid black;" class="sticky-coll">Currrent Reporting MGR</th>
        <th style="border-right: 1px solid black;" class="sticky-colll">Field Force Name</th>
        <?php foreach ($Zones as $zone) { ?>
            <th>Sale</th>
            <th>Target</th>
            <th style="border-right: 1px solid black;">Achivement %</th>
        <?php } ?>
    </tr>
    
    </thead>
<tbody>
<?php 
$grandTotalSales = $grandTotalTarget = 0; 
$processedItems = []; // To keep track of processed items

foreach ($zone_wise as $value):
    $state = $value->state;
    $mgr = $value->current_reporting_manager;
    $person = $value->field_force_name;
    $key = "$state-$mgr-$person"; // Generating a unique key for each row

    // Skip if this key is already processed
    if (in_array($key, $processedItems)) {
        continue;
    }
    
    $processedItems[] = $key; // Mark this key as processed

?>
    <tr>
        <td style="border-right: 1px solid black;" class='sticky-col'><?php echo $state; ?></td>
        <td style="border-right: 1px solid black;" class='sticky-coll'><?php echo $mgr; ?></td>
        <td style="border-right: 1px solid black;" class='sticky-colll'><?php echo $person; ?></td>

        <?php 
        $hasData = false; // Flag to check if any data exists for this row
        $rowTotalSales = $rowTotalSamples = 0;
        foreach ($Zones as $zone): 
            $monthData = array_filter($zone_wise, function ($item) use ($zone, $state, $mgr, $person) {
                return $item->month_y == $zone && $item->state == $state && $item->current_reporting_manager == $mgr && $item->field_force_name == $person;
            });

            if (!empty($monthData)): 
                $item = reset($monthData); // Get the first element
                $rowTotalSales += $item->sale;
                $rowTotalSamples += $item->target;
                if ($item->sale > 0 || $item->target > 0) {
                    $hasData = true; // Set the flag to true if any value is greater than 0
                }
            ?>
                <td class="rupee-value" data-original="<?php echo $item->sale; ?>"><?php echo $item->sale; ?></td>
                <td class="rupee-value" data-original="<?php echo $item->target; ?>"><?php echo $item->target; ?></td>
                <td style='border-right: 1px solid #000;'><?php echo ($item->target != 0) ? number_format($item->sale / $item->target * 100, 0) . '%' : '0'; ?></td>
            <?php else: ?>
                <td>0</td>
                <td>0</td>
                <td style='border-right: 1px solid #000;'>0%</td>
            <?php endif; ?>
        <?php endforeach; ?>
    </tr>
<?php
        if ($hasData) { // Only display the row if any data exists
            $grandTotalSales += $rowTotalSales;
            $grandTotalTarget += $rowTotalSamples;
        }
endforeach; ?>
<tfoot>
<?php
// Add Grand Total Row
echo "<tr class='sticky-foot' style ='background:#ffbef7;font-weight: 600;'>";
echo "<td class='sticky-col1'>Total</td>";
echo "<td class='sticky-col1'></td>";
echo "<td class='sticky-col1'></td>";

foreach ($Zones as $zoneKey) { // Rename the loop variable to $zoneKey
    $totalSales = $totalSamples = 0;

    foreach ($zone_wise as $value) { 
        if ($value->month_y == $zoneKey) { 
            $totalSales += $value->sale;
            $totalSamples += $value->target;
        }
    }

    if ($totalSales > 0 || $totalSamples > 0) {
        echo "<td class='rupee-value' data-original='$totalSales' >{$totalSales}</td>";
        echo "<td class='rupee-value' data-original='$totalSamples'>{$totalSamples}</td>";

        if ($totalSamples != 0) {
            echo "<td style='border-right: 1px solid #000;'>" . number_format($totalSales / $totalSamples * 100, 0) . " % </td>";
        } else {
            echo '<td style="border-right: 1px solid #000;">0</td>';
        }
    }
}

echo "</tr>";
?>
</tfoot>



</table>
</div>
    
    
    <!-- Include jQuery -->

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>

    <script>
    // on chnange zone based region
      $(document).on('change', '.zone', function() {
        var prdgroup = $('.zone').select2('val');
        if (prdgroup != '') {
            $(".region").jCombo("{{ URL::to('jcombosecondsales?table=sd_prmyscdyupload_t:region:region') }}&parent=zone='" + prdgroup + "'&order_by=region asc", {
                selected_value: ""
            });
        }
        console.log(prdgroup);
    });
    
      // refresh region
        $(document).on('click','.re_region',function()
        {
  
        $(".region").jCombo("{{ URL::to('jcombosecondsales?table=sd_prmyscdyupload_t:region:region') }}&order_by=region asc",
        {selected_value:""});
        });
    </script>
    
    <script>
        $(document).ready(function() {
            
            var startDate = "{{ request('date_select') }}";
            $('#date_select').val(startDate);
            
        
          });
  
        $(document).ready(function() {
            $('#Table2').DataTable({
            });
        });
                        // search alert
         document.getElementById('searchForm').addEventListener('submit', function(event) {
        var zone = document.getElementById('zone').value;
        var region = document.getElementById('region').value;
        var state = document.getElementById('state').value;
        var startDate = document.getElementById('date_select').value;

        if (zone === '' && region === '' && startDate === '' && state ==='') {
            alert('Please select Zone, Region, or Month Before Searching.');
            event.preventDefault(); 
        }
    });
    </script>
    <script>
    // calendar freeze
    document.addEventListener('DOMContentLoaded', function() {
        var today = new Date();
        var currentYear = {{ $last_yr }};
       var currentMonth = '{{ sprintf('%02d', $last_mon) }}';
    
        var startMonthYear = '2022-04';
        var endMonthYear = currentYear + '-' + currentMonth;
    
        document.getElementById('date_select').setAttribute('min', startMonthYear);
        document.getElementById('date_select').setAttribute('max', endMonthYear);
    });
    </script>
          
<!-- convert money inr to k and l purpose  -->
<script>
    $(document).ready(function () {

        // Event handler for the "Reset" button
        $('#btnReset').on('click', function () {
                location.reload();
        });
    });
</script>
<!-- end  -->
    @endsection