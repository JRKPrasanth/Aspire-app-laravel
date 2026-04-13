@extends('layouts.header')
@section('content')


<form>
    {{ csrf_field() }}

    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
            <h5 class="mb-0">Production Plan</h5>
            <a href="{{ URL::to($_GET['pageurl']) }}" class="btn btn-sm btn-danger">
                <i class="bi bi-x-lg"></i>
            </a>
        </div>

        <div class="card-body">
            <div class="row g-4">
                <!-- Left Column -->
                <div class="col-md-4">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Plan No:</strong> {!! $row->plan_no !!}</li>
                        <li class="list-group-item"><strong>Product:</strong> {!! $productid !!}</li>
                        <li class="list-group-item"><strong>Batch No:</strong> {!! $row->batch_no !!}</li>
                        <li class="list-group-item"><strong>Workorder Due Date:</strong> {!! date(\Session::get('p_date_format'),strtotime($row->workorder_due_date)) !!}</li>
                    </ul>
                </div>

                <!-- Middle Column -->
                <div class="col-md-4">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Plan Date:</strong> {!! date(\Session::get('p_date_format'),strtotime($row->plan_date)) !!}</li>
                        <li class="list-group-item"><strong>UOM:</strong> {!! $uom !!}</li>
                        <li class="list-group-item"><strong>Start Date:</strong> {!! date(\Session::get('p_date_format'),strtotime($row->start_date)) !!}</li>
                        <li class="list-group-item"><strong>End Date:</strong> {!! date(\Session::get('p_date_format'),strtotime($row->end_date)) !!}</li>
                    </ul>
                </div>

                <!-- Right Column -->
                <div class="col-md-4">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Wo Reference No:</strong> {!! $row->reference_no !!}</li>
                        <li class="list-group-item"><strong>Production Qty:</strong> {!! $row->production_qty !!}</li>
                        <li class="list-group-item"><strong>Remarks:</strong> {!! $row->remarks !!}</li>
                    </ul>
                </div>
            </div>

            <!-- Line Items Table -->
            <div class="mt-5">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th scope="col">Line No</th>
                                <th scope="col">Product</th>
                                <th scope="col">UOM Code</th>
                                <th scope="col">Qty</th>
                                <!-- <th>Production Qty</th>
                                <th>Pending Qty</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($linedata as $key => $value)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $value->product_id }}</td>
                                    <td>{{ $value->uom_code_id }}</td>
                                    <td class="text-end">{{ $value->qty }}</td>
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