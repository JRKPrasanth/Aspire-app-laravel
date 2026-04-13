@extends('layouts.header')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<link rel="stylesheet" type="text/css" href="{{asset('css/dashboard.css')}}">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
<script src='https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js'></script>


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
        background-color: #74af00;
        color: #fff;
        border-radius: 5px;
        text-align: center;
        text-decoration: none;
        transition: background-color 0.3s ease;
        text-transform: capitalize;
        width: 190px;
    }

    .list a {
        color: #fff;
        text-decoration: none;
    }

    .list:hover {
        background-color: #3c763d;
    }

    .align {
        text-align: center;
        background: #50a152;
        color: #fff;
    }

    .align1 {
        text-align: center;
        background: #481a73;
        color: #fff;
        border: 1px solid black !important;
    }

    @media only screen and (max-width: 600px) {
        .ul {
            display: block;
        }

        .sticky-col {
            position: sticky;
            left: 0;
            z-index: 1;
            background-color: #b3d1ff;
        }
    }

    #productChartContainer {
        display: none;
        /* Hide the chart initially */
    }

    .nodata {
        font-weight: 800;
        text-align: center;
    }

    .dash_charrt {
        border-radius: 10px;
        border-left: 3px solid #4e73df !important;
        border-right: 1px solid #ccc !important;
        border-bottom: 1px solid #ccc !important;
        border-top: 1px solid #ccc !important;
        margin: 30px 0;
        box-shadow: 0 .15rem 1.75rem 0 rgba(58, 59, 69, .15) !important;
    }

    .select {
        padding: 5px;
        background: #c0cbdd;
        text-align: center !important;
        /* line-height: 76px; */
        font-size: 12px;
    }
</style>

<h3 class="heads">purchase mis</h3>
<ul class="ul">
    <a href="purchasedashboard"><li class="list">Supplier Based</li></a>
    <a href="purchasedashboardproduct"><li class="list">Product Based</li></a>
    <a href="purchasedashboardqty&val"><li  class="list">Top Rating Qty & Value</li></a>
    <a href="purchasedashboardpricetrend"><li class="list">Product Price Trend</li></a>
    <a href="purchasedashboardpricediff"><li class="list">Product Price Difference</li></a>
   <a href="purchasedashboardpackdelay"><li style="background:#3c763d;"  class="list">Packing Material Delay</li></a>
</ul>
    <ul class="ul">
        <a href="purchaseproductmostspend"><li class="list">Most Spend Value Products</li></a>
        <a href="rejectedpuritems"><li class="list">Rejected Products List</li></a>
          <a href="consumptionquantityreport "><li class="list">Consumption Quantity Report </li></a>
      <!--  <a href="purchasedashboardqty&val"><li class="list">Top Rating Qty & Value</li></a>
        <a href="purchasedashboardpricetrend"><li  class="list">Product Price Trend</li></a>
        <a href="purchaseproductexceptionrpt"><li style="background:#3c763d;" class="list">Price Trend Exception</li></a> 
        <a href="purchasedashboardpackdelay"><li class="list">Packing Material Delay</li></a>  -->
    </ul>
<h3 class="heads" style="background:#3c763d !important;">Packing Material Delay</h3>

<!-- drop down -->
    <div class="col-md-12">
        <form action="{{ url('purchasedashboardpackdelay') }}" method="get" id="searchForm">
    <div class="col-md-4">
        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4">Product Name</label>
            <div class="col-md-8">
                <select style="width:100%" name='product_name' id='product_name' class='form-control product_name select2' required>
                    {!! $pro_name !!}
                </select>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4">From Date</label>
            <div class="col-md-8">
                <div class="input-group form_date" data-date="" data-link-format="yyyy-mm-dd">
                    <input class="form-control start_date" id="start_date" name="start_date" required type="text" value="" style="border-radius: 5px;" autocomplete="off">
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-4">To Date</label>
            <div class="col-md-8">
                <div class="input-group form_date" data-date="" data-link-format="yyyy-mm-dd">
                    <input class="form-control end_date" id="end_date" name="end_date" required type="text" value="" style="border-radius: 5px;" autocomplete="off">
                </div>
            </div>
        </div>
    </div>

        <div class="form-group row">
            <label for="inputIsValid" class="form-control-label col-md-offset-3 col-md-2"><span style="color:red;"></span></label>
            <div class="col-md-12" style="text-align: justify;">
                <button type="submit" class="btn search search" id="searchButton" style="left:46%;margin-top: 0px;">Search</button>
            </div>
        </div>
    </form>

