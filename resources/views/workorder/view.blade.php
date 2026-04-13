@extends('layouts.header')
@section('content')


<form>
    {{ csrf_field() }}

    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
            <h5 class="mb-0">Workorder Details</h5>
            <a href="{{ URL::to($_GET['pageurl']) }}" class="btn btn-sm btn-danger">Close</a>
        </div>

        <div class="card-body">

            <!-- Workorder Meta Info -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <p><strong>Workorder Number:</strong> {!! $workorder_no !!}</p>
                    <p><strong>Workorder Date:</strong> {!! date(\Session::get('p_date_format'),strtotime($workorder_date)) !!}</p>
                    <p><strong>Shift:</strong> {!! $shift !!}</p>
                </div>

                <div class="col-md-4">
                    <p><strong>Workorder Source:</strong> {!! $source !!}</p>
                    <p><strong>Remarks:</strong> {!! $remarks !!}</p>
                </div>

                <div class="col-md-4 text-md-end">
                    <p><strong>So Reference No:</strong> {!! $so_reference_number !!}</p>
                    <p><strong>Created By:</strong> {!! $created_by !!}</p>
                </div>
            </div>

            <!-- Line Items -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Line No</th>
                            <th>Product</th>
                            <th>UOM Code</th>
                            <th>Quantity</th>
                            <th>Due Date</th>
                            <th>Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vlinesdata as $key=>$value)
                            <tr>
                                <td>{{ $value->line_no }}</td>
                                <td>{{ $value->product_code . '-' . $value->concatenated_product }}</td>
                                <td>{{ $value->uom_code }}</td>
                                <td>{{ $value->qty }}</td>
                                <td>{{ date(\Session::get('p_date_format'), strtotime($value->due_date)) }}</td>
                                <td>{{ $value->comments }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</form>






@endsection



