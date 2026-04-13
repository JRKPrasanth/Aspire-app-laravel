@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Zone Wise Ratio</h3>

  <!-- tabs header -->
  <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h6 class="text-muted fw-bold">Last Updated At: <span
          class="text-success fw-bold">{{ $last_update[0]->created_at }}</span></h6>
      <h6 class="text-muted">Data Upto: <span class="text-success fw-bold">{{ $last_data }}</span></h6>
      <a href="{{ url($pageModule) }}" class="btn btn-outline-success fw-bold">Tabs</a>
    </div>
  </div>

  <div class="card shadow-lg rounded-4 border-0 p-4 mb-4">
    <form action="{{ url('primarysalezoneratio') }}" method="get" id="searchForm">
      <div class="row g-3 align-items-end">

        <!-- Zone -->
        <div class="col-md-3">
          <label for="zone" class="form-label">Zone</label>
          <select name="zone" id="zone" class="form-select select2 zone text-center">
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
        <div class="col-md-3">
          <label for="division" class="form-label">Division</label>
          <select name="division" id="division" class="form-select division select2 text-center">
            <option value="">-- please select --</option>
            @foreach($divisions as $division)
              <option value="{{ $division->division }}">{{ $division->division }}</option>
            @endforeach
          </select>
        </div>
        <!-- Month -->
        <div class="col-md-3">
          <label for="date_select" class="form-label">Month</label>
          <input type="month" name="date_select" id="date_select" class="form-control date_select" autocomplete="off">
        </div>

        <!-- Search Button -->
        <div class="col-12 text-center mt-4">
          <button type="submit" class="btn btn-primary px-5"><i class="bi bi-search"></i> Search</button>
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
            <?php if (request('division') != ''): ?>
            <th class="align text-white bg-danger text-center">{{ request('division') }}</th>
            <?php endif; ?>
            <th colspan="1" class="align text-white bg-danger text-left">last 6 months upto {{ $mon_yr}}</th>
            <th colspan="15" class="align text-white bg-danger text-left">ALL INDIA - % SAMPLE to SALES RATIO - ZONE
              WISE</th>
          </tr>
          <tr>
            <th colspan="1" class="bg-warning text-center text-white"></th>
            <?php $Zones = []; ?>
            <?php foreach ($zone_wise as $value) {
    $zone_type = $value->zone;
    if (!in_array($zone_type, $Zones)) {
      $Zones[] = $zone_type; ?>
            <th colspan="3" class="text-white bg-secondary text-center"><?php    echo $zone_type; ?></th>
            <?php  }
  } ?>
          </tr>
          <tr>
            <th class="bg-secondary text-center text-white">Product</th>
            <?php foreach ($Zones as $zone) { ?>
            <th class="bg-warning text-center">Sales</th>
            <th class="bg-warning text-center">Sample</th>
            <th class="bg-warning text-center">Ratio %</th>
            <?php } ?>
          </tr>

        </thead>
        <tbody>
          <?php 
          $products = array_unique(array_column($zone_wise, 'sfg_product_name'));
  $grandTotal = $grandTotalsales = $grandTotalsample = 0;
  foreach ($products as $product): ?>
          <tr>
            <td class='sticky-col'><?php  echo $product; ?></td>
            <?php  foreach ($Zones as $zone): ?>
            <?php 
                      $productData = array_filter($zone_wise, function ($value) use ($zone, $product) {
        return $value->zone == $zone && $value->sfg_product_name == $product;
      });

      if (!empty($productData)):
        $value = reset($productData); // Get the first element
                          ?>
            <td class="rupee-value" data-original="{{ $value->sale }}"><?php      echo $value->sale; ?></td>
            <td class="rupee-value" data-original="{{ $value->sale }}"><?php      echo $value->sample; ?></td>
            <td><?php      echo ($value->sale != '0') ? number_format($value->sample / $value->sale * 100, 0) . '%' : '0'; ?>
            </td>
            <?php    else: ?>
            <td>0</td>
            <td>0</td>
            <td>0%</td>
            <?php    endif; ?>
            <?php  endforeach; ?>
          </tr>
          <?php endforeach; ?>

        </tbody>
        <tfoot class="table-danger">
          <?php
  // Add Grand Total Row
  echo "<tr class='sticky-foot fw-bold'>";
  echo "<td class='sticky-col'>Total</td>";
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
      echo "<td >" . number_format($totalSamples / $totalSales * 100, 0) . " % </td>";
    } else {
      echo '<td >0</td>';
    }
  }

  echo "</tr>";
  ?>
        </tfoot>

      </table>
    </div>
  </div>


  <!-- END -->

  <!-- end -->

@endsection
@push('scripts')

  <!-- Include charts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

  <script>

    $(document).ready(function () {

      var startDate = "{{ request('date_select') }}";
      $('#date_select').val(startDate);


    });

    $(document).ready(function () {
      $('#Table1').DataTable({
                 scrollX: true,
                  scrollY: "50vh",
      });

    });

    // search alert
    document.getElementById('searchForm').addEventListener('submit', function (event) {
      var zone = document.getElementById('zone').value;
      var region = document.getElementById('region').value;
      var startDate = document.getElementById('date_select').value;
      var Division = document.getElementById('division').value;

      if (zone === '' && region === '' && startDate === '' && Division === '') {
        alert('Please select Zone, Region, or Month Before Searching.');
        event.preventDefault();
      }
    });

    // calendar freeze
    document.addEventListener('DOMContentLoaded', function () {
      var today = new Date();
      var currentYear = {{ $last_yr }};
      var currentMonth = '{{ sprintf('%02d', $last_mon) }}';

      var startMonthYear = '2022-04';
      var endMonthYear = currentYear + '-' + currentMonth;

      document.getElementById('date_select').setAttribute('min', startMonthYear);
      document.getElementById('date_select').setAttribute('max', endMonthYear);
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