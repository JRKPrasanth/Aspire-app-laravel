@extends('layouts.header')
@section('content')

<h3 class="text-danger">Purchase Dashboard</h3>

<div class="container mt-4">
    <!-- First row of buttons -->
    <div class="row g-3 mb-3">
        <div class="col-12 col-md-3">
            <a href="purchasedashboard" class="btn btn-success w-100 text-white">Supplier Based</a>
        </div>
        <div class="col-12 col-md-3">
            <a href="purchasedashboardproduct" class="btn btn-outline-primary w-100">Product Based</a>
        </div>
        <div class="col-12 col-md-3">
            <a href="purchasedashboardqty-val" class="btn btn-outline-info w-100">Top Rating Qty & Value</a>
        </div>
        <div class="col-12 col-md-3">
            <a href="purchasedashboardpricetrend" class="btn btn-outline-secondary w-100">Product Price Trend</a>
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
    <form action="{{ url('purchasedashboard') }}" method="get" id="searchForm">
        <div class="row g-4">

            <!-- Supplier Name -->
            <div class="col-md-4">
                <label for="supplier_name" class="form-label fw-semibold">Supplier Name</label>
                <select name="supplier_name" id="supplier_name" class="form-select select2" required>
                    {!! $supplier_name !!}
                </select>
            </div>

            <!-- From Date -->
            <div class="col-md-4">
                <label for="start_date" class="form-label fw-semibold">From Date</label>
                <input type="text" class="form-control start_date1" id="start_date" name="start_date" required
                    autocomplete="off" placeholder="YYYY-MM-DD">
            </div>

            <!-- To Date -->
            <div class="col-md-4">
                <label for="end_date" class="form-label fw-semibold">To Date</label>
                <input type="text" class="form-control end_date1" id="end_date" name="end_date" required
                    autocomplete="off" placeholder="YYYY-MM-DD">
            </div>

            <!-- Search Button -->
            <div class="col-12 text-center mt-3">
                <button type="submit" class="btn btn-primary px-5">Search</button>
            </div>

        </div>
    </form>
</div>

<!-- end -->

<!-- category based -->
<div class="card shadow-lg rounded-4 border-0 p-4">
    <div class="table-responsive" style="overflow-x: auto;">
        <table id="Table1" class="table table-bordered table-striped table-hover w-100">
            <thead>
                <tr>
                    <?php if (request('supplier_name') != ''): ?>
                        <th class="text-center text-white" style="background:#c54444">{{ $supplier }}</th>
                    <?php endif; ?>
                    <th colspan="12" class="text-center text-white" style="background:#47647c;">Product Category Based
                        Purchase Value</th>
                </tr>
                <tr style="background:#c32323; color: #fff;">
                    <th class="text-center bg-secondary text-white">Product Category</th>
                    <?php
                    $displayedMonths = [];
                    foreach ($category_wise as $value) {
                        $monthYear = $value->month_y;
                        if (!in_array($monthYear, $displayedMonths)) {
                            $displayedMonths[] = $monthYear;
                            echo "<th class='text-center bg-secondary text-white'>{$monthYear}</th>";
                        }
                    }
                    ?>
                    <th class="text-center bg-secondary text-white">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalStock = 0;
                $monthlySales = [];

                foreach ($category_wise as $value) {
                    $totalStock += $value->subtotal;
                    $monthlySales[$value->category_name][$value->month_y] = $value->subtotal;
                }

                foreach ($monthlySales as $category_name => $monthData) {
                    $rowTotal = 0;
                    echo '<tr>';
                    echo "<td class='text-center'>{$category_name}</td>";

                    foreach ($displayedMonths as $month) {
                        $saleValue = isset($monthData[$month]) ? $monthData[$month] : 0;
                        $rowTotal += $saleValue;
                        echo "<td class='text-center'>{$saleValue}</td>";
                    }

                    echo "<td class='text-center fw-bold'>{$rowTotal}</td>";
                    echo '</tr>';
                }
                ?>
            </tbody>
            <tfoot>
                <tr style="background:#ffbef7; font-weight: 600;">
                    <td class="text-center">Total</td>
                    <?php
                    $grandRowTotal = 0;
                    foreach ($displayedMonths as $month) {
                        $totalSamples = 0;
                        foreach ($monthlySales as $monthData) {
                            $totalSamples += isset($monthData[$month]) ? $monthData[$month] : 0;
                        }
                        $grandRowTotal += $totalSamples;
                        echo "<td class='text-center'>{$totalSamples}</td>";
                    }
                    echo "<td class='text-center fw-bold'>{$grandRowTotal}</td>";
                    ?>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<!-- chart -->

