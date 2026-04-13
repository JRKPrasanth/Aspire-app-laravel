@extends('layouts.header')
@section('content')
<h3 class="text-danger">Month Wise</h3>

<!-- tabs header -->
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-3">
	<h6 class="text-muted fw-bold">Last Updated At: <span class="text-success fw-bold">{{ $last_update[0]->created_at }}</span></h6>
    <h6 class="text-muted">Data Upto: <span class="text-success fw-bold">{{ $last_data }}</span></h6>
	  <a href="{{ url($pageModule) }}" class="btn btn-outline-success fw-bold">Tabs</a>
  </div>
 </div>

<div class="card shadow-lg rounded-4 border-0 p-4 mb-4">
  <form action="{{ url('primarycostsaleratio') }}" method="get" id="searchForm">
    <div class="row g-3 align-items-end">

      <!-- Zone -->
      <div class="col-md-3">
        <label for="zone" class="form-label">Zone</label>
        <select name="zone" id="zone" class="form-select zone select2 text-center">
          <option value="">-- please select --</option>
          @foreach($zones as $zone)
            <option value="{{ $zone->zone }}">{{ $zone->zone }}</option>
          @endforeach
        </select>
      </div>

      <!-- Region -->
      <div class="col-md-3">
        <label for="region" class="form-label">Region</label>
        <select name="region" id="region" class="form-select region select2 text-center">
          <option value="">-- please select --</option>
          @foreach($regions as $region)
            <option value="{{ $region->region }}">{{ $region->region }}</option>
          @endforeach
        </select>
      </div>

      <!-- Month -->
      <div class="col-md-3">
        <label for="date_select" class="form-label">Month</label>
        <input type="month" name="date_select" id="date_select" class="form-control date_select" autocomplete="off">
      </div>

      <!-- Search Button -->
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary px-5"><i class="bi bi-search"></i> Search</button>
            </div>
    </div>
  </form>
</div>



<!-- convert money inr to k and l purpose  -->
<div class="text-center mb-4">
  <button class="btn btn-primary fw-bold me-2" id="btnThousand">Show in Thousands</button>
  <button class="btn btn-success fw-bold me-2" id="btnLakhs">Show in Lakhs</button>
  <button class="btn btn-danger fw-bold" id="btnReset">Reset</button>
</div>
<!-- end -->

  <!-- month wise ratio  cur Fy-->
  <div class="row">
<div class="col-md-6">
     <!--tables-->
<div class="card shadow-lg rounded-4 border-0 p-4">
    <div class="table-responsive" style="overflow-x: auto;">
        <table id="Table1" class="table table-bordered table-striped table-hover w-100">
        <thead>
            <tr>
             <?php if (request('zone') != ''): ?>
                <th class="align text-white bg-danger text-center" >{{ request('zone') }}</th>
            <?php elseif (request('region') != ''): ?>
                <th class="align text-white bg-danger text-center" >{{ request('region') }}</th>
            <?php elseif (request('division') != ''): ?>
              <th class="align text-white bg-danger text-center" >{{ request('division') }}</th>
                <?php endif; ?>

                    <th colspan="2" class="align text-white bg-danger text-center" >{{ $cur_fy_year }} upto {{ $mon_yr}}</th>
                    <th colspan="4" class="align text-white bg-danger text-center" >ALL INDIA</th>



                </tr>
                <tr>
                    <th class="align text-white bg-secondary text-center" >Month</th>
                     <th class="align text-white bg-secondary text-center" >Sales</th>
                      <th class="align text-white bg-secondary text-center" >Salary Exp Incen</th>
                       <th class="align text-white bg-secondary text-center" >Manpower</th>
                         <th class="align text-white bg-secondary text-center" >% of Cost to Sales Ratio</th>
                           <th class="align text-white bg-secondary text-center" >Per Person Productivity</th>
                </tr>
            </thead>
   <tbody>
    <?php
    $totalSale = 0;
    $totalExpence = 0;
    $totalMan = 0;

    foreach ($month_wise as $value) {
        $totalSale += $value->sale;
        $totalExpence += $value->expence;
         $totalMan += $value->man;
    ?>
        <tr>
            <td class="sticky-col">{{ $value->month }}</td>
            <td class="rupee-value" data-original="{{ $value->sale }}">{{ $value->sale }}</td>
            <td class="rupee-value" data-original="{{ $value->expence }}">{{ $value->expence }}</td>
            <td>{{ $value->man }}</td>
            <?php if ($value->sale != '0'): ?><td ><?php echo number_format($value->expence / $value->sale * 100, 0) ?>%</td><?php else: ?><td >0</td><?php endif; ?>


                    <?php
                    if (isset($value->man) && is_numeric($value->man) && $value->man !== '0' &&
                        isset($value->sale) && is_numeric($value->sale) && $value->sale !== '0') {
                        $result = $value->sale / $value->man;
                    } else {
                        $result = 0; // Define default value if $value->man is 0 or non-numeric
                    }
                    ?>
<td class="rupee-value" data-original="<?php echo $result; ?>" >
    <?php
    // Output formatted result within the table cell
    $result = number_format($result, 0); echo str_replace(',', '', $result);
    ?>
</td>
    </tr>
     <?php } ?>
   </tbody>  
    <tfoot>
    <tr class="sticky-foot fw-bold table-danger">
        <td>Total</td>
        <td class="rupee-value" data-original="{{ $totalSale}}">{{ $totalSale }}</td>
        <td class="rupee-value" data-original="{{ $totalExpence }}" >{{ $totalExpence }}</td>
        <td>{{ $totalMan }}</td>
        <td><?php  if ($totalExpence && $totalSale != 0) {echo number_format($totalExpence / $totalSale * 100, 0);} else {echo '0';} ?>%</td>
        <?php
    // Calculate $result before using it
    if ($totalSale && $totalMan != '0' ) {
        $value_lak = $totalSale / $totalMan ;
    } else {
        $value_lak = 0; // Define default value if $value->man is 0
    }
   ?>
        <td class="rupee-value" data-original="<?php echo $value_lak; ?>"><?php  if ($totalMan && $totalSale != 0) {echo number_format($totalSale / $totalMan , 0);} else {echo '0';} ?></td>
    </tr>
