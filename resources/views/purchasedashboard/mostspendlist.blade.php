@extends('layouts.header')
@section('content')

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
                <a href="purchaseproductmostspend" class="btn btn-dark w-100">Most Spend Value Products</a>
            </div>
            <div class="col-12 col-md-3">
                <a href="rejectedpuritems" class="btn btn-outline-danger w-100">Rejected Products List</a>
            </div>
            <div class="col-12 col-md-3">
                <a href="consumptionquantityreport" class="btn btn-outline-warning w-100">Consumption Quantity Report</a>
            </div>

            <div class="col-12 col-md-3">
                <a href="movementreport" class="btn btn-outline-primary w-100">Movement Report</a>
            </div>

            <div class="col-12 col-md-3">
                <a href="purchase-supplier-summary" class="btn btn-outline-info w-100">New Supplier Report</a>
            </div>

            <div class="col-12 col-md-3">
                <a href="purchase-product-summary" class="btn btn-outline-secondary w-100">New Purchase Product</a>
            </div>

            <div class="col-12 col-md-3">
                <a href="productissuedelay" class="btn btn-outline-primary w-100">Pack Material Delay Issue Report</a>
            </div>

        </div>
    </div>
    <!--date machine work hrs popup-->

    <div class="modal fade" id="ProductModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl"> <!-- modal-xl replaces custom width -->
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Product Wise Most Spend Value Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="closeButton"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="mb-3 text-center">
                        <h5>Category: <b><span id="prd_type"></span></b></h5>
                    </div>

                    <div style="max-height: 450px; overflow-y: auto;">
                        <div id="analyzetable"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!--end-->

    <!-- drop down -->
    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="container-fluid">
            <form action="{{ url('purchaseproductmostspend') }}" method="get" id="searchForm">
                <div class="row g-3 align-items-center mb-3">

                    <!-- Product Group -->
                    <div class="col-md-4">
                        <label for="product_group" class="form-label">Product Group</label>
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
                        <th colspan="1" class="text-center text-white" style="background: #c54444;">Yr_Month</th>
                        <?php $displayedMonths = array(); ?>
                        <?php foreach ($product_summary as $value): ?>
                        <?php    $monthYear = $value->yr_month ?>
                        <?php    if (!in_array($monthYear, $displayedMonths)): ?>
                        <th colspan="2" class="text-center text-white" style="background: #47647c;"><?= $monthYear; ?></th>
                        <?php        $displayedMonths[] = $monthYear; ?>
                        <?php    endif; ?>
                        <?php endforeach; ?>
                        <th colspan="2" class="text-center text-white" style="background: #47647c;">Total</th>
                    </tr>
                    <tr class="sticky-row2">
                        <th class="text-center text-white bg-secondary">Product Category</th>
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
            $prd_types = array_unique(array_column($product_summary, 'category_name'));
    $column_totals = array_fill(0, count($displayedMonths), ['qty' => 0, 'value' => 0]);
            ?>
                    <?php foreach ($prd_types as $prd_type) {
        $row_qty_total = 0;
        $row_value_total = 0;
            ?>
                    <tr>
                        <td style="text-decoration: underline;cursor: pointer;" class="sticky-col prd_type"><?= $prd_type ?>
                        </td>
                        <?php 
                    foreach ($displayedMonths as $index => $month) {
            $qty = 0;
            $act_qty = 0;

            foreach ($product_summary as $value) {
                if ($value->category_name == $prd_type && $value->yr_month == $month) {
                    $qty = $value->p_qty;
                    $act_qty = $value->subtotal;
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
                <tfoot>
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
                    { extend: 'excelHtml5', title: "Top_rating_qty&value", exportOptions: { columns: ':visible' } }
                ]
            });
        });


        $(document).ready(function () {
            $('.prd_type').click(function () {
                // Show the modal
                $('#ProductModal').modal('show');

                // Log the HTML of the clicked row
                var row = $(this).closest('tr');

                // Fetch the product type from the correct column
                var prd_type = row.find('.sticky-col.prd_type').text().trim();

                if (prd_type) {
                    // Placeholder dates - replace with actual data
                    var startDate = "{{ request('start_date') }}";
                    var endDate = "{{ request('end_date') }}";
                    var pro_grp = "{{ request('product_name') }}";
                    $('#prd_type').text(prd_type);

                    // Build the query string
                    var params = $.param({
                        start_date1: startDate,
                        end_date1: endDate,
                        prd_type: prd_type,
                        pro_grp: pro_grp
                    });

                    // Build the full URL for the AJAX request
                    var url = "{{ route('mostvalueproduct') }}" + "?" + params;

                    // AJAX request to fetch data
                    $.get(url, function (data) {
                        // Update HTML content with received data
                        $('#analyzetable').html(data);
                    });
                } else {
                    console.log("Type not found.");
                }
            });

            // Close modal button to close the sidebar
            $('#closeButton').click(function () {
                $('#ProductModal').modal('hide');
            });

            // Clear modal content when it is hidden
            $('#ProductModal').on('hidden.bs.modal', function () {
                $('#analyzetable').html('');
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