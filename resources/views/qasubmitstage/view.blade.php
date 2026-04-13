@extends('layouts.header')
@section('content')
<h3 class="text-danger">Jobcard Completion Details</h3>
<form>
    {{ csrf_field() }}

    <div class="container-fluid py-3">
        <div class="card shadow-lg border-0 rounded-4">

            {{-- Header --}}
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-4">
                <h5 class="mb-0 fw-bold">Jobcard Completion Details</h5>
                <a href="{{ URL::to($pageurl) }}" class="btn btn-sm btn-danger">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>

            {{-- Body --}}
            <div class="card-body" id="section-to-print">

                {{-- Job Info --}}
                <div class="row g-4 mb-4">

                    {{-- Column 1 --}}
                    <div class="col-md-4">
                        <div class="border rounded-3 p-3 h-100 bg-light">
                            <p><strong>Reference No:</strong> {{ $header->reference_no }}</p>
                            <p><strong>Job No:</strong> {{ $header->job_no }}</p>
                            <p><strong>Product:</strong> {{ $header->product_code }} - {{ $header->concatenated_product }}</p>
                            <p><strong>Batch No:</strong> {{ $header->batch_no }}</p>
                            <p><strong>Quality Check:</strong> {{ $header->quality_check }}</p>
                            <p><strong>EMP Working Hrs:</strong> {{ $header->total_working_hrs }}</p>
                            <p><strong>Assigned To:</strong> {{ $header->assigned_names }}</p>
                        </div>
                    </div>

                    {{-- Column 2 --}}
                    <div class="col-md-4">
                        <div class="border rounded-3 p-3 h-100 bg-light">
                            <p><strong>QA Trx Date:</strong> {{ $header->qatrx_date }}</p>
                            <p><strong>Job Date:</strong> {{ $header->job_date }}</p>
                            <p><strong>UOM Code:</strong> {{ $header->uom_code }}</p>
                            <p><strong>Activity Incharge:</strong> {{ $header->first_name }}</p>
                            <p><strong>Store Move:</strong> {{ $header->store_move }}</p>
                            @if($pageurl !='packingjobcardcompletiondetails')
                            <p>
                            <strong>Machine Details:</strong> 
                            {{ !empty($header->assigned_machines) ? $header->assigned_machines : 'Machines not added' }}
                            </p>
                            @endif
                        </div>
                    </div>

                    {{-- Column 3 --}}
                    <div class="col-md-4">
                        <div class="border rounded-3 p-3 h-100 bg-light">
                            <p><strong>QA Status:</strong> {{ $header->qa_status }}</p>
                            <p><strong>Job Qty:</strong> {{ $header->job_qty }}</p>
                            <p><strong>Production Qty:</strong> {{ $header->production_qty }}</p>
                            <p><strong>Remarks:</strong> {{ $header->remarks }}</p>
                            <p><strong>Move to Subinventory:</strong> {{ $header->moveto_subinventory }}</p>
                            <p><strong>Subinventory:</strong> {{ $header->subinventory_name }}</p>
                            <p><strong>Sublocator:</strong> {{ $header->locator_code }}</p>
                        </div>
                    </div>

                </div>

                <div class="row" style="padding-left: 1rem;padding-right: 1rem;">
                    <h4><strong>Employee Working Details:</strong></h4>

                    @if(!empty($header->employee_details))

                        <table class="table table-responsive table-bordered table-sm w-100">
                            <thead class="table-secondary text-center">
                                <tr>
                                    <th>S.No</th>
                                    <th>Employee Name</th>
                                    <th>Working Hours</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($header->employee_details as $key => $emp)
                                <tr>
                                    <td class="text-center">{{ $key + 1 }}</td>
                                    <td>{{ $emp['name'] }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-success">
                                            {{ $emp['working_hours'] }} hrs
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    @else
                        <span class="text-danger">No employee details found</span>
                    @endif

                </div>

                {{-- Line Items --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-primary text-center">
                            <tr>
                                <th>No</th>
                                <th>Product</th>
                                <th>UOM</th>
                                <th>Qty</th>
                                <th>Production</th>
                                <th>Return</th>
                                <th>Scrap</th>
                                <th>Exceed</th>
                                <th>Batch No</th>
                                <th>Subinventory</th>
                                <th>Locator</th>
                                <th>Comments</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($linesdata as $key => $value)
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td>{{ $value->product_code }} - {{ $value->concatenated_product }}</td>
                                <td class="text-center">{{ $value->uom_code }}</td>
                                <td class="text-end">{{ $value->qty }}</td>
                                <td class="text-end">{{ $value->production_qty }}</td>
                                <td class="text-end">{{ $value->return_qty }}</td>
                                <td class="text-end">{{ $value->scrap_qty }}</td>
                                <td class="text-end">{{ $value->exceed_qty }}</td>
                                <td>{{ $value->batchnum }}</td>
                                <td>{{ $value->subinventory_name }}</td>
                                <td>{{ $value->locator_code }}</td>
                                <td>{{ $value->comment }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</form>
@endsection
