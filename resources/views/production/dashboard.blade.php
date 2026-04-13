@extends('layouts.header')
@section('content')

<style>
/* --- Presentation Dashboard Mode --- */

/* Section Title Bar (Excel Style) */
.card-header {
    font-size: 17px !important;
    padding: 10px 15px !important;
}

/* Reduce vertical gaps between sections */
.card {
    margin-bottom: 18px !important;
}

/* Softer card corners & shadow */
.card {
    border-radius: 8px !important;
    box-shadow: 0px 2px 6px rgba(0,0,0,0.08) !important;
}

/* Table Header presentation style */
.table thead th {
    background: #1b4f72 !important;
    color: #fff !important;
    font-weight: bold !important;
    text-align: center;
    vertical-align: middle !important;
}

/* Table body look */
.table tbody td {
    background: #fdfefe !important;
    padding: 6px 10px !important;
}

/* Reduce space between different chart cards */
.chart-card {
    margin-bottom: 12px !important;
}

/* Chart card title alignment */
.chart-title {
    font-size: 16px;
    font-weight: bold;
    text-align: left;
    margin-bottom: 5px;
}

/* Fix chart card padding */
.card-body {
    padding: 12px !important;
}

/* Improve filter row spacing */
form .form-label {
    font-weight: 600;
}
</style>


<h3 class="text-danger">Monthly Summary Report</h3>

