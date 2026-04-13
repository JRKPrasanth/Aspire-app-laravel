@extends('layouts.header')
@section('content')
<h3 class="text-danger">Product wise Target and Achivement</h3>

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
      <form action="{{ url('misreporthqpro') }}" method="get" id="searchForm">
        <div class="row g-3">

            <!-- HQ -->
            <div class="col-md-3">
                <label for="region" class="col-form-label">HQ</label>
                <select name="region" id="region" class="form-select region select2 text-center">
                    <option value="">-- please select --</option>
                    @foreach($regions as $region)
                        <option value="{{ $region->hq_name }}">{{ $region->hq_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Area -->
            <div class="col-md-3">
                <label for="area" class="col-form-label">Area</label>
                <select name="area" id="area" class="form-select area select2 text-center">
                    <option value="">-- please select --</option>
                    @foreach($areas as $area)
                        <option value="{{ $area->area }}">{{ $area->area }}</option>
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
            <div class="col-md-3">
                <label for="product" class="col-form-label">Product</label>
                <select name="product" id="product" class="form-select product select2 text-center">
                    <option value="">-- please select --</option>
                    @foreach($products as $product)
                                <option value="{{ $product->product_name }}">{{ $product->product_name }}</option>
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
 <!---  product Wise -->
<div class="card shadow-lg rounded-4 border-0 p-4">
    <div class="table-responsive" style="overflow-x: auto;">
        <table id="Table1" class="table table-bordered table-striped table-hover w-100">
        <thead>
        <tr>
            <?php if (request('product') != ''): ?>
            <th class="align text-white bg-danger text-center">{{ request('product') }}</th>
            <?php endif; ?>
            <?php if (request('region') != ''): ?>
            <th class="align text-white bg-danger text-center">{{ request('region') }}</th>
            <?php endif; ?>
             <?php if (request('manager') != ''): ?>
            <th class="align text-white bg-danger text-center">{{ request('manager') }}</th>
            <?php endif; ?>
            <?php if (request('area') != ''): ?>
              <th class="align text-white bg-danger text-center">{{ request('area') }}</th>
              <?php endif; ?>
            <th colspan="7" class="align text-white bg-danger text-center">HQ WISE PRODUCT Target And Achivement UPTO - {{ $mon_yr }}</th>
        </tr>
        <tr>
            <th colspan="2" class="align text-white bg-secondary text-center"></th>
            <th colspan="2" class="align text-white bg-secondary text-center">Sale</th>
            <th colspan="1" class="align text-white bg-secondary text-center">Target</th>
            <th colspan="3" class="align text-white bg-secondary text-center"></th>
        </tr>
        <tr>
            <th class="align text-white bg-success text-center">Hq Name</th>
            <th class="align text-white bg-success text-center">Product Name</th>
            <th class="align text-white bg-success text-center"><span class="text-success" style="font-size:6px;">Sale </span>{{ $pre_fy_year }}</th>
            <th class="align text-white bg-success text-center"><span class="text-success" style="font-size:6px;">Sale </span>{{ $cur_fy_year}}</th>
            <th class="align text-white bg-success text-center"><span class="text-success" style="font-size:6px;">Target </span>{{ $cur_fy_year}}</th>
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

        foreach ($all_ind_sal as $value) {
            $Zone = $value->hq_name;
            $ProductName = $value->name;

            if (!isset($zoneData[$Zone])) {
                $zoneData[$Zone] = [];
            }

            if (!isset($zoneData[$Zone][$ProductName])) {
                $zoneData[$Zone][$ProductName] = [
                    'primary_dis_pre_fy' => 0,
                    'primary_dis_cur_fy' => 0,
                    'target_cur_fy' => 0,
                ];
            }

            if ($value->f_year == $pre_fy_year) {
                $zoneData[$Zone][$ProductName]['primary_dis_pre_fy'] += $value->sale;
            } elseif ($value->f_year == $cur_fy_year) {
                $zoneData[$Zone][$ProductName]['primary_dis_cur_fy'] += $value->sale;
                $zoneData[$Zone][$ProductName]['target_cur_fy'] += $value->target;
            }

            // Update division totals
            $divisionTotals['totalPrimaryDis_pre_fy'] += $value->f_year == $pre_fy_year ? $value->sale : 0;
            $divisionTotals['totalPrimaryDis_cur_fy'] += $value->f_year == $cur_fy_year ? $value->sale : 0;
            $divisionTotals['totaltarget_cur_fy'] += $value->f_year == $cur_fy_year ? $value->target : 0;
        }

        foreach ($zoneData as $Zone => $products) {
            foreach ($products as $ProductName => $data) {
        ?>
        <tr>
            <td class="sticky-col" ><?php echo $Zone; ?></td>
            <td ><?php echo $ProductName; ?></td>
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
            <td  <?php echo getBackgroundColor($data['primary_dis_cur_fy'], $data['target_cur_fy']); ?>>
                <?php
                if ($data['target_cur_fy'] != 0) {
                    echo round(($data['primary_dis_cur_fy'] / $data['target_cur_fy']) * 100, 0) . '%';
                } else {
                    echo '0';
                }
                ?>
            </td>
        </tr>
        <?php } } ?>
    </tbody>
    <!-- Add the total row for all products -->
    <tfoot>
        <tr class="sticky-foot fw-bold">
            <td >Grand Total</td>
            <td ></td>
            <td class="rupee-value" data-original="<?php echo $divisionTotals['totalPrimaryDis_pre_fy']; ?>" ><?php echo $divisionTotals['totalPrimaryDis_pre_fy']; ?></td>
            <td class="rupee-value" data-original="<?php echo $divisionTotals['totalPrimaryDis_cur_fy']; ?>" ><?php echo $divisionTotals['totalPrimaryDis_cur_fy']; ?></td>
            <td class="rupee-value" data-original="<?php echo $divisionTotals['totaltarget_cur_fy']; ?>" ><?php echo $divisionTotals['totaltarget_cur_fy']; ?></td>
            <td >
                <?php
                if ($divisionTotals['totalPrimaryDis_pre_fy'] != 0) {
                    echo round(($divisionTotals['totalPrimaryDis_cur_fy'] / $divisionTotals['totalPrimaryDis_pre_fy'] - 1) * 100, 0) . '%';
                } else {
                    echo '0';
                }
                ?>
            </td>
            <td  <?php echo getBackgroundColor($divisionTotals['totalPrimaryDis_cur_fy'], $divisionTotals['totaltarget_cur_fy']); ?>>
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
        </table>
    </div>
</div>
    <!---  product Wise -->
<div class="card shadow-lg rounded-4 border-0 p-4">
    <div class="table-responsive" style="overflow-x: auto;">
        <table id="Table2" class="table table-bordered table-striped table-hover w-100">
        <thead>
    <tr>
        <?php if (request('product') != ''): ?>
            <th class="align text-white bg-danger text-center"><?= request('product') ?></th>
        <?php endif; ?>
        <?php if (request('region') != ''): ?>
            <th class="align text-white bg-danger text-center"><?= request('region') ?></th>
        <?php endif; ?>
        <?php if (request('manager') != ''): ?>
            <th class="align text-white bg-danger text-center"><?= request('manager') ?></th>
        <?php endif; ?>
        <?php if (request('area') != ''): ?>
            <th class="align text-white bg-danger text-center"><?= request('area') ?></th>
        <?php endif; ?>
        <th colspan="6" class="align text-white bg-danger text-center">PRODUCT Wise Target And Achievement UPTO - <?= $mon_yr ?></th>
    </tr>
    <tr>
        <th class="align text-white bg-secondary text-center">Product Name</th>
        <th class="align text-white bg-secondary text-center">Sale <?= $pre_fy_year ?></th>
        <th class="align text-white bg-secondary text-center">Sale <?= $cur_fy_year ?></th>
        <th class="align text-white bg-secondary text-center">Target <?= $cur_fy_year ?></th>
        <th class="align text-white bg-secondary text-center">Growth</th>
        <th class="align text-white bg-secondary text-center">Trg Vs Ach %</th>
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

    foreach ($all_ind_sal_pro as $value) {
        $ProductName = $value->name;

        if (!isset($zoneData[$ProductName])) {
            $zoneData[$ProductName] = [
                'primary_dis_pre_fy' => 0,
                'primary_dis_cur_fy' => 0,
                'target_cur_fy' => 0,
            ];
        }

        if ($value->f_year == $pre_fy_year) {
            $zoneData[$ProductName]['primary_dis_pre_fy'] += $value->sale;
        } elseif ($value->f_year == $cur_fy_year) {
            $zoneData[$ProductName]['primary_dis_cur_fy'] += $value->sale;
            $zoneData[$ProductName]['target_cur_fy'] += $value->target;
        }

        $divisionTotals['totalPrimaryDis_pre_fy'] += $value->f_year == $pre_fy_year ? $value->sale : 0;
        $divisionTotals['totalPrimaryDis_cur_fy'] += $value->f_year == $cur_fy_year ? $value->sale : 0;
        $divisionTotals['totaltarget_cur_fy'] += $value->f_year == $cur_fy_year ? $value->target : 0;
    }

    foreach ($zoneData as $ProductName => $data) {
        ?>
        <tr>
            <td class="sticky-col"><?= $ProductName ?></td>
            <td class="rupee-value" ><?= $data['primary_dis_pre_fy'] ?></td>
            <td class="rupee-value" ><?= $data['primary_dis_cur_fy'] ?></td>
            <td class="rupee-value" ><?= $data['target_cur_fy'] ?></td>
            <td >
                <?php
                if ($data['primary_dis_pre_fy'] != 0) {
                    echo round(($data['primary_dis_cur_fy'] / $data['primary_dis_pre_fy'] - 1) * 100, 0) . '%';
                } else {
                    echo '0';
                }
                ?>
            </td>
            <td <?= getBackgroundColor($data['primary_dis_cur_fy'], $data['target_cur_fy']) ?>>
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
<tfoot>
    <tr class="fw-bold">
        <td >Grand Total</td>
        <td class="rupee-value" ><?= $divisionTotals['totalPrimaryDis_pre_fy'] ?></td>
        <td class="rupee-value" ><?= $divisionTotals['totalPrimaryDis_cur_fy'] ?></td>
        <td class="rupee-value" ><?= $divisionTotals['totaltarget_cur_fy'] ?></td>
        <td >
            <?php
            if ($divisionTotals['totalPrimaryDis_pre_fy'] != 0) {
                echo round(($divisionTotals['totalPrimaryDis_cur_fy'] / $divisionTotals['totalPrimaryDis_pre_fy'] - 1) * 100, 0) . '%';
            } else {
                echo '0';
            }
            ?>
        </td>
        <td <?= getBackgroundColor($divisionTotals['totalPrimaryDis_cur_fy'], $divisionTotals['totaltarget_cur_fy']) ?>>
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

        </table>
    </div>
</div>

@endsection
@push('scripts')

    <script>
    // on chnange zone based region
      $(document).on('change', '.zone', function() {
        var prdgroup = $('.zone').select2('val');
        if (prdgroup != '') {
            $(".region").jCombo("{{ URL::to('jcombosecondsales?table=sd_prmyscdyupload_t:region:region') }}&parent=zone='" + prdgroup + "'&order_by=region asc", {
                selected_value: ""
            });
        }
    });
    
    // state
          $(document).on('change', '.region', function() {
        var prdgroup = $('.region').select2('val');
        if (prdgroup != '') {
            $(".state").jCombo("{{ URL::to('jcombosecondsales?table=sd_prmyscdyupload_t:state:state') }}&parent=region='" + prdgroup + "'&order_by=state asc", {
                selected_value: ""
            });
        }
    });
    
      // refresh region
        $(document).on('click','.re_region',function(){
  
        $(".region").jCombo("{{ URL::to('jcombosecondsales?table=sd_prmyscdyupload_t:region:region') }}&order_by=region asc",
        {selected_value:""});
        });
        
              // refresh state
        $(document).on('click','.re_state',function(){
  
        $(".state").jCombo("{{ URL::to('jcombosecondsales?table=sd_prmyscdyupload_t:state:state') }}&order_by=state asc",
        {selected_value:""});
        });


        $(document).ready(function() {
            
            var startDate = "{{ request('date_select') }}";
            $('#date_select').val(startDate);
            
        
          });
  
        $(document).ready(function() {
        $('#Table1').DataTable({
            dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
            order: [],
            buttons: [
                {
                    extend: 'excelHtml5',
                    filename: 'HQ Product Wise Target And Achivement',
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
                    filename: 'HQ Product Wise Target And Achivement',
                    exportOptions: {
                        format: {
                            header: function(data, columnIdx) {
                                // Concatenate headers with line breaks
                                var header1 = $('#Table1 thead tr:eq(0) th').eq(columnIdx).text();
                              //  var header2 = $('#Table1 thead tr:eq(1) th').eq(columnIdx).text();
                                var header3 = $('#Table1 thead tr:eq(2) th').eq(columnIdx).text();

                                return header1 + '\n' + header3;
                            }
                        }
                    }
                }
            ]
        });
            $('#Table2').DataTable({
            dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
            order: [],
            buttons: [
                {
                    extend: 'excelHtml5',
                    filename: 'Product Wise Target And Achivement',
                    exportOptions: {
                        format: {
                            header: function(data, columnIdx) {
                                var header1 = $('#Table2 thead tr:eq(0) th').eq(columnIdx).text();
                                var header3 = $('#Table2 thead tr:eq(2) th').eq(columnIdx).text();

                                return header1 + '\n' +  header3;
                            }
                        }
                    }
                },
                {
                    extend: 'pdfHtml5',
                    filename: 'Product Wise Target And Achivement',
                    exportOptions: {
                        format: {
                            header: function(data, columnIdx) {
                                // Concatenate headers with line breaks
                                var header1 = $('#Table2 thead tr:eq(0) th').eq(columnIdx).text();
                              //  var header2 = $('#Table2 thead tr:eq(1) th').eq(columnIdx).text();
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
        var Area = document.getElementById('area').value;
        
        if ( region === '' && startDate === '' && Division === '' && Area ==='') {
            alert('Please select HQ, Area, or Month Before Searching.');
            event.preventDefault(); 
        }
    });
    </script>
<!-- end  -->
 @endpush