</div>

<!-- end -->

<!-- product based -->
<div class="col-md-12">
    <table id="Table1" class="display" style="width:100%">
        <thead>
            <tr>
                <th colspan="12" class="align" style="background:#47647c;">PACKING MATERIAL DELAY</th>
            </tr>

            <tr style="background:#c32323 !important;">

                <th class="align" style="border-right: 1px solid #000;">MONTH</th>
                <th class="align" style="border-right: 1px solid #000;">TOTAL NO OF PM JOB CARD</th>
                <th class="align" style="border-right: 1px solid #000;">PM DELAY</th>
                 <th class="align" style="border-right: 1px solid #000;">REASON FOR DELAY</th>
                  <th class="align" style="border-right: 1px solid #000;">REQ DATE</th>
                    <th class="align" style="border-right: 1px solid #000;">RECEIVE DATE</th>
                   <th class="align" style="border-right: 1px solid #000;">NO OF DAYS DELAY</th>
                      <th class="align" style="border-right: 1px solid #000;">ON TIME MATERIAL</th>
                         <th class="align" style="border-right: 1px solid #000;">ON TIME MATERIAL IN %</th>

            </tr>
        </thead>
        <tbody>
             <?php foreach ($pro_delay as $value) { ?>
             <tr>
                        <td style="text-align:center;border: 1px solid #000;">{{$value->month_y}}</td>
                        <td style="border: 1px solid #000;text-align: center;">{{$value->concatenated_product}}</td>
                        <td style="border: 1px solid #000;text-align: center;">{{$value->concatenated_product}}</td>
                        <td style="text-align:center;border: 1px solid #000;">{{$value->concatenated_product}}</td>
                        <td style="border: 1px solid #000;text-align: center;">{{$value->req_date}}</td>
                        <td style="border: 1px solid #000;text-align: center;">{{$value->rec_date}}</td>
                        <td style="text-align:center;border: 1px solid #000;">{{$value->delay_days}}</td>
                        <td style="border: 1px solid #000;text-align: center;">{{$value->month_y}}</td>
                        <td style="border: 1px solid #000;text-align: center;">{{$value->month_y}}</td>

             </tr>
                <?php } ?>
        </tbody>
       
    </table>
</div>

<!-- Scripts-->

<script>

    $(document).on('change', '.product_group', function() {
        var prdgroup = $('.product_group').select2('val');
        if (prdgroup != '') {
            $(".product_name").jCombo("{{ URL::to('jcomboform?table=m_products_t:product_id:concatenated_product') }}&parent=product_group_id="+prdgroup+"&order_by=concatenated_product asc", {
                selected_value: ""
            });
        }
        console.log(prdgroup);
        
    });
    
    $(document).ready(function () {
        var fyDate = "{{ \Session::get('griddate') }}";
        var dateToday = new Date();

        $(".start_date").datepicker({
            changeMonth: true,
            dateFormat: "yy-mm-dd",
            changeYear: true,
            minDate: new Date(fyDate),
            maxDate: dateToday,
            onClose: function () {
                $(this).parsley().validate();
                $(".end_date").datepicker("option", "minDate", $(this).datepicker("getDate"));
            }
        }).attr('readonly', 'readonly');

        $(".end_date").datepicker({
            changeMonth: true,
            dateFormat: "yy-mm-dd",
            changeYear: true,
            minDate: dateToday,
            maxDate: dateToday,
            onClose: function () {
                $(this).parsley().validate();
            }
        }).attr('readonly', 'readonly');
    });

    $(document).ready(function () {
        $('#Table1').DataTable({
            dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
            order: [],
            pageLength: 1000,
            scrollY: '300px',
            scrollX: true,
            scrollCollapse: true,
            buttons: [
                {
                    extend: 'excelHtml5',
                    filename: 'Top_rating_qty&value',
                },
                {
                    extend: 'pdfHtml5',
                    filename: 'Top_rating_qty&value',
                }
            ]
        });
    });

    $(document).ready(function () {
        var productName = "{{ request('product_name') }}";
        var productGroup = "{{ request('product_group') }}";
        var startDate = "{{ request('start_date') }}";
        var endDate = "{{ request('end_date') }}";

        $('.product_name').select2();
        $('#product_name').val(productName).trigger('change');
        $('.product_group').select2();
        $('#product_group').val(productGroup).trigger('change');

        $('#start_date').val(startDate);
        $('#end_date').val(endDate);

    });

</script>



<!-- end -->

<!-- Include jQuery -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>
@endsection