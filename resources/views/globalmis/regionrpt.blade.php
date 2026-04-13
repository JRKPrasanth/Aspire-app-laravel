@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Region Wise</h3>

    <!-- tabs header -->
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="text-muted fw-bold">Last Updated At: <span
                    class="text-primary fw-bold">{{ $last_update[0]->created_at }}</span></h6>
            <h6 class="text-muted">Data Upto: <span class="text-primary fw-bold">{{ $last_data }}</span></h6>
            <a href="{{ url($pageModule) }}" class="btn btn-outline-primary fw-bold">Tabs</a>
        </div>
    </div>

    <div class="card shadow-lg rounded-4 border-0 p-4 mb-4">
        <form action="{{ url('globalmisreportregion') }}" method="get" id="searchForm">
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

                <!-- Month -->
                <div class="col-md-3">
                    <label for="date_select" class="form-label">Month</label>
                    <input type="month" name="date_select" id="date_select" class="form-control" autocomplete="off">
                </div>

                <!-- Submit Button -->
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100" id="searchButton"> <i class="bi bi-search"></i>
                        Search</button>
                </div>

            </div>
        </form>
    </div>

    <!-- Value Format Buttons -->
    <div class="text-center mb-4">
        <button class="btn btn-primary fw-bold me-2" id="btnThousand">Show in Thousands</button>
        <button class="btn btn-success fw-bold me-2" id="btnLakhs">Show in Lakhs</button>
        <button class="btn btn-danger fw-bold" id="btnReset">Reset</button>
    </div>

    <!-- end -->

    <!--tables-->
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
                        <th colspan="4" class="align text-white bg-danger text-center">REGION WISE SALES AND GROWTH UPTO
                            {{ $mon_yr }}</th>

                    </tr>
                    <tr>
                        <th colspan="1" class="align text-white bg-secondary text-center"></th>
                        <th colspan="2" class="align text-white bg-secondary text-center">Sale</th>
                        <th colspan="1" class="align text-white bg-secondary text-center"></th>

                    </tr>
                    <tr>
                        <th class="align text-white bg-success text-center">Region</th>
                        <th class="align text-white bg-success text-center"><span class="text-success"
                                style="font-size:1px;">sale </span>{{ $pre_fy_year }}</th>
                        <th class="align text-white bg-success text-center"><span class="text-success"
                                style="font-size:1px;">sale </span>{{ $cur_fy_year}}</th>
                        <th class="align text-white bg-warning text-center">Growth</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
    $zoneData = [];
    $divisionTotals = [
        'totalPrimaryDis_pre_fy' => 0,
        'totalPrimaryDis_cur_fy' => 0,

    ];

    foreach ($all_ind_sal as $value) {
        $Zone = $value->region;

        if (!isset($zoneData[$Zone])) {
            $zoneData[$Zone] = [

                'primary_dis_pre_fy' => 0,
                'primary_dis_cur_fy' => 0,

            ];
        }

        if ($value->f_year == $pre_fy_year) {
            $zoneData[$Zone]['primary_dis_pre_fy'] += $value->sale;

        } elseif ($value->f_year == $cur_fy_year) {

            $zoneData[$Zone]['primary_dis_cur_fy'] += $value->sale;

        }

        // Update division totals
        $divisionTotals['totalPrimaryDis_pre_fy'] += $value->f_year == $pre_fy_year ? $value->sale : 0;
        $divisionTotals['totalPrimaryDis_cur_fy'] += $value->f_year == $cur_fy_year ? $value->sale : 0;

    }

    foreach ($zoneData as $Zone => $data) {
    ?>
                    <tr>
                        <td class="sticky-col"><?php    echo $Zone; ?></td>
                        <td class="rupee-value" data-original="<?php    echo $data['primary_dis_pre_fy']; ?>">
                            <?php    echo $data['primary_dis_pre_fy']; ?></td>
                        <td class="rupee-value" data-original="<?php    echo $data['primary_dis_cur_fy']; ?>">
                            <?php    echo $data['primary_dis_cur_fy']; ?></td>

                        <?php    if ($data['primary_dis_pre_fy'] != 0) { ?>
                        <td><?php        echo round(($data['primary_dis_cur_fy'] / $data['primary_dis_pre_fy'] - 1) * 100, 0); ?>%
                        </td> <?php    } else { ?>
                        <td>0</td> <?php    } ?>

                    </tr>
                    <?php } ?>
                </tbody>

                <!-- Add the total row for all products -->
                <tfoot class="table-danger">
                    <tr class="sticky-foot fw-bold">
                        <td>Grand Total</td>
                        <td class="rupee-value" data-original="<?php echo $divisionTotals['totalPrimaryDis_pre_fy']; ?>">
                            <?php echo $divisionTotals['totalPrimaryDis_pre_fy']; ?></td>
                        <td class="rupee-value" data-original="<?php echo $divisionTotals['totalPrimaryDis_cur_fy']; ?>">
                            <?php echo $divisionTotals['totalPrimaryDis_cur_fy']; ?></td>
                        <?php  if ($divisionTotals['totalPrimaryDis_pre_fy'] != 0) { ?>
                        <td><?php    echo round(($divisionTotals['totalPrimaryDis_cur_fy'] / $divisionTotals['totalPrimaryDis_pre_fy'] - 1) * 100, 0); ?>%
                        </td><?php } else { ?>
                        <td>0</td> <?php } ?>

                    </tr>

                </tfoot>
            </table>
        </div>
    </div>

    <!-- END -->

    <!-- machine wise summary chart -->
    <div class="card shadow-lg rounded-4 border-0 p-4">
        <h5 class="chart_tittle">Region Wise Growth UPTO - {{ $mon_yr }}</h5>
        <canvas id="myChart" style="max-height:460px"></canvas>

    </div>
    <!-- end -->
