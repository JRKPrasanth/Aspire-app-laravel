@extends('layouts.header')
@section('content')
<h3 class="text-danger">Overall Product Search</h3>

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
    <form action="{{ url('misreportpayout') }}" method="get" id="searchForm">
        <div class="row g-3">

            <!-- HQ -->
            <div class="col-md-4">
                <label for="region" class="form-label">HQ</label>
                <select name="region" id="region" class="form-select region select2 text-center">
                    <option value="">-- please select --</option>
                    @foreach($regions as $region)
                        <option value="{{ $region->hq_name }}">{{ $region->hq_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Manager -->
            <div class="col-md-4">
                <label for="manager" class="form-label">Manager</label>
                <select name="manager" id="manager" class="form-select manager select2 text-center">
                    <option value="">-- please select --</option>
                    @foreach($managers as $manager)
                        <option value="{{ $manager->name }}">{{ $manager->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Month -->
            <div class="col-md-3">
                <label for="date_select" class="form-label">Month</label>
                <input type="month" name="date_select" id="date_select" class="form-control" autocomplete="off" required>
            </div>

            <!-- Submit Button -->
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100" id="searchButton">Search</button>
            </div>

        </div>
    </form>
</div>

<!-- end -->
 <!---  state Wise Sale value -->
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

            <?php if (request('manager') != ''): ?>
            <th class="align text-white bg-danger text-center">{{ request('manager') }}</th>
            <?php endif; ?>
                <th colspan="12" class="align text-white bg-danger text-center">Personwise Payment Outstanding UPTO - {{ $mon_yr }}</th>
                
            </tr>

                <tr>
                      <th class="align text-white bg-secondary text-center">HQ Name</th>
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
    <td >{{ $value->hq_name}}</td>
    <td >{{ $value->name}}</td>
    <td class="rupee-value" data-original="{{ $value->sale}}" >{{ $value->sale}}</td>
    </tr>
   <?php } ?>
</tbody>
<!-- Add the total row for all products -->
<tfoot>
    <tr class="sticky-foot fw-bold">
        <td >Grand Total</td>
        <td ></td>
        <td class="rupee-value" data-original="{{ $totalStock}}" >{{$totalStock}}</td>
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
           $('#Table2').DataTable({
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
                                var header2 = $('#Table2 thead tr:eq(1) th').eq(columnIdx).text();
                                var header3 = $('#Table2 thead tr:eq(2) th').eq(columnIdx).text();

                                return header1 + '\n' + (header2 ? header2 + '\n' : '') + header3;
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
                                var header2 = $('#Table2 thead tr:eq(1) th').eq(columnIdx).text();
                                var header3 = $('#Table2 thead tr:eq(2) th').eq(columnIdx).text();

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
        var zone = document.getElementById('zone').value;
        var region = document.getElementById('region').value;
        var startDate = document.getElementById('date_select').value;
        var Manager = document.getElementById('manager').value;
        
        if (zone === '' && region === '' && startDate === '' && Manager === '') {
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