@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Accounts Dashboard</h3>
    @include('layouts.breadcrumb')

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <style>
        .dash-card {
            border-radius: 14px;
            min-height: 210px;
            box-shadow: 0 18px 40px rgba(17, 24, 39, .08);
            overflow: hidden;
            border: 0;
        }

        /* gradients similar to your screenshot */
        .card-gradient-receivable {
            background: linear-gradient(90deg, #ffb36a 0%, #ff6b88 55%, #ff5a8c 100%);
        }

        .card-gradient-payables {
            background: linear-gradient(90deg, #d874ff 0%, #8c6bff 55%, #8a56ff 100%);
        }

        .dash-progress {
            height: 10px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .35);
        }

        .dash-progress-bar {
            background: #1fe3a7;
            /* green line */
            border-radius: 999px;
        }

        .dash-amount {
            font-size: 30px;
            font-weight: 600;
            line-height: 1.05;
            letter-spacing: .2px;
        }

        .dash-sub {
            font-size: 1rem;
            font-weight: 500;
        }

        h5 {
            font-size: 20px;
        }

        .rupee-symbol {
            font-size: 32px;
            font-weight: 800;
            color: #fff;
            line-height: 1;
        }

        /* mini cards */
        .mini-card {
            background: #fff;
            border-radius: 12px;
            padding: 28px 22px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(17, 24, 39, .06);
            border: 1px solid rgba(17, 24, 39, .06);
            height: 100%;
        }

        .mini-icon {
            width: 62px;
            height: 62px;
            margin: 0 auto 14px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 20px rgba(17, 24, 39, .08);
        }

        .mini-value {
            font-size: 2rem;
            font-weight: 800;
            color: #111827;
            line-height: 1.1;
            letter-spacing: .2px;
        }

        .mini-label {
            font-size: .95rem;
            color: #111827;
            opacity: .85;
        }

        /* icon colors (close to screenshot) */
        .bg-purple {
            background: #b06cff;
        }

        .bg-yellow {
            background: #f6d10c;
        }

        .bg-teal {
            background: #1cc9b7;
        }

        .bg-pink {
            background: linear-gradient(135deg, #ffb06b, #ff4fa3);
        }

        /* right panel */
        .panel-card {
            background: #fff;
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 10px 30px rgba(17, 24, 39, .06);
            border: 1px solid rgba(17, 24, 39, .06);
        }

        .panel-link {
            text-decoration: none;
            font-size: .95rem;
            color: #111827;
            opacity: .8;
        }

        .panel-link:hover {
            opacity: 1;
            text-decoration: underline;
        }

        .status-block {
            border: 1px solid rgba(17, 24, 39, .08);
            border-radius: 8px;
            padding: 14px 14px 10px;
        }

        .status-title {
            font-weight: 700;
            font-size: 1rem;
        }

        .status-row {
            display: flex;
            justify-content: space-between;
            padding: 7px 0;
            font-size: .95rem;
            color: #111827;
        }

        .text-pink {
            color: #ff4f8f;
        }

        .text-teal {
            color: #16c2b3;
        }

        .chart-card {
            border: 0;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(17, 24, 39, .06);
        }

        .chart-wrap {
            height: 450px;
            /* similar tall chart area */
        }

        /* Legend like the template: small dots + text, aligned right */
        .chart-legend ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 18px;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
        }

        .chart-legend li {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: #6b7280;
            user-select: none;
        }

        .chart-legend span.dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
        }
    </style>


    <div class="container py-5">
        <div class="row g-4">
            <!-- Card 1 -->
            <div class="col-12 col-lg-6">
                <div class="dash-card card-gradient-receivable p-4">
                    <h5 class="mb-4 fw-semibold text-white">Total Receivable</h5>

                    <div class="progress dash-progress mb-4" role="progressbar" aria-valuenow="55" aria-valuemin="0"
                        aria-valuemax="100">
                        <div class="progress-bar dash-progress-bar" style="width:55%"></div>
                    </div>

                    <div class="d-flex justify-content-between align-items-end">
                        <div>
                            <div class="dash-amount text-white">₹ {{ number_format($cmonth, 2) }} </div>
                            <div class="dash-sub text-white">Current Month</div>
                        </div>

                        <div class="text-end">
                            <div class="dash-amount text-white">₹ {{ number_format($grandTotal, 2) }}</div>
                            <div class="dash-sub text-white">overdue</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col-12 col-lg-6">
                <div class="dash-card card-gradient-payables p-4">
                    <h5 class="mb-4 fw-semibold text-white">Total Payables</h5>

                    <div class="progress dash-progress mb-4" role="progressbar" aria-valuenow="55" aria-valuemin="0"
                        aria-valuemax="100">
                        <div class="progress-bar dash-progress-bar" style="width:55%"></div>
                    </div>

                    <div class="d-flex justify-content-between align-items-end">
                        <div>
                            <div class="dash-amount text-white">₹ {{ number_format($cmonthpay, 2) }} </div>
                            <div class="dash-sub text-white">Current Month</div>
                        </div>

                        <div class="text-end">
                            <div class="dash-amount text-white">₹ {{ number_format($grandTotalpay, 2) }} </div>
                            <div class="dash-sub text-white">overdue</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-4">
        <div class="row g-4 align-items-stretch">

            <!-- LEFT: 4 mini cards -->
            <div class="col-12 col-lg-6">
                <div class="row g-4">
                    <!-- Total Income -->
                    <div class="col-12 col-md-6">
                        <div class="mini-card">
                            <div class="mini-icon bg-purple">
                                <!-- ₹ icon -->

                                <span class="rupee-symbol">₹</span>

                            </div>
                            <div class="mini-value">{{ number_format($totalsales, 2) }}</div>
                            <div class="mini-label fw-bold">Total Sales</div>
                        </div>
                    </div>

                    <!-- Budget -->
                    <div class="col-12 col-md-6">
                        <div class="mini-card">
                            <div class="mini-icon bg-pink">
                                <!-- wallet icon -->

                                <span class="rupee-symbol">₹</span>

                            </div>
                            <div class="mini-value">{{ number_format($totalexpense, 2) }}</div>
                            <div class="mini-label fw-bold">Total Expenses</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="row g-4">
                    <!-- Total Income -->
                    <div class="col-12 col-md-6">
                        <div class="mini-card">
                            <div class="mini-icon bg-teal">
                                <!-- ₹ icon -->

                                <span class="rupee-symbol">₹</span>

                            </div>
                            <div class="mini-value">{{ number_format($grandTotal, 2) }}</div>
                            <div class="mini-label fw-bold">Distributor Overdue</div>
                        </div>
                    </div>

                    <!-- Budget -->
                    <div class="col-12 col-md-6">
                        <div class="mini-card">
                            <div class="mini-icon bg-yellow">
                                <!-- wallet icon -->

                                <span class="rupee-symbol">₹</span>

                            </div>
                            <div class="mini-value">{{ number_format($grandTotalpay, 2) }}</div>
                            <div class="mini-label fw-bold">Supplier Overdue</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- RIGHT: Order Status -->
    <div class="col-12 ">
        <div class="card shadow-lg rounded-4 border-0">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="mb-0 fw-semibold">Sales And Expense</h6>

                    <div id="salesExpenseLegend" class="chart-legend"></div>
                </div>

                <div class="chart-wrap">
                    <canvas id="salesExpenseChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-lg rounded-4 border-0">
        <h5 class="fw-bold text-primary text-center mt-4 mb-3">Financial Performance Summary</h5>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Particulars</th>
                        <th scope="col">Current Period</th>
                        <th scope="col">Previous Period</th>
                        <th scope="col">Variance</th>

                    </tr>
                </thead>
                <tbody>
                    <tr class="table-primary">
                        <th scope="row">Total Revenue</th>
                        <td>{{ number_format($revenue, 2) }}</td>
                        <td>{{ number_format($revenue_pre, 2) }}</td>
                        <td>{{ number_format($revenue - $revenue_pre, 2) }}</td>
                    </tr>

                    <tr class="table-secondary">
                        <th scope="row">Manufacturing Cost</th>
                        <td>{{ number_format($manuf, 2) }}</td>
                        <td>{{ number_format($manuf_pre, 2) }}</td>
                        <td>{{ number_format($manuf - $manuf_pre, 2) }}</td>
                    </tr>

                    <tr class="table-success">
                        <th scope="row">Gross Profit</th>
                        <td>{{ number_format($profit, 2) }}</td>
                        <td>{{ number_format($profit_pre, 2) }}</td>
                        <td>{{ number_format($profit - $profit_pre, 2) }}</td>
                    </tr>

                    <tr class="table-danger">
                        <th scope="row">Operating Expenses</th>
                        <td>{{ number_format($opexp, 2) }}</td>
                        <td>{{ number_format($opexp_pre, 2) }}</td>
                        <td>{{ number_format($opexp - $opexp_pre, 2) }}</td>
                    </tr>

                    <tr class="table-info">
                        <th scope="row">Net Profit / Loss</th>
                        <td>{{ number_format($pandl, 2) }}</td>
                        <td>{{ number_format($pandl_pre, 2) }}</td>
                        <td>{{ number_format($pandl - $pandl_pre, 2) }}</td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

