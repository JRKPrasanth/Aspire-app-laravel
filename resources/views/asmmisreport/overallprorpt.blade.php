@extends('layouts.header')
@section('content')
<h3 class="text-danger">Overall Product</h3>

<!-- tabs header -->
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-3">
	<h6 class="text-muted fw-bold">Last Updated At: <span class="text-danger fw-bold">{{ $last_update[0]->created_at }}</span></h6>
    <h6 class="text-muted">Data Upto: <span class="text-danger fw-bold">{{ $last_data }}</span></h6>
	  <a href="{{ url($pageModule) }}" class="btn btn-outline-danger fw-bold">Tabs</a>
  </div>
 </div>
<!-- end -->

<div class="card shadow-lg rounded-4 border-0 p-4 mb-4">
      <form action="{{ url('prosearchrpt') }}" method="get" id="searchForm">
        <div class="row g-3">

            <!-- HQ -->
            <div class="col-md-3">
                <label for="region" class="col-form-label">Product</label>
                <select name="region" id="region" class="form-select region select2 text-center">
                    <option value="">-- please select --</option>
                    @foreach($pro_names as $product)
                        <option value="{{ $product->product_form_change }}">{{ $product->product_form_change }}</option>
                    @endforeach
                </select>
            </div>


            <!-- Manager -->
            <div class="col-md-3">
                <label for="manager" class="col-form-label">Manager</label>
                <select name="manager" id="manager" class="form-select manager select2 text-center">
                    <option value="">-- please select --</option>
                    @foreach($managers as $manager)
                        <option value="{{ $manager->name }}">{{ $manager->name }}</option>
                    @endforeach
                </select>
            </div>


            <!-- Month -->
            <div class="col-md-3">
                <label for="date_select" class="col-form-label">Month</label>
                <input type="month" name="date_select" id="date_select" class="form-control" autocomplete="off" style="border-radius: 5px;">
            </div>

            <!-- Submit Button -->
            <div class="col-md-2 d-flex align-items-end justify-content-end">
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
<!-- end -->
 <!---  product Wise -->
   <div class="card shadow-lg rounded-4 border-0 p-4">
    <div class="table-responsive" style="overflow-x: auto;">
        <table id="Table1" class="table table-bordered table-striped table-hover w-100">
        <thead>
        <tr>
            <?php if (request('product') != ''): ?>
            <th class="align text-white bg-danger text-center">{{ request('product') }}</th>
            <?php endif; ?>
              <?php if (request('manager') != ''): ?>
            <th class="align text-white bg-danger text-center">{{ request('manager') }}</th>
            <?php endif; ?>
            <th colspan="10" class="align text-white bg-danger text-center">  PRODUCT WISE UP TO - {{ $mon_yr }}</th>
        </tr>
        <tr>
            <th colspan="5" class="align text-white bg-secondary text-center"></th>
            <th colspan="2" class="align text-white bg-secondary text-center">Sales</th>
            <th colspan="1" class="align text-white bg-secondary text-center">Target</th>
            <th colspan="2" class="align text-white bg-secondary text-center"></th>
        </tr>
        <tr>
            <th class="align text-white bg-success text-center">Zone</th>
            <th class="align text-white bg-success text-center">Region</th>
            <th class="align text-white bg-success text-center">State</th>
            <th class="align text-white bg-success text-center">Hq Name</th>
            <th class="align text-white bg-success text-center">Fieldforce Name</th>
            <th class="align text-white bg-success text-center"><span style="color:#9799e5;font-size:1px;">Sale </span>{{ $pre_fy_year }}</th>
            <th class="align text-white bg-success text-center"><span style="color:#9799e5;font-size:1px;">Sale </span>{{ $cur_fy_year }}</th>
            <th class="align text-white bg-success text-center"><span style="color:#9799e5;font-size:1px;">Targer </span>{{ $cur_fy_year }}</th>
             <th class="align text-white bg-success text-center">Growth</th>
            <th class="align text-white bg-success text-center">Trg Vs Ach %</th>
        </tr>
    </thead>
<tbody>
    
<?php

