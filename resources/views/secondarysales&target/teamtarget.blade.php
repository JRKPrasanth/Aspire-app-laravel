@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Team Target Vs Achivement</h3>

    <!-- tabs header -->
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="text-muted fw-bold">Last Updated At: <span
                    class="text-primary fw-bold">{{ $last_update[0]->created_at }}</span></h6>
            <h6 class="text-muted">Data Upto: <span class="text-primary fw-bold">{{ $last_data }}</span></h6>
            <a href="{{ url($pageModule) }}" class="btn btn-outline-primary fw-bold">Tabs</a>
        </div>
    </div>

    <!-- Search Card -->
    <div class="card shadow-lg rounded-4 border-0 p-4 mb-4">
        <form action="{{ url('salesandtargetteam') }}" method="get" id="searchForm">
            <div class="row g-3 align-items-end">

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


                <!-- State -->
                <div class="col-md-3">
                    <label for="state" class="form-label">State</label>
                    <select name="state" id="state" class="form-select state select2 text-center">
                        <option value="">-- please select --</option>
                        @foreach($states as $state)
                            <option value="{{ $state->state }}">{{ $state->state }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Area -->
                <div class="col-md-3">
                    <label for="area" class="form-label">Area</label>
                    <select name="area" id="area" class="form-select area select2 text-center">
                        <option value="">-- please select --</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->area }}">{{ $area->area }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="manager" class="form-label">Manager</label>
                    <select name="manager" id="manager" class="form-select select2 manager text-center">
                        <option value="">-- please select --</option>
                        @foreach($managers as $manager)
                            <option value="{{ $manager->name }}">{{ $manager->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Month -->
                <div class="col-md-3">
                    <label for="date_select" class="form-label">Month</label>
                    <input type="month" name="date_select" id="date_select" class="form-control date_select"
                        autocomplete="off">
                </div>

                <!-- Search Button -->
                <div class="col-5 text-center mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Search
                    </button>
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
    <!-- end -->
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
                        <?php if (request('state') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('state') }}</th>
                        <?php endif; ?>
                        <?php if (request('manager') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('manager') }}</th>
                        <?php endif; ?>
                        <?php if (request('area') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('area') }}</th>
                        <?php endif; ?>
                        <th colspan="8" class="align text-white bg-danger text-center">TEAM WISE TARGET ACHIVEMENT UPTO -
                            {{ $mon_yr }}</th>

                    </tr>
                    <tr>
                        <th colspan="2" class="align text-white bg-secondary text-center"></th>
                        <th colspan="2" class="align text-white bg-secondary text-center">Purchase</th>
                        <th colspan="2" class="align text-white bg-secondary text-center">Target</th>
                        <th colspan="3" class="align text-white bg-secondary text-center"></th>

                    </tr>
                    <tr>

                        <th class="align bg-warning">Currrent Reporting MGR</th>
                        <th class="align bg-warning">HQ Name</th>
                        <th class="align bg-warning"><span class="text-warning" style="font-size:6px;">Purchase
                            </span>{{ $pre_fy_year }}</th>
                        <th class="align bg-warning"><span class="text-warning" style="font-size:6px;">Purchase
                            </span>{{ $cur_fy_year}}</th>
                        <th class="align bg-warning"><span class="text-warning" style="font-size:6px;">Target
                            </span>{{ $cur_fy_year}}</th>
                        <th class="align bg-warning">Growth</th>
                        <th class="align bg-warning">Trg Vs Ach %</th>
                        <th class="align bg-warning">Pre Mon Shortfall</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
    $zoneData = [];
    $divisionTotals = [
        'totalPrimaryDis_pre_fy' => 0,
        'totalPrimaryDis_cur_fy' => 0,
        'totaltarget_cur_fy' => 0,
        'totaltarget_nxt_mon' => 0,
    ];

    foreach ($all_ind_sal as $value) {
        $Zone = $value->hq_name;


        if (!isset($zoneData[$Zone])) {
            $zoneData[$Zone] = [

                'hq_name' => $value->name,
                'primary_dis_pre_fy' => 0,
                'primary_dis_cur_fy' => 0,
                'target_cur_fy' => 0,
                'target_nxt_mon' => 0,

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


                        <td><?php    echo $data['hq_name']; ?></td>
                        <td class="sticky-col"><?php    echo $Zone; ?></td>
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
                        </td>

                    </tr>
                    <?php } ?>
                </tbody>
                <!-- Add the total row for all products -->
                <tfoot class="table-danger">
                    <tr class="sticky-foot fw-bold">
                        <td>Grand Total</td>
                        <td></td>
                        <td class="rupee-value" data-original="<?php echo $divisionTotals['totalPrimaryDis_pre_fy']; ?>">
                            <?php echo $divisionTotals['totalPrimaryDis_pre_fy']; ?></td>
                        <td class="rupee-value" data-original="<?php echo $divisionTotals['totalPrimaryDis_cur_fy']; ?>">
                            <?php echo $divisionTotals['totalPrimaryDis_cur_fy']; ?></td>
                        <td class="rupee-value" data-original="<?php echo $divisionTotals['totaltarget_cur_fy']; ?>">
                            <?php echo $divisionTotals['totaltarget_cur_fy']; ?></td>
                        <td>
                            <?php
    if ($divisionTotals['totalPrimaryDis_pre_fy'] != 0 && $divisionTotals['totaltarget_cur_fy'] != 0) {
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

        $(document).on('change', '.region', function () {

            var region = $(this).val();
            var $state = $(".state");

            if (region !== "") {

                var condition = encodeURIComponent("region='" + region + "'");

                var url = "{{ URL::to('jcombosecondsales') }}" +
                    "?table=sd_primarydataupload_t:state:state" +
                    "&parent=" + condition +
                    "&order_by=state asc";

                $.ajax({
                    url: url,
                    type: "GET",
                    success: function (response) {

                        let data = response;

                        if (typeof response === "string") {
                            try {
                                data = JSON.parse(response);
                            } catch (e) {
                                console.error("Invalid JSON:", response);
                                return;
                            }
                        }

                        // Clear previous options
                        $state.empty().append('<option value="">-- Select State --</option>');

                        // Populate results
                        $.each(data, function (i, item) {
                            $state.append(
                                `<option value="${item.val}">${item.option_name}</option>`
                            );
                        });

                        // Reinitialize Select2 if required
                        $state.trigger('change.select2');
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", error);
                    }
                });

            } else {
                // Reset dropdown when region is empty
                $state.empty().append('<option value="">-- Select State --</option>');
            }
        });

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
                        filename: 'TEAM WISE TARGET ACHIVEMENT',
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
                        filename: 'TEAM WISE TARGET ACHIVEMENT',
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

        // search alert
        document.getElementById('searchForm').addEventListener('submit', function (event) {
            var zone = document.getElementById('zone').value;
            var region = document.getElementById('region').value;
            var state = document.getElementById('state').value;
            var area = document.getElementById('area').value;
            var startDate = document.getElementById('date_select').value;

            if (zone === '' && region === '' && startDate === '' && state === '' && area === '') {
                alert('Please select Zone, Region,State,Area or Month Before Searching.');
                event.preventDefault();
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
    </script>
    <!-- end  -->
@endpush