<div class="container-fluid mt-3">
<div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
    <!-- ======================= DATE RANGE ======================= -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-2"></div>
        <div class="col-md-3">
            <label class="form-label">Start Date</label>
            <input type="date" name="start_date" value="{{ $start_date }}" class="form-control">
        </div>

        <div class="col-md-3">
            <label class="form-label">End Date</label>
            <input type="date" name="end_date" value="{{ $end_date }}" class="form-control">
        </div>

        <div class="col-md-2 align-self-end">
            <button class="btn btn-primary w-100">Filter</button>
        </div>
    </form>
    </div>
       </div>
    <!-- ===================== 1. SUMMARY PRODUCTION ===================== -->
    <div class="card mb-4 border-primary shadow-sm">
        <div class="card-header bg-primary text-white fw-bold">
            Production Summary
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Product Class</th>
                            <th>Total Batches Taken</th>
                            <th>Total Production Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($summary as $row)
                        <tr>
                            <td>{{ $row->product_classification }}</td>
                            <td>{{ $row->tot_bch_tkn }}</td>
                            <td>{{ $row->tot_prdn_qty }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<!-- =====================2. MONTHLY PRODUCTION CHARTS ===================== -->
<h3 class="fw-bold mb-3">Monthly Production Charts by Product Classification</h3>

@php
// Prepare chart data for JS
$chartDataJS = [];
foreach ($chart as $row) {
    $chartDataJS[] = [
        'product_classification' => $row->product_classification,
        'month' => $row->month,
        'tot_prdn_qty' => (int)$row->tot_prdn_qty,
        'canvas_id' => preg_replace('/[^A-Za-z0-9]/', '-', $row->product_classification)
    ];
}

// Unique product classifications
$classifications = [];
foreach ($chartDataJS as $row) {
    if (!in_array($row['product_classification'], $classifications)) {
        $classifications[] = $row['product_classification'];
    }
}

// Unique months
$months = [];
foreach ($chartDataJS as $row) {
    if (!in_array($row['month'], $months)) {
        $months[] = $row['month'];
    }

}

// Sort months chronologically. We prefix "01-" to parse as a real date, e.g. "01-Jan-24"
usort($months, function($a, $b) {
    $ta = strtotime('01-' . $a);
    $tb = strtotime('01-' . $b);
    if ($ta === $tb) return 0;
    return ($ta < $tb) ? -1 : 1;
});

// Colors for cards
$colors = ['success','primary','warning','danger','info','secondary'];
@endphp

@php
    // Group classifications into pairs
    $classPairs = array_chunk($classifications, 2);
@endphp

@foreach ($classPairs as $pair)
<div class="row mb-3">

    @foreach ($pair as $cls)
        @php
            $canvas_id = preg_replace('/[^A-Za-z0-9]/', '-', $cls);
        @endphp

        <div class="col-md-6">
            <div class="card chart-card shadow-sm">
                <div class="card-header bg-primary text-white fw-bold">
                    {{ $cls }}
                </div>
                <div class="card-body">
                    <canvas id="{{ $canvas_id }}" height="120"></canvas>
                </div>
            </div>
        </div>
    @endforeach

</div>
@endforeach



    <!-- ===================== 3. MATERIAL ISSUE DELAY ===================== -->
    <div class="card mb-4 border-danger shadow-sm">
        <div class="card-header bg-danger text-white fw-bold">
            Material Issue Delays
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Parent Product</th>
                            <th>No. of Products Issued in Delay</th>
                            <th>Last Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mtrl_delay as $row)
                        <tr>
                            <td>{{ $row->prnt_prd }}</td>
                            <td 
                            data-bs-toggle="tooltip"
                            data-bs-html="true"
                            title="
                                @php 
                                    $subs = explode('||', $row->sub_products);
                                    foreach ($subs as $s) { echo $s . '<br>'; }
                                @endphp
                            "
                            >
                            {{ $row->no_of_prods }}
                            </td>

                            <td>{{ $row->updated_at }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ===================== 4. BREAKDOWN MAINTENANCE ===================== -->
    <div class="card mb-4 border-warning shadow-sm">
        <div class="card-header bg-warning text-dark fw-bold">
            Breakdown Maintenance
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Machine</th>
                            <th>Issue</th>
                            <th>Corrective Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($breakdown as $b)
                        <tr>
                            <td>{{ $b->sno }}</td>
                            <td>{{ $b->machine_name }}</td>
                            <td>{{ $b->issue_causes }}</td>
                            <td>{{ $b->corrective_action }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ===================== 5. UNDER PROCESS PRODUCTS ===================== -->
    <div class="card mb-4 border-info shadow-sm">
        <div class="card-header bg-info text-blue fw-bold">
            Under Process Products
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Job Type</th>
                            <th>Full Product</th>
                            <th>Batch Size</th>
                            <th>Production Date</th>
                            <th>QC Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($under_process as $u)
                        <tr>
                            <td>{{ $u->sno }}</td>
                            <td>{{ $u->prd_name }}</td>
                            <td>{{ $u->job_type }}</td>
                            <td>{{ $u->concatenated_product }}</td>
                            <td>{{ $u->btch_size }}</td>
                            <td>{{ $u->prdn_date }}</td>
                            <td>{{ $u->qc_date }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ===================== 6. OPRN BDR ===================== -->
    <div class="card mb-4 border-success shadow-sm">
        <div class="card-header bg-success text-white fw-bold">
            Operation BDR more than 7 Days
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Qty</th>
                            <th>Open BDR Days</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($oprn_bdr as $o)
                        <tr>
                            <td>{{ $o->concatenated_product }}</td>
                            <td>{{ $o->strt_date }}</td>
                            <td>{{ $o->end_date }}</td>
                            <td>{{ $o->qty }}</td>
                            <td class="fw-bold text-danger">{{ $o->opn_bdr }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ===================== 7. TOTAL BDR (QC → FINAL) ===================== -->
    <div class="card mb-4 border-success shadow-sm">
        <div class="card-header bg-success text-white fw-bold">
            Total BDR more than 12 Days
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Batch No</th>
                            <th>Qty</th>
                            <th>Prdn Date</th>
                            <th>End Date</th>
                            <th>Total BDR</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tot_bdr as $t)
                        <tr>
                            <td>{{ $t->prd_name }}</td>
                            <td>{{ $t->batch_no }}</td>
                            <td>{{ $t->qty }}</td>
                            <td>{{ $t->prdn_date }}</td>
                            <td>{{ $t->end_date }}</td>
                            <td class="fw-bold text-danger">{{ $t->tot_bdr }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<!-- ===================== 8. MONTHLY OUTWARD CHART ===================== -->

@php
    // Prepare chart data for JS
    $chartData1JS = [];
    $classifications1 = [];
    $productsByClass = [];
    $months1 = [];

    foreach ($outwardchart as $row) {

        $chartData1JS[] = [
            'product_classification' => $row->product_classification,
            'product' => $row->concatenated_product,
            'month' => $row->month,
            'sales' => (int)$row->sales
        ];

        if (!in_array($row->product_classification, $classifications1)) {
            $classifications1[] = $row->product_classification;
        }

        $productsByClass[$row->product_classification][] = $row->concatenated_product;

        if (!in_array($row->month, $months1)) {
            $months1[] = $row->month;
        }
    }

    // Remove duplicates
    foreach ($productsByClass as $cls => $prods) {
        $productsByClass[$cls] = array_values(array_unique($prods));
    }

    // PHP 5–compatible month sorting
    usort($months1, function($a, $b) {
        return strtotime("01-" . $a) - strtotime("01-" . $b);
    });
@endphp


<h3 class="fw-bold mb-3">Monthly Outward Charts by Product</h3>

<!-- Product Classification Dropdown -->
<div class="row mb-3">
    <div class="col-md-4">
        <label class="fw-bold">Select Product Classification</label>
        <select id="select_classification" class="form-select">
            <option value="">-- Select Category --</option>
            @foreach ($classifications1 as $cls)
                <option value="{{ $cls }}">{{ $cls }}</option>
            @endforeach
        </select>
    </div>
</div>

<!-- Container where charts will be generated dynamically -->
<div id="outward_chart_container" class="row g-3"></div>


    <!-- ===================== 9. MONTH END REQUIREMENT ===================== -->
    <div class="card mb-4 border-success shadow-sm">
        <div class="card-header bg-success text-white fw-bold">
            Month End Requirement
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Classification</th>
                            <th>Product</th>
                            <th>Required Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($monthend as $m)
                        <tr>
                            <td>{{ $m->prd_class }}</td>
                            <td>{{ $m->prd_name }}</td>
                            <td>{{ $m->qty }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    

</div>

@endsection
@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script>
    var chartData = <?php echo json_encode($chartDataJS); ?>;

    // Collect unique months
     var months = <?php echo json_encode($months); ?>;
    
    chartData.forEach(function(r){
        if(months.indexOf(r.month) === -1) months.push(r.month);
    });

    // Collect unique classifications
    var classifications = [];
    chartData.forEach(function(r){
        if(classifications.indexOf(r.product_classification) === -1) classifications.push(r.product_classification);
    });

    // Multi-color palette (auto repeats)
    var colorPalette = [
        'rgba(255, 99, 132, 0.55)',
        'rgba(54, 162, 235, 0.55)',
        'rgba(255, 206, 86, 0.55)',
        'rgba(75, 192, 192, 0.55)',
        'rgba(153, 102, 255, 0.55)',
        'rgba(255, 159, 64, 0.55)',
        'rgba(0, 128, 255, 0.55)',
        'rgba(0, 200, 150, 0.55)',
        'rgba(200, 0, 200, 0.55)',
        'rgba(150, 150, 0, 0.55)'
    ];

    classifications.forEach(function(cls) {
        var canvasId = cls.replace(/[^A-Za-z0-9]/g, '-');

        // values for this product classification
        var dataForClass = months.map(function(m){
            var row = chartData.find(function(r){
                return r.product_classification === cls && r.month === m;
            });
            return row ? row.tot_prdn_qty : 0;
        });

        // generate color per bar
        var barColors = months.map(function(_, i){
            return colorPalette[i % colorPalette.length];
        });

        var ctx = document.getElementById(canvasId).getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: cls,
                    data: dataForClass,
                    backgroundColor: barColors,
                    borderColor: '#333',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                barPercentage: 0.55, // reduce bar width
                categoryPercentage: 0.55, // reduce spacing between bars
                scales: {
                    y: { beginAtZero: true },
                    x: {}
                },
                plugins: {
                    legend: { display: false },
                datalabels: {
                anchor: 'end',
                align: 'end',
                color: '#000',       // label color
                font: {
                    weight: 'bold',
                    size: 12
                },
                formatter: function(value) {
                    return value > 0 ? value : '';
                }
            }
        }
    },
    plugins: [ChartDataLabels]   // ⭐ Required
        }); 
    });
   

document.addEventListener("DOMContentLoaded", () => {

    const container = document.getElementById("outward_chart_container");

    document.getElementById("select_classification").addEventListener("change", function () {

        const selectedClass = this.value;

        container.innerHTML = ""; // Clear old charts

        if (selectedClass === "") return;

        // AJAX request
        fetch("{{ route('outward.byClassAjax') }}?classification=" + selectedClass +
              "&start_date={{ $start_date }}&end_date={{ $end_date }}")
        .then(res => res.json())
        .then(data => {

            let index = 0;

            Object.keys(data).forEach(product => {

                // Create column (2 per row)
                const col = document.createElement("div");
                col.className = "col-md-6";

                // Unique canvas id
                const canvasId = "chart_" + product.replace(/[^A-Za-z0-9]/g, "_");

                col.innerHTML = `
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white fw-bold">
                            ${product}
                        </div>
                        <div class="card-body">
                            <canvas id="${canvasId}" height="110"></canvas>
                        </div>
                    </div>
                `;

                container.appendChild(col);

                // Draw chart
                const ctx = document.getElementById(canvasId).getContext("2d");

                new Chart(ctx, {
                    type: "bar",
                    data: {
                        labels: data[product].months,
                        datasets: [{
                            label: product,
                            data: data[product].sales,
                            backgroundColor: data[product].sales.map(() =>
                                `hsl(${Math.random()*360}, 70%, 60%)`
                            ),
                            borderColor: "#333",
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: { beginAtZero: true }
                        },
                        plugins: {
                            legend: { display: false },
                
                            // ENABLE DATA LABELS
                            datalabels: {
                                anchor: 'end',
                                align: 'top',
                                color: '#000',
                                font: {
                                    weight: 'bold',
                                    size: 12
                                },
                                formatter: v => v > 0 ? v : ""
                            }
                        }
                    },
                    plugins: [ChartDataLabels]   // MUST register plugin
                });

                index++;
            });
        });
    });

});



document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});


</script>
@endpush