</tfoot>
        </table>
    </div>
</div>

    </div>
    
      <!-- month wise ratio  Pre Fy-->
  
<div class="col-md-6">
<!--tables-->
<div class="card shadow-lg rounded-4 border-0 p-4">
    <div class="table-responsive" style="overflow-x: auto;">
        <table id="Table2" class="table table-bordered table-striped table-hover w-100">
        <thead>
            <tr>
             <?php if (request('zone') != ''): ?>
                <th class="align text-white bg-danger text-center">{{ request('zone') }}</th>
            <?php elseif (request('region') != ''): ?>
                <th class="align text-white bg-danger text-center">{{ request('region') }}</th>
            <?php elseif (request('division') != ''): ?>
                <?php endif; ?>

                    <th colspan="2" class="align text-white bg-danger text-center" >{{ $pre_fy_year }}</th>
                    <th colspan="4" class="align text-white bg-danger text-center" >ALL INDIA</th>



                </tr>
                <tr>
                    <th class="align text-white bg-secondary text-center" >Month</th>
                     <th class="align text-white bg-secondary text-center" >Sales</th>
                      <th class="align text-white bg-secondary text-center" >Salary Exp Incen</th>
                       <th class="align text-white bg-secondary text-center" >Manpower</th>
                         <th class="align text-white bg-secondary text-center" >% of Cost to Sales Ratio</th>
                           <th class="align text-white bg-secondary text-center" >Per Person Productivity</th>
                </tr>
            </thead>
   <tbody>
    <?php
    $totalSale = 0;
    $totalExpence = 0;
    $totalMan = 0;

    foreach ($month_wise_prefy as $value) {
        $totalSale += $value->sale;
        $totalExpence += $value->expence;
         $totalMan += $value->man;
    ?>
        <tr>
            <td class="sticky-col" >{{ $value->month }}</td>
            <td class="rupee-value" data-original="{{ $value->sale }}"  >{{ $value->sale }}</td>
            <td class="rupee-value" data-original="{{ $value->expence }}"  >{{ $value->expence }}</td>
            <td >{{ $value->man }}</td>
            <?php if ($value->sale != '0'): ?><td ><?php echo number_format($value->expence / $value->sale * 100, 0) ?>%</td><?php else: ?><td >0</td><?php endif; ?>

                    <?php
                    if (isset($value->man) && is_numeric($value->man) && $value->man !== '0' &&
                        isset($value->sale) && is_numeric($value->sale) && $value->sale !== '0') {
                        $result = $value->sale / $value->man;
                    } else {
                        $result = 0; // Define default value if $value->man is 0 or non-numeric
                    }
                    ?>
<td class="rupee-value" data-original="<?php echo $result; ?>" >
    <?php
    // Output formatted result within the table cell
    $result = number_format($result, 0); echo str_replace(',', '', $result);
    ?>
</td>
</tr>
     <?php } ?>
