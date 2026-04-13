@extends('layouts.header')
@section('content')
    <style>
        .sticky-col {
            position: sticky;
            background: #fff;
            left: 0;
        }
    </style>

    <h3 class="text-danger">Purchase Dashboard</h3>

    <div class="container mt-4">
        <!-- First row of buttons -->
        <div class="row g-3 mb-3">
            <div class="col-12 col-md-3">
                <a href="purchasedashboard" class="btn btn-outline-success w-100 ">Supplier Based</a>
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
                <a href="consumptionquantityreport" class="btn btn-warning w-100">Consumption Quantity Report</a>
            </div>

            <div class="col-12 col-md-3">
                <a href="movementreport" class="btn btn-outline-secondary w-100">Movement Report</a>
            </div>

            <div class="col-12 col-md-3">
                <a href="purchase-supplier-summary" class="btn btn-outline-info w-100">New Supplier Report</a>
            </div>

            <div class="col-12 col-md-3">
                <a href="purchase-product-summary" class="btn btn-outline-primary w-100">New Purchase Product</a>
            </div>

            <div class="col-12 col-md-3">
                <a href="productissuedelay" class="btn btn-outline-primary w-100">Pack Material Delay Issue Report</a>
            </div>

        </div>
    </div>
    <!-- drop down -->
    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="container-fluid">
            <form action="{{ url('consumptionquantityreport') }}" method="get" id="searchForm">
                <div class="row g-3 align-items-center mb-3">

                    <!-- Product Group -->
                    <div class="col-md-4">
                        <label for="product_name" class="form-label">Product Group</label>
                        <select name="product_name" id="product_name" class="form-select product_name select2 w-100">
                            {!! $pro_group !!}
                        </select>
                    </div>

                    <!-- From Date -->
                    <div class="col-md-4">
                        <label for="start_date" class="form-label">From Date</label>
                        <input type="text" class="form-control start_date1" id="start_date" name="start_date" required>
                    </div>

                    <!-- To Date -->
                    <div class="col-md-4">
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
                    <tr class="sticky-row">
                        <th class="text-white sticky-col" style="background: #c54444;">Product/Yr_Mon/Qty</th>
                        <?php $displayedMonths = array(); ?>
                        <?php foreach ($product_summary as $value): ?>
                        <?php    $monthYear = $value->yr_month; ?>
                        <?php    if (!in_array($monthYear, $displayedMonths)): ?>
                        <th class="text-white" style="background: #47647c;"><?= $monthYear; ?></th>
                        <?php        $displayedMonths[] = $monthYear; ?>
                        <?php    endif; ?>
                        <?php endforeach; ?>
                        <th class="text-white" style="background: #47647c;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                $prd_types = array_unique(array_column($product_summary, 'product'));
    $column_totals = array_fill(0, count($displayedMonths), 0);
                ?>
                    <?php foreach ($prd_types as $prd_type):
        $row_value_total = 0;
                ?>
                    <tr>
                        <td class="sticky-col"><?= $prd_type ?></td>
                        <?php    foreach ($displayedMonths as $index => $month):
            $act_qty = 0;
            foreach ($product_summary as $value) {
                if ($value->product == $prd_type && $value->yr_month == $month) {
                    $act_qty = $value->qty;
                    break;
                }
            }
            $row_value_total += $act_qty;
            $column_totals[$index] += $act_qty;
                        ?>
                        <td style=" text-align: center;"><?= $act_qty ?></td>
                        <?php    endforeach; ?>
                        <td style=" text-align: center;"><?= $row_value_total ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot style="background: #ffc0cb;">
                    <tr>
                        <td style="font-weight: bold; text-align: center;">Total</td>
                        <?php 
                    $grand_value_total = 0;
    foreach ($column_totals as $total) {
        $grand_value_total += $total;
                    ?>
                        <td style="font-weight: bold;  text-align: center;"><?= $total ?></td>
                        <?php } ?>
                        <td style="font-weight: bold;  text-align: center;"><?= $grand_value_total ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <!-- value based -->
    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table2" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <tr class="sticky-row" style="background:#7abaf2;">
                        <th class="text-white sticky-col" style="background: #c54444;">Yr_Month</th>
                        <?php $displayedMonths = array(); ?>
                        <?php foreach ($value_summary as $value): ?>
                        <?php    $monthYear = $value->yr_month ?>
                        <?php    if (!in_array($monthYear, $displayedMonths)): ?>
                        <th colspan="2" class="text-white text-center" style="background: #47647c;"><?= $monthYear; ?></th>
                        <?php        $displayedMonths[] = $monthYear; ?>
                        <?php    endif; ?>
                        <?php endforeach; ?>
                        <th colspan="2" class="text-white" style="background: #47647c;">Total</th>
                    </tr>
                    <tr class="sticky-row2" style="background:#e9efef;">
                        <th class="sticky-col text-center text-white bg-secondary">Product Name</th>
                        <?php foreach ($displayedMonths as $month): ?>
                        <th class="text-center text-white bg-secondary">Qty</th>
                        <th class="text-center text-white bg-secondary">Value (Rs)</th>
                        <?php endforeach; ?>
                        <th class="text-center text-white bg-secondary">Qty</th>
                        <th class="text-center text-white bg-secondary">Value (Rs)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
            $prd_types = array_unique(array_column($value_summary, 'product'));
    $column_totals = array_fill(0, count($displayedMonths), ['qty' => 0, 'value' => 0]);
            ?>
                    <?php foreach ($prd_types as $prd_type) {
        $row_qty_total = 0;
        $row_value_total = 0;
            ?>
                    <tr>
                        <td class="sticky-col prd_type"><?= $prd_type ?></td>
                        <?php 
                    foreach ($displayedMonths as $index => $month) {
            $qty = 0;
            $act_qty = 0;

            foreach ($value_summary as $value) {
                if ($value->product == $prd_type && $value->yr_month == $month) {
                    $qty = $value->p_qty;
                    $act_qty = $value->value;
                    break;
                }
            }

            $row_qty_total += $qty;
            $row_value_total += $act_qty;
            $column_totals[$index]['qty'] += $qty;
            $column_totals[$index]['value'] += $act_qty;
                    ?>
                        <td><?= $qty ?></td>
                        <td><?= $act_qty ?></td>
                        <?php    } ?>
                        <td><?= $row_qty_total ?></td>
                        <td><?= $row_value_total ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
                <tfoot style="background:#ffc0cb;">
                    <tr>
                        <td style="font-weight:bold;">Total</td>
                        <?php 
                $grand_qty_total = 0;
    $grand_value_total = 0;
    foreach ($column_totals as $totals) {
        $grand_qty_total += $totals['qty'];
        $grand_value_total += $totals['value'];
                ?>
                        <td style="font-weight:bold;"><?= $totals['qty'] ?></td>
                        <td style="font-weight:bold;"><?= $totals['value'] ?></td>
                        <?php } ?>
                        <td style="font-weight:bold;"><?= $grand_qty_total ?></td>
                        <td style="font-weight:bold;"><?= $grand_value_total ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <!-- Scripts-->
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
                    { extend: 'excelHtml5', title: "Product_Consumption_Report", exportOptions: { columns: ':visible' } }
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
                    { extend: 'excelHtml5', title: "Product_Consumption_Report", exportOptions: { columns: ':visible' } }
                ]
            });
        });

        $(document).ready(function () {

            var productName = "{{ request('product_name') }}";
            var startDate = "{{ request('start_date') }}";
            var endDate = "{{ request('end_date') }}";

            $('.product_name').select2();
            $('#product_name').val(productName).trigger('change');

            $('#start_date').val(startDate);
            $('#end_date').val(endDate);

        });

    </script>

@endpush