<div class="card shadow-lg rounded-4 border-0 p-4">
    <p class="nodata">Purchase Chart (Product Category Based)</p>
    <div class="dash_charrt">
        <canvas id="typeChart"></canvas>
        <p class="nodata" id="typeChart1"></p>
    </div>
</div>
<!-- product based -->
<div class="card shadow-lg rounded-4 border-0 p-4">
    <div class="table-responsive">
        <table id="Table2" class="table table-bordered table-striped table-hover nowrap w-100">
            <thead>
                <tr>
                    <?php if (request('supplier_name') != ''): ?>
                        <th class="text-center text-white bg-danger">{{ $supplier }}</th>
                    <?php endif; ?>
                    <th colspan="<?php echo count($displayedMonths) + 2; ?>" class="text-center text-white"
                        style="background-color: #47647c;">
                        Product Based Purchase Value
                    </th>
                </tr>
                <tr class="text-white" style="background-color:#c32323;">
                    <th class="text-center border-end bg-secondary text-white">Category</th>
                    <th class="text-center border-end bg-secondary text-white">Product Name</th>

                    <?php $displayedMonths = []; ?>
                    <?php foreach ($product_wise as $value):
                        $monthYear = $value->month_y;
                        if (!in_array($monthYear, $displayedMonths)) {
                            $displayedMonths[] = $monthYear; ?>
                            <th class="text-center border-end bg-secondary text-white">
                                <?= $monthYear ?>
                            </th>
                        <?php } ?>
                    <?php endforeach; ?>
                    <th class="text-center border-end bg-secondary text-white">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalStock = 0;
                $monthlySales = [];

                foreach ($product_wise as $value) {
                    $totalStock += $value->subtotal;
                    $monthlySales[$value->category_name][$value->concatenated_product][$value->month_y] = $value->subtotal;
                }

                foreach ($monthlySales as $category_name => $products) {
                    foreach ($products as $product_name => $monthData) {
                        $rowTotal = 0;
                        echo '<tr>';
                        echo '<td class="text-center border-end">' . $category_name . '</td>';
                        echo '<td class="text-center border-end">' . $product_name . '</td>';

                        foreach ($displayedMonths as $month) {
                            $saleValue = isset($monthData[$month]) ? $monthData[$month] : 0;
                            $rowTotal += $saleValue;
                            echo '<td class="text-center border-end">' . $saleValue . '</td>';
                        }

                        echo '<td class="text-center border-end fw-bold">' . $rowTotal . '</td>';
                        echo '</tr>';
                    }
                }
                ?>
            </tbody>
            <tfoot>
                <?php
                echo "<tr class='bg-light fw-bold'>";
                echo "<td class='text-center border-end'>Total</td>";
                echo "<td class='text-center border-end'></td>";

                $grandRowTotal = 0;
                foreach ($displayedMonths as $month) {
                    $totalSamples = 0;
                    foreach ($monthlySales as $products) {
                        foreach ($products as $monthData) {
                            $totalSamples += isset($monthData[$month]) ? $monthData[$month] : 0;
                        }
                    }
                    $grandRowTotal += $totalSamples;
                    echo "<td class='text-center border-end'>{$totalSamples}</td>";
                }

                echo "<td class='text-center border-end fw-bold'>{$grandRowTotal}</td>";
                echo "</tr>";
                ?>
            </tfoot>
        </table>
    </div>
</div>


<!-- product based  chart -->

<div class="card shadow-lg rounded-4 border-0 p-4">
    <p class="nodata">Purchase Chart (Product Based)</p>
    <div class="dash_charrt">
        <div class='col-md-4 select'>
            <select id="productDropdown" style=" text-align: center !important;">
                <option>--please select--</option>
            </select>
        </div>

        <div class='col-md-8' id="productChartContainer">
            <canvas id="productChart"></canvas>
        </div>
    </div>
</div>

