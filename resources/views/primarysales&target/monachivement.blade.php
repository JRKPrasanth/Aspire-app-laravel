@extends('layouts.header')
@section('content')
<h3 class="text-danger">For the Month Achivement</h3>

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
    <form action="{{ url('primarysalesmonachive') }}" method="get" id="searchForm">
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
                <label for="date_select" class="col-form-label text-end d-block">Month</label>
                <input type="month" name="date_select" id="date_select" class="form-control" style="border-radius: 5px;" autocomplete="off">
            </div>

            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100" id="searchButton"><i class="bi bi-search"></i> Search</button>
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

<!---  distributor Wise Sale value -->
<!--tables-->
<div class="card shadow-lg rounded-4 border-0 p-4">
    <div class="table-responsive" style="overflow-x: auto;">
        <table id="Table1" class="table table-bordered table-striped table-hover w-100">
        <thead>
        <tr>

          <?php if (request('zone') != ''): ?>
                <th class="align text-white bg-danger text-center" >{{ request('zone') }}</th>
                <?php endif; ?>
            <?php if (request('region') != ''): ?>
                <th class="align text-white bg-danger text-center" >{{ request('region') }}</th>
                <?php endif; ?>
            <?php if (request('state') != ''): ?>
              <th class="align text-white bg-danger text-center" >{{ request('state') }}</th>
                <?php endif; ?>
                   <?php if (request('area') != ''): ?>
                  <th class="align" style="background:#9b900d">{{ request('area') }}</th>
                  <?php endif; ?>
                <th colspan="8" class="align text-white bg-danger text-center" >FOR THE MONTH TARGET AND ACHIVEMENT - {{ $mon_yr }}</th>
            
        </tr>
            <tr>
                <th colspan="4" class="align text-white bg-secondary text-center" ></th>
                <th colspan="1" class="align text-white bg-secondary text-center" >Sale</th>
                <th colspan="2" class="align text-white bg-secondary text-center" >Target</th>
                 <th colspan="1" class="align text-white bg-secondary text-center" ></th>

            </tr>
            <tr>
                  <th class="align bg-warning text-center">Zone</th>
                 <th class="align bg-warning text-center">Region</th>
                <th class="align bg-warning text-center">State</th>
                 <th class="align bg-warning text-center">Distributor's Name</th>
             <th class="align bg-warning text-center"><span class="text-warning">Sale </span>{{ $mon_yr }}</th>
            <th class="align bg-warning text-center"><span class="text-warning">Target </span>{{ $mon_yr }}</th>
             <th class="align bg-warning text-center"><span class="text-warning">Target </span>{{ $nxt_target }}</th>
                 <th class="align bg-warning text-center">Trg Vs Ach %</th>
            </tr>
        </thead>
<tbody>
  <?php 
    $grandTotalSale = 0;
    $grandTotalTarget = 0;
    $grandTotalnxtTarget = 0;
    foreach ($all_ind_sal as $value) { 
      $grandTotalSale += $value->sale;
      $grandTotalTarget += $value->target;
      $grandTotalnxtTarget += $value->nxt_target;
  ?>
  <tr>
    <td><?php echo $value->zone; ?></td>
    <td><?php echo $value->region; ?></td>
    <td><?php echo $value->state; ?></td>
    <td><?php echo $value->name; ?></td>
    <td><?php echo $value->sale; ?></td>
    <td><?php echo $value->target; ?></td>
    <td><?php echo $value->nxt_target; ?></td>
    <?php  
      if ($value->target != 0 && $value->sale !=0) { 
        echo '<td style="border-right: 1px solid #000; text-align: center; ' . getBackgroundColor($value->sale, $value->target) . '">';
        echo round(($value->sale / $value->target) * 100, 0) . '%';
        echo '</td>';
      } else { 
        echo '<td style="border-right: 1px solid #000;text-align: center;background: #c96fc6;">0</td>';
      } 
    ?>
  </tr>
  <?php } ?>
  </tbody>
  <tfoot>
  <tr class="sticky-foot fw-bold table-danger">
    <td>Grand Total</td>
    <td></td>
    <td></td>
    <td></td>
    <td><?php echo $grandTotalSale; ?></td>
    <td><?php echo $grandTotalTarget; ?></td>
    <td><?php echo $grandTotalnxtTarget; ?></td>
    <td><?php if ($grandTotalTarget != 0 && $grandTotalSale !=0) { echo round(($grandTotalSale / $grandTotalTarget) * 100, 0) . '%'; }else{ echo '0'; }?></td>
  </tr>

</tfoot>
        </table>
    </div>
</div>

@endsection
@push('scripts')

<!-- Include charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

  <script>
    // on chnange zone based region

        $(document).ready(function() {
            
            var startDate = "{{ request('date_select') }}";
            $('#date_select').val(startDate);
            
        
          });
  
        $(document).ready(function() {
        $('#Table1').DataTable({
            order: [],
            scrollY: "50vh",
            scrollCollapse: true, 
            buttons: [
                {
                    extend: 'excelHtml5',
                    filename: 'FOR_THE_MONTH_ACHIVEMENT',
                    exportOptions: {
                        format: {
                            header: function(data, columnIdx) {
                                var header1 = $('#Table1 thead tr:eq(0) th').eq(columnIdx).text();
                                var header3 = $('#Table1 thead tr:eq(2) th').eq(columnIdx).text();

                                return header1 + '\n' +  header3;
                            }
                        }
                    }
                },
                {
                    extend: 'pdfHtml5',
                    filename: 'FOR_THE_MONTH_ACHIVEMENT',
                    exportOptions: {
                        format: {
                            header: function(data, columnIdx) {
                                // Concatenate headers with line breaks
                                var header1 = $('#Table1 thead tr:eq(0) th').eq(columnIdx).text();
                                var header3 = $('#Table1 thead tr:eq(2) th').eq(columnIdx).text();

                                return header1 + '\n' +  header3;
                            }
                        }
                    }
                }
            ]
        });
        });
        
                        // search alert
         document.getElementById('searchForm').addEventListener('submit', function(event) {
        var zone = document.getElementById('zone').value;
        var region = document.getElementById('region').value;
        var state = document.getElementById('state').value;
         var Area = document.getElementById('area').value;
        var startDate = document.getElementById('date_select').value;

        if (zone === '' && region === '' && startDate === '' && state ==='' && Area ==='') {
            alert('Please select Zone, Region,Area or Month Before Searching.');
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


@endpush