@extends('layouts.header')
@section('content')
<h3 class="text-danger">Consumable Details</h3>
@include('layouts.breadcrumb')

	<div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Consumable Details</h6>
            <a href="../consumable" class="btn btn-sm btn-danger">Close</a>
        </div>

    <div class="card-body">
        <!-- Info Section -->
        <div class="row mb-4">
            <div class="col-md-4">
                <p class="mb-1"><strong>Consumable Number:</strong> {!! $consumable_number !!}</p>
            </div>
            <div class="col-md-4">
                <p class="mb-1"><strong>Date:</strong> {!! date(\Session::get('p_date_format'),strtotime($consumable_date)) !!}</p>
            </div>
            <div class="col-md-4">
                <p class="mb-1"><strong>Status:</strong> {!! $status !!}</p>
            </div>
        </div>

        <!-- Lines Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Line No</th>
                        <th>Product</th>
                        <th>Batch No</th>
                        <th>Subinventory</th>
                        <th>Locator</th>
                        <th>QOH</th>
                        <th>Qty</th>
                        <th>Comments</th>
                        <th>Account</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($linesdata as $key => $value)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $value->product_code }} - {{ $value->concatenated_product }}</td>
                            <td>{{ $value->batch_no }}</td>
                            <td>{{ $value->subinventory_name }}</td>
                            <td>{{ $value->locator_code }}</td>
                            <td>{{ $value->qoh }}</td>
                            <td>{{ $value->qty }}</td>
                            <td>{{ $value->comments }}</td>
                            <td>{{ $value->concatenated_segments }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection