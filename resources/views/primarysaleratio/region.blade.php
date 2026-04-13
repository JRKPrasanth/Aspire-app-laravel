@extends('layouts.header')
@section('content')
<h3 class="text-danger">Region Wise</h3>

<!-- tabs header -->
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-3">
	<h6 class="text-muted fw-bold">Last Updated At: <span class="text-success fw-bold">{{ $last_update[0]->created_at }}</span></h6>
    <h6 class="text-muted">Data Upto: <span class="text-success fw-bold">{{ $last_data }}</span></h6>
	  <a href="{{ url($pageModule) }}" class="btn btn-outline-success fw-bold">Tabs</a>
  </div>
 </div>

<div class="card shadow-lg rounded-4 border-0 p-4 mb-4">
  <form action="{{ url('primarysaleratioregion') }}" method="get" id="searchForm">
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
      <!-- Month -->
      <div class="col-md-3">
        <label for="date_select" class="form-label">Month</label>
        <input type="month" name="date_select" id="date_select" class="form-control date_select" autocomplete="off">
      </div>

      <!-- Search Button -->
      <div class="col-md-2 d-grid">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-search"></i> Search
        </button>
      </div>
    </div>
  </form>
</div>


<div class="card shadow-lg rounded-4 border-0 p-4 mb-4">
  <div class="row g-3 text-center">
    <div class="col-md-3 col-sm-6">
      <div class="p-3 bg-light rounded">
        <strong>UPTO 3%</strong><br>Good
      </div>
    </div>
    <div class="col-md-3 col-sm-6">
      <div class="p-3 bg-light rounded">
        <strong>UPTO 5%</strong><br>Ok
      </div>
    </div>
    <div class="col-md-3 col-sm-6">
      <div class="p-3 text-white rounded" style="background-color:#f9b436;">
        <strong>5% To 8%</strong><br>Need To Reduce Sampling
      </div>
    </div>
    <div class="col-md-3 col-sm-6">
      <div class="p-3 text-white rounded" style="background-color:#c96fc6;">
        <strong>Above 8%</strong><br>Stop Sampling
      </div>
    </div>
  </div>
</div>


<!-- convert money inr to k and l purpose  -->
<div class="text-center mb-4">
  <button class="btn btn-primary fw-bold me-2" id="btnThousand">Show in Thousands</button>
  <button class="btn btn-success fw-bold me-2" id="btnLakhs">Show in Lakhs</button>
  <button class="btn btn-danger fw-bold" id="btnReset">Reset</button>
</div>
<!-- end -->

<!--tables-->
<div class="card shadow-lg rounded-4 border-0 p-4">
    <div class="table-responsive" style="overflow-x: auto;">
        <table id="Table1" class="table table-bordered table-striped table-hover w-100">
        <thead>
            <tr>
                  <?php if (request('zone') != ''): ?>
                <th class="align text-white bg-danger text-center">{{ request('zone') }}</th>
                <?php endif; ?>
                <?php if (request('region') != ''): ?>
                <th class="align text-white bg-danger text-center">{{ request('region') }}</th>
                <?php endif; ?>
                
                <th  colspan="4" class="align text-white bg-danger text-center">ALL INDIA - % SAMPLE TO SALES RATIO - Region</th>
                 </tr>
                 <tr style="background:#ffd2bc">

                    <th colspan="1" class="align text-white bg-secondary text-center">last 6 months upto  {{ $mon_yr}}</th>
                    <th colspan="1" class="align text-white bg-secondary text-center">REGION WISE</th>
                    <th colspan="2" class="align text-white bg-secondary text-center">Sum of Asseesable Value</th>


                </tr>
                <tr>
                    <th colspan="1" class="align  table-warning text-center" >Region</th>
                     <th colspan="1" class="align  table-warning text-center" >Sales</th>
                      <th colspan="1" class="align  table-warning text-center" >Sample</th>
                       <th colspan="1" class="align  table-warning text-center" >Ratio</th>
                </tr>
            </thead>
   <tbody>
    <?php
    $totalSale = 0;
    $totalSample = 0;

    foreach ($month_wise as $value) {
        $totalSale += $value->sale;
        $totalSample += $value->sample;
    ?>
        <tr>
            <td class="sticky-col"  style="text-align:center;">{{ $value->region }}</td>
            <td class="rupee-value" data-original="{{ $value->sale }}" style="text-align:center;">{{ $value->sale }}</td>
            <td class="rupee-value" data-original="{{ $value->sample }}" style="text-align:center;">{{ $value->sample }}</td>
                    
        <?php if($value->sale == '0'){ ?>
            <td style="text-align:center;background-color: violet;">100%</td>
         <?php } else if ($value->sample == '0' && $value->sale != '0') { ?>
         <td style="text-align:center;">0%</td>
         <?php } else if ($value->sample == '0' && $value->sale == '0') { ?>
         <td style="text-align:center;">0%</td>
         <?php }else{ ?>
    <td style="text-align:center; 
        @if(number_format($value->sample / $value->sale * 100, 0) <= 5)
            background-color: white;
        @elseif(number_format($value->sample / $value->sale * 100, 0) <= 8)
            background-color: orange;
        @else
            background-color: violet;
        @endif ">
    {{ number_format($value->sample / $value->sale * 100, 0) }}%
   </td>
     <?php } ?>
        </tr>
     
    <?php } ?>
</tbody>
<!-- Add the total row for all products -->
<tfoot class="table-danger">
    <tr class='sticky-foot' style="background:#ffbef7;font-weight: 600;">
        <td >Grand Total</td>
        <td  class="rupee-value" data-original="{{ $totalSale }}" >{{ $totalSale }}</td>
        <td  class="rupee-value" data-original="{{ $totalSample }}">{{ $totalSample }}</td>
        <?php if( $totalSale == '0'){ ?>
           <td >0%</td>
         <?php   } else if ($totalSample == '0' && $totalSale == '0') { ?>
         <td >0%</td>
         <?php }else{ ?>
    <td style="text-align:center; 
    background-color:
        @if(number_format($totalSample / $totalSale * 100, 0) <= 5)
            white;
        @elseif(number_format($totalSample / $totalSale * 100, 0) <= 8)
            orange;
        @else
            violet;
        @endif
    ">
        {{ number_format($totalSample / $totalSale * 100, 0) }}%
    </td>
    <?php } ?>
    </tr>
</tfoot>
        </table>
    </div>
</div>
     <!-- END -->
 
     
         <!-- machine wise summary chart -->
         <div class="card shadow-lg rounded-4 border-0 p-3 mb-4">
              <h5 class="chart_tittle">Region Wise Sample Ratio - {{ $mon_yr}}</h5>
                    <canvas id="monthChart" style="max-height:460px"></canvas>

            </div>
<!-- end -->  
    
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
                scrollY: "50vh",

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

const monthData = {!! $month_sumchart !!};
var variant = monthData.map(item => item.region);
var yValues = monthData.map(item => parseFloat(item.ratio.replace('%', '')) || 0);
var barColors = Array.from({ length: variant.length }, () => generateRandomColor());

new Chart("monthChart", {
    type: "bar",
    data: {
        labels: variant,
        datasets: [
            {
                label: "Ratio %",
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
                ctx.fillStyle  = 'black';
                this.data.datasets.forEach(function(dataset, i) {
                    var meta = chartInstance.controller.getDatasetMeta(i);
                    meta.data.forEach(function(bar, index) {
                        var data = dataset.data[index];
                        data = data + '%';
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