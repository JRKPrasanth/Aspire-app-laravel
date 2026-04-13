@extends('layouts.header')
@section('content')


<form>
    {{ csrf_field() }}

    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
            <h5 class="mb-0">Jobcard Details</h5>
            <a href="{{ URL::to($pageurl) }}" class="btn btn-sm btn-danger">
                <i class="bi bi-x-lg"></i> 
            </a>
        </div>

        <div class="card-body">
            <div class="row g-4">

                <!-- Column 1 -->
                <div class="col-md-4">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Job No:</strong> {!! $job_no !!}</li>
                        <li class="list-group-item"><strong>Job Date:</strong> {!! date(\Session::get('p_date_format'),strtotime($job_date)) !!}</li>
                        <li class="list-group-item"><strong>Completion Date:</strong> {!! date(\Session::get('p_date_format'),strtotime($job_completion_date)) !!}</li>
                        <li class="list-group-item"><strong>Batch No:</strong> {!! $batch_no !!}</li>
                        <li class="list-group-item"><strong>Status:</strong> 
                            <span class="badge 
                                @if($job_status=='Completed') bg-success 
                                @elseif($job_status=='Pending') bg-warning 
                                @else bg-secondary @endif">
                                {!! $job_status !!}
                            </span>
                        </li>
                        <li class="list-group-item"><strong>Remarks:</strong> {!! $remarks !!}</li>
                    </ul>
                </div>

                <!-- Column 2 -->
                <div class="col-md-4">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Product:</strong> {!! $assembly_product !!}</li>
                        <li class="list-group-item"><strong>UOM:</strong> {!! $uom_code_id !!}</li>
                        <li class="list-group-item"><strong>Job Qty:</strong> {!! $job_qty !!}</li>
                        <li class="list-group-item"><strong>Adjusted Qty:</strong> {!! $job_adjusted_qty !!}</li>
                        <li class="list-group-item"><strong>Created By:</strong> {!! $job_created_by !!}</li>
                        <li class="list-group-item"><strong>Assigned To:</strong> {!! $assigned_name !!}</li>
                    </ul>
                </div>

                <!-- Column 3 -->
                <div class="col-md-4">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>BOM Product:</strong> {!! $bom_product_id !!}</li>
                        <li class="list-group-item"><strong>Machine:</strong> {!! $machine !!}</li>
                        <li class="list-group-item"><strong>Capacity:</strong> {!! $machine_capacity !!}</li>
                        <li class="list-group-item"><strong>Hour(s):</strong> {!! $hour !!}</li>
                        @if($bom_process=='FINALPROCESS')
                            <li class="list-group-item"><strong>Packing Box Qty:</strong> {!! $kitpack_no !!}</li>
                        @endif
                    </ul>
                </div>

            </div>
        </div>
    </div>
</form>



@endsection