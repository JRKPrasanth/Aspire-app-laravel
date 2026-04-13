@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Product Pack Wise</h3>

    <!-- tabs header -->
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="text-muted fw-bold">Last Updated At: <span
                    class="text-primary fw-bold">{{ $last_update[0]->created_at }}</span></h6>
            <h6 class="text-muted">Data Upto: <span class="text-primary fw-bold">{{ $last_data }}</span></h6>
            <a href="{{ url($pageModule) }}" class="btn btn-outline-primary fw-bold">Tabs</a>
        </div>
    </div>
    <!-- end -->

    <div class="card shadow-lg rounded-4 border-0 p-4 mb-4">
        <form action="{{ url('primarysalesdistributorpropack') }}" method="get" id="searchForm">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="zone" class="col-form-label text-end d-block">Zone</label>
                    <select name="zone" id="zone" class="form-select zone select2 text-center">
                        <option value="">-- please select --</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->zone }}">{{ $zone->zone }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="region" class="col-form-label text-end d-block">Region</label>
                    <select name="region" id="region" class="form-select region select2 text-center">
                        <option value="">-- please select --</option>
                        @foreach($regions as $region)
                            <option value="{{ $region->region }}">{{ $region->region }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="state" class="col-form-label text-end d-block">State</label>
                    <select name="state" id="state" class="form-select state select2 text-center">
                        <option value="">-- please select --</option>
                        @foreach($states as $state)
                            <option value="{{ $state->state }}">{{ $state->state }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="area" class="col-form-label text-end d-block">Area</label>
                    <select name="area" id="area" class="form-select area select2 text-center">
                        <option value="">-- please select --</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->area }}">{{ $area->area }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="dist_name" class="col-form-label text-end d-block">Distributor</label>
                    <select name="dist_name" id="dist_name" class="form-select dist_name select2 text-center">
                        <option value="">-- please select --</option>
                        @foreach($distributor_name as $dist)
                            <option value="{{ $dist->name }}">{{ $dist->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="division" class="col-form-label text-end d-block">Division</label>
                    <select name="division" id="division" class="form-select division select2 text-center">
                        <option value="">-- please select --</option>
                        @foreach($divisions as $division)
                            <option value="{{ $division->division }}">{{ $division->division }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="date_select" class="col-form-label text-end d-block">Month</label>
                    <input type="month" name="date_select" id="date_select" class="form-control" style="border-radius: 5px;"
                        autocomplete="off">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100" id="searchButton"><i class="bi bi-search"></i>
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
                <strong>POOR</strong>
            </div>
            <div class="px-3 py-2 text-dark rounded" style="background:#e1e1e1; width: 160px;">75% - 85% → <strong>SUB
                    STANDARD</strong></div>
            <div class="px-3 py-2 text-white rounded" style="background:#0bb921; width: 160px;">85% - 100% →
                <strong>GOOD</strong>
            </div>
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
    <!---  distributor Wise Sale value -->
    <!--tables-->
    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table1" class="table table-bordered table-striped table-hover w-100 datatable-common">
                <thead>
                    <tr>

                        <?php if (request('zone') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('zone') }}</th>
                        <?php endif; ?>
                        <?php if (request('region') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('region') }}</th>
                        <?php endif; ?>
                        <?php if (request('division') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('division') }}</th>
                        <?php endif; ?>
                        <?php if (request('dist_name') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('dist_name') }}</th>
                        <?php endif; ?>
                        <?php if (request('state') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('state') }}</th>
                        <?php endif; ?>
                        <?php if (request('area') != ''): ?>
                        <th class="align text-white bg-danger text-center" style="background:#9b900d">{{ request('area') }}
                        </th>
                        <?php endif; ?>
                        <th colspan="7" class="align text-white bg-danger text-center">PRODUCT PACK WISE SALES AND TARGET
                            UPTO {{ $mon_yr }}</th>

                    </tr>

                    <tr>
                        <th class="bg-secondary text-center text-white">Division</th>
                        <th class="bg-secondary text-center text-white">Product Name</th>
                        <th class="bg-secondary text-center text-white">Sale {{ $pre_fy_year }}
                        </th>
                        <th class="bg-secondary text-center text-white">Sale {{ $cur_fy_year}}
                        </th>
                        <th class="bg-secondary text-center text-white">Target {{ $cur_fy_year}}
                        </th>
                        <th class="bg-secondary text-center text-white">Growth</th>
                        <th class="bg-secondary text-center text-white">Trg Vs Ach %</th>
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
        $Zone = $value->product_name;


        if (!isset($zoneData[$Zone])) {
            $zoneData[$Zone] = [
                'state' => $value->division,
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
                        <td><?php    echo $data['state']; ?></td>
                        <td class="sticky-col"><?php    echo $Zone; ?></td>
                        <td class="rupee-value" data-original="<?php    echo $data['primary_dis_pre_fy']; ?>">
                            <?php    echo $data['primary_dis_pre_fy']; ?>
                        </td>
                        <td class="rupee-value" data-original="<?php    echo $data['primary_dis_cur_fy']; ?>">
                            <?php    echo $data['primary_dis_cur_fy']; ?>
                        </td>
                        <td class="rupee-value" data-original="<?php    echo $data['target_cur_fy']; ?>">
                            <?php    echo $data['target_cur_fy']; ?>
                        </td>
                        <?php    if ($data['primary_dis_pre_fy'] != 0 && $data['primary_dis_cur_fy'] != 0) { ?>
                        <td><?php        echo round(($data['primary_dis_cur_fy'] / $data['primary_dis_pre_fy'] - 1) * 100, 0); ?>%
                        </td> <?php    } else { ?>
                        <td>0</td> <?php    } ?>
                        <?php    if ($data['target_cur_fy'] != 0) { ?>
                        <td
                            style=" text-align: center; <?php        echo getBackgroundColor($data['primary_dis_cur_fy'], $data['target_cur_fy']); ?>">
                            <?php        echo round(($data['primary_dis_cur_fy'] / $data['target_cur_fy']) * 100, 0); ?>%
                        </td> <?php    } else { ?>
                        <td>0</td> <?php    } ?>
                    </tr>
                    <?php } ?>
                </tbody>
                <!-- Add the total row for all products -->
                <tfoot>
                    <tr class="sticky-foot fw-bold table-danger">
                        <td>Grand Total</td>
                        <td></td>
                        <td class="rupee-value" data-original="<?php echo $divisionTotals['totalPrimaryDis_pre_fy']; ?>">
                            <?php echo $divisionTotals['totalPrimaryDis_pre_fy']; ?>
                        </td>
                        <td class="rupee-value" data-original="<?php echo $divisionTotals['totalPrimaryDis_cur_fy']; ?>">
                            <?php echo $divisionTotals['totalPrimaryDis_cur_fy']; ?>
                        </td>
                        <td class="rupee-value" data-original="<?php echo $divisionTotals['totaltarget_cur_fy']; ?>">
                            <?php echo $divisionTotals['totaltarget_cur_fy']; ?>
                        </td>
                        <?php  if ($divisionTotals['totalPrimaryDis_pre_fy'] != 0 && $divisionTotals['totalPrimaryDis_cur_fy'] != 0) { ?>
                        <td><?php    echo round(($divisionTotals['totalPrimaryDis_cur_fy'] / $divisionTotals['totalPrimaryDis_pre_fy'] - 1) * 100, 0); ?>%
                        </td><?php } else { ?>
                        <td>0</td> <?php } ?>
                        <?php  if ($divisionTotals['totaltarget_cur_fy'] != 0) { ?>
                        <td
                            style=" text-align: center; <?php    echo getBackgroundColor($divisionTotals['totalPrimaryDis_cur_fy'], $divisionTotals['totaltarget_cur_fy']); ?>">
                            <?php    echo round(($divisionTotals['totalPrimaryDis_cur_fy'] / $divisionTotals['totaltarget_cur_fy']) * 100, 0); ?>%
                        </td> <?php } else { ?>
                        <td>0</td> <?php } ?>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
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

                $('.datatable-common').DataTable({

                    processing: true,
                    serverSide: false,

                    scrollX: true,
                    scrollY: "50vh",

                    autoWidth: false,
                    orderCellsTop: true,
                    fixedHeader: true,

                    pageLength: 25,

                    dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',

                    buttons: [

                        {
                            extend: 'excelHtml5',
                            filename: 'primary_sale_and_target_pro_pack'
                        },

                        {
                            extend: 'pdfHtml5',
                            filename: 'primary_sale_and_target_pro_pack',
                            orientation: 'landscape',
                            pageSize: 'A4'
                        }

                    ],

                    initComplete: function () {
                        this.api().columns.adjust();
                    }

                });

            });

        // search alert
        document.getElementById('searchForm').addEventListener('submit', function (event) {
            var zone = document.getElementById('zone').value;
            var region = document.getElementById('region').value;
            var startDate = document.getElementById('date_select').value;
            var Division = document.getElementById('division').value;
            var State = document.getElementById('state').value;
            var Dist = document.getElementById('dist_name').value;
            var Area = document.getElementById('area').value;

            if (zone === '' && region === '' && startDate === '' && Division === '' && State === '' && Dist === '' && Area === '') {
                alert('Please select Zone, Region,Area or Month Before Searching.');
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

    </script>
    <!-- end  -->
@endpush