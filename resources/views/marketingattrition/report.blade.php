@extends('layouts.header')
@section('content')
<h3 class="text-danger">Marketing Attrition Report</h3>
@include('layouts.breadcrumb')

    <div class="card shadow-lg rounded-4 border-0 p-4">
    <form action="{{ url('marketingattrition') }}" method="get" id="searchForm">
        <div class="row g-4">


            <!-- From Date -->
            <div class="col-md-4">
                <label for="start_date" class="form-label fw-semibold">From Date</label>
                <input type="text" class="form-control start_date1" id="start_date" name="start_date" required placeholder="YYYY-MM-DD">
            </div>

            <!-- To Date -->
            <div class="col-md-4">
                <label for="end_date" class="form-label fw-semibold">To Date</label>
                <input type="text" class="form-control end_date1" id="end_date" name="end_date" required placeholder="YYYY-MM-DD">
            </div>

            <!-- Search Button -->
           <div class="col-md-4" style="margin-top: 3.5rem !important;">
                <button type="submit" class="btn btn-primary px-5"><i class="bi bi-search"> </i> Search</button>
            </div>

        </div>
    </form>
</div>


<div class="card shadow-lg rounded-4 border-0 p-4">
    <div class="table-responsive" style="overflow-x:auto;">
        @if (empty($attrition_summary))
            <p class="nodata">No records available.</p>
        @else
            @php
                $displayedMonths = [];
                foreach ($attrition_summary as $row) {
                    if (!in_array($row->report_month, $displayedMonths)) {
                        $displayedMonths[] = $row->report_month;
                    }
                }

                $groupedData = [];
                foreach ($attrition_summary as $row) {
                    $zone = $row->zone_name ?? 'NA';
                    $state = $row->state_name ?? 'NA';
                    $month = $row->report_month;

                    $groupedData[$zone][$state][$month] = [
                        'opening_balance'       => $row->opening_balance ?? 0,
                        'joined_count'          => $row->joined_count ?? 0,
                        'resigned_count'        => $row->resigned_count ?? 0,
                        'closing_balance'       => $row->closing_balance ?? 0,
                        'attrition_percentage'  => $row->attrition_percentage ?? 0,
                    ];
                }
            @endphp

            <table id="Table3" class="table table-bordered table-striped table-hover w-100">
                <thead>
                    <tr class="sticky-row">
                        <th colspan="2" class="text-center text-white bg-danger">Month Year</th>
                        @foreach ($displayedMonths as $month)
                            <th colspan="5" class="text-center text-white bg-primary">{{ $month }}</th>
                        @endforeach
                    </tr>
                    <tr class="sticky-row2">
                        <th class="sticky-col text-white bg-secondary">Zone</th>
                        <th class="sticky-col text-white bg-secondary">State</th>
                        @foreach ($displayedMonths as $month)
                            <th class="text-white bg-success">Opening</th>
                            <th class="text-white bg-success">Add</th>
                            <th class="text-white bg-success">Del</th>
                            <th class="text-white bg-success">Closing</th>
                            <th class="text-white bg-success">%</th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>
                    @php
                        $columnTotals = [];
                        foreach ($displayedMonths as $month) {
                            $columnTotals[$month] = [
                                'opening_balance' => 0,
                                'joined_count' => 0,
                                'resigned_count' => 0,
                                'closing_balance' => 0,
                            ];
                        }
                    @endphp

                    @foreach ($groupedData as $zone => $states)
                        @foreach ($states as $state => $monthsData)
                            <tr>
                                <td class="sticky-col">{{ $zone }}</td>
                                <td class="sticky-col">{{ $state }}</td>

                                @foreach ($displayedMonths as $month)
                                    @php
                                        $data = $monthsData[$month] ?? null;

                                        $opening = $data['opening_balance'] ?? 0;
                                        $joined  = $data['joined_count'] ?? 0;
                                        $resigned = $data['resigned_count'] ?? 0;
                                        $closing = $data['closing_balance'] ?? 0;
                                        $attrition = $data['attrition_percentage'] ?? 0;

                                        $columnTotals[$month]['opening_balance'] += $opening;
                                        $columnTotals[$month]['joined_count'] += $joined;
                                        $columnTotals[$month]['resigned_count'] += $resigned;
                                        $columnTotals[$month]['closing_balance'] += $closing;
                                    @endphp

                                    <td>{{ $opening }}</td>
                                    <td>{{ $joined }}</td>
                                    <td>{{ $resigned }}</td>
                                    <td>{{ $closing }}</td>
                                    <td>{{ number_format((float)$attrition, 2) }}%</td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>

                <tfoot>
                    <tr class="fw-bold bg-warning text-dark">
                        <td colspan="2" class="text-center">Total</td>

                        @foreach ($displayedMonths as $month)
                            @php
                                $openingTotal = $columnTotals[$month]['opening_balance'];
                                $joinedTotal = $columnTotals[$month]['joined_count'];
                                $resignedTotal = $columnTotals[$month]['resigned_count'];
                                $closingTotal = $columnTotals[$month]['closing_balance'];

                                $totalAttrition = $openingTotal > 0
                                    ? ($resignedTotal * 100) / $openingTotal
                                    : 0;
                            @endphp

                            <td>{{ $openingTotal }}</td>
                            <td>{{ $joinedTotal }}</td>
                            <td>{{ $resignedTotal }}</td>
                            <td>{{ $closingTotal }}</td>
                            <td>{{ number_format($totalAttrition, 2) }}%</td>
                        @endforeach
                    </tr>
                </tfoot>
            </table>
        @endif
    </div>
</div>




@endsection
@push('scripts')

<script>
        $('#Table3').DataTable({
                scrollCollapse: true,
                scrollX: true,
                scrollY: "50vh",
                dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
                buttons: [
                    {
                        extend: 'colvis',
                        text: '<i class="bi bi-layout-three-columns"></i> Columns',
                        className: 'btn bg-primary btn-sm',
                        postfixButtons: ['colvisRestore'] // adds "Restore Columns" button
                    },
                    { extend: 'excelHtml5', title: "Marketing Attrition Report", exportOptions: { columns: ':visible' } }
                ]
        });
</script>

@endpush