@extends('layouts.header')
@section('content')


<form>
    {{ csrf_field() }}

    <div class="card shadow-lg border-0 rounded-3">
        <!-- Header -->
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Machine Details</h5>
            <a href="{{ URL::to('machine') }}" class="btn btn-sm btn-danger">
                <i class="bi bi-x-lg"></i> Close
            </a>
        </div>

        <!-- Body -->
        <div class="card-body">

            <!-- Top Details -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <p><strong>Machine Code:</strong> {!! $machine_code !!}</p>
                    <p><strong>Remarks:</strong> {!! $remarks !!}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Machine Name:</strong> {!! $machine_name !!}</p>
                    <p><strong>Created By:</strong> {!! $created_by !!}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Assigned To:</strong> {!! $assigned_name !!}</p>
                    <p><strong>Electricity Cost:</strong> {!! $electricity_cost !!}</p>
                </div>
            </div>

            <!-- Line Details -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Line No</th>
                            <th>Product Type</th>
                            <th>Machine Capacity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($linesdata as $key=>$value)
                        <tr>
                            <td>{{ $value->line_no }}</td>
                            <td>{{ $value->product_type_name }}</td>
                            <td>{{ $value->machine_capacity }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</form>




@endsection