@endsection
@push('scripts')

    <!-- Include charts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

    <script>



        $(document).ready(function () {

            var startDate = "{{ request('date_select') }}";
            $('#date_select').val(startDate);


        });

        $(document).ready(function () {
            $('#Table1').DataTable({
                buttons: [
                    {
                        extend: 'excelHtml5',
                        filename: 'REGION WISE SALES AND GROWTH',
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
                        filename: 'REGION WISE SALES AND GROWTH',
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
        });

        // search alert
        document.getElementById('searchForm').addEventListener('submit', function (event) {
            var zone = document.getElementById('zone').value;
            var region = document.getElementById('region').value;
            var startDate = document.getElementById('date_select').value;

            if (zone === '' && region === '' && startDate === '') {
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

        function generateRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        // Assuming $this->data['prim_sumchart'] is a JSON-encoded string
        const productData = {!! $prim_sumchart !!};

        var xValues = productData.map(function (data) {
            return data.zone;
        });

        var growthValues = productData.map(function (data) {
            return parseFloat(data.growth.replace('%', '')) || 0;
        });

        var growthColor = generateRandomColor();


        var datasets = [
            {
                label: 'Growth',
                backgroundColor: growthColor,
                data: growthValues
            }

        ];

        new Chart("myChart", {
            type: "bar",
            data: {
                labels: xValues,
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
                                data = data + '%';
                                ctx.fillText(data, bar._model.x, bar._model.y - 5);
                            });
                        });
                    }
                },
                legend: {
                    display: true
                },
                title: {
                    display: true,
                    text: "Region Growth Chart"
                },
                tooltips: {
                    callbacks: {
                        label: function (tooltipItem, data) {
                            var dataset = data.datasets[tooltipItem.datasetIndex];
                            var currentValue = dataset.data[tooltipItem.index];
                            return dataset.label + ": " + currentValue + "%";
                        }
                    }
                }
            }
        });


        $(document).ready(function () {
            // Function to format numbers as per the selected option
            function formatNumber(number, format) {
                if (format === 'k') {
                    return (number / 1000).toFixed(1) + 'K';
                } else if (format === 'l') {
                    return (number / 100000).toFixed(1) + 'L';
                } else {
                    return number;
                }
            }

            // Event handler for the "K" button
            $('#btnThousand').on('click', function () {
                $('.rupee-value').each(function () {
                    var originalValue = parseFloat($(this).data('original'));
                    $(this).text(formatNumber(originalValue, 'k'));
                });
            });

            // Event handler for the "L" button
            $('#btnLakhs').on('click', function () {
                $('.rupee-value').each(function () {
                    var originalValue = parseFloat($(this).data('original'));
                    $(this).text(formatNumber(originalValue, 'l'));
                });
            });

            // Event handler for the "Reset" button
            $('#btnReset').on('click', function () {
                location.reload();
            });
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

    </script>
    <!-- end  -->
@endpush