@extends('layouts.header')
@section('content')
<h3 class="text-danger">Person and Product wise sale unit</h3>

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
      <form action="{{ url('misreportprohqsale') }}" method="get" id="searchForm">
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

            <!-- Month -->
            <div class="col-md-3">
                <label for="date_select" class="col-form-label">Month</label>
                <input type="month" name="date_select" id="date_select" class="form-control" autocomplete="off" style="border-radius: 5px;">
            </div>

            <!-- Submit Button -->
            <div class="col-12 text-center mt-3">
                <button type="submit" class="btn btn-primary px-5">Search</button>
            </div>

        </div>
    </form>
</div>

<!--tables-->
<div class="card shadow-lg rounded-4 border-0 p-4">
    <div class="table-responsive" style="overflow-x: auto;">
        <table id="Table1" class="table table-bordered table-striped table-hover w-100">
        <thead>
            <tr>
            <?php if (request('pro_name') != ''): ?>
            <th class="align text-white bg-danger text-center">{{ request('pro_name') }}</th>
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
             <th colspan="1" class="align text-white bg-danger text-center" >Last 6 Months</th>
                <th colspan="9" class="align text-white bg-danger text-center" >Person and Product wise Sale Unit UPTO - {{ $mon_yr }}</th>
                
            </tr>

               <tr>
                <th class="align text-white bg-secondary text-center">Product Name</th> 
                <th class="align text-white bg-secondary text-center">HQ Name</th>  
                <?php $displayedMonths = []; ?>
                <?php foreach ($distributor_cb as $value) {
                    $monthYear = $value->month_y;
                    if (!in_array($monthYear, $displayedMonths)) {
                        $displayedMonths[] = $monthYear; ?>
                        <th class="align text-white bg-secondary text-center"><?php echo $monthYear; ?></th>
                <?php }
                } ?>
                 <th class="align text-white bg-secondary text-center">Grand Total</th>
            </tr>
        </thead>

        <?php
    $monthlySales = []; // Array to store sales data for each month
    
    foreach ($distributor_cb as $value) {
        // Store sales data for each month
        $monthlySales[$value->name][$value->hq_name][$value->month_y]= $value->sale;
    }
    ?>

<tbody>
<?php
$grandTotal = []; // Initialize an array to store the grand total for each column

foreach ($displayedMonths as $month) {
    $grandTotal[$month] = 0; // Initialize grand total for each month to 0
}

foreach ($monthlySales as $name => $hq_names) {
    foreach ($hq_names as $hq_name => $monthData) {
    echo '<tr>'; // Each iteration starts a new row

    // Output the product name in the first column
    echo '<td class="sticky-col" >' . $name . '</td>';
    echo '<td class="sticky-col" >' . $hq_name . '</td>';
    $rowTotal = 0; // Initialize row total

    foreach ($displayedMonths as $month) {
        // Check if data exists for the current month
        if (isset($monthData[$month])) {
            $saleValue = $monthData[$month];
        } else {
            $saleValue = 0; // If no data exists, set value to 0
        }

        // Output sales value for each month
        echo '<td class="rupee-value" data-original="' . $saleValue . '" >' . $saleValue . '</td>';

        // Update the row total
        $rowTotal += $saleValue;
        
        // Update the grand total for the current month
        $grandTotal[$month] += $saleValue;
    }
        
            // Display the row total in the last column
            echo '<td class="rupee-value" data-original="' . $rowTotal . '" >' . $rowTotal . '</td>';
        
            echo '</tr>'; // Close the row
        }
        }
        ?>
        </tbody>
        <tfoot>
        <?php
        // Output the grand total row
        echo '<tr class="sticky-foot fw-bold">';
        echo '<td class="sticky-col" >Grand Total</td>';
       echo '<td class="sticky-col" ></td>';
        $grandTotalOverall = 0; // Initialize grand total overall
        
        foreach ($displayedMonths as $month) {
            // Output the grand total for each month
            echo '<td class="rupee-value" data-original="' . $grandTotal[$month] . '" >' . $grandTotal[$month] . '</td>';
        
            // Update the grand total overall
            $grandTotalOverall += $grandTotal[$month];
        }
        
        // Output the overall grand total
        echo '<td class="rupee-value" data-original="' . $grandTotalOverall . '" >' . $grandTotalOverall . '</td>';
        
        echo '</tr>'; // Close the grand total row
        ?>
        </tfoot>
        </table>
    </div>
</div>



