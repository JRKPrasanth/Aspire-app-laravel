@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Business Brief MIS</h3>

    <div class="container mt-4">
        <!-- First row of buttons -->
        <div class="row g-3 mb-2">
            <div class="col-12 col-md-3">
                <a href="primarybusinessmis" class="btn btn-outline-success w-100">Zone Wise</a>
            </div>
            <div class="col-12 col-md-3">
                <a href="primarybusinessmisstate" class="btn btn-success w-100">State Wise</a>
            </div>
            <div class="col-12 col-md-3">
                <a href="primarybusinessmisperson" class="btn btn-outline-success w-100">Product wise Productivity</a>
            </div>
        </div>
    </div>

    <div class="card shadow-lg rounded-4 border-0 p-4 mb-4">
        <form action="{{ url('primarybusinessmisstate') }}" method="get" id="searchForm">
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
                    <input type="month" name="date_select" id="date_select" class="form-control date_select"
                        autocomplete="off">
                </div>

                <!-- Submit Button -->
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100" id="searchButton"> <i class="bi bi-search"></i>
                        Search</button>
                </div>

            </div>
        </form>
    </div>

    <div class="card shadow-lg rounded-4 border-0 p-4">
        <div class="table-responsive" style="overflow-x: auto;">
            <table id="Table1" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <tr>
                        <?php if (request('region') != ''): ?>
                        <th class="align text-white bg-danger text-center">{{ request('region') }}</th>
                        <?php endif; ?>

                        <th colspan="6" class="align text-white bg-danger text-center">{{ $cur_fy_year }} UPTO -
                            {{ $mon_yr }}</th>
                        <th colspan="6" class="align text-white bg-danger text-center">Calculation on Primary Sale Value
                        </th>
                        <th colspan="2" class="align text-white bg-secondary text-center">Secondary Value</th>
                        <th colspan="2" class="align text-white bg-success text-center">{{ $last_avg_mon }}</th>
                    </tr>

                    <tr>


                        <th class="align text-white bg-secondary text-center">Region</th>
                        <th class="align text-white bg-secondary text-center">State</th>
                        <th class="align text-white bg-secondary text-center">Growth over {{ $pre_fy_year }}</th>
                        <th colspan="1" class="align text-white bg-secondary text-center">Trg Vs Ach % {{ $cur_fy_year }}
                        </th>
                        <th class="align text-white bg-secondary text-center">Per Person Productivity</th>
                        <th class="align text-white bg-secondary text-center">Employee Cost Ratio</th>
                        <th class="align text-white bg-secondary text-center">Sample Cost Ratio</th>
                        <th class="align text-white bg-secondary text-center">Sales Return Ratio</th>
                        <th class="align text-white bg-secondary text-center">PM Cost Ratio</th>
                        <th class="align text-white bg-secondary text-center">Transport Cost Ratio</th>
                        <th class="align text-white bg-secondary text-center">Offer/Free unit cost Ratio</th>
                        <th class="align text-white bg-secondary text-center">Total cost Ratio</th>
                        <th class="align text-white bg-danger text-center">Growth over {{ $pre_fy_year }}</th>
                        <th class="align text-white bg-danger text-center">Trg Vs Ach % {{ $cur_fy_year }} </th>
                        <th class="align text-white bg-secondary text-center">Dr. Coverage</th>
                        <th class="align text-white bg-secondary text-center">Dr. Call Avg </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($state_wise_mis as $value) { ?>
                    <tr>

                        <td>{{$value['region']}}</td>
                        <td class="sticky-col">{{$value['state']}}</td>
                        <td>{{$value['growth']}}</td>
                        <td colspan="1">{{$value['achive']}}</td>
                        <td>{{$value['personproduction']}}</td>
                        <td>{{$value['empcostratio']}}</td>
                        <td>{{$value['samplecost_ratio']}}</td>
                        <td>{{$value['sales_return_ratio']}}</td>
                        <td>{{$value['pm_cost_ratio']}}</td>
                        <td>{{$value['trans_cost_ratio']}}</td>
                        <td>{{$value['free_cost_ratio']}}</td>
                        <td>{{$value['total_cost_ratio']}}</td>
                        <td>{{$value['growth_two']}}</td>
                        <td>{{$value['achive_two']}}</td>
                        <td>{{$value['doc_cov']}}</td>
                        <td>{{$value['doc_cl_avg']}}</td>
                    </tr>
                    <?php }  ?>
                </tbody>
                <tfoot class="table-danger">
                    <?php foreach ($state_wise_mis_gt as $value) { ?>
                    <tr style="background:#ffbef7;font-weight: 600;">
                        <td>Total</td>
                        <td></td>
                        <td>{{$value['growth']}}</td>
                        <td colspan="1">{{$value['achive']}}</td>
                        <td>{{$value['personproduction']}}</td>
                        <td>{{$value['empcostratio']}}</td>
                        <td>{{$value['samplecost_ratio']}}</td>
                        <td>{{$value['sales_return_ratio']}}</td>
                        <td>{{$value['pm_cost_ratio']}}</td>
                        <td>{{$value['trans_cost_ratio']}}</td>
                        <td>{{$value['free_cost_ratio']}}</td>
                        <td>{{$value['total_cost_ratio']}}</td>
                        <td>{{$value['growth_two']}}</td>
                        <td>{{$value['achive_two']}}</td>

                        <?php }  ?>

                        <?php foreach ($doc_cl_avg as $value) { ?>
                        <td>{{ $value->doc_cov_avg}}</td>
                        <td>{{ $value->doc_cl_avg}}</td>
                    </tr>
                    <?php }  ?>

                </tfoot>
            </table>
        </div>
    </div>

@endsection
@push('scripts')

    <script>

        $(document).ready(function () {

            var Zone = "{{ request('zone') }}";

            $('.zone').select2();
            $('#zone').val(Zone).trigger('change');


        });



        $(document).ready(function () {

            var startDate = "{{ request('date_select') }}";
            $('#date_select').val(startDate);


        });

        $(document).ready(function () {
            $('#Table1').DataTable({
                scrollX: true,
                scrollY: "50vh",
                buttons: [
                    {
                        extend: 'excelHtml5',
                        filename: 'Month_wise_sample',
                    },
                    {
                        extend: 'pdfHtml5',
                        filename: 'Month_wise_sample',
                    }
                ]
            });
        });

        // search alert
        document.getElementById('searchForm').addEventListener('submit', function (event) {
            var zone = document.getElementById('zone').value;
            var region = document.getElementById('region').value;
            var startDate = document.getElementById('date_select').value;
            var Division = document.getElementById('division').value;

            if (zone === '' && region === '' && startDate === '' && Division === '') {
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
@endpush