$zoneData = [];
$divisionTotals = [
    'totalPrimaryDis_pre_fy' => 0,
    'totalPrimaryDis_cur_fy' => 0,
    'totaltarget_cur_fy' => 0,
];
 if(!empty ($all_ind_sal)) {
foreach ($all_ind_sal as $value) {
    $Zone = $value->hq_name;

    
    if (!isset($zoneData[$Zone])) {
        $zoneData[$Zone] = [
            
            'region' => $value->region,
            'state' => $value->state,
            'hq_name' => $value->zone,
            'name' => $value->field_name,
            'primary_dis_pre_fy' => 0,
            'primary_dis_cur_fy' => 0,
            'target_cur_fy' => 0,

        ];
    }

    if ($value->f_year == $pre_fy_year) {
        
        $zoneData[$Zone]['primary_dis_pre_fy'] += $value->sale;

    } elseif ($value->f_year == $cur_fy_year) {

        $zoneData[$Zone]['primary_dis_cur_fy'] += $value->sale;
        $zoneData[$Zone]['target_cur_fy'] += $value->target;
    }

    // Update division totals
    $divisionTotals['totalPrimaryDis_pre_fy'] += $value->f_year == $pre_fy_year ? $value->sale : 0;
    $divisionTotals['totalPrimaryDis_cur_fy'] += $value->f_year == $cur_fy_year ? $value->sale : 0;
    $divisionTotals['totaltarget_cur_fy'] += $value->f_year == $cur_fy_year ? $value->target : 0;

}

foreach ($zoneData as $Zone => $data) {
?>
<tr>

    <td ><?php echo $data['hq_name']; ?></td>
    <td ><?php echo $data['region']; ?></td>
    <td ><?php echo $data['state']; ?></td>
    <td class="sticky-col" ><?php echo $Zone; ?></td>
    <td ><?php echo $data['name']; ?></td>

    <td class="rupee-value" data-original="<?php echo $data['primary_dis_pre_fy']; ?>" ><?php echo $data['primary_dis_pre_fy']; ?></td>
    <td class="rupee-value" data-original="<?php echo $data['primary_dis_cur_fy']; ?>" ><?php echo $data['primary_dis_cur_fy']; ?></td>
    <td class="rupee-value" data-original="<?php echo $data['target_cur_fy']; ?>" ><?php echo $data['target_cur_fy']; ?></td>
        <td >
        <?php
        if ($data['primary_dis_cur_fy'] != 0 && $data['primary_dis_pre_fy'] != 0) {
            echo round(($data['primary_dis_cur_fy'] / $data['primary_dis_pre_fy'] - 1) * 100, 0) . '%';
        } else {
            echo '0';
        }
        ?>
    </td>

    <td style= <?php echo getBackgroundColor($data['primary_dis_cur_fy'], $data['target_cur_fy']); ?>>
        <?php
        if ($data['target_cur_fy'] != 0) {
            echo round(($data['primary_dis_cur_fy'] / $data['target_cur_fy']) * 100, 0) . '%';
        } else {
            echo '0';
        }
        ?>
    </td>

</tr>
<?php } ?>
  </tbody>
<!-- Add the total row for all products -->
<tfoot>
<tr class="sticky-foot fw-bold">
    <td >Grand Total</td>
    <td ></td>
    <td ></td>
    <td ></td>
        <td ></td>
<td class="rupee-value" data-original="<?php echo $divisionTotals['totalPrimaryDis_pre_fy']; ?>" ><?php echo $divisionTotals['totalPrimaryDis_pre_fy']; ?></td>
    <td class="rupee-value" data-original="<?php echo $divisionTotals['totalPrimaryDis_cur_fy']; ?>" ><?php echo $divisionTotals['totalPrimaryDis_cur_fy']; ?></td>
    <td class="rupee-value" data-original="<?php echo $divisionTotals['totaltarget_cur_fy']; ?>"  ><?php echo $divisionTotals['totaltarget_cur_fy']; ?></td>
    <td >
        <?php
        if ($divisionTotals['totalPrimaryDis_pre_fy'] != 0) {
            echo round(($divisionTotals['totalPrimaryDis_cur_fy'] / $divisionTotals['totalPrimaryDis_pre_fy'] - 1) * 100, 0) . '%';
        } else {
            echo '0';
        }
        ?>
    </td>

    <td style= <?php echo getBackgroundColor($divisionTotals['totalPrimaryDis_cur_fy'], $divisionTotals['totaltarget_cur_fy']); ?>>
        <?php
        if ($divisionTotals['totaltarget_cur_fy'] != 0) {
            echo round(($divisionTotals['totalPrimaryDis_cur_fy'] / $divisionTotals['totaltarget_cur_fy']) * 100, 0) . '%';
        } else {
            echo '0';
        }
        ?>
    </td>

</tr>
</tfoot>
				<?php  } ?>
        </table>

    </div>
</div>

	@endsection
	@push('scripts')

    <script>

        $(document).ready(function() {
            
            var startDate = "{{ request('date_select') }}";
            $('#date_select').val(startDate);
            
            
             var Product = "{{ request('product') }}";
            $('.product').select2();
            $('#product').val(Product).trigger('change');
          });
  
        $(document).ready(function() {
            $('#Table1').DataTable({
            dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
            order: [],
            buttons: [
                {
                    extend: 'excelHtml5',
                    filename: 'PRODUCT WISE SEARCH',
                    exportOptions: {
                        format: {
                            header: function(data, columnIdx) {
                                var header1 = $('#Table2 thead tr:eq(0) th').eq(columnIdx).text();
                                var header3 = $('#Table2 thead tr:eq(2) th').eq(columnIdx).text();

                                return header1 + '\n' + header3;
                            }
                        }
                    }
                },
                {
                    extend: 'pdfHtml5',
                    filename: 'PRODUCT WISE SEARCH',
                    exportOptions: {
                        format: {
                            header: function(data, columnIdx) {
                                // Concatenate headers with line breaks
                                var header1 = $('#Table2 thead tr:eq(0) th').eq(columnIdx).text();
                                var header3 = $('#Table2 thead tr:eq(2) th').eq(columnIdx).text();

                                return header1 + '\n' + header3;
                            }
                        }
                    }
                }
            ]
        });
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
         // search alert
         document.getElementById('searchForm').addEventListener('submit', function(event) {
        var region = document.getElementById('manager').value;
        var startDate = document.getElementById('date_select').value;
        var Division = document.getElementById('product').value;
        
        if ( region === '' && startDate === '' && Division === '') {
            alert('Please select Zone, Region, or Month Before Searching.');
            event.preventDefault(); 
        }
    });
    
                                    // search alert
         document.getElementById('searchForm').addEventListener('submit', function(event) {
        var date = document.getElementById('date_select').value;

        
        if (date === '') {
            alert('Please Select Month Before Searching.');
            event.preventDefault(); 
        }
    });
    </script>
<!-- end  -->
@endpush