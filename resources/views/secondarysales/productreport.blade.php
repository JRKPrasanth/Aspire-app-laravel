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
                <a href="statesalesreport" class="btn btn-outline-primary w-100">State Wise</a>
            </div>
            <div class="col-12 col-md-3">
                <a href="lastsalereport" class="btn btn-outline-primary w-100">Closing Stock level</a>
            </div>
            <div class="col-12 col-md-2">
                <a href="productsalesreport" class="btn btn-primary w-100">Product Wise</a>
            </div>
        </div>
    </div>

    <div class="card shadow-lg rounded-4 border-0 p-4 mb-4">
        <form action="{{ url('productsalesreport') }}" method="get" id="searchForm">
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


                <!-- State -->
                <div class="col-md-3">
                    <label for="state" class="form-label">State</label>
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
                    <input type="month" name="date_select" id="date_select" class="form-control" autocomplete="off">
                </div>

                <!-- Submit Button -->
                <div class="col-12 text-center mt-3">
                    <button type="submit" class="btn btn-primary px-5" id="searchButton"><i class="bi bi-search"></i>
                        Search</button>
                </div>

            </div>
        </form>
    </div>

    <!---  product Wise Sale value -->
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
                        <?php if (request('division') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('division') }}</th>
                        <?php endif; ?>

                        <th colspan="8" class="align text-white bg-danger text-center">PRODUCT PACK WISE UNITS UPTO
                            {{ $last_month }}</th>

                    </tr>
                    <tr>
                        <th colspan="2" class="text-white bg-secondary text-center">Sum Of Sales Unit</th>
                        <th colspan="2" class="text-white bg-secondary text-center">Primary</th>
                        <th colspan="2" class="text-white bg-secondary text-center">Distributor Sale</th>
                        <th colspan="2" class="text-white bg-secondary text-center">Stockist Sale</th>

                    </tr>
                    <tr>
                        <th colspan="1" class="align text-white bg-success text-center">Product Division</th>
                        <th colspan="1" class="align sticky-col text-white bg-success text-center">Product Name</th>
                        <th class="align text-white bg-success text-center"><span class="text-success"
                                style="font-size:1px;">Primary sale </span>{{ $pre_fy_year }}</th>
                        <th class="align text-white bg-success text-center"><span class="text-success"
                                style="font-size:1px;">Primary sale </span>{{ $cur_fy_year}}</th>
                        <th class="align text-white bg-success text-center"><span class="text-success"
                                style="font-size:1px;">Distributor sale </span>{{ $pre_fy_year }}</th>
                        <th class="align text-white bg-success text-center"><span class="text-success"
                                style="font-size:1px;">Distributor sale </span>{{ $cur_fy_year}}</th>
                        <th class="align text-white bg-success text-center"><span class="text-success"
                                style="font-size:1px;">Stockist sale </span>{{ $pre_fy_year }}</th>
                        <th class="align text-white bg-success text-center"><span class="text-success"
                                style="font-size:1px;">Stockist sale </span>{{ $cur_fy_year}}</th>

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
                'division' => $value->division,
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
                        <td><?php    echo $data['division']; ?></td>
                        <td class="sticky-col"><?php    echo $productName; ?></td>
                        <td><?php    echo $data['primary_dis_pre_fy']; ?></td>
                        <td><?php    echo $data['primary_dis_cur_fy']; ?></td>
                        <td><?php    echo $data['sales_dist_pre_fy']; ?></td>
                        <td><?php    echo $data['sales_dist_cur_fy']; ?></td>
                        <td><?php    echo $data['sales_stock_pre_fy']; ?></td>
                        <td><?php    echo $data['sales_stock_cur_fy']; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
                <!-- Add the total row for all products -->
                <tfoot class="table-danger">
                    <tr class="sticky-total fw-bold">
                        <td>Grand Total</td>
                        <td></td>
                        <td><?php echo $divisionTotals['totalPrimaryDis_pre_fy']; ?></td>
                        <td><?php echo $divisionTotals['totalPrimaryDis_cur_fy']; ?></td>
                        <td><?php echo $divisionTotals['totalSalesDist_pre_fy']; ?></td>
                        <td><?php echo $divisionTotals['totalSalesDist_cur_fy']; ?></td>
                        <td><?php echo $divisionTotals['totalSalesStock_pre_fy']; ?></td>
                        <td><?php echo $divisionTotals['totalSalesStock_cur_fy']; ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>


    <div class="row">
        <div class="col-md-7">
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
                                <th colspan="4" class="align text-white bg-danger text-center">SALES GROWTH
                                    {{ $pre_fy_year }}</th>

                            </tr>

                            <tr class="sticky-row ">
                                <th class="align1 sticky-col text-white bg-secondary text-center">Product</th>
                                <th class="align1 text-white bg-secondary text-center">Primary</th>
                                <th class="align1 text-white bg-secondary text-center">Distributor Sale</th>
                                <th class="align1 text-white bg-secondary text-center">Stockist Sale</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sale_value as $value) { ?>
                            <tr>
                                <td class="sticky-col">{{ $value['product'] }}</td>
                                <td>{{ $value['primary_dis_ratio'] }}</td>
                                <td>{{ $value['sales_dist_ratio'] }}</td>
                                <td>{{ $value['purchase_ratio'] }}</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot class="table-danger">
                            <?php foreach ($grand_value as $value) { ?>
                            <tr class="sticky-foot fw-bold">
                                <td>Total</td>
                                <td>{{ $value['primary_dis_ratio'] }}</td>
                                <td>{{ $value['sales_dist_ratio'] }}</td>
                                <td>{{ $value['purchase_ratio'] }}</td>
                            </tr>
                            <?php } ?>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>


        <div class="col-md-5">

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
                                <th colspan="3" class="align text-white bg-danger text-center">STOCK LIQUDATION RATIO %
                                    {{ $cur_fy_year}}</th>

                            </tr>
                            <tr>
                                <th class="align text-white bg-secondary text-center sticky-col">Product</th>
                                <th class="align text-white bg-secondary text-center">Dist. Sale ÷ Primary</th>
                                <th class="align text-white bg-secondary text-center">Stockiest Sale ÷ Primary</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sale_value as $value) { ?>
                            <tr>
                                <td class="sticky-col">{{ $value['product'] }}</td>
                                <td>{{ $value['slae_ratio'] }}</td>
                                <td>{{ $value['stock_ratio'] }}</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot class="table-danger">
                            <?php foreach ($grand_value as $value) { ?>
                            <tr style="background:#ffbef7;font-weight: 600;" class="sticky-foot">
                                <td>Total</td>
                                <td>{{ $value['slae_ratio'] }}</td>
                                <td>{{ $value['stock_ratio'] }}</td>

                            </tr>
                            <?php } ?>
                        </tfoot>
                    </table>
                </div>
            </div>
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
                        filename: 'PRODUCT PACK WISE UNITS',
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
                        filename: 'PRODUCT PACK WISE UNITS',
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
                scrollX: true,
                scrollY: "50vh",
                buttons: [
                    {
                        extend: 'excelHtml5',
                        filename: 'SALES GROWTH PRODUCT WISE',
                        exportOptions: {
                            format: {
                                header: function (data, columnIdx) {
                                    var header1 = $('#Table2 thead tr:eq(0) th').eq(columnIdx).text();
                                    var header3 = $('#Table2 thead tr:eq(1) th').eq(columnIdx).text();

                                    return header1 + '\n' + header3;
                                }
                            }
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        filename: 'SALES GROWTH PRODUCT WISE',
                        exportOptions: {
                            format: {
                                header: function (data, columnIdx) {
                                    // Concatenate headers with line breaks
                                    var header1 = $('#Table2 thead tr:eq(0) th').eq(columnIdx).text();
                                    var header3 = $('#Table2 thead tr:eq(1) th').eq(columnIdx).text();

                                    return header1 + '\n' + header3;
                                }
                            }
                        }
                    }
                ]
            });

            $('#Table3').DataTable({
                scrollX: true,
                scrollY: "50vh",
                buttons: [
                    {
                        extend: 'excelHtml5',
                        filename: 'STOCK LIQUDATION RATIO PRODUCT WISE',
                        exportOptions: {
                            format: {
                                header: function (data, columnIdx) {
                                    var header1 = $('#Table3 thead tr:eq(0) th').eq(columnIdx).text();
                                    var header3 = $('#Table3 thead tr:eq(1) th').eq(columnIdx).text();

                                    return header1 + '\n' + header3;
                                }
                            }
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        filename: 'STOCK LIQUDATION RATIO PRODUCT WISE',
                        exportOptions: {
                            format: {
                                header: function (data, columnIdx) {
                                    // Concatenate headers with line breaks
                                    var header1 = $('#Table3 thead tr:eq(0) th').eq(columnIdx).text();
                                    var header3 = $('#Table3 thead tr:eq(1) th').eq(columnIdx).text();

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
            var Division = document.getElementById('division').value;
            var State = document.getElementById('state').value;

            if (zone === '' && region === '' && startDate === '' && Division === '' && State === '') {
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