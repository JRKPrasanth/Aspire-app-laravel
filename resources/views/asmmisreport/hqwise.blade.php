@extends('layouts.header')
@section('content')
    <h3 class="text-danger">HQ Wise</h3>

    <!-- tabs header -->
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="text-muted fw-bold">Last Updated At: <span
                    class="text-danger fw-bold">{{ $last_update[0]->created_at }}</span></h6>
            <h6 class="text-muted">Data Upto: <span class="text-danger fw-bold">{{ $last_data }}</span></h6>
            <a href="{{ url($pageModule) }}" class="btn btn-outline-danger fw-bold">Tabs</a>
        </div>
    </div>
    <!-- end -->

    <div class="card shadow-lg rounded-4 border-0 p-4 mb-4">
        <form action="{{ url('misreporthq') }}" method="get" id="searchForm">
            <div class="row g-3">

                <!-- HQ -->
                <div class="col-md-3">
                    <label for="region" class="col-form-label">HQ</label>
                    <select name="region" id="region" class="form-select region select2 text-center">
                        <option value="">-- please select --</option>
                        @foreach($regions as $region)
                            <option value="{{ $region->hq_name }}">{{ $region->hq_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Area -->
                <div class="col-md-3">
                    <label for="area" class="col-form-label">Area</label>
                    <select name="area" id="area" class="form-select area select2 text-center">
                        <option value="">-- please select --</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->area }}">{{ $area->area }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Manager -->
                <div class="col-md-3">
                    <label for="manager" class="col-form-label">Manager</label>
                    <select name="manager" id="manager" class="form-select manager select2 text-center">
                        <option value="">-- please select --</option>
                        @foreach($managers as $manager)
                            <option value="{{ $manager->name }}">{{ $manager->name }}</option>
                        @endforeach
                    </select>
                </div>


                <!-- Month -->
                <div class="col-md-3">
                    <label for="date_select" class="col-form-label">Month</label>
                    <input type="month" name="date_select" id="date_select" class="form-control" autocomplete="off"
                        style="border-radius: 5px;">
                </div>

                <!-- Submit Button -->
                <div class="col-12 text-center mt-4">
                    <button type="submit" class="btn btn-primary px-5" id="searchButton"><i class="bi bi-search"></i>
                        Search</button>
                </div>

            </div>
        </form>
    </div>

    <!-- Status Legend -->
    <div class="card shadow-lg rounded-4 border-0 p-3 mb-4">
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <div class="px-3 py-2 text-white rounded" style="background:#c96fc6; width: 160px;">0% - 50% → <strong>VERY
                    POOR</strong></div>
            <div class="px-3 py-2 text-white rounded" style="background:#df9f29; width: 160px;">51% - 75% →
                <strong>POOR</strong></div>
            <div class="px-3 py-2 text-dark rounded" style="background:#e1e1e1; width: 160px;">75% - 85% → <strong>SUB
                    STANDARD</strong></div>
            <div class="px-3 py-2 text-white rounded" style="background:#0bb921; width: 160px;">85% - 100% →
                <strong>GOOD</strong></div>
        </div>
    </div>

    <!-- Value Format Buttons -->
    <div class="text-center mb-4">
        <button class="btn btn-primary fw-bold me-2" id="btnThousand">Show in Thousands</button>
        <button class="btn btn-success fw-bold me-2" id="btnLakhs">Show in Lakhs</button>
        <button class="btn btn-danger fw-bold" id="btnReset">Reset</button>
    </div>


    <?php
    function getBackgroundColor($value1, $value2)
    {

        if ($value2 != 0) {
            $percentage = ($value1 / $value2) * 100;

            if ($percentage >= 0 && $percentage <= 50) {
                return 'background: #c96fc6;';
            } elseif ($percentage > 50 && $percentage <= 75) {
                return 'background: #df9f29;';
            } elseif ($percentage > 75 && $percentage <= 85) {
                return 'background: #e1e1e1;';
            } elseif ($percentage > 85 && $percentage <= 100) {
                return 'background: #00f100;';
            } else {
                return 'background: #28b916;';
            }
        }
    }
    ?>
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
                        <?php if (request('manager') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('manager') }}</th>
                        <?php endif; ?>
                        <?php if (request('area') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('area') }}</th>
                        <?php endif; ?>
                        <th colspan="7" class="align text-white bg-danger text-center">HQ WISE STOCKIST PURCHASE AND TARGET
                            UPTO - {{ $mon_yr }}</th>

                    </tr>
                    <tr>
                        <th colspan="1" class="align text-white bg-secondary text-center"></th>
                        <th colspan="2" class="align text-white bg-secondary text-center">Sale</th>
                        <th colspan="1" class="align text-white bg-secondary text-center">Target</th>
                        <th colspan="3" class="align text-white bg-secondary text-center"></th>

                    </tr>
                    <tr>

                        <th class="align bg-warning">HQ Name</th>
                        <th class="align bg-warning">{{ $pre_fy_year }}</th>
                        <th class="align bg-warning">{{ $cur_fy_year}}</th>
                        <th class="align bg-warning">{{ $cur_fy_year}}</th>
                        <th class="align bg-warning">Growth</th>
                        <th class="align bg-warning">Trg Vs Ach %</th>
                        <th class="align bg-warning">Pre.Mon Shortfall</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
    $zoneData = [];
    $divisionTotals = [
        'totalPrimaryDis_pre_fy' => 0,
        'totalPrimaryDis_cur_fy' => 0,
        'totaltarget_cur_fy' => 0,
    ];

    foreach ($all_ind_sal as $value) {
        $Zone = $value->hq_name;


        if (!isset($zoneData[$Zone])) {
            $zoneData[$Zone] = [
                'primary_dis_pre_fy' => 0,
                'primary_dis_cur_fy' => 0,
                'target_cur_fy' => 0,

            ];
        }

        if ($value->f_year == $pre_fy_year) {
            $zoneData[$Zone]['primary_dis_pre_fy'] += $value->sale;

        } elseif ($value->f_year == $cur_fy_year) {

            $zoneData[$Zone]['primary_dis_cur_fy'] += $value->sale;
            $zoneData[$Zone]['target_cur_fy'] += $value->target;

        }

        // Update division totals
        $divisionTotals['totalPrimaryDis_pre_fy'] += $value->f_year == $pre_fy_year ? $value->sale : 0;
        $divisionTotals['totalPrimaryDis_cur_fy'] += $value->f_year == $cur_fy_year ? $value->sale : 0;
        $divisionTotals['totaltarget_cur_fy'] += $value->f_year == $cur_fy_year ? $value->target : 0;

    }

    foreach ($zoneData as $Zone => $data) {
    ?>
                    <tr>

                        <td class="sticky-col"><?php echo $Zone; ?></td>

                        <td class="rupee-value" data-original="<?php    echo $data['primary_dis_pre_fy']; ?>">
                            <?php    echo $data['primary_dis_pre_fy']; ?></td>
                        <td class="rupee-value" data-original="<?php    echo $data['primary_dis_cur_fy']; ?>">
                            <?php    echo $data['primary_dis_cur_fy']; ?></td>
                        <td class="rupee-value" data-original="<?php    echo $data['target_cur_fy']; ?>">
                            <?php    echo $data['target_cur_fy']; ?></td>

                        <td>
                            <?php
        if ($data['primary_dis_cur_fy'] != 0 && $data['primary_dis_pre_fy'] != 0) {
            echo round(($data['primary_dis_cur_fy'] / $data['primary_dis_pre_fy'] - 1) * 100, 0) . '%';
        } else {
            echo '0';
        }
            ?>
                        </td>

                        <td
                            style=" text-align: center; <?php    echo getBackgroundColor($data['primary_dis_cur_fy'], $data['target_cur_fy']); ?>">
                            <?php
        if ($data['target_cur_fy'] != 0) {
            echo round(($data['primary_dis_cur_fy'] / $data['target_cur_fy']) * 100, 0) . '%';
        } else {
            echo '0';
        }
            ?>
                        </td>

                        <?php 
        $value = 0; // Default value
        if ($data['target_cur_fy'] != 0) {
            $value = round(($data['target_cur_fy'] - $data['primary_dis_cur_fy']), 0);
        }
    ?>

                        <td class="rupee-value" data-original="<?php    echo max($value, 0); ?>">
                            <?php    echo max($value, 0); ?>

                    </tr>
                    <?php } ?>
                </tbody>
                <!-- Add the total row for all products -->
                <tfoot>
                    <tr class="sticky-foot fw-bold">
                        <td>Total</td>

                        <td class="rupee-value" data-original="<?php echo $divisionTotals['totalPrimaryDis_pre_fy']; ?>">
                            <?php echo $divisionTotals['totalPrimaryDis_pre_fy']; ?></td>
                        <td class="rupee-value" data-original="<?php echo $divisionTotals['totalPrimaryDis_cur_fy']; ?>">
                            <?php echo $divisionTotals['totalPrimaryDis_cur_fy']; ?></td>
                        <td class="rupee-value" data-original="<?php echo $divisionTotals['totaltarget_cur_fy']; ?>">
                            <?php echo $divisionTotals['totaltarget_cur_fy']; ?></td>

                        <td>
                            <?php
    if ($divisionTotals['totalPrimaryDis_pre_fy'] != 0) {
        echo round(($divisionTotals['totalPrimaryDis_cur_fy'] / $divisionTotals['totalPrimaryDis_pre_fy'] - 1) * 100, 0) . '%';
    } else {
        echo '0';
    }
            ?>
                        </td>

                        <td <?php echo getBackgroundColor($divisionTotals['totalPrimaryDis_cur_fy'], $divisionTotals['totaltarget_cur_fy']); ?>>
                            <?php
    if ($divisionTotals['totaltarget_cur_fy'] != 0) {
        echo round(($divisionTotals['totalPrimaryDis_cur_fy'] / $divisionTotals['totaltarget_cur_fy']) * 100, 0) . '%';
    } else {
        echo '0';
    }
            ?>
                        </td>

                        <?php 
        $value = 0; // Default value  
    if ($divisionTotals['totaltarget_cur_fy'] != 0) {
        $value = round(($divisionTotals['totaltarget_cur_fy'] - $divisionTotals['totalPrimaryDis_cur_fy']), 0);
    }
    ?>

                        <td class="rupee-value" data-original="<?php echo max($value, 0); ?>">
                            <?php echo max($value, 0); ?>
                        </td>
                    </tr>
                </tfoot>

            </table>
        </div>
    </div>


