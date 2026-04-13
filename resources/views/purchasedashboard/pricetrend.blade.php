@extends('layouts.header')
@section('content')

    <h3 class="text-danger">Purchase Dashboard</h3>

    <div class="container mt-4">
        <!-- First row of buttons -->
        <div class="row g-3 mb-3">
            <div class="col-12 col-md-3">
                <a href="purchasedashboard" class="btn btn-outline-success w-100">Supplier Based</a>
            </div>
            <div class="col-12 col-md-3">
                <a href="purchasedashboardproduct" class="btn btn-outline-primary w-100">Product Based</a>
            </div>
            <div class="col-12 col-md-3">
                <a href="purchasedashboardqty-val" class="btn btn-outline-info w-100">Top Rating Qty & Value</a>
            </div>
            <div class="col-12 col-md-3">
                <a href="purchasedashboardpricetrend" class="btn btn-secondary w-100">Product Price Trend</a>
            </div>
            <div class="col-12 col-md-3">
                <a href="purchaseproductexceptionrpt" class="btn btn-outline-primary w-100">Price Trend Exception</a>
            </div>
            <div class="col-12 col-md-3">
                <a href="purchaseproductmostspend" class="btn btn-outline-dark w-100">Most Spend Value Products</a>
            </div>
            <div class="col-12 col-md-3">
                <a href="rejectedpuritems" class="btn btn-outline-danger w-100">Rejected Products List</a>
            </div>
            <div class="col-12 col-md-3">
                <a href="consumptionquantityreport" class="btn btn-outline-warning w-100">Consumption Quantity Report</a>
            </div>

            <div class="col-12 col-md-3">
                <a href="movementreport" class="btn btn-outline-secondary w-100">Movement Report</a>
            </div>

            <div class="col-12 col-md-3">
                <a href="purchase-supplier-summary" class="btn btn-outline-primary w-100">New Supplier Report</a>
            </div>

            <div class="col-12 col-md-3">
                <a href="purchase-product-summary" class="btn btn-outline-info w-100">New Purchase Product</a>
            </div>

            <div class="col-12 col-md-3">
                <a href="productissuedelay" class="btn btn-outline-primary w-100">Pack Material Delay Issue Report</a>
            </div>

        </div>
    </div>
    <!-- drop down -->
    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="container-fluid">
            <form action="{{ url('purchasedashboardpricetrend') }}" method="get" id="searchForm">
                <div class="row g-3 align-items-center mb-3">

                    <!-- Product Group -->
                    <div class="col-md-3">
                        <label for="product_group" class="form-label">Product Group</label>
                        <select name="product_group" id="product_group" class="form-select product_group select2 w-100">
                            {!! $pro_group !!}
                        </select>
                    </div>

                    <!-- Product Name -->
                    <div class="col-md-3">
                        <label for="product_name" class="form-label">Product Name</label>
                        <select name="product_name" id="product_name" class="form-select product_name select2 w-100">
                            {!! $pro_name !!}
                        </select>
                    </div>

                    <!-- From Date -->
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">From Date</label>
                        <input type="text" class="form-control start_date1" id="start_date" name="start_date" required>
                    </div>

                    <!-- To Date -->
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">To Date</label>
                        <input type="text" class="form-control end_date1" id="end_date" name="end_date" required>
                    </div>
                </div>

                <!-- Search Button -->
                <div class="row">
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary" id="searchButton">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- end -->

    <!-- product based -->
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-lg rounded-4 border-0 p-4">
                <div class="table-responsive" style="overflow-x: auto;">
                    <table id="Table1" class="table table-bordered table-striped table-hover w-100">
                        <thead>


                            <tr>
                                <th colspan="6" class="text-center text-white bg-secondary">Product Price Trend
                                    <span class="text-warning"> @if(!empty($selected_product_name)) -
                                    {{ $selected_product_name }} @endif </span>
                                </th>
                            </tr>

                            <tr>

                                <th class="text-center bg-danger text-white">Supplier Name</th>
                                <th class="text-center bg-danger text-white">Month</th>
                                <th class="text-center bg-danger text-white">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($price_trend as $value) { ?>
                            <tr>
                                <td style="text-align:center;">{{$value->supplier_name}}</td>
                                <td style="text-align: center;">{{$value->month_y}}</td>
                                <td style="text-align: center;">{{$value->price}}</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- chart -->
        <div class="col-md-6">
            <div class="card shadow-lg rounded-4 border-0 p-4">
                <p class="nodata">Product Price Trend</p>
                <div class="dash_charrt">
                    <canvas id="lineChart"></canvas>
                    <p class="nodata" id="lineChart1"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts-->
@endsection
@push('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

    <script>

        $(document).on('change', '.product_group', function () {
            var prdgroup = $('.product_group').val(); // or select2('val')

            if (prdgroup !== '') {
                var url = "{{ URL::to('jcomboform1') }}?table=m_products_t:product_id:concatenated_product"
                    + "&parent=and product_group_id=" + prdgroup
                    + "&order_by=concatenated_product asc";

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function (data) {
                        // Parse JSON string if needed
                        if (typeof data === "string") {
                            try {
                                data = JSON.parse(data);
                            } catch (e) {
                                console.error("Invalid JSON response:", data);
                                return;
                            }
                        }

                        // Reset dropdown
                        $('.product_name').html('<option value="">-- Select Product --</option>');

                        // Populate options
                        $.each(data, function (i, item) {
                            let selected = item.val == "{{ $row->product_id ?? '' }}" ? 'selected' : '';
                            $('.product_name').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
                        });

                        // Refresh select2 if used
                        $('.product_name').trigger('change.select2');
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", error);
                    }
                });
            }

            console.log(prdgroup);
        });

        $(document).ready(function () {
            $('#Table1').DataTable({
                dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
                buttons: [
                    {
                        extend: 'colvis',
                        text: '<i class="bi bi-layout-three-columns"></i> Columns',
                        className: 'btn bg-primary btn-sm',
                        postfixButtons: ['colvisRestore'] // adds "Restore Columns" button
                    },
                    { extend: 'excelHtml5', title: "Product_price_trend", exportOptions: { columns: ':visible' } }
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

    <script>
        // color generator

        function generateRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }
        // end

        // Chart

        var PriceData = {!! $price_trend_chart !!};
        console.log(PriceData);

        if (PriceData.length == 0) {
            document.getElementById("lineChart1").innerHTML = "No Data available.";
        } else {
            var variant = PriceData.map(item => item.month);
            var yValues = PriceData.map(item => item.value); // Fixed: Use item.value instead of value
            var barColors = Array.from({ length: variant.length }, () => generateRandomColor());

            var datasets = [
                {
                    label: "Price Trend",
                    data: yValues,
                    fill: false,
                    borderColor: "#FF0000",
                    tension: 0.1
                }
            ];

            new Chart("lineChart", {
                type: "line",
                data: {
                    labels: variant,
                    datasets: datasets
                },
                options: {
                    hover: {
                        animationDuration: 0
                    },
                    animation: {
                        duration: 1,
                        onComplete: function () {
                            var chartInstance = this.chart,
                                ctx = chartInstance.ctx;

                            ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, 'bold ' + Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'bottom';
                            ctx.fillStyle = 'black';
                            this.data.datasets.forEach(function (dataset, i) {
                                var meta = chartInstance.controller.getDatasetMeta(i);
                                meta.data.forEach(function (bar, index) {
                                    var data = dataset.data[index];
                                    ctx.fillText(data, bar._model.x, bar._model.y - 5);
                                });
                            });
                        }
                    },

                    tooltips: {
                        callbacks: {
                            label: function (tooltipItem, data) {
                                var dataset = data.datasets[tooltipItem.datasetIndex];
                                var currentValue = dataset.data[tooltipItem.index];
                                return "Price" + ": " + currentValue;
                            }
                        }
                    },
                    plugins: {
                        datalabels: {
                            display: true,
                            align: 'top',
                            formatter: (value, context) => {
                                return value + '%';
                            }
                        }
                    }
                }
            });
        }

    </script>
@endpush