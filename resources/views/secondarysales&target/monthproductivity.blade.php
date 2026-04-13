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
            gap: 20px;
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
            width:190px;
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
        .sticky-col1{
        
    position: sticky;
    left: 0;
    z-index: 3;
    background-color: #b3d1ff;
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


    
        <h3 style="text-align:end !important;font-size:15px;text-transform: capitalize;font-weight: bold;">Last Updated At:  <span class="list"> {{ $last_update[0]->created_at }}</span></h3>
        <h3 class="heads">Secondary Sales And Target  <span style="float: right !important;">Data Upto: {{ $last_data }}</span> </h3>

<ul class="ul">
 <a href="salesandtargetmonth"> <li class="list">Month Wise</li></a>
  <a href="salesandtargetzone"> <li  class="list">Zone Wise</li></a>
   <a  href="salesandtargetregion"> <li class="list">Region Wise</li></a>
    <a href="salesandtargetstate">  <li class="list">Region And State Wise</li> </a>
    <a href="salesandtargetdistributor"> <li class="list">Distributor Wise</li></a>
    <a href="salesandtargethq"><li class="list">HQ Wise</li></a> 
    <a href="salesandtargetperson"> <li class="list">Per&Mon Productivity</li></a>
</ul>

<ul class="ul">
                 <a href="salesandtargetproduct"><li class="list">Product Wise</li></a>
                  <a href="salesandtargetteam"><li  class="list">Team-Trt VS Ach</li></a>
                <a href="salesandtargetteammonth"><li  class="list">Trt VS Ach -For Month </li></a>
                <a href="salesandtargetmtstrend"><li class="list">Sales & Purch Trend</li></a>
                 <a href="salesandtargetclobal"><li class="list">Dist wise Clo bal</li></a>
                  <a href="salesandtargetperclobal"><li class="list">Person wise Clo bal</li></a>
                 <a href="salesandtargetpayout"><li class="list">Payment Outstanding</li></a>
 </ul>

<h3 class="heads" style="background:#3c763d !important;">Person Wise Productivity</h3>
<?php
function getBackgroundColor($value1, $value2) {
    
    if ($value2 !=0){
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
    }}
}
?>