</tbody>
<tfoot>
    <tr class="sticky-foot fw-bold table-danger" >
        <td >Total</td>
        <td class="rupee-value" data-original="{{ $totalSale}}" >{{ $totalSale }}</td>
        <td class="rupee-value" data-original="{{ $totalExpence }}"  >{{ $totalExpence }}</td>
        <td >{{ $totalMan }}</td>
        <td ><?php  if ($totalExpence && $totalSale != 0) {echo number_format($totalExpence / $totalSale * 100, 0);} else {echo '0';} ?>%</td>
        <?php
    // Calculate $result before using it
    if ($totalSale && $totalMan != '0' ) {
        $value_lak = $totalSale / $totalMan ;
    } else {
        $value_lak = 0; // Define default value if $value->man is 0
    }
?>
        <td class="rupee-value" data-original="<?php echo $value_lak; ?>" ><?php  if ($totalMan && $totalSale != 0) {echo number_format($totalSale / $totalMan , 0);} else {echo '0';} ?></td>
    </tr>
</tfoot>
        </table>
    </div>
</div>
    </div>
      </div>
         <!-- production per person chart -->
          <div class='row' >
         
             <div class='col-md-6 card shadow-lg rounded-4 border-0 p-3 mb-4' >
                    <h5 class="chart_tittle">Per Person Productivity - {!! $cur_fy_year !!}</h5>
                    
                    <canvas id="monthChart" style="max-height:460px"></canvas>

            </div>
            <div class='col-md-6 card shadow-lg rounded-4 border-0 p-3 mb-4' >
                   <h5 class="chart_tittle">Per Person Productivity - {!! $pre_fy_year !!}</h5>
                <canvas id="monthChartpre" style="max-height:460px"></canvas>

            </div>
            </div>
<!-- end -->     

         <!-- cost ratio chart -->
          <div class='row' >
         
             <div class='col-md-6 card shadow-lg rounded-4 border-0 p-3 mb-4' >
                    <h5 class="chart_tittle">Cost Of Sale Ratio % - {!! $cur_fy_year !!}</h5>
                    
                    <canvas id="lineChart" style="max-height:460px"></canvas>

            </div>
            <div class='col-md-6 card shadow-lg rounded-4 border-0 p-3 mb-4' >
                   <h5 class="chart_tittle">Cost Of Sale Ratio % - {!! $pre_fy_year !!}</h5>
                <canvas id="lineChartpre" style="max-height:460px"></canvas>

            </div>
			  
            </div>

@endsection
@push('scripts')

<!-- Include charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>


    <script>
 

        $(document).ready(function() {
            
            var startDate = "{{ request('date_select') }}";
            $('#date_select').val(startDate);
            
        
          });
  
        $(document).ready(function() {
            $('#Table1').DataTable({
                  scrollX: true,
                  scrollY: "80vh",
                  pageLength: 20,
                  order: false,
                buttons: [
                    {
                        extend: 'excelHtml5',
                        filename: 'cost_to_sale_month',
                    },
                    {
                        extend: 'pdfHtml5',
                        filename: 'cost_to_sale_month',
                    }
                ]
            });
            
            
                $('#Table2').DataTable({
                  scrollX: true,
                  scrollY: "80vh",
                  pageLength: 20,
                  order: false,
                  buttons: [
                    {
                        extend: 'excelHtml5',
                        filename: 'cost_to_sale_month',
                    },
                    {
                        extend: 'pdfHtml5',
                        filename: 'cost_to_sale_month',
                    }
                ]
            });
        });
        
          // search alert
         document.getElementById('searchForm').addEventListener('submit', function(event) {
        var zone = document.getElementById('zone').value;
        var region = document.getElementById('region').value;
        var startDate = document.getElementById('date_select').value;


        if (zone === '' && region === '' && startDate === '') {
            alert('Please select Zone, Region, or Month Before Searching.');
            event.preventDefault(); 
        }
    });

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

// chart
// Generate random color for each dataset
function generateRandomColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}
   // cur fy charts 
const primchartData = {!! $pro_sumchart !!};
var variant = primchartData.map(item => item.name);
var yValues = primchartData.map(item => parseFloat(item.ratio.replace('%', '')) || 0);
var barColors = Array.from({ length: variant.length }, () => generateRandomColor());

