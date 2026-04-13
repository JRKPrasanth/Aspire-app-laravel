@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Product Wise</h3>

  <!-- tabs header -->
  <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h6 class="text-muted fw-bold">Last Updated At: <span
          class="text-secondary fw-bold">{{ $last_update[0]->created_at }}</span></h6>
      <h6 class="text-muted">Data Upto: <span class="text-secondary fw-bold">{{ $last_data }}</span></h6>
      <a href="{{ url($pageModule) }}" class="btn btn-outline-primary fw-bold">Tabs</a>
    </div>
  </div>

  <div class="card shadow-lg rounded-4 border-0 p-4 mb-4">
    <form action="{{ url('primarysalesreturnproduct') }}" method="get" id="searchForm">
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
        <div class="col-12 text-center mt-3">
          <button type="submit" class="btn btn-primary px-5"> <i class="bi bi-search"></i> Search</button>
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

            <th colspan="5" class="align text-white bg-danger text-center"> PRODUCT WISE RATIO UPTO - {{ $mon_yr}}</th>
          </tr>

          <tr>
            <th class="align text-white bg-secondary text-center">Division</th>
            <th class="align text-white bg-secondary text-center">Product</th>
            <th class="align text-white bg-secondary text-center">Sales</th>
            <th class="align text-white bg-secondary text-center">Sales Return</th>
            <th class="align text-white bg-secondary text-center">Ratio</th>
          </tr>
        </thead>
        <tbody>
          <?php
  $totalSale = 0;
  $totalSample = 0;

  foreach ($state_wise as $value) {
    $totalSale += $value->sale;
    $totalSample += $value->sample;
      ?>
          <tr>
            <td>{{ $value->division }}</td>
            <td class="sticky-col">{{ $value->name }}</td>
            <td class="rupee-value" data-original="{{ $value->sale }}">{{ $value->sale }}</td>
            <td class="rupee-value" data-original="{{ $value->sample }}">{{ $value->sample }}</td>

            <?php  if ($value->sample != '0' && $value->sale == '0') { ?>
            <td style="text-align:center;background-color: violet;">100%</td>
            <?php  } else if ($value->sample == '0' && $value->sale != '0') { ?>
            <td>0%</td>
            <?php    } else if ($value->sample == '0' && $value->sale == '0') { ?>
            <td>0%</td>
            <?php    } else { ?>
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
            <?php    } ?>
          </tr>

          <?php } ?>
        </tbody>
        <!-- Add the total row for all products -->
        <tfoot>
          <tr class="sticky-foot fw-bold table-danger">
            <td>Grand Total</td>
            <td></td>
            <td class="rupee-value" data-original="{{ $totalSale }}">{{ $totalSale }}</td>
            <td class="rupee-value" data-original="{{ $totalSample }}">{{ $totalSample }}</td>
            <?php if ($totalSample == '0' && $totalSale != '0') { ?>
            <td>0%</td>
            <?php   } else if ($totalSample == '0' && $totalSale == '0') { ?>
            <td>0%</td>
            <?php  } else { ?>
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
            <?php  } ?>
          </tr>
        </tfoot>

      </table>
    </div>
  </div>

  <!-- END -->


  <!-- end -->
@endsection
@push('scripts')

  <script>

    $(document).ready(function () {

      var startDate = "{{ request('date_select') }}";
      $('#date_select').val(startDate);
    });


    $(document).ready(function () {
      $('#Table1').DataTable({
        scrollX: true,
        scrollY: "50vh",
        buttons: [
          {
            extend: 'excelHtml5',
            filename: 'product_wise_sales_return',
          },
          {
            extend: 'pdfHtml5',
            filename: 'product_wise_sales_return',
          }
        ]
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