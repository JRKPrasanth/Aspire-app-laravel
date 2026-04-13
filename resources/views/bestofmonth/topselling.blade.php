@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Top Selling Brands</h3>

    <!-- tabs header -->
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="text-muted fw-bold">Last Updated At: <span
                    class="text-primary fw-bold">{{ $last_update[0]->created_at }}</span></h6>
            <h6 class="text-muted">Data Upto: <span class="text-primary fw-bold">{{ $last_data }}</span></h6>
        </div>
    </div>

    <!-- Search Card -->
    <div class="card shadow-lg rounded-4 border-0 p-4 mb-4">
        <form action="{{ url('topsellingmis') }}" method="get" id="searchForm">
            <div class="row g-3 align-items-end">

                <!-- Zone -->
                <div class="col-md-3">
                    <label for="zone" class="form-label">Zone</label>
                    <select name="zone" id="zone" class="form-select select2 text-center zone">
                        <option value="">-- please select --</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->zone }}">{{ $zone->zone }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Region -->
                <div class="col-md-3">
                    <label for="region" class="form-label">Region</label>
                    <select name="region" id="region" class="form-select select2 text-center region">
                        <option value="">-- please select --</option>
                        @foreach($regions as $region)
                            <option value="{{ $region->region }}">{{ $region->region }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- State -->
                <div class="col-md-3">
                    <label for="state" class="form-label">State</label>
                    <select name="state" id="state" class="form-select select2 text-center state">
                        <option value="">-- please select --</option>
                        @foreach($states as $state)
                            <option value="{{ $state->state }}">{{ $state->state }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Area -->
                <div class="col-md-3">
                    <label for="area" class="form-label">Area</label>
                    <select name="area" id="area" class="form-select select2 text-center area">
                        <option value="">-- please select --</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->area }}">{{ $area->area }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Hq -->
                <div class="col-md-3">
                    <label for="hq" class="col-form-label">HQ</label>
                    <select name="hq" id="hq" class="form-select hq select2 text-center">
                        <option value="">-- please select --</option>
                        @foreach($hqs as $hq)
                            <option value="{{ $hq->hq_name }}">{{ $hq->hq_name }}</option>
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
                    <label for="date_select" class="form-label">Month</label>
                    <input type="month" name="date_select" id="date_select" class="form-control date_select"
                        autocomplete="off">
                </div>

                <!-- Search Button -->
                <div class="col-md-3 text-center">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Search
                    </button>
                </div>
            </div>
        </form>
    </div>



    <div class="row">

        <div class="col-6">

            <div class="card shadow-lg rounded-4 border-0 p-4">
                <div class="table-responsive" style="overflow-x: auto;">
                    <table id="Table1" class="table table-bordered table-striped table-hover w-100">
                        <thead>
                            <tr>
                                <?php if (request('zone') != ''): ?>
                                <th class="align text-white bg-secondary text-center">{{ request('zone') }}</th>
                                <?php endif; ?>
                                <?php if (request('region') != ''): ?>
                                <th class="align text-white bg-secondary text-center">{{ request('region') }}</th>
                                <?php endif; ?>
                                <?php if (request('state') != ''): ?>
                                <th class="align text-white bg-secondary text-center">{{ request('state') }}</th>
                                <?php endif; ?>
                                <?php if (request('area') != ''): ?>
                                <th class="align text-white bg-secondary text-center">{{ request('area') }}</th>
                                <?php endif; ?>
                                <?php if (request('hq') != ''): ?>
                                <th class="align text-white bg-secondary text-center">{{ request('hq') }}</th>
                                <?php endif; ?>
                                <?php if (request('manager') != ''): ?>
                                <th class="align text-white bg-secondary text-center">{{ request('manager') }}</th>
                                <?php endif; ?>
                                <th colspan="3" class="align text-white bg-secondary text-center">All India Top 15 Products
                                    (By Units) Up To {{ $mon_yr }}</th>

                            </tr>

                            <tr>
                                <th class="align bg-danger text-white">S.No</th>
                                <th class="align bg-danger text-white">Product Name</th>
                                <th class="align bg-danger text-white">Sales Unit</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($sql as $key => $row)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $row->product_name }}</td>
                                    <td>{{ $row->unit }}</td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>

        </div>




        <div class="col-6">

            <div class="card shadow-lg rounded-4 border-0 p-4">
                <div class="table-responsive" style="overflow-x: auto;">
                    <table id="Table2" class="table table-bordered table-striped table-hover w-100">
                        <thead>
                            <tr>
                                <?php if (request('zone') != ''): ?>
                                <th class="align text-white bg-secondary text-center">{{ request('zone') }}</th>
                                <?php endif; ?>
                                <?php if (request('region') != ''): ?>
                                <th class="align text-white bg-secondary text-center">{{ request('region') }}</th>
                                <?php endif; ?>
                                <?php if (request('state') != ''): ?>
                                <th class="align text-white bg-secondary text-center">{{ request('state') }}</th>
                                <?php endif; ?>
                                <?php if (request('area') != ''): ?>
                                <th class="align text-white bg-secondary text-center">{{ request('area') }}</th>
                                <?php endif; ?>
                                <?php if (request('hq') != ''): ?>
                                <th class="align text-white bg-secondary text-center">{{ request('hq') }}</th>
                                <?php endif; ?>
                                <?php if (request('manager') != ''): ?>
                                <th class="align text-white bg-secondary text-center">{{ request('manager') }}</th>
                                <?php endif; ?>
                                <th colspan="3" class="align text-white bg-secondary text-center">All India Top 15 Products
                                    (By
                                    Value) Up To {{ $mon_yr }}</th>

                            </tr>

                            <tr>
                                <th class="align bg-danger text-white">S.No</th>
                                <th class="align bg-danger text-white">Product Name</th>
                                <th class="align bg-danger text-white">Sales Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sql1 as $key => $row)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $row->product_name }}</td>
                                    <td>{{ $row->unit }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <div class="col-12">

        <div class="card shadow-lg rounded-4 border-0 p-4">
            <div class="table-responsive" style="overflow-x: auto;">
                <table id="Table3" class="table table-bordered table-striped table-hover w-100">
                    <thead>
                        <tr>
                            <th colspan='1' class="align bg-secondary text-white">For the Month {{ $mon_yr }}</th>
                            <th colspan='6' class="align bg-secondary text-white">Zone Rank by No. of Units Sold</th>
                        </tr>
                        <tr>
                            <th class="align bg-danger text-white">Product Name</th>
                            <th class="align bg-danger text-white">All India</th>
                            <th class="align bg-danger text-white">CW</th>
                            <th class="align bg-danger text-white">East</th>
                            <th class="align bg-danger text-white">North</th>
                            <th class="align bg-danger text-white">South 1 </th>
                            <th class="align bg-danger text-white">South 2</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sql2 as $key => $row)
                            <tr>
                                <td>{{ $row->product_name }}</td>
                                <td>{{ $row->all_india_rank }}</td>
                                <td>{{ $row->cw }}</td>
                                <td>{{ $row->east }}</td>
                                <td>{{ $row->north }}</td>
                                <td>{{ $row->south1 }}</td>
                                <td>{{ $row->south2 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

@endsection
@push('scripts')

    <script>


        $(document).ready(function () {
            $('#Table1').DataTable({

                buttons: [
                    {
                        extend: 'excelHtml5',
                        filename: 'Best Of The Month By Units',
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
                    }
                ]
            });

            $('#Table2').DataTable({

                buttons: [
                    {
                        extend: 'excelHtml5',
                        filename: 'Best Of The Month By Value',
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
                    }
                ]
            });

            $('#Table3').DataTable({

                buttons: [
                    {
                        extend: 'excelHtml5',
                        filename: 'Best Of The Month By Value',
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
                    }
                ]
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

                $state.empty().append('<option value="">-- Select State --</option>');
            }
        });

    </script>

@endpush