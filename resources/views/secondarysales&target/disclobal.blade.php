@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Distributor Wise Closing Balence</h3>

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
    <form action="{{ url('salesandtargetclobal') }}" method="get" id="searchForm">
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

        <div class="col-md-3">
          <label for="division" class="form-label">State</label>
          <select name="state" id="state" class="form-select state select2 text-center">
            <option value="">-- please select --</option>
            @foreach($states as $state)
              <option value="{{ $state->state }}">{{ $state->state }}</option>
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
          <button type="submit" class="btn btn-primary px-5"> <i class="bi bi-search"></i> Search</button>
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
            <?php if (request('state') != ''): ?>
            <th class="align text-white bg-danger text-center">{{ request('state') }}</th>
            <?php endif; ?>
            <th colspan="5" class="align text-white bg-danger text-center">Distributors Wise Closing Stock Value UPTO -
              {{ $mon_yr }}</th>

          </tr>

          <tr>

            <th class="align text-white bg-secondary text-center">Zone</th>
            <th class="align text-white bg-secondary text-center">Region</th>
            <th class="align text-white bg-secondary text-center">State</th>
            <th class="align text-white bg-secondary text-center">Stockist / Distributor Name</th>
            <th class="align text-white bg-secondary text-center">{{ $mon_yr }}</th>
          </tr>
        </thead>
        <tbody>

          <?php  
        $totalStock = 0;
  foreach ($all_ind_sal as $value) {
    $totalStock += $value->sale;
          ?>
          <tr>
            <td>{{ $value->zone}}</td>
            <td>{{ $value->region}}</td>
            <td>{{ $value->state}}</td>
            <td>{{ $value->name}}</td>
            <td class="rupee-value" data-original="{{ $value->sale}}">{{ $value->sale}}</td>
          </tr>
          <?php } ?>
        </tbody>
        <tfoot class="table-danger">
          <!-- Add the total row for all products -->

          <tr class="sticky-foot fw-bold">
            <td>Grand Total</td>
            <td></td>
            <td></td>
            <td></td>
            <td class="rupee-value" data-original="{{ $totalStock}}">{{$totalStock}}</td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>


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
            filename: 'Distributors Wise Closing Stock Value',
            exportOptions: {
              format: {
                header: function (data, columnIdx) {
                  var header1 = $('#Table1 thead tr:eq(0) th').eq(columnIdx).text();
                  var header2 = $('#Table1 thead tr:eq(1) th').eq(columnIdx).text();
                  var header3 = $('#Table1 thead tr:eq(2) th').eq(columnIdx).text();

                  return header1 + '\n' + (header2 ? header2 + '\n' : '') + header3;
                }
              }
            }
          },
          {
            extend: 'pdfHtml5',
            filename: 'Distributors Wise Closing Stock Value',
            exportOptions: {
              format: {
                header: function (data, columnIdx) {
                  // Concatenate headers with line breaks
                  var header1 = $('#Table1 thead tr:eq(0) th').eq(columnIdx).text();
                  var header2 = $('#Table1 thead tr:eq(1) th').eq(columnIdx).text();
                  var header3 = $('#Table1 thead tr:eq(2) th').eq(columnIdx).text();

                  return header1 + '\n' + (header2 ? header2 + '\n' : '') + header3;
                }
              }
            }
          }
        ]
      });
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

    // search alert
    document.getElementById('searchForm').addEventListener('submit', function (event) {
      var zone = document.getElementById('zone').value;
      var region = document.getElementById('region').value;
      var state = document.getElementById('state').value;
      var startDate = document.getElementById('date_select').value;

      if (zone === '' && region === '' && startDate === '' && state === '') {
        alert('Please select Zone, Region,State or Month Before Searching.');
        event.preventDefault();
      }
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

    $(document).on('change', '.region', function () {

      var region = $(this).val();
      var $state = $(".state");

      if (region !== "") {

        var condition = encodeURIComponent("region='" + region + "'");

        var url = "{{ URL::to('jcombosecondsales') }}" +
          "?table=sd_primarydataupload_t:state:state" +
          "&parent=" + condition +
          "&order_by=state asc";

        $.ajax({
          url: url,
          type: "GET",
          success: function (response) {

            let data = response;

            if (typeof response === "string") {
              try {
                data = JSON.parse(response);
              } catch (e) {
                console.error("Invalid JSON:", response);
                return;
              }
            }

            // Clear previous options
            $state.empty().append('<option value="">-- Select State --</option>');

            // Populate results
            $.each(data, function (i, item) {
              $state.append(
                `<option value="${item.val}">${item.option_name}</option>`
              );
            });

            // Reinitialize Select2 if required
            $state.trigger('change.select2');
          },
          error: function (xhr, status, error) {
            console.error("AJAX Error:", error);
          }
        });

      } else {
        // Reset dropdown when region is empty
        $state.empty().append('<option value="">-- Select State --</option>');
      }
    });

  </script>
  <!-- end  -->
@endpush