<!--tables-->
<div class="card shadow-lg rounded-4 border-0 p-4">
    <div class="table-responsive" style="overflow-x: auto;">
        <table id="Table2" class="table table-bordered table-striped table-hover w-100">
        <thead>
            <tr>
            <?php if (request('pro_name') != ''): ?>
            <th class="align text-white bg-danger text-center">{{ request('pro_name') }}</th>
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
             <th colspan="1" class="align text-white bg-danger text-center" >Last 6 Months</th>
                <th colspan="8" class="align text-white bg-danger text-center" >Person and Product wise Sale Unit UPTO - {{ $mon_yr }}</th>
                
            </tr>

               <tr>
                <th class="align text-white bg-secondary text-center">Product Name</th> 
                <?php $displayedMonths = []; ?>
                <?php foreach ($sale_unit as $value) {
                    $monthYear = $value->month_y;
                    if (!in_array($monthYear, $displayedMonths)) {
                        $displayedMonths[] = $monthYear; ?>
                        <th class="align text-white bg-secondary text-center"><?php echo $monthYear; ?></th>
                <?php }
                } ?>
                 <th class="align text-white bg-secondary text-center">Grand Total</th>
            </tr>
        </thead>

        <?php
    $monthlySales = []; // Array to store sales data for each month
    
    foreach ($sale_unit as $value) {
        // Store sales data for each month
        $monthlySales[$value->name][$value->month_y]= $value->sale;
    }
    ?>

<tbody>
<?php
$grandTotal = []; // Initialize an array to store the grand total for each column

foreach ($displayedMonths as $month) {
    $grandTotal[$month] = 0; // Initialize grand total for each month to 0
}

foreach ($monthlySales as $name => $monthData) {
    echo '<tr>'; // Each iteration starts a new row

    // Output the product name in the first column
    echo '<td class="sticky-col" >' . $name . '</td>';
    $rowTotal = 0; // Initialize row total

    foreach ($displayedMonths as $month) {
        // Check if data exists for the current month
        if (isset($monthData[$month])) {
            $saleValue = $monthData[$month];
        } else {
            $saleValue = 0; // If no data exists, set value to 0
        }

        // Output sales value for each month
        echo '<td class="rupee-value" data-original="' . $saleValue . '" >' . $saleValue . '</td>';

        // Update the row total
        $rowTotal += $saleValue;
        
        // Update the grand total for the current month
        $grandTotal[$month] += $saleValue;
    }
        
            // Display the row total in the last column
            echo '<td class="rupee-value" data-original="' . $rowTotal . '" >' . $rowTotal . '</td>';
        
            echo '</tr>'; // Close the row
        }
        
        ?>
        </tbody>
        <tfoot>
        <?php
        // Output the grand total row
        echo '<tr class="sticky-foot fw-bold">';
        echo '<td class="sticky-col" >Grand Total</td>';
        $grandTotalOverall = 0; // Initialize grand total overall
        
        foreach ($displayedMonths as $month) {
            // Output the grand total for each month
            echo '<td class="rupee-value" data-original="' . $grandTotal[$month] . '" >' . $grandTotal[$month] . '</td>';
        
            // Update the grand total overall
            $grandTotalOverall += $grandTotal[$month];
        }
        
        // Output the overall grand total
        echo '<td class="rupee-value" data-original="' . $grandTotalOverall . '" >' . $grandTotalOverall . '</td>';
        
        echo '</tr>'; // Close the grand total row
        ?>
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
        console.log(prdgroup);
    });
    
      // refresh region
        $(document).on('click','.re_region',function()
        {
  
        $(".region").jCombo("{{ URL::to('jcombosecondsales?table=sd_prmyscdyupload_t:region:region') }}&order_by=region asc",
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
                    filename: 'Hq Based Person and Product wise Sale Unit',
                    exportOptions: {
                        format: {
                            header: function(data, columnIdx) {
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
                    filename: 'Hq Based Person and Product wise Sale Unit',
                    exportOptions: {
                        format: {
                            header: function(data, columnIdx) {
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
          $('#Table2').DataTable({
            dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
            order: [],
            buttons: [
                {
                    extend: 'excelHtml5',
                    filename: 'Person and Product wise Sale Unit',
                    exportOptions: {
                        format: {
                            header: function(data, columnIdx) {
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
                    filename: 'Person and Product wise Sale Unit',
                    exportOptions: {
                        format: {
                            header: function(data, columnIdx) {
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
    document.addEventListener('DOMContentLoaded', function() {
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
         document.getElementById('searchForm').addEventListener('submit', function(event) {
        var zone = document.getElementById('pro_name').value;
        var region = document.getElementById('manager').value;
        var startDate = document.getElementById('date_select').value;
        var Area = document.getElementById('area').value;
        
        if (zone === '' && region === '' && startDate === '' && Area ==='') {
            alert('Please select HQ, Area, or Month Before Searching.');
            event.preventDefault(); 
        }
    });
</script>
<!-- end  -->
 @endpush