@endsection
@push('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

<script>





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
                { extend: 'excelHtml5', title: "Supplier_based_purchase_value", exportOptions: { columns: ':visible' } }
            ]
        });

        $('#Table2').DataTable({
            scrollX: true,
            scrollY: "50vh",
            dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
            buttons: [
                {
                    extend: 'colvis',
                    text: '<i class="bi bi-layout-three-columns"></i> Columns',
                    className: 'btn bg-primary btn-sm',
                    postfixButtons: ['colvisRestore'] // adds "Restore Columns" button
                },
                { extend: 'excelHtml5', title: "Supplier_based_purchase_value", exportOptions: { columns: ':visible' } }
            ]
        });
    });

    $(document).ready(function () {
        var machineName = "{{ request('supplier_name') }}";
        var startDate = "{{ request('start_date') }}";
        var endDate = "{{ request('end_date') }}";

        $('.supplier_name').select2();
        $('#supplier_name').val(machineName).trigger('change');


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


    var monthData = {!! $prim_sumchart !!};

    if (monthData.length == 0) {
        document.getElementById("typeChart1").innerHTML = "No Data available.";
    } else {
        var variant = monthData.map(item => item.month);
        var yValues = monthData.map(item => item.value);
        var Name = monthData.map(item => item.name);
        var barColors = Array.from({ length: variant.length }, () => generateRandomColor());

        // Calculate linear trendline
        function calculateTrendline(data) {
            const n = data.length;
            let sumX = 0, sumY = 0, sumXY = 0, sumX2 = 0;

            for (let i = 0; i < n; i++) {
                sumX += i;
                sumY += data[i];
                sumXY += i * data[i];
                sumX2 += i * i;
            }

            const slope = (n * sumXY - sumX * sumY) / (n * sumX2 - sumX * sumX);
            const intercept = (sumY - slope * sumX) / n;

            return Array.from({ length: n }, (_, i) => slope * i + intercept);
        }

        // Calculate trendline data
        const trendlineData = calculateTrendline(yValues);

        var ctx = document.getElementById('typeChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: variant,
                datasets: [
                    {
                        label: 'Purchase Value',
                        backgroundColor: barColors,
                        data: yValues,
                    },
                    {
                        type: 'line',
                        label: 'Trendline',
                        data: trendlineData,
                        fill: false,
                        borderColor: 'red',
                    }
                ]
            },
            options: {

                tooltips: {
                    callbacks: {
                        label: function (tooltipItem, data) {
                            const dataset = data.datasets[tooltipItem.datasetIndex];
                            const currentValue = dataset.data[tooltipItem.index];
                            return Name[tooltipItem.index] + ': ' + currentValue;
                        }
                    }
                }
            }

        });
    }
    document.getElementById('typeChart').style.height = '360px';
    // product chart

    // product chart
    var productData = {!! $product_sumchart !!};

    if (productData.length == 0) {
        document.getElementById("productChart1").innerHTML = "No Data available.";
    } else {
        var variant = productData.map(item => item.month);
        var yValues = productData.map(item => item.value);
        var names = productData.map(item => item.name);
        var barColors = Array.from({ length: variant.length }, () => generateRandomColor());

        // Create a dropdown for product names
        var dropdown = document.getElementById("productDropdown");
        names.forEach(function (name) {
            var option = document.createElement("option");
            option.text = name;
            dropdown.add(option);
        });

        // Handle dropdown change event
        dropdown.addEventListener("change", function () {
            var selectedName = dropdown.value;
            var selectedData = productData.filter(item => item.name === selectedName);
            updateChart(selectedData, selectedName);
        });
    }

    var productChartInstance = null;

    function updateChart(data, selectedProduct) {

        const ctx = document.getElementById('productChart').getContext('2d');

        // 🔥 Destroy previous chart completely
        if (productChartInstance) {
            productChartInstance.destroy();
            productChartInstance = null;
        }

        productChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.map(item => item.month),
                datasets: [{
                    label: selectedProduct,
                    data: data.map(item => item.value),
                    backgroundColor: generateRandomColor()
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    tooltip: {
                        callbacks: {
                            title: function (context) {
                                return context[0].label; // Month
                            },
                            label: function (context) {
                                return selectedProduct + ' Purchase: ' + context.raw;
                            }
                        }
                    }
                },

                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    document.getElementById('productChart').style.height = '350px';

    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('end_date').disabled = false;
    });



</script>

<!-- end -->


@endpush