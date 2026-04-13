@extends('layouts.header')
@section('content')

<div class="card shadow-lg rounded-4 border-0">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0 text-danger">Machine Checklist</h4>
        <a href="{{ URL::to('pmchecksheet') }}" class="btn btn-danger btn-sm">
            <i class="bi bi-x-circle"></i> Close
        </a>
    </div>

    <div class="card-body">
        <div class="mb-4">
            <div class="row mb-2">
                <div class="col-md-4">
                    <p><strong>Department Name:</strong> {{ $header->department_name }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Machine Name:</strong> {{ $header->machine_name }}</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Frequency Name:</strong> {{ $header->frequency_name }}</p>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 10%;">Line No</th>
                        <th>Checklist</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($linesdata as $key => $value)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $value->checklist_name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
