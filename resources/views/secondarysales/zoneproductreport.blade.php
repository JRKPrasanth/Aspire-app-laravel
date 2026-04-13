@extends('layouts.header')

@section('content')

<style>
    .bread {
        display: none;
    }

    body {
        background: #fff;
    }

        .ul {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        .list {
            margin: 0;
            padding: 10px;
            background-color: #3996d5;
            color: #fff;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease;
            text-transform: capitalize;
             width:220px;
        }

        .list a {
            color: #fff;
            text-decoration: none;
        }

        .list:hover {
            background-color: #1c2c78;
        }
    .align {
        text-align: center;
        background: #9799e5;
    }

    .align1 {
        text-align: center;
        background: #481a73;
        color: #fff;
        border: 1px solid black !important;
    }

    .sticky-row {
        position: sticky;
        top: 0;
        z-index: 1;
    }

    .sticky-row2 {
        position: sticky;
        top: 41px;
        z-index: 2;
    }
    td, th {

    padding: 8px 10px;
}
 @media screen and (max-width: 768px) {
    .ul {
        flex-direction: column; 
        gap: 10px;
    }

    .list {
        width: 100%; 
    }
    .sticky-col {
        position: sticky;
        left: 0;
        z-index: 1;
        background-color: #b3d1ff;
    }
    .stock-tbl{
        margin-top:25px;
    }
     #Table1 th,
    #Table1 td {
        display: block;
        width: 100%;
        box-sizing: border-box;
    }

    #Table1 th.sticky-col,
    #Table1 td.sticky-col {
        border-right: none;
    }

    #Table1 thead {
     /* Hide the header on small screens */
    }

    #Table1 tbody tr {
        margin-bottom: 20px; /* Add some spacing between rows on small screens */
    }
 } 
     .sticky-total {
        position: sticky;
        bottom: 0;
        z-index: 1;
        background-color: #ffbef7;
    }
</style>
        <h3 style="text-align:end !important;font-size:15px;text-transform: capitalize;font-weight: bold;">Last Updated At:  <span class="list"> {{ $last_update[0]->created_at }}</span></h3>
<h3 class="heads">All India One Glance Sales Trend <span style="float: right !important;">Data Upto: {{ $last_data }}</span> </h3>
<ul class="ul">
   <a href="secondarysalesreport"> <li class="list">Month Wise</li></a>
       <a href="zonesalesreport"> <li class="list">Zone and Region Wise</li></a>
          <a href="statesalesreport">  <li  class="list">State Wise</li> </a>
               <a href="lastsalereport"> <li class="list">Closing Stock level</li></a>
               <a href="productsalesreport"> <li class="list">Product Wise Sale </li></a>
                     <!--  <a href="zoneproductreport"><li class="list">Trans Product Wise Sale </li></a> -->
</ul>

<h3 class="heads" style="background:#3c763d !important;">Trans Product Wise Sale Unit</h3>

