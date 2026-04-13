@extends('layouts.header')
@section('content')

    <h2 class="text-danger">Purchase Dashboard</h2>

    <div class="container mt-4">
        <!-- First row of buttons -->
        <div class="row g-3 mb-3">
            <div class="col-12 col-md-3">
                <a href="purchasedashboard" class="btn btn-outline-success w-100">Supplier Based</a>
            </div>
            <div class="col-12 col-md-3">
                <a href="purchasedashboardproduct" class="btn btn-primary w-100">Product Based</a>
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
    <!-- Bootstrap 5 Form Layout -->
    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="container-fluid">
            <form action="{{ url('purchasedashboardproduct') }}" method="get" id="searchForm">
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
    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table1" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <tr>

                        <?php if (request('product_name') != ''): ?>
                        <th class="text-center text-white" style="background:#c54444">{{ $product }}</th>
                        <?php else: ?>
                        <th class="align" style="background:#47647c"></th>
                        <?php endif; ?>

                        <?php $displayedMonths = []; ?>
                        <?php foreach ($category_wise as $value) {
        $monthYear = $value->month_y;
        if (!in_array($monthYear, $displayedMonths)) {
            $displayedMonths[] = $monthYear; ?>
                        <th colspan="2" class="text-center text-white" style="background:#47647c;">
                            <?php        echo $monthYear; ?>
                        </th>
                        <?php    }
    } ?>
                        <th colspan="2" class="text-center text-white" style="text-align: center;background: #47647c;">Total
                        </th>
                    </tr>

                    <!-- Second header row with 'qty' and 'value' columns under each month -->
                    <tr style="background:#c32323 !important;">
                        <th class="text-center bg-danger text-white">Supplier Name</th>
                        <?php foreach ($displayedMonths as $month) { ?>
                        <th class="text-center bg-danger text-white">Qty</th>
                        <th class="text-center bg-danger text-white">Value</th>
                        <?php } ?>
                        <th class="text-center bg-danger text-white">Qty</th>
                        <th class="text-center bg-danger text-white">Value </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
    $totalStock = 0;
    $totalqty = 0;
    $grandTotalQty = 0; // Grand total for all quantities
    $grandTotalValue = 0; // Grand total for all values
    $monthlySales = []; // Array to store sales data for each month

    // Process each category-wise value
    foreach ($category_wise as $value) {
        $totalStock += $value->subtotal;
        $totalqty += $value->p_qty;

        // Store sales data for each supplier, month, and quantity
        $monthlySales[$value->supplier_name][$value->month_y]['subtotal'] = isset($monthlySales[$value->supplier_name][$value->month_y]['subtotal']) ? $monthlySales[$value->supplier_name][$value->month_y]['subtotal'] + $value->subtotal : $value->subtotal;
        $monthlySales[$value->supplier_name][$value->month_y]['p_qty'] = isset($monthlySales[$value->supplier_name][$value->month_y]['p_qty']) ? $monthlySales[$value->supplier_name][$value->month_y]['p_qty'] + $value->p_qty : $value->p_qty;
    }

    // Display each supplier's sales data for the months
    foreach ($monthlySales as $supplier_name => $monthData) {
        $rowTotalQty = 0; // Row total for quantity
        $rowTotalValue = 0; // Row total for value

        echo '<tr>';
        echo '<td class="sticky-col" style="text-align:center;">' . $supplier_name . '</td>';

        foreach ($displayedMonths as $month) {
            $p_qty = isset($monthData[$month]['p_qty']) ? $monthData[$month]['p_qty'] : 0;
            $saleValue = isset($monthData[$month]['subtotal']) ? $monthData[$month]['subtotal'] : 0;

            // Update row totals
            $rowTotalQty += $p_qty;
            $rowTotalValue += $saleValue;

            // Update grand totals
            $grandTotalQty += $p_qty;
            $grandTotalValue += $saleValue;

            // Display qty and value in separate cells
            echo '<td style="text-align:center;">' . $p_qty . '</td>';
            echo '<td style="text-align:center;">' . $saleValue . '</td>';
        }

        // Display row totals
        echo '<td style="text-align:center;font-weight: bold;">' . $rowTotalQty . '</td>';
        echo '<td style="text-align:center;font-weight: bold;">' . $rowTotalValue . '</td>';
        echo '</tr>';
    }
            ?>
                </tbody>
                <tfoot>
                    <?php
    // Add Grand Total Row
    echo "<tr style='background:#ffbef7;font-weight: 600;'>";
    echo "<td class='sticky-col' style='font-weight: bold;'>Grand Total</td>";

    foreach ($displayedMonths as $month) {
        $totalQty = 0;
        $totalValue = 0;

        // Calculate total for each month
        foreach ($monthlySales as $monthData) {
            $totalQty += isset($monthData[$month]['p_qty']) ? $monthData[$month]['p_qty'] : 0;
            $totalValue += isset($monthData[$month]['subtotal']) ? $monthData[$month]['subtotal'] : 0;
        }

        // Display the total quantity and total value in separate cells
        echo "<td style='text-align:center;'>" . $totalQty . "</td>";
        echo "<td style='text-align:center;'>" . $totalValue . "</td>";
    }

    // Display grand totals
    echo "<td style='text-align:center; font-weight: bold;'>" . $grandTotalQty . "</td>";
    echo "<td style='text-align:center; font-weight: bold;'>" . $grandTotalValue . "</td>";
    echo "</tr>";
            ?>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- product based top value-->
    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table2" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <tr>
                        <?php if (request('product_name') != ''): ?>
                        <th class="text-center text-white" style="background:#c54444">{{ $product }}</th>
                        <?php endif; ?>
                        <th colspan="50" class="text-center text-white" style="background:#47647c;">Supplier Based Top
                            Purchase Value</th>
                    </tr>

                    <tr style="background:#c32323 !important;">

                        <th class="text-center bg-danger text-white">Months</th>

                        <?php $displayedMonths = []; ?>
                        <?php foreach ($category_wise_top as $value) {
        $supplierName = $value->supplier_name;
        if (!in_array($supplierName, $displayedMonths)) {
            $displayedMonths[] = $supplierName; ?>
                        <th class="text-center bg-danger text-white">
                            <?php        echo $supplierName; ?>
                        </th>
                        <?php    }
    } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
    $totalStock = 0;
    $monthlySales = []; // Array to store sales data for each month

    foreach ($category_wise_top as $value) {
        $totalStock += $value->subtotal;

        // Store sales data for each month
        $monthlySales[$value->month_y][$value->supplier_name] = $value->subtotal;
    }

    // Loop through unique F_Year, HQ Name, Zone, Region, State combinations
    foreach ($monthlySales as $month_y => $monthData) {



        echo '<tr>';
        echo '<td class="sticky-col" style="text-align:center;">' . $month_y . '</td>';

        foreach ($displayedMonths as $month) {
            $saleValue = isset($monthData["$month"]) ? $monthData["$month"] : 0;
            echo '<td style="text-align:center;">' . $saleValue . '</td>';
        }

        echo '</tr>';



    }
                    ?>
                </tbody>
                <tfoot>
                    <?php
    // Add Grand Total Row
    echo "<tr style='background:#ffbef7;font-weight: 600;'>";
    echo "<td  class='sticky-col1'>Total</td>";
    foreach ($displayedMonths as $month) {
        $totalSamples = 0;

        foreach ($monthlySales as $month_y => $monthData) {
            $totalSamples += isset($monthData["$month"]) ? $monthData["$month"] : 0;
        }



        echo "<td style='text-align:center;'>{$totalSamples}</td>";
    }

    echo "</tr>";
                    ?>
                </tfoot>
            </table>
        </div>
    </div>
    <!-- chart -->

    <div class="card shadow-lg rounded-4 border-0 p-4">
        <p class="nodata">Month Wise Purchase Chart (Supplier Based)</p>
        <div class="dash_charrt">
            <canvas id="typeChart"></canvas>
            <p class="nodata" id="typeChart1"></p>
        </div>
    </div>


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
                    { extend: 'excelHtml5', title: "Product_based_purchase_value", exportOptions: { columns: ':visible' } }
                ]
            });

            $('#Table2').DataTable({
                dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
                buttons: [
                    {
                        extend: 'colvis',
                        text: '<i class="bi bi-layout-three-columns"></i> Columns',
                        className: 'btn bg-primary btn-sm',
                        postfixButtons: ['colvisRestore'] // adds "Restore Columns" button
                    },
                    { extend: 'excelHtml5', title: "Product_based_purchase_value", exportOptions: { columns: ':visible' } }
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
                type: 'line',
                data: {
                    labels: variant,
                    datasets: [
                        {
                            label: 'Purchase Value',
                            data: yValues,
                            borderColor: '#3b82f6',
                            backgroundColor: 'transparent',
                            fill: false,
                            tension: 0.2,       // smoothness (0 = straight)
                            pointRadius: 2,     // smaller dots for many values
                            pointHoverRadius: 4,
                            borderWidth: 2
                        },
                        {
                            label: 'Trendline',
                            data: trendlineData,
                            borderColor: 'red',
                            backgroundColor: 'transparent',
                            fill: false,
                            tension: 0,
                            pointRadius: 0,     // no dots for trendline
                            borderWidth: 2
                        }
                    ]
                },
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const currentValue = context.parsed.y;
                                    return Name[context.dataIndex] + ': ' + currentValue;
                                }
                            }
                        }
                    },
                    scales: {
                        x: { ticks: { autoSkip: true, maxRotation: 45, minRotation: 45 } },
                        y: { beginAtZero: true }
                    }
                }
            });

            document.getElementById('typeChart').style.height = '400px';
        }
    </script>
@endpush