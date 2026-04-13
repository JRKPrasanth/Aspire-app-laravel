@extends('layouts.header')
@section('content')
    <h3 class="text-danger">All India One Glance Sales Trend</h3>

    <div class="container mt-4">
        <!-- First row of buttons -->
        <div class="row g-3 mb-2">
            <div class="col-12 col-md-2">
                <a href="secondarysalesreport" class="btn btn-outline-primary w-100">Month Wise</a>
            </div>
            <div class="col-12 col-md-3">
                <a href="zonesalesreport" class="btn btn-outline-primary w-100">Zone and Region Wise</a>
            </div>
            <div class="col-12 col-md-2">
                <a href="statesalesreport" class="btn btn-primary w-100">State Wise</a>
            </div>
            <div class="col-12 col-md-3">
                <a href="lastsalereport" class="btn btn-outline-primary w-100">Closing Stock level</a>
            </div>
            <div class="col-12 col-md-2">
                <a href="productsalesreport" class="btn btn-outline-primary w-100">Product Wise</a>
            </div>
        </div>
    </div>

    <div class="card shadow-lg rounded-4 border-0 p-4 mb-4">
        <form action="{{ url('statesalesreport') }}" method="get" id="searchForm">
            <div class="row g-3">

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

                <!-- Month -->
                <div class="col-md-3">
                    <label for="date_select" class="form-label">Month</label>
                    <input type="month" name="date_select" id="date_select" class="form-control" autocomplete="off">
                </div>

                <!-- Submit Button -->
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100" id="searchButton"> <i class="bi bi-search"></i>
                        Search</button>
                </div>

            </div>
        </form>
    </div>

    <!-- Value Format Buttons -->
    <div class="text-center mb-4">
        <button class="btn btn-primary fw-bold me-2" id="btnThousand">Show in Thousands</button>
        <button class="btn btn-success fw-bold me-2" id="btnLakhs">Show in Lakhs</button>
        <button class="btn btn-danger fw-bold" id="btnReset">Reset</button>
    </div>
    <!-- end -->

    <!---  State Wise Sale value -->
    <div class="row">
        <div class="col-md-6">
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
                                <th colspan="5" class="align text-white bg-danger text-center">STATE WISE SALES upto
                                    {{ $mon_yr }}</th>

                            </tr>
                            <tr>
                                <th class="text-white bg-secondary text-center">{{ $cur_fy_year}}</th>
                                <th colspan="2" class="align text-white bg-secondary text-center">Distributor</th>
                                <th colspan="2" class="align text-white bg-secondary text-center">Stockist </th>

                            </tr>
                            <tr>
                                <th class="align sticky-col text-white bg-success text-center">State</th>
                                <th class="align text-white bg-success text-center"><span class="text-success"
                                        style="font-size:1px;">Distributor </span>Primary</th>
                                <th class="align text-white bg-success text-center"><span class="text-success"
                                        style="font-size:1px;">Distributor </span>Sales</th>
                                <th class="align text-white bg-success text-center"><span class="text-success"
                                        style="font-size:1px;">Stockist </span>Purchase</th>
                                <th class="align text-white bg-success text-center"><span class="text-success"
                                        style="font-size:1px;">Stockist </span>Sales</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
    $totalPrimaryDis = 0;
    $totalSalesDist = 0;
    $totalPurchase = 0;
    $totalSalesStock = 0;

    foreach ($all_ind_sal as $value) {
        $totalPrimaryDis += $value->primary_dis;
        $totalSalesDist += $value->sales_dist;
        $totalPurchase += $value->purchase;
        $totalSalesStock += $value->sales_stock;
                    ?>
                            <tr>
                                <td class="sticky-col">{{$value->state}}</td>
                                <td class="rupee-value" data-original="{{ $value->primary_dis }}">{{$value->primary_dis}}
                                </td>
                                <td class="rupee-value" data-original="{{ $value->sales_dist }}">{{$value->sales_dist}}</td>
                                <td class="rupee-value" data-original="{{ $value->purchase }}">{{$value->purchase}}</td>
                                <td class="rupee-value" data-original="{{ $value->sales_stock }}">{{$value->sales_stock}}
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr class="sticky-total fw-bold">
                                <td>Grand Total</td>
                                <td class="rupee-value" data-original="{{ $totalPrimaryDis }}">{{$totalPrimaryDis}}</td>
                                <td class="rupee-value" data-original="{{ $totalSalesDist }}">{{$totalSalesDist}}</td>
                                <td class="rupee-value" data-original="{{ $totalPurchase}}">{{$totalPurchase}}</td>
                                <td class="rupee-value" data-original="{{ $totalSalesStock}}">{{$totalSalesStock}}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-lg rounded-4 border-0 p-4">
                <div class="table-responsive" style="overflow-x: auto;">
                    <table id="Table2" class="table table-bordered table-striped table-hover w-100">
                        <thead>
                            <tr>
                                <?php if (request('zone') != ''): ?>
                                <th class="align text-white bg-danger text-center">{{ request('zone') }}</th>
                                <?php endif; ?>
                                <?php if (request('region') != ''): ?>
                                <th class="align text-white bg-danger text-center">{{ request('region') }}</th>
                                <?php endif; ?>
                                <th colspan="5" class="align text-white bg-danger text-center">STATE WISE SALES upto
                                    {{ $last_month}}'{{ $select_year }}</th>

                            </tr>
                            <tr>
                                <th class="text-white bg-secondary text-center">{{ $pre_fy_year }}</th>
                                <th colspan="2" class="align text-white bg-secondary text-center">Distributor</th>
                                <th colspan="2" class="align text-white bg-secondary text-center">Stockist </th>

                            </tr>
                            <tr>
                                <th class="align sticky-col text-white bg-success text-center">State</th>
                                <th class="align text-white bg-success text-center"><span class="text-success"
                                        style="font-size:1px;">Distributor </span>Primary</th>
                                <th class="align text-white bg-success text-center"><span class="text-success"
                                        style="font-size:1px;">Distributor </span>Sales</th>
                                <th class="align text-white bg-success text-center"><span class="text-success"
                                        style="font-size:1px;">Stockist </span>Purchase</th>
                                <th class="align text-white bg-success text-center"><span class="text-success"
                                        style="font-size:1px;">Stockist </span>Sales</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
    $totalPrimaryDis = 0;
    $totalSalesDist = 0;
    $totalPurchase = 0;
    $totalSalesStock = 0;

    foreach ($all_ind_sal_pre as $value) {
        $totalPrimaryDis += $value->primary_dis;
        $totalSalesDist += $value->sales_dist;
        $totalPurchase += $value->purchase;
        $totalSalesStock += $value->sales_stock;
                    ?>
                            <tr>
                                <td class="sticky-col">{{$value->state}}</td>
                                <td class="rupee-value" data-original="{{ $value->primary_dis }}">{{$value->primary_dis}}
                                </td>
                                <td class="rupee-value" data-original="{{ $value->sales_dist }}">{{$value->sales_dist}}</td>
                                <td class="rupee-value" data-original="{{ $value->purchase }}">{{$value->purchase}}</td>
                                <td class="rupee-value" data-original="{{ $value->sales_stock }}">{{$value->sales_stock}}
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr class="sticky-total">
                                <td>Grand Total</td>
                                <td class="rupee-value" data-original="{{ $totalPrimaryDis }}">{{$totalPrimaryDis}}</td>
                                <td class="rupee-value" data-original="{{ $totalSalesDist }}">{{$totalSalesDist}}</td>
                                <td class="rupee-value" data-original="{{ $totalPurchase}}">{{$totalPurchase}}</td>
                                <td class="rupee-value" data-original="{{ $totalSalesStock}}">{{$totalSalesStock}}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7">

            <div class="card shadow-lg rounded-4 border-0 p-4">
                <div class="table-responsive" style="overflow-x: auto;">
                    <table id="Table3" class="table table-bordered table-striped table-hover w-100">
                        <thead>
                            <tr>
                                <?php if (request('zone') != ''): ?>
                                <th class="align text-white bg-danger text-center">{{ request('zone') }}</th>
                                <?php endif; ?>
                                <?php if (request('region') != ''): ?>
                                <th class="align text-white bg-danger text-center">{{ request('region') }}</th>
                                <?php endif; ?>
                                <th colspan="5" class="align text-white bg-danger text-center">SALES GROWTH
                                    {{ $pre_fy_year }}</th>

                            </tr>
                            <tr>
                                <th colspan="1" class="text-white bg-secondary text-center">{{ $pre_fy_year }}</th>
                                <th colspan="2" class="align text-white bg-secondary text-center">Distributor</th>
                                <th colspan="2" class="align text-white bg-secondary text-center">Stockist </th>

                            </tr>
                            <tr>
                                <th colspan="1" class="align sticky-col text-white bg-success text-center">State</th>
                                <th class="align text-white bg-success text-center"><span class="text-success"
                                        style="font-size:1px;">Distributor </span>Primary</th>
                                <th class="align text-white bg-success text-center"><span class="text-success"
                                        style="font-size:1px;">Distributor </span>Sales</th>
                                <th class="align text-white bg-success text-center"><span class="text-success"
                                        style="font-size:1px;">Stockist </span>Purchase</th>
                                <th class="align text-white bg-success text-center"><span class="text-success"
                                        style="font-size:1px;">Stockist </span>Sales</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ratio_val as $value) { ?>
                            <tr>
                                <td class="sticky-col">{{$value['state']}}</td>
                                <td>{{$value['primary_dis_ratio']}}</td>
                                <td>{{$value['sales_dist_ratio']}}</td>
                                <td colspan="1">{{$value['purchase_ratio']}}</td>
                                <td>{{$value['sales_stock_ratio']}}</td>
                            </tr>
                            <?php }  ?>
                        </tbody>
                        <tfoot>
                            <?php foreach ($grand_val as $value) { ?>
                            <tr class="sticky-foot fw-bold">
                                <td>Total</td>
                                <td>{{$value['primary_dis_ratio']}}</td>
                                <td>{{$value['sales_dist_ratio']}}</td>
                                <td colspan="1">{{$value['purchase_ratio']}}</td>
                                <td>{{$value['sales_stock_ratio']}}</td>
                            </tr>
                            <?php }  ?>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>


        <div class="col-md-5">
            <div class="card shadow-lg rounded-4 border-0 p-4">
                <div class="table-responsive" style="overflow-x: auto;">
                    <table id="Table4" class="table table-bordered table-striped table-hover w-100">
                        <thead>
                            <tr>
                                <?php if (request('zone') != ''): ?>
                                <th class="align text-white bg-danger text-center">{{ request('zone') }}</th>
                                <?php endif; ?>
                                <?php if (request('region') != ''): ?>
                                <th class="align text-white bg-danger text-center">{{ request('region') }}</th>
                                <?php endif; ?>
                                <th colspan="3" class="align text-white bg-danger text-center">STOCK LIQUDATION RATIO %
                                    {{ $cur_fy_year}}</th>

                            </tr>
                            <tr>
                                <th colspan="3" class="align text-white bg-secondary text-center">{{ $cur_fy_year}}</th>
                            </tr>
                            <tr>
                                <th class="align sticky-col text-white bg-success text-center">State</th>
                                <th class="align text-white bg-success text-center">Dist. Sale ÷ Primary</th>
                                <th class="align text-white bg-success text-center">Stockiest Sale ÷ Primary</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ratio_val as $value) { ?>
                            <tr>
                                <td>{{$value['state']}}</td>
                                <td>{{$value['dist_sale_ratio']}}</td>
                                <td>{{$value['stock_sale_ratio']}}</td>
                            </tr>
                            <?php }  ?>
                        </tbody>
                        <tfoot>
                            <?php foreach ($grand_val as $value) { ?>
                            <tr style="background:#ffbef7;font-weight: 600;" class="sticky-foot">
                                <td>Total</td>
                                <td>{{$value['dist_sale_ratio']}}</td>
                                <td>{{$value['stock_sale_ratio']}}</td>
                            </tr>
                            <?php }  ?>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <!-- END -->

