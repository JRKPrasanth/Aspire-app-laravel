@extends('layouts.header')
@section('content')
<h3 class="text-danger">Product Wise</h3>

<!-- tabs header -->
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-3">
	<h6 class="text-muted fw-bold">Last Updated At: <span class="text-primary fw-bold">{{ $last_update[0]->created_at }}</span></h6>
    <h6 class="text-muted">Data Upto: <span class="text-primary fw-bold">{{ $last_data }}</span></h6>
	  <a href="{{ url($pageModule) }}" class="btn btn-outline-primary fw-bold">Tabs</a>
  </div>
 </div>
<!-- end -->

<div class="card shadow-lg rounded-4 border-0 p-4 mb-4">
    <form action="{{ url('primarysalesandtargetproduct') }}" method="get" id="searchForm">
        <div class="row g-3">
            <div class="col-md-3">
                <label for="zone" class="col-form-label text-end d-block">Zone</label>
                <select name="zone" id="zone" class="form-select zone select2 text-center">
                    <option value="">-- please select --</option>
                    @foreach($zones as $zone)
                        <option value="{{ $zone->zone }}">{{ $zone->zone }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="region" class="col-form-label text-end d-block">Region</label>
                <select name="region" id="region" class="form-select region select2 text-center">
                    <option value="">-- please select --</option>
                    @foreach($regions as $region)
                        <option value="{{ $region->region }}">{{ $region->region }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="state" class="col-form-label text-end d-block">State</label>
                <select name="state" id="state" class="form-select state select2 text-center">
                    <option value="">-- please select --</option>
                    @foreach($states as $state)
                        <option value="{{ $state->state }}">{{ $state->state }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="area" class="col-form-label text-end d-block">Area</label>
                <select name="area" id="area" class="form-select area select2 text-center">
                    <option value="">-- please select --</option>
                    @foreach($areas as $area)
                        <option value="{{ $area->area }}">{{ $area->area }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="dist_name" class="col-form-label text-end d-block">Distributor</label>
                <select name="dist_name" id="dist_name" class="form-select dist_name select2 text-center">
                    <option value="">-- please select --</option>
                    @foreach($distributor_name as $dist)
                        <option value="{{ $dist->name }}">{{ $dist->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="division" class="col-form-label text-end d-block">Division</label>
                <select name="division" id="division" class="form-select division select2 text-center">
                    <option value="">-- please select --</option>
                    @foreach($divisions as $division)
                        <option value="{{ $division->division }}">{{ $division->division }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="date_select" class="col-form-label text-end d-block">Month</label>
                <input type="month" name="date_select" id="date_select" class="form-control" style="border-radius: 5px;" autocomplete="off">
            </div>

            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary" id="searchButton"><i class="bi bi-search"></i> Search</button>
            </div>
        </div>
    </form>
</div>

<!-- Status Legend -->
<div class="card shadow-lg rounded-4 border-0 p-3 mb-4">
  <div class="d-flex flex-wrap justify-content-center gap-3">
    <div class="px-3 py-2 text-white rounded" style="background:#c96fc6; width: 160px;">0% - 50% → <strong>VERY POOR</strong></div>
    <div class="px-3 py-2 text-white rounded" style="background:#df9f29; width: 160px;">51% - 75% → <strong>POOR</strong></div>
    <div class="px-3 py-2 text-dark rounded" style="background:#e1e1e1; width: 160px;">75% - 85% → <strong>SUB STANDARD</strong></div>
    <div class="px-3 py-2 text-white rounded" style="background:#0bb921; width: 160px;">85% - 100% → <strong>GOOD</strong></div>
  </div>
</div>

<!-- Value Format Buttons -->
<div class="text-center mb-4">
  <button class="btn btn-primary fw-bold me-2" id="btnThousand">Show in Thousands</button>
  <button class="btn btn-success fw-bold me-2" id="btnLakhs">Show in Lakhs</button>
  <button class="btn btn-danger fw-bold" id="btnReset">Reset</button>
</div>


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
<!--tables-->
<div class="card shadow-lg rounded-4 border-0 p-4">
    <div class="table-responsive">
        <table id="Table1" class="table table-bordered table-striped table-hover w-100 datatable-common">

            <thead>

                {{-- TOP TITLE ROW --}}
                <tr>
                    <th colspan="9" class="text-white bg-danger text-center">
                        PRODUCT WISE SALES AND TARGET UPTO {{ $mon_yr }}
                    </th>
                </tr>

                {{-- ACTUAL COLUMN HEADER --}}
                <tr>

                    <th class="bg-secondary text-white">Division</th>
                    <th class="bg-secondary text-white">Product</th>
                    <th class="bg-secondary text-white">Sale {{ $pre_fy_year }}</th>
                    <th class="bg-secondary text-white">Sale {{ $cur_fy_year }}</th>
                    <th class="bg-secondary text-white">Increase / Decrease</th>
                    <th class="bg-secondary text-white">Growth %</th>
                    <th class="bg-secondary text-white">Target {{ $cur_fy_year }}</th>
                    <th class="bg-secondary text-white">Short Fall In Target</th>
                    <th class="bg-secondary text-white">Trg Vs Ach %</th>

                </tr>

            </thead>

            <tbody>

<?php
$zoneData = [];
$divisionTotals = [
    'pre' => 0,
    'cur' => 0,
    'target' => 0,
];

foreach ($all_ind_sal as $value) {

    $product = $value->product_name;

    if (!isset($zoneData[$product])) {
        $zoneData[$product] = [
            'division' => $value->division,
            'pre' => 0,
            'cur' => 0,
            'target' => 0,
        ];
    }

    if ($value->f_year == $pre_fy_year) {
        $zoneData[$product]['pre'] += $value->sale;
    }

    if ($value->f_year == $cur_fy_year) {
        $zoneData[$product]['cur'] += $value->sale;
        $zoneData[$product]['target'] += $value->target;
    }

    $divisionTotals['pre'] += $value->f_year == $pre_fy_year ? $value->sale : 0;
    $divisionTotals['cur'] += $value->f_year == $cur_fy_year ? $value->sale : 0;
    $divisionTotals['target'] += $value->f_year == $cur_fy_year ? $value->target : 0;
}
?>

<?php foreach ($zoneData as $product => $data): ?>

<tr>

    <td class="text-center">{{ $data['division'] }}</td>
    <td class="text-center sticky-col">{{ $product }}</td>

    <td>{{ number_format($data['pre']) }}</td>
    <td>{{ number_format($data['cur']) }}</td>

    <td>{{ number_format($data['cur'] - $data['pre']) }}</td>

    <td>
        {{ $data['pre'] > 0 ? round((($data['cur']/$data['pre']) - 1) * 100,0).'%' : '0%' }}
    </td>

    <td>{{ number_format($data['target']) }}</td>

    <td>{{ number_format($data['cur'] - $data['target']) }}</td>

    <td>
        @if($data['target'] > 0)
            {{ round(($data['cur']/$data['target'])*100,0) }}%
        @else
            0%
        @endif
    </td>

</tr>

<?php endforeach; ?>
<tfoot>
<tr class="table-danger fw-bold">

    <td>Grand Total</td>
    <td></td>

    <td>{{ number_format($divisionTotals['pre']) }}</td>
    <td>{{ number_format($divisionTotals['cur']) }}</td>

    <td>{{ number_format($divisionTotals['cur'] - $divisionTotals['pre']) }}</td>

    <td>
        {{ $divisionTotals['pre'] > 0 ? round((($divisionTotals['cur']/$divisionTotals['pre']) -1)*100,0).'%' : '0%' }}
    </td>

    <td>{{ number_format($divisionTotals['target']) }}</td>

    <td>{{ number_format($divisionTotals['cur'] - $divisionTotals['target']) }}</td>

    <td>
        {{ $divisionTotals['target'] > 0 ? round(($divisionTotals['cur']/$divisionTotals['target'])*100,0).'%' : '0%' }}
    </td>

</tr>
</tfoot>

</tbody>
</table>
</div>
</div>
     <!-- END -->

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
  
            $(document).ready(function () {

                $('.datatable-common').DataTable({

                    processing: true,
                    serverSide: false,

                    scrollX: true,
                    scrollY: "50vh",

                    autoWidth: false,
                    orderCellsTop: true,
                    fixedHeader: true,

                    pageLength: 25,

                    dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',

                    buttons: [

                        {
                            extend: 'excelHtml5',
                            filename: 'primary_sale_and_target_product'
                        },

                        {
                            extend: 'pdfHtml5',
                            filename: 'primary_sale_and_target_product',
                            orientation: 'landscape',
                            pageSize: 'A4'
                        }

                    ],

                    initComplete: function () {
                        this.api().columns.adjust();
                    }

                });

            });
        
     // search alert
         document.getElementById('searchForm').addEventListener('submit', function(event) {  
        var zone = document.getElementById('zone').value;
        var region = document.getElementById('region').value;
        var startDate = document.getElementById('date_select').value;
        var Division = document.getElementById('division').value;
        var Dist = document.getElementById('dist_name').value;
        var Area = document.getElementById('area').value;

        if (zone === '' && region === '' && startDate === '' && Division === '' && Dist === '' && Area ==='') {
            alert('Please select Zone, Region,Distributor,Area or Month Before Searching.');
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


<!-- convert money inr to k and l purpose  -->

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