<div class="col-lg-12">
            <form action="{{ url('zoneproductreport') }}" method="get" id="searchForm">
                                 <div class="col-md-3">
                <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-4"  style="text-align: end;">Zone</label>
                    <div class="col-md-8">
                        <select style="width:100%;text-align:center;" name='zone' id='zone' class='form-control zone select2'>
                                <option value="">-- please select --</option>
                            @foreach($zones as $zone)
                                <option value="{{ $zone->zone }}">{{ $zone->zone }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-4"  style="text-align: end;">Region</label>
                    <div class="col-md-8">
                        <select style="width:100%;text-align:center;" name='region' id='region' class='form-control region select2'>
                                <option value="">-- please select --</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->region }}">{{ $region->region }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <?php if ($groupname != '14') { ?>
                    <div class="col-md-1 showinline" style="margin-top: 8px;">
                    <span class="showspan"> <i class="fa fa-refresh re_region"></i></span>
                    </div>
                    <?php } ?>
            <div class="col-md-4">
                <div class="form-group row">
                    <label for="inputIsValid" class="form-control-label col-md-4">Division</label>
                    <div class="col-md-8">
                        <select style="width:100%;text-align:center;" name='division' id='division' class='form-control division select2'>
                                <option value="">-- please select --</option>
                            @foreach($divisions as $division)
                                <option value="{{ $division->division }}">{{ $division->division }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
             </div>
            <div class="col-md-3">
            <div class="form-group row">
                <label for="inputIsValid" class="form-control-label col-md-4" style="text-align:end;" >Month</label>
                <div class="col-md-8">
                    <div class="input-group form_date " data-date="" data-link-format="yyyy-mm-dd">
                        <input class="form-control date_select  " id="date_select" name="date_select" type="month" value="" style="border-radius: 5px;" autocomplete="off">
                    </div>
                </div>
            </div>
    </div>

    <div class="col-md-4" style="margin-top:-9px;text-align:end;">
        <a>
            <button type="submit" class="btn search search" id="searchButton">search</button>
        </a>

    </div>
    </form>
</div>
 <!---  product Wise Sale value -->
<div class="col-md-12">
    <table id="Table1" class="display" style="width:100%">
        <thead>
            <tr>
                <?php if (request('zone') != ''): ?>
                    <th class="align" style="background:#9b900d">{{ request('zone') }}</th>
                    <?php endif; ?>
                <?php if (request('region') != ''): ?>
                    <th class="align" style="background:#9b900d">{{ request('region') }}</th>
                    <?php endif; ?>
                <?php if (request('division') != ''): ?>
                    <th class="align" style="background:#9b900d">{{ request('division') }}</th>
                <?php endif; ?>
                <th colspan="9" class="align" style="background:#fdf15c">PRODUCT PACK WISE SALE UNITS UPTO {{ $last_month }}</th>

            </tr>
            <tr>
                <th colspan="3" style="background:#f1ea00;border-right: 1px solid #000;text-align: center">Sum Of Slaes Unit</th>
                 <th colspan="2" style="background:#f1ea00;border-right: 1px solid #000;text-align: center">Primary</th>
                  <th colspan="2" style="background:#f1ea00;border-right: 1px solid #000;text-align: center">Distributor Sale</th>
                   <th colspan="2" style="background:#f1ea00;border-right: 1px solid #000;text-align: center">Stockist Sale</th>

            </tr>
            <tr> 

                 <th colspan="3" class="align sticky-col" style="border-right: 1px solid #000;">Product Pack Name</th>
                 <th class="align" style="border-right: 1px solid #000;">{{ $pre_fy_year }}</th>
                 <th class="align" style="border-right: 1px solid #000;">{{ $cur_fy_year}}</th>
                 <th class="align" style="border-right: 1px solid #000;">{{ $pre_fy_year }}</th>
                 <th class="align" style="border-right: 1px solid #000;">{{ $cur_fy_year}}</th>
                <th class="align" style="border-right: 1px solid #000;">{{ $pre_fy_year }}</th>
                <th class="align" style="border-right: 1px solid #000;">{{ $cur_fy_year}}</th>
               
            </tr>
        </thead>
<tbody>
<?php
$productData = [];
$divisionTotals = [
    'totalPrimaryDis_pre_fy' => 0,
    'totalPrimaryDis_cur_fy' => 0,
    'totalSalesDist_pre_fy' => 0,
    'totalSalesDist_cur_fy' => 0,
    'totalSalesStock_pre_fy' => 0,
    'totalSalesStock_cur_fy' => 0,
];

foreach ($all_ind_sal as $value) {
    $productName = $value->product_pack_name;

    if (!isset($productData[$productName])) {
        $productData[$productName] = [
            'primary_dis_pre_fy' => 0,
            'sales_dist_pre_fy' => 0,
            'sales_stock_pre_fy' => 0,
            'primary_dis_cur_fy' => 0,
            'sales_dist_cur_fy' => 0,
            'sales_stock_cur_fy' => 0,
        ];
    }

    if ($value->f_year == $pre_fy_year) {
        $productData[$productName]['primary_dis_pre_fy'] += $value->primary_dis;
        $productData[$productName]['sales_dist_pre_fy'] += $value->sales_dist;
        $productData[$productName]['sales_stock_pre_fy'] += $value->sales_stock;
    } elseif ($value->f_year == $cur_fy_year) {

        $productData[$productName]['primary_dis_cur_fy'] += $value->primary_dis;
        $productData[$productName]['sales_dist_cur_fy'] += $value->sales_dist;
        $productData[$productName]['sales_stock_cur_fy'] += $value->sales_stock;
    }

    // Update division totals
    $divisionTotals['totalPrimaryDis_pre_fy'] += $value->f_year == $pre_fy_year ? $value->primary_dis : 0;
    $divisionTotals['totalPrimaryDis_cur_fy'] += $value->f_year == $cur_fy_year ? $value->primary_dis : 0;
    $divisionTotals['totalSalesDist_pre_fy'] += $value->f_year == $pre_fy_year ? $value->sales_dist : 0;
    $divisionTotals['totalSalesDist_cur_fy'] += $value->f_year == $cur_fy_year ? $value->sales_dist : 0;
    $divisionTotals['totalSalesStock_pre_fy'] += $value->f_year == $pre_fy_year ? $value->sales_stock : 0;
    $divisionTotals['totalSalesStock_cur_fy'] += $value->f_year == $cur_fy_year ? $value->sales_stock : 0;
}

foreach ($productData as $productName => $data) {
?>
    <tr>
        <td colspan="3"  class="sticky-col" style="text-align:center;border-right: 1px solid #000;"><?php echo $productName; ?></td>
        <td style="border-right: 1px solid #000;text-align: center;"><?php echo $data['primary_dis_pre_fy']; ?></td>
        <td style="border-right: 1px solid #000;text-align: center;"><?php echo $data['primary_dis_cur_fy']; ?></td>
        <td style="border-right: 1px solid #000;text-align: center;"><?php echo $data['sales_dist_pre_fy']; ?></td>
        <td style="border-right: 1px solid #000;text-align: center;"><?php echo $data['sales_dist_cur_fy']; ?></td>
        <td style="border-right: 1px solid #000;text-align: center;"><?php echo $data['sales_stock_pre_fy']; ?></td>
        <td style="border-right: 1px solid #000;text-align: center;"><?php echo $data['sales_stock_cur_fy']; ?></td>
    </tr>
<?php } ?>
    </tbody>
        <tfoot>

<!-- Add the total row for all products -->
<tr class="sticky-total"  style="background:#ffbef7;font-weight: 600;">
    <td colspan="3"  style="text-align:center;border-right: 1px solid #000;">Grand Total</td>
    <td style="border-right: 1px solid #000;text-align: center;"><?php echo $divisionTotals['totalPrimaryDis_pre_fy']; ?></td>
    <td style="border-right: 1px solid #000;text-align: center;"><?php echo $divisionTotals['totalPrimaryDis_cur_fy']; ?></td>
    <td style="border-right: 1px solid #000;text-align: center;"><?php echo $divisionTotals['totalSalesDist_pre_fy']; ?></td>
    <td style="border-right: 1px solid #000;text-align: center;"><?php echo $divisionTotals['totalSalesDist_cur_fy']; ?></td>
    <td style="border-right: 1px solid #000;text-align: center;"><?php echo $divisionTotals['totalSalesStock_pre_fy']; ?></td>
    <td style="border-right: 1px solid #000;text-align: center;"><?php echo $divisionTotals['totalSalesStock_cur_fy']; ?></td>
</tr>
    </tfoot>
    </table>
</div>


<div class="col-md-12" style="margin-top:50px;">
    <div class="col-md-7" style="height:345px;overflow-y:auto;">
  <table id="Table2" style="width:100%">
            <thead>
                <tr>
                <?php if (request('zone') != ''): ?>
                    <th class="align" style="background:#9b900d">{{ request('zone') }}</th>
                    <?php endif; ?>
                <?php if (request('region') != ''): ?>
                    <th class="align" style="background:#9b900d">{{ request('region') }}</th>
                    <?php endif; ?>
                <?php if (request('division') != ''): ?>
                    <th class="align" style="background:#9b900d">{{ request('division') }}</th>
                <?php endif; ?>
                    <th colspan="3" class="align" style="background:#97a100;color:#fff;border:1px solid black;">SALES GROWTH % OVER UPTO {{ $last_month }}</th>
                           <th colspan="3" style="background:#f1ea00;border: 1px solid #000;text-align: center;color:#000;">{{ $pre_fy_year}}</th>
                </tr>
                <tr class="sticky-row">
                    <th colspan="3" class="align1">Product</th>
                    <th colspan="1" class="align1">Primary</th>
                    <th colspan="1" class="align1">Distributor Sale</th>
                    <th colspan="1" class="align1">Stockist Sale</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sale_value as $value) { ?>
                    <tr>
                        <td colspan="3" style="text-align:left;border:1px solid black;">{{ $value['product'] }}</td>
                        <td colspan="1" style="text-align:center;border:1px solid black;">{{ $value['primary_dis_ratio'] }}</td>
                        <td colspan="1" style="text-align:center;border:1px solid black;">{{ $value['sales_dist_ratio'] }}</td>
                        <td colspan="1" style="text-align:center;border:1px solid black;">{{ $value['purchase_ratio'] }}</td>
                    </tr>
                <?php } ?>
                    </tbody>
                        <tfoot>
                  <?php foreach ($grand_value as $value) { ?>
                    <tr style="background:#ffbef7;font-weight: 600;">
                        <td colspan="3" style="text-align:center;border: 1px solid #000;text-align: center;">Total</td>
                        <td colspan="1" style="text-align:center;border:1px solid black;">{{ $value['primary_dis_ratio'] }}</td>
                        <td colspan="1" style="text-align:center;border:1px solid black;">{{ $value['sales_dist_ratio'] }}</td>
                        <td colspan="1" style="text-align:center;border:1px solid black;">{{ $value['purchase_ratio'] }}</td>
                    </tr>
                      <?php } ?>
             </tfoot>
        </table>
    </div>
    
     <div class="col-md-5"  style="height:333px;overflow-y:auto;">
        <table id="Table3"  style="width:100%" class="stock-tbl">
            <thead>
                <tr>
                <?php if (request('zone') != ''): ?>
                    <th class="align" style="background:#9b900d">{{ request('zone') }}</th>
                    <?php endif; ?>
                <?php if (request('region') != ''): ?>
                    <th class="align" style="background:#9b900d">{{ request('region') }}</th>
                    <?php endif; ?>
                <?php if (request('division') != ''): ?>
                    <th class="align" style="background:#9b900d">{{ request('division') }}</th>
                <?php endif; ?>
                    <th  style="background:#f1ea00;border: 1px solid #000;text-align: center;color:#000;">{{ $cur_fy_year}}</th>
                    <th colspan="6" class="align" style="background:#97a100;color:#fff;border:1px solid black;">STOCK LIQUDATION_RATIO % UPTO {{ $last_month }}</th>
                </tr>
                <tr class="sticky-row">
                  
                    <th colspan="2" class="align1">Dist. Sale ÷ Primary</th>
                    <th colspan="2" class="align1">Stockiest Sale ÷ Primary</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sale_value as $value) { ?>
                    <tr>
                      
                        <td colspan="2" style="text-align:center;border:1px solid black;">{{ $value['slae_ratio'] }}</td>
                        <td colspan="2" style="text-align:center;border:1px solid black;">{{ $value['stock_ratio'] }}</td>
                    </tr>
                <?php } ?>
                 </tbody>
                 <tfoot>
                  <?php foreach ($grand_value as $value) { ?>
                        <tr style="background:#ffbef7;font-weight: 600;">
                            
                        <td colspan="2" style="text-align:center;border:1px solid black;">{{ $value['slae_ratio'] }}</td>
                        <td colspan="2" style="text-align:center;border:1px solid black;">{{ $value['stock_ratio'] }}</td>

                    </tr>
                      <?php } ?>
           </tfoot>
        </table>
    </div>
