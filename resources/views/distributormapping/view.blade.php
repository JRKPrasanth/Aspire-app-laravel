@extends('layouts.header')
@section('content')

<form>
    {{ csrf_field() }}

    <div class="card shadow-lg rounded-4 border-0 p-4 mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-danger">Distributor Mapping Details</h5>
            <a href="{{ url('distributorbeatmapping') }}" class="btn btn-danger btn-sm">Close</a>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Employee Name:</strong> {!! $headerdata->first_name !!}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Description:</strong> {!! $headerdata->description !!}</p>
                </div>
            </div>

            <div class="mb-3">
                <h5 class="fw-bold">Additional Details</h5>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr class="table-warning">
                            <th>Line No</th>
                            <th>State Name</th>
                            <th>Town Name</th>
                            <th>Distributor Name</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($linesdata as $key => $value)
                            <tr>
                                <td>{!! $key + 1 !!}</td>
                                <td>{!! $value->state_name !!}</td>
                                <td>{!! $value->city_name !!}</td>
                                <td>{!! $value->customer_name !!}</td>
                                <td>{!! $value->description !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</form>

@endsection
