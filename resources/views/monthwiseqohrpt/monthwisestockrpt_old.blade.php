@extends('layouts.header')
@section('content')

<h3 class="text-danger"> Monthly Stock Report  </h3>
@include('layouts.breadcrumb')


<form action="{{ url('monthwiseqohrpt') }}" method="get" id="searchForm" class="mb-4">
    <div class="card shadow border-0 rounded-4">


        <div class="card-body">
            <div class="row g-4 align-items-end">

                <!-- Product Group -->
                <div class="col-md-4">
                    <label for="product_group_id" class="form-label fw-semibold">Product Group</label>
                    <div class="input-group">
                        <select name="product_group_id" id="product_group_id" class="form-select select2">
                            {!! $product_group_id !!}
                        </select>
                    </div>
                </div>

                <!-- Start Date -->
                <div class="col-md-4">
                    <label for="start_date" class="form-label fw-semibold">Start Date</label>
                    <div class="input-group">
         
                        <input type="text" class="form-control start_date1" id="start_date" name="start_date"
                               value="{{ request('start_date') }}" required autocomplete="off">
                    </div>
                </div>

                <!-- End Date -->
                <div class="col-md-4">
                    <label for="end_date" class="form-label fw-semibold">End Date</label>
                    <div class="input-group">
               
                        <input type="text" class="form-control end_date1" id="end_date" name="end_date"
                               value="{{ request('end_date') }}" required autocomplete="off">
                    </div>
                </div>

            </div>

            <!-- Search Button -->
            <div class="row mt-4">
                <div class="col text-center">
                    <button type="submit" class="btn btn-primary px-5" id="searchButton">
                        <i class="bi bi-search me-1"></i> Search
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>


<!--tables-->

<div class="card shadow-lg rounded-4 border-0 p-4">
 <div class="table-responsive" style="overflow-x: auto;">
            <?php if (empty($prim_summary)) { ?>
                <p class="nodata">No records available.</p>
            <?php } else { ?>
        <table id="Table1" class="table table-bordered table-striped table-hover w-100">
    <thead>
        <tr>
            <th colspan="2" class="bg-danger text-white text-center">Particulars</th>
            <?php $displayedMonths = []; ?>
            <?php foreach ($prim_summary as $value) {
                $monthYear = $value->log_month;
                if (!in_array($monthYear, $displayedMonths)) {
                    $displayedMonths[] = $monthYear; ?>
                    <th colspan="3" class="bg-danger text-white text-center"><?php echo $monthYear; ?></th>
            <?php }} ?>
            <th colspan="3" class="bg-danger text-white text-center">Total</th>
        </tr>
        <tr>
            <th class="sticky-col bg-secondary text-white text-center">Product</th>
            <th class="sticky-col1 bg-secondary text-white text-center">Batch / Locator / Inv</th>
            <?php foreach ($displayedMonths as $month) { ?>
                <th class="bg-success text-white text-center">Qty</th>
                <th class="bg-success text-white text-center">Cost</th>
                <th class="bg-success text-white text-center">Value</th>
            <?php } ?>
            <th class="bg-secondary text-white text-center">Total Qty</th>
            <th class="bg-secondary text-white text-center">Total Cost</th>
            <th class="bg-secondary text-white text-center">Total Value</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        // Group by batch_no + locator_id + subinventory_id
        $grouped = [];
        foreach ($prim_summary as $item) {
            $key = $item->batch_no . '|' . $item->locator_id . '|' . $item->subinventory_id;
            $grouped[$key]['items'][] = $item;
            $grouped[$key]['product_name'] = $item->product_name;
            $grouped[$key]['batch_no'] = $item->batch_no;
            $grouped[$key]['locator_id'] = $item->locator_id;
            $grouped[$key]['subinventory_id'] = $item->subinventory_id;
        }

        $columnTotals = array_fill(0, count($displayedMonths) * 3, 0);
        ?>

        <?php foreach ($grouped as $group) { ?>
            <?php 
                $product = $group['product_name'];
                $batch_no = $group['batch_no'];
                $locator_id = $group['locator_id'];
                $subinventory_id = $group['subinventory_id'];

                $totalQty = $totalCost = $totalValue = 0;
                $colIndex = 0;
            ?>
            <tr>
                <td class="sticky-col"><?= $product ?></td>
                <td class="sticky-col1">
                    <?= $batch_no ?><br>
                    <small><b>Locator:</b> <?= $locator_id ?> | <b>Inv:</b> <?= $subinventory_id ?></small>
                </td>

                <?php foreach ($displayedMonths as $month) { ?>
                    <?php 
                        $match = collect($group['items'])->first(function($item) use ($month) {
                            return $item->log_month == $month;
                        });
                    ?>
                    <?php if ($match): ?>
                        <td><?= $match->quantity ?></td>
                        <td><?= $match->cost ?></td>
                        <td ROL RM Report><?= number_format($match->value, 2) ?></td>
                        <?php 
                            $totalQty += $match->quantity;
                            $totalCost += $match->cost;
                            $totalValue += $match->value;

                            $columnTotals[$colIndex]     += $match->quantity;
                            $columnTotals[$colIndex + 1] += $match->cost;
                            $columnTotals[$colIndex + 2] += $match->value;
                        ?>
                    <?php else: ?>
                        <td>-</td>
                        <td>-</td>
                        <td ROL RM Report>-</td>
                    <?php endif; ?>
                    <?php $colIndex += 3; ?>
                <?php } ?>

                <td><?= $totalQty ?></td>
                <td><?= $totalCost ?></td>
                <td><?= number_format($totalValue, 2) ?></td>
            </tr>
        <?php } ?>
    </tbody>
    <tfoot class="fw-bold table-danger">
        <!-- Grand Total Row -->
        <tr>
            <td>Grand Total</td>
            <td></td>
            <?php 
                $grandQty = $grandCost = $grandValue = 0;
                $colIndex = 0;
                foreach ($columnTotals as $key => $total) {
                    if ($key % 3 == 0) {
                        $grandQty += $total;
                        echo "<td>{$total}</td>";
                    }
                    if ($key % 3 == 1) {
                        $grandCost += $total;
                        echo "<td>{$total}</td>";
                    }
                    if ($key % 3 == 2) {
                        $grandValue += $total;
                        echo "<td>" . number_format($total, 2) . "</td>";
                    }
                }
            ?>
            <td ><?= $grandQty ?></td>
            <td ><?= $grandCost ?></td>
            <td ><?= number_format($grandValue, 2) ?></td>
        </tr>
    </tfoot>
        </table>
          <?php } ?>
    </div>
</div>

@endsection
@push('scripts')
			
<script>

        $(document).on('click','.reproduct_name',function()
        {
  
        $(".product_group_id").jCombo("{{ URL::to('jcomboform?table=m_product_groups_t:product_group_id:group_name') }}&order_by=group_name asc",
        {selected_value:""});
        });

	
     // tables
    $(document).ready(function() {
        $('#Table1').DataTable({
        });
    });

  
$(document).ready(function() {
    var proName = "{{ request('product_group_id') }}";
    $('.product_group_id').select2();
    $('#product_group_id').val(proName).trigger('change');


});  

</script>

@endpush