@endsection
@push('scripts')

    <script>
        // on chnange zone based region
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


        $(document).ready(function () {

            var startDate = "{{ request('date_select') }}";
            $('#date_select').val(startDate);


        });

        $(document).ready(function () {
            $('#Table1').DataTable({
                dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
                order: [],
                buttons: [
                    {
                        extend: 'excelHtml5',
                        filename: 'STATE WISE SALES',
                        exportOptions: {
                            format: {
                                header: function (data, columnIdx) {
                                    var header1 = $('#Table1 thead tr:eq(0) th').eq(columnIdx).text();
                                    var header3 = $('#Table1 thead tr:eq(2) th').eq(columnIdx).text();

                                    return header1 + '\n' + header3;
                                }
                            }
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        filename: 'STATE WISE SALES',
                        exportOptions: {
                            format: {
                                header: function (data, columnIdx) {
                                    // Concatenate headers with line breaks
                                    var header1 = $('#Table1 thead tr:eq(0) th').eq(columnIdx).text();
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
                        filename: 'STATE WISE SALES',
                        exportOptions: {
                            format: {
                                header: function (data, columnIdx) {
                                    var header1 = $('#Table2 thead tr:eq(0) th').eq(columnIdx).text();
                                    var header3 = $('#Table2 thead tr:eq(2) th').eq(columnIdx).text();

                                    return header1 + '\n' + header3;
                                }
                            }
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        filename: 'STATE WISE SALES',
                        exportOptions: {
                            format: {
                                header: function (data, columnIdx) {
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
            $('#Table3').DataTable({
                dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
                order: [],
                buttons: [
                    {
                        extend: 'excelHtml5',
                        filename: 'SALES GROWTH STATE',
                        exportOptions: {
                            format: {
                                header: function (data, columnIdx) {
                                    var header1 = $('#Table3 thead tr:eq(0) th').eq(columnIdx).text();
                                    var header3 = $('#Table3 thead tr:eq(2) th').eq(columnIdx).text();

                                    return header1 + '\n' + header3;
                                }
                            }
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        filename: 'SALES GROWTH STATE',
                        exportOptions: {
                            format: {
                                header: function (data, columnIdx) {
                                    // Concatenate headers with line breaks
                                    var header1 = $('#Table3 thead tr:eq(0) th').eq(columnIdx).text();
                                    var header3 = $('#Table3 thead tr:eq(2) th').eq(columnIdx).text();

                                    return header1 + '\n' + header3;
                                }
                            }
                        }
                    }
                ]
            });

            $('#Table4').DataTable({
                dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
                order: [],
                buttons: [
                    {
                        extend: 'excelHtml5',
                        filename: 'STOCK LIQUDATION RATIO STATE',
                        exportOptions: {
                            format: {
                                header: function (data, columnIdx) {
                                    var header1 = $('#Table4 thead tr:eq(0) th').eq(columnIdx).text();
                                    var header3 = $('#Table4 thead tr:eq(2) th').eq(columnIdx).text();

                                    return header1 + '\n' + header3;
                                }
                            }
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        filename: 'STOCK LIQUDATION RATIO STATE',
                        exportOptions: {
                            format: {
                                header: function (data, columnIdx) {
                                    // Concatenate headers with line breaks
                                    var header1 = $('#Table4 thead tr:eq(0) th').eq(columnIdx).text();
                                    var header3 = $('#Table4 thead tr:eq(2) th').eq(columnIdx).text();

                                    return header1 + '\n' + header3;
                                }
                            }
                        }
                    }
                ]
            });

        });
        // search alert
        document.getElementById('searchForm').addEventListener('submit', function (event) {
            var zone = document.getElementById('zone').value;
            var region = document.getElementById('region').value;
            var startDate = document.getElementById('date_select').value;

            if (zone === '' && region === '' && startDate === '') {
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
    </script>
    <!-- end  -->
@endpush