@endsection
@push('scripts')

    <script>

        $(document).ready(function () {

            var startDate = "{{ request('date_select') }}";
            $('#date_select').val(startDate);


        });

        $(document).ready(function () {
            $('#Table1').DataTable({
                dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
                order: [],
                buttons: [
                    {
                        extend: 'excelHtml5',
                        filename: 'HQ WISE STOCKIST PURCHASE AND TARGET ',
                        exportOptions: {
                            format: {
                                header: function (data, columnIdx) {
                                    var header1 = $('#Table1 thead tr:eq(0) th').eq(columnIdx).text();
                                    var header2 = $('#Table1 thead tr:eq(1) th').eq(columnIdx).text();
                                    var header3 = $('#Table1 thead tr:eq(2) th').eq(columnIdx).text();

                                    return header1 + '\n' + (header2 ? header2 + '\n' : '') + header3;
                                }
                            }
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        filename: 'HQ WISE STOCKIST PURCHASE AND TARGET ',
                        exportOptions: {
                            format: {
                                header: function (data, columnIdx) {
                                    // Concatenate headers with line breaks
                                    var header1 = $('#Table1 thead tr:eq(0) th').eq(columnIdx).text();
                                    var header2 = $('#Table1 thead tr:eq(1) th').eq(columnIdx).text();
                                    var header3 = $('#Table1 thead tr:eq(2) th').eq(columnIdx).text();

                                    return header1 + '\n' + (header2 ? header2 + '\n' : '') + header3;
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
            var Manager = document.getElementById('manager').value;
            var Area = document.getElementById('area').value;
            var startDate = document.getElementById('date_select').value;

            if (zone === '' && region === '' && startDate === '' && Manager === '' && Area === '') {
                alert('Please select Hq, Area, or Month Before Searching.');
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
    </script>
    <!-- end  -->
@endpush