@endsection
@push('scripts')




    <script>

        const chartData = <?php echo $chart_datas; ?>;

        const labels = chartData.map(item => item.month);
        const salesData = chartData.map(item => item.sale);
        const expenseData = chartData.map(item => item.expense);

        const data = {
            labels: labels,
            datasets: [
                {
                    label: 'Sales',
                    data: salesData,
                    backgroundColor: '#ff4f8f',
                    barThickness: 15,
                    maxBarThickness: 15,
                    borderRadius: 5
                },
                {
                    label: 'Expense',
                    data: expenseData,
                    backgroundColor: '#8b5cf6',
                    barThickness: 15,
                    maxBarThickness: 15,
                    borderRadius: 5
                }
            ]
        };

        const ctx = document.getElementById('salesExpenseChart');

        new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            boxWidth: 7,
                            boxHeight: 7,
                            padding: 18,
                            color: '#9ca3af',
                            font: { size: 12 }
                        }
                    }
                },

                scales: {
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { color: '#9ca3af', font: { size: 12 }, padding: 12 }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#9ca3af',
                            font: { size: 12 }
                        },
                        grid: {
                            color: 'rgba(229,231,235,.9)',
                            drawBorder: false
                        }
                    }
                },

                datasets: {
                    bar: {
                        categoryPercentage: 0.35,
                        barPercentage: 1.0
                    }
                }
            }
        });

    </script>


@endpush