</div>

    
     <!-- END -->
    
    <!-- Include jQuery -->

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>

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
    </script>
    
    <script>
        
        $(document).ready(function() {
            
            var startDate = "{{ request('date_select') }}";
            $('#date_select').val(startDate);
            
        
          });
      
        $(document).ready(function() {
            
            $('#Table1').DataTable({
                dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
                order: [],
                pageLength: 1000,
                scrollX: true,
                scrollY: '300px', 
                scrollCollapse: true, 
                buttons: [
                    {
                        extend: 'excelHtml5',
                        filename: 'Product_packwise_sale_value',
                    },
                    {
                        extend: 'pdfHtml5',
                        filename: 'Product_packwise_sale_value',
                    }
                ]
            });
                $('#Table2').DataTable({
                dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
                order: [],
                pageLength: 1000,
                scrollY: '300px', 
                scrollX: true,
                scrollCollapse: true, 
                buttons: [
                    {
                        extend: 'excelHtml5',
                        filename: 'Product_packwise_sale_value',
                    },
                    {
                        extend: 'pdfHtml5',
                        filename: 'Product_packwise_sale_value',
                    }
                ]
            });
                $('#Table3').DataTable({
                dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
                order: [],
                pageLength: 1000,
                scrollY: '300px', 
                scrollX: true,
                scrollCollapse: true, 
                buttons: [
                    {
                        extend: 'excelHtml5',
                        filename: 'Product_packwise_sale_value',
                    },
                    {
                        extend: 'pdfHtml5',
                        filename: 'Product_packwise_sale_value',
                    }
                ]
            });
           
        });
                // search alert
         document.getElementById('searchForm').addEventListener('submit', function(event) {
        var zone = document.getElementById('zone').value;
        var region = document.getElementById('region').value;
        var startDate = document.getElementById('date_select').value;
        var Division = document.getElementById('division').value;
 
        if (zone === '' && region === '' && startDate === '' && Division === '') {
            alert('Please select Zone, Region, or Month Before Searching.');
            event.preventDefault(); 
        }
    });
    </script>
    <script>
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
    </script>
      
    @endsection