<div class="col-lg-12">
    
        <form action="{{ url('primarysalezoneratio') }}" method="get" id="searchForm">
                                     <div class="col-md-3">
                <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-4"  style="text-align: end;">Zone</label>
                    <div class="col-md-8">
                        <select style="width:100%;text-align:center;" name='zone' id='zone' class='form-control zone select2'>
                                <option value="">-- please select --</option>
                            @foreach($zones as $zone)
                                <option value="{{ $zone->zone }}">{{ $zone->zone }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-4"  style="text-align: end;">Region</label>
                    <div class="col-md-8">
                        <select style="width:100%;text-align:center;" name='region' id='region' class='form-control region select2'>
                                <option value="">-- please select --</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->region }}">{{ $region->region }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <?php if ($groupname != '14') { ?>
                    <div class="col-md-1 showinline" style="margin-top: 8px;">
                    <span class="showspan"> <i class="fa fa-refresh re_region"></i></span>
                    </div>
                    <?php } ?>
            <div class="col-md-4">
                <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-4">Division</label>
                    <div class="col-md-8">
                        <select style="width:100%;text-align:center;" name='division' id='division' class='form-control division select2'>
                                <option value="">-- please select --</option>
                            @foreach($divisions as $division)
                                <option value="{{ $division->division }}">{{ $division->division }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
             </div>
              <div class="col-md-3">
            <div class="form-group row">
                <label for="inputIsValid" class="form-control-label col-md-4" style="text-align: end;">Month</label>
                <div class="col-md-8">
                    <div class="input-group form_date " data-date="" data-link-format="yyyy-mm">
                        <input class="form-control date_select  " id="date_select" name="date_select" type="month" value="" style="border-radius: 5px;" autocomplete="off">
                    </div>
                </div>
            </div>
    </div>
    <div class="col-md-4" style="margin-top:-9px;text-align:end;">
        <a>
            <button type="submit" class="btn search search" id="searchButton">search</button>
        </a>

    </div>
    </form>
</div>

<div class="col-lg-12">
    <ul class="ul">
        <li class="list" style="background:#e1e1e1;color:#000;width: 160px;">UPTO 3% Good</li>
        <li class="list" style="background:#e1e1e1;color:#000;width: 160px;">UPTO 5% Ok</li>
        <li class="list" style="background:#f9b436;color:#000;width: 160px;">5% To 8% Need To Reduce Sampling</li>
        <li class="list" style="background:#c96fc6;width: 160px;">Above 8% Stop Sampling</li>
    </ul>
</div>    
<!-- convert money inr to k and l purpose  -->
<div class="col-md-12" style="text-align: center;">
<button class="btn btn-primary" id="btnThousand"> Show in Thousands</button>
<button class="btn btn-danger" id="btnLakhs">Show in Lakhs</button>
<button class="btn btn-success" id="btnReset">Reset</button>
</div>
<!-- end -->
 <!---  product Wise Sale value -->
<div class="col-md-12">
        <table id="Table1" class="display" style="width:100%">
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
                <th colspan="2" class="align" style="border-right: 1px solid #000;background:#eba11b !important;">last 6 months upto  {{ $mon_yr}}</th>
                <th  colspan="14" class="align" style="border-right: 1px solid #000;background:#eba11b !important;">ALL INDIA - % SAMPLE to SALES RATIO - ZONE WISE</th>
                 </tr>
                <tr>
        <th colspan="2" class="align" style="background:#b3d1ff !important;"></th>
        <?php $Zones = []; ?>
        <?php foreach ($zone_wise as $value) {
            $zone_type = $value->zone;
            if (!in_array($zone_type, $Zones)) {
                $Zones[] = $zone_type; ?>
                <th colspan="3" style="text-align: center;background: #b3d1ff"><?php echo $zone_type; ?></th>
        <?php }
        } ?>
     </tr>
         <tr>
        <th class="sticky-col1">Product</th>
        <?php foreach ($Zones as $zone) { ?>
            <th>Sales</th>
            <th>Sample</th>
            <th style="border-right: 1px solid #000;">Ratio %</th>
        <?php } ?>
    </tr>
    
    </thead>
    <tbody>
        <?php 
        $products = array_unique(array_column($zone_wise, 'sfg_product_name')); 
        $grandTotal = $grandTotalsales = $grandTotalsample = 0; 
        foreach ($products as $product): ?>
            <tr>
                <td class='sticky-col1'><?php echo $product; ?></td>
                <?php foreach ($Zones as $zone): ?>
                    <?php 
                    $productData = array_filter($zone_wise, function ($value) use ($zone, $product) {
                        return $value->zone == $zone && $value->sfg_product_name == $product;
                    });

                    if (!empty($productData)): 
                        $value = reset($productData); // Get the first element
                        ?>
                        <td class="rupee-value" data-original="{{ $value->sale }}"><?php echo $value->sale; ?></td>
                        <td class="rupee-value" data-original="{{ $value->sale }}"><?php echo $value->sample; ?></td>
                        <td style='border-right: 1px solid #000;'><?php echo ($value->sale != '0') ? number_format($value->sample / $value->sale * 100, 0) . '%' : '0'; ?></td>
                    <?php else: ?>
                        <td>0</td>
                        <td>0</td>
                        <td style='border-right: 1px solid #000;'>0%</td>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    
</tbody>
<tfoot>
    <?php
    // Add Grand Total Row
echo "<tr class='sticky-foot' style ='background:#ffbef7;font-weight: 600;'>";
echo "<td class='sticky-col1'>Total</td>";
foreach ($Zones as $zoneKey) { // Rename the loop variable to $zoneKey
    $totalSales = $totalSamples = 0;

    foreach ($zone_wise as $value) { 
        if ($value->zone == $zoneKey) { 
            $totalSales += $value->sale;
            $totalSamples += $value->sample;
        }
    }

    $grandTotalsales += $totalSales;
    $grandTotalsample += $totalSamples;

    echo "<td class='rupee-value' data-original='$totalSales' >{$totalSales}</td>";
    echo "<td class='rupee-value' data-original='$totalSamples'>{$totalSamples}</td>";
    
    if ($totalSales != 0) {
        echo "<td style='border-right: 1px solid #000;'>" . number_format($totalSamples / $totalSales * 100, 0) . " % </td>";
    } else {
        echo '<td style="border-right: 1px solid #000;">0</td>';
    }
}

echo "</tr>";
?>
</tfoot>
        </table>
    </div>

     <!-- END -->
     
<!-- end -->  
    
    <!-- Include jQuery -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>

    <script>
      $(document).on('change', '.zone', function() {
        var prdgroup = $('.zone').select2('val');
        if (prdgroup != '') {
            $(".region").jCombo("{{ URL::to('jcombosecondsales?table=sd_primarydataupload_t:region:region') }}&parent=zone='" + prdgroup + "'&order_by=region asc", {
                selected_value: ""
            });
        }
        console.log(prdgroup);
    });
    
        $(document).on('click','.re_region',function()
        {
  
        $(".region").jCombo("{{ URL::to('jcombosecondsales?table=sd_primarydataupload_t:region:region') }}&order_by=region asc",
        {selected_value:""});
        });
    </script>


    <script>
        
        $(document).ready(function() {
            
            var startDate = "{{ request('date_select') }}";
            $('#date_select').val(startDate);
            
        
          });
  
        $(document).ready(function() {
            $('#Table1').DataTable({
                dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
                order: [],
                pageLength: 1000,
                scrollY: '300px', 
                scrollX: true,
                scrollCollapse: true, 
                buttons: [
                    {
                        extend: 'excelHtml5',
                        filename: 'sample_to_sale_ratio_zone',
                    },
                    {
                        extend: 'pdfHtml5',
                        filename: 'sample_to_sale_ratio_zone',
                    }
                ]
            });

        });
                
    // search alert
         document.getElementById('searchForm').addEventListener('submit', function(event) {
        var zone = document.getElementById('zone').value;
        var region = document.getElementById('region').value;
        var startDate = document.getElementById('date_select').value;
         var Division = document.getElementById('division').value;

        if (zone === '' && region === '' && startDate === '' && Division === '') {
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
@endsection