new Chart("monthChart", {
    type: "bar",
    data: {
        labels: variant,
        datasets: [
            {
                label: "Per Person Productivity",
                backgroundColor: barColors,
                data: yValues
            }
        ]
    },
    options: {
        hover: {
            animationDuration: 0
        },
        animation: {
            duration: 1,
            onComplete: function() {
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
                        ctx.fillText(data , bar._model.x, bar._model.y - 5);
                    });
                });
            }
        },
        legend: {
            display: true
        },

        tooltips: {
            callbacks: {
                label: function(tooltipItem, data) {
                    var dataset = data.datasets[tooltipItem.datasetIndex];
                    var currentValue = dataset.data[tooltipItem.index];
                    return variant[tooltipItem.index] + ": " + currentValue ;
                }
            }
        }
    }
});

    //pre fy chart
    
const MonthData = {!! $month_pre_fy !!};
var variant = MonthData.map(item => item.name);
var yValues = MonthData.map(item => parseFloat(item.ratio.replace('%', '')) || 0);
var barColors = Array.from({ length: variant.length }, () => generateRandomColor());

new Chart("monthChartpre", {
    type: "bar",
    data: {
        labels: variant,
        datasets: [
            {
                label: "Per Person Productivity",
                backgroundColor: barColors,
                data: yValues
            }
        ]
    },
    options: {
        hover: {
            animationDuration: 0
        },
        animation: {
            duration: 1,
            onComplete: function() {
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
                        ctx.fillText(data , bar._model.x, bar._model.y - 5);
                    });
                });
            }
        },
        legend: {
            display: true
        },

        tooltips: {
            callbacks: {
                label: function(tooltipItem, data) {
                    var dataset = data.datasets[tooltipItem.datasetIndex];
                    var currentValue = dataset.data[tooltipItem.index];
                    return variant[tooltipItem.index] + ": " + currentValue ;
                }
            }
        }
    }
});


    //coat cur fy chart
    
const CostData = {!! $pro_sumchart !!};
var variant = CostData.map(item => item.name);
var yValues = CostData.map(item => parseFloat(item.cost.replace('%', '')) || 0);
var barColors = Array.from({ length: variant.length }, () => generateRandomColor());

var datasets = [
    {
        label: "Ratio",
        data: yValues,
        fill: false,
        borderColor: "#FFFF00", 
        tension: 0.1
    }
];

new Chart("lineChart", {
    type: "line",
    data: {
        labels: variant,
        datasets: datasets
    },
    options: {
                hover: {
            animationDuration: 0
        },
        animation: {
            duration: 1,
            onComplete: function() {
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
                        // Concatenate "%" to the data variable
                        data = data + '%';
                        ctx.fillText(data, bar._model.x, bar._model.y - 5);
                    });
                });
            }
        },

        tooltips: {
            callbacks: {
                label: function (tooltipItem, data) {
                    var dataset = data.datasets[tooltipItem.datasetIndex];
                    var currentValue = dataset.data[tooltipItem.index];
                    return dataset.label + ": " + currentValue + " %";
                }
            }
        },
        plugins: {
            datalabels: {
                display: true,
                align: 'top',
                formatter: (value, context) => {
                    return value + '%';
                }
            }
        }
    }
});


    // pre fy chart
    
const CostpreData = {!! $month_pre_fy !!};
var variant = CostpreData.map(item => item.name);
var yValues = CostpreData.map(item => parseFloat(item.cost.replace('%', '')) || 0);
var barColors = Array.from({ length: variant.length }, () => generateRandomColor());

var datasets = [
    {
        label: "Ratio",
        data: yValues,
        fill: false,
        borderColor: "#FF0000", 
        tension: 0.1
    }
];

new Chart("lineChartpre", {
    type: "line",
    data: {
        labels: variant,
        datasets: datasets
    },
    options: {
                hover: {
            animationDuration: 0
        },
        animation: {
            duration: 1,
            onComplete: function() {
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
                        // Concatenate "%" to the data variable
                        data = data + '%';
                        ctx.fillText(data, bar._model.x, bar._model.y - 5);
                    });
                });
            }
        },

        tooltips: {
            callbacks: {
                label: function (tooltipItem, data) {
                    var dataset = data.datasets[tooltipItem.datasetIndex];
                    var currentValue = dataset.data[tooltipItem.index];
                    return dataset.label + ": " + currentValue + " %";
                }
            }
        },
        plugins: {
            datalabels: {
                display: true,
                align: 'top',
                formatter: (value, context) => {
                    return value + '%';
                }
            }
        }
    }
});

    //end

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

</script>
<!-- end  -->

@endpush