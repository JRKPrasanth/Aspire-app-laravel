@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Purchase Order Pending Qty Report</h3>
    @include('layouts.breadcrumb')


    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body p-4">
            <form action="{{ url('salesreturnsummaryreport') }}" method="get" id="searchForm">
                @csrf
                <div class="row g-4 mb-3">
                    <div class="col-md-2"></div>
                    <div class="col-md-4">
                        <label for="start_date" class="form-label fw-semibold">From Date</label>
                        <input type="text" class="form-control start_date1" id="start_date" name="start_date" required
                            autocomplete="off">
                        <div class="invalid-feedback">Please select a start date.</div>
                    </div>

                    <div class="col-md-4">
                        <label for="end_date" class="form-label fw-semibold">To Date</label>
                        <input type="text" class="form-control end_date1" id="end_date" name="end_date" required
                            autocomplete="off">
                        <div class="invalid-feedback">Please select an end date.</div>
                    </div>
                </div>

                <!-- Second Row: Centered Search Button -->
                <div class="row">
                    <div class="col-md-12 text-center mt-2">
                        <button type="submit" class="btn btn-primary px-4 report_search" id="report_search">
                            <i class="bi bi-search-heart me-1"></i> Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!--Sales vs Return Order Summary Report -->
    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="table-responsive" style="overflow-x: auto;">
            <?php if (empty($prim_summary)) { ?>
            <p class="nodata text-danger">No records available.</p>
            <?php } else { ?>
            <table id="Table1" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <tr>
                        <th colspan="<?= 1 + (count(array_unique(array_column($prim_summary, 'log_month'))) * 3) + 3 ?>"
                            class="text-white bg-danger text-center">
                            Sales vs Return Order Summary Report
                        </th>
                    </tr>
                    <tr>
                        <th class="text-center text-white bg-secondary">Particulars</th>
                        <?php
        $displayedMonths = [];
        foreach ($prim_summary as $value) {
            $monthYear = $value->log_month;
            if (!in_array($monthYear, $displayedMonths)) {
                $displayedMonths[] = $monthYear;
                echo '<th colspan="3" class="text-center text-white bg-secondary">' . $monthYear . '</th>';
            }
        }
                            ?>
                        <th colspan="3" class="text-center text-white bg-secondary">Total</th>
                    </tr>
                    <tr>
                        <th class="text-center text-white bg-warning">Distributor</th>
                        <?php    foreach ($displayedMonths as $month) { ?>
                        <th class="text-center text-white bg-danger">Sales</th>
                        <th class="text-center text-white bg-danger">Return</th>
                        <th class="text-center text-white bg-danger">Return %</th>
                        <?php    } ?>
                        <th class="text-center text-white bg-success">Total Sales</th>
                        <th class="text-center text-white bg-success">Total Return</th>
                        <th class="text-center text-white bg-success">Total Return %</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
        $channels = array_unique(array_column($prim_summary, 'cus_name'));
        $grandTotalQty = $grandTotalReturn = $grandTotalrtnper = 0;
        $columnTotals = array_fill(0, count($displayedMonths) * 3, 0);

        foreach ($channels as $channel) {
            echo '<tr>';
            echo '<td class="sticky-col">' . $channel . '</td>';

            $totalQty = $totalReturn = $totalrtnper = 0;
            $colIndex = 0;

            foreach ($displayedMonths as $month) {
                $foundData = false;

                foreach ($prim_summary as $value) {
                    if ($value->cus_name === $channel && $value->log_month === $month) {
                        echo '<td>' . $value->sales . '</td>';
                        echo '<td>' . $value->rtn . '</td>';
                        echo '<td>' . $value->rtnper . '</td>';

                        $totalQty += $value->sales;
                        $totalReturn += $value->rtn;
                        $totalrtnper += $value->rtnper;

                        $columnTotals[$colIndex] += $value->sales;
                        $columnTotals[$colIndex + 1] += $value->rtn;
                        $columnTotals[$colIndex + 2] += $value->rtnper;

                        $foundData = true;
                        break;
                    }
                }

                if (!$foundData) {
                    echo '<td>-</td><td>-</td><td>-</td>';
                }

                $colIndex += 3;
            }

            echo '<td>' . $totalQty . '</td>';
            echo '<td>' . $totalReturn . '</td>';
            echo '<td>' . $totalrtnper . '</td>';
            echo '</tr>';
        }

        // Grand total row
        echo '<tr>';
        echo '<td class="sticky-col font-weight-bold">Grand Total</td>';
        $colIndex = 0;
        foreach ($columnTotals as $total) {
            if ($colIndex % 3 == 0)
                $grandTotalQty += $total;
            if ($colIndex % 3 == 1)
                $grandTotalReturn += $total;
            if ($colIndex % 3 == 2)
                $grandTotalrtnper += $total;

            echo '<td>' . $total . '</td>';
            $colIndex++;
        }
        echo '<td>' . $grandTotalQty . '</td>';
        echo '<td>' . $grandTotalReturn . '</td>';
        echo '<td>' . $grandTotalrtnper . '</td>';
        echo '</tr>';
                        ?>
                </tbody>
            </table>
            <?php } ?>
        </div>
    </div>

    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="table-responsive" style="overflow-x: auto;">
            <?php if (empty($type_summary)) { ?>
            <p class="nodata text-danger">No records available.</p>
            <?php } else { ?>
            <table id="Table2" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <tr>
                        <th colspan="<?= 1 + (count(array_unique(array_column($type_summary, 'log_month'))) * 3) + 3 ?>"
                            class="text-white bg-danger text-center">
                            Sales vs Return Qty Summary Report
                        </th>
                    </tr>
                    <tr>
                        <th class="text-center text-white bg-secondary">Particulars</th>
                        <?php
        $displayedMonths = [];
        foreach ($type_summary as $value) {
            $monthYear = $value->log_month;
            if (!in_array($monthYear, $displayedMonths)) {
                $displayedMonths[] = $monthYear;
                echo '<th colspan="3" class="text-center text-white bg-secondary">' . $monthYear . '</th>';
            }
        }
                            ?>
                        <th colspan="3" class="text-center text-white bg-secondary">Total</th>
                    </tr>
                    <tr>
                        <th class="text-center text-white bg-warning">Distributor</th>
                        <?php    foreach ($displayedMonths as $month) { ?>
                        <th class="text-center text-white bg-danger">Sales</th>
                        <th class="text-center text-white bg-danger">Return</th>
                        <th class="text-center text-white bg-danger">Return %</th>
                        <?php    } ?>
                        <th class="text-center text-white bg-success">Total Sales</th>
                        <th class="text-center text-white bg-success">Total Return</th>
                        <th class="text-center text-white bg-success">Total Return %</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
        $channels = array_unique(array_column($type_summary, 'cus_name'));
        $grandTotalQty = $grandTotalReturn = $grandTotalrtnper = 0;
        $columnTotals = array_fill(0, count($displayedMonths) * 3, 0);

        foreach ($channels as $channel) {
            echo '<tr>';
            echo '<td class="sticky-col">' . $channel . '</td>';

            $totalQty = $totalReturn = $totalrtnper = 0;
            $colIndex = 0;

            foreach ($displayedMonths as $month) {
                $foundData = false;

                foreach ($type_summary as $value) {
                    if ($value->cus_name === $channel && $value->log_month === $month) {
                        echo '<td>' . $value->sales . '</td>';
                        echo '<td>' . $value->rtn . '</td>';
                        echo '<td>' . $value->rtnper . '</td>';

                        $totalQty += $value->sales;
                        $totalReturn += $value->rtn;
                        $totalrtnper += $value->rtnper;

                        $columnTotals[$colIndex] += $value->sales;
                        $columnTotals[$colIndex + 1] += $value->rtn;
                        $columnTotals[$colIndex + 2] += $value->rtnper;

                        $foundData = true;
                        break;
                    }
                }

                if (!$foundData) {
                    echo '<td>-</td><td>-</td><td>-</td>';
                }

                $colIndex += 3;
            }

            echo '<td>' . $totalQty . '</td>';
            echo '<td>' . $totalReturn . '</td>';
            echo '<td>' . $totalrtnper . '</td>';
            echo '</tr>';
        }

        // Grand total row
        echo '<tr>';
        echo '<td class="sticky-col font-weight-bold">Grand Total</td>';
        $colIndex = 0;
        foreach ($columnTotals as $total) {
            if ($colIndex % 3 == 0)
                $grandTotalQty += $total;
            if ($colIndex % 3 == 1)
                $grandTotalReturn += $total;
            if ($colIndex % 3 == 2)
                $grandTotalrtnper += $total;

            echo '<td>' . $total . '</td>';
            $colIndex++;
        }
        echo '<td>' . $grandTotalQty . '</td>';
        echo '<td>' . $grandTotalReturn . '</td>';
        echo '<td>' . $grandTotalrtnper . '</td>';
        echo '</tr>';
                        ?>
                </tbody>
            </table>
            <?php } ?>
        </div>
    </div>

    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="table-responsive" style="overflow-x: auto;">
            <?php if (empty($value_summary)) { ?>
            <p class="nodata text-danger">No records available.</p>
            <?php } else { ?>
            <table id="Table3" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <tr>
                        <th colspan="<?= 1 + (count(array_unique(array_column($value_summary, 'log_month'))) * 3) + 3 ?>"
                            class="text-white bg-danger text-center">
                            Sales vs Return Value Summary Report
                        </th>
                    </tr>
                    <tr>
                        <th class="text-center text-white bg-secondary">Particulars</th>
                        <?php
        $displayedMonths = [];
        foreach ($value_summary as $value) {
            $monthYear = $value->log_month;
            if (!in_array($monthYear, $displayedMonths)) {
                $displayedMonths[] = $monthYear;
                echo '<th colspan="3" class="text-center text-white bg-secondary">' . $monthYear . '</th>';
            }
        }
                            ?>
                        <th colspan="3" class="text-center text-white bg-secondary">Total</th>
                    </tr>
                    <tr>
                        <th class="text-center text-white bg-warning">Distributor</th>
                        <?php    foreach ($displayedMonths as $month) { ?>
                        <th class="text-center text-white bg-danger">Sales</th>
                        <th class="text-center text-white bg-danger">Return</th>
                        <th class="text-center text-white bg-danger">Return %</th>
                        <?php    } ?>
                        <th class="text-center text-white bg-success">Total Sales</th>
                        <th class="text-center text-white bg-success">Total Return</th>
                        <th class="text-center text-white bg-success">Total Return %</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
        $channels = array_unique(array_column($value_summary, 'cus_name'));
        $grandTotalQty = $grandTotalReturn = $grandTotalrtnper = 0;
        $columnTotals = array_fill(0, count($displayedMonths) * 3, 0);

        foreach ($channels as $channel) {
            echo '<tr>';
            echo '<td class="sticky-col">' . $channel . '</td>';

            $totalQty = $totalReturn = $totalrtnper = 0;
            $colIndex = 0;

            foreach ($displayedMonths as $month) {
                $foundData = false;

                foreach ($value_summary as $value) {
                    if ($value->cus_name === $channel && $value->log_month === $month) {
                        echo '<td>' . $value->sales . '</td>';
                        echo '<td>' . $value->rtn . '</td>';
                        echo '<td>' . $value->rtnper . '</td>';

                        $totalQty += $value->sales;
                        $totalReturn += $value->rtn;
                        $totalrtnper += $value->rtnper;

                        $columnTotals[$colIndex] += $value->sales;
                        $columnTotals[$colIndex + 1] += $value->rtn;
                        $columnTotals[$colIndex + 2] += $value->rtnper;

                        $foundData = true;
                        break;
                    }
                }

                if (!$foundData) {
                    echo '<td>-</td><td>-</td><td>-</td>';
                }

                $colIndex += 3;
            }

            echo '<td>' . $totalQty . '</td>';
            echo '<td>' . $totalReturn . '</td>';
            echo '<td>' . $totalrtnper . '</td>';
            echo '</tr>';
        }

        // Grand total row
        echo '<tr>';
        echo '<td class="sticky-col font-weight-bold">Grand Total</td>';
        $colIndex = 0;
        foreach ($columnTotals as $total) {
            if ($colIndex % 3 == 0)
                $grandTotalQty += $total;
            if ($colIndex % 3 == 1)
                $grandTotalReturn += $total;
            if ($colIndex % 3 == 2)
                $grandTotalrtnper += $total;

            echo '<td>' . $total . '</td>';
            $colIndex++;
        }
        echo '<td>' . $grandTotalQty . '</td>';
        echo '<td>' . $grandTotalReturn . '</td>';
        echo '<td>' . $grandTotalrtnper . '</td>';
        echo '</tr>';
                        ?>
                </tbody>
            </table>
            <?php } ?>
        </div>
    </div>

@endsection
@push('scripts')

    <script>


        $(document).ready(function () {


            $('#Table1').DataTable({

            });

            $('#Table2').DataTable({

            });

            $('#Table3').DataTable({

            });



            var startDate = "{{ request('start_date') }}";
            var endDate = "{{ request('end_date') }}";

            $('#start_date').val(startDate);
            $('#end_date').val(endDate);

        });


    </script>

@endpush