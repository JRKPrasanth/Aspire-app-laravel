@extends('layouts.header')
@section('content')
<form>
    {{ csrf_field() }}
<div class="card shadow-lg rounded-4 border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-danger">Approval Details</h5>
            <a href="{{ URL::to('approvalsettings') }}" class="btn btn-sm btn-danger">
                 Close
            </a>
        </div>

        <div class="card-body">
            <div class="mb-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <p><strong>Module Name:</strong> {{ $module_name }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Created By:</strong> {{ $created_by }}</p>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">S. No</th>
                            <th scope="col">Value From</th>
                            <th scope="col">Value To</th>
                            <th scope="col">Approve Required</th>
                            <th scope="col">Approver</th>
                            <th scope="col">Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($linesdata as $key => $value)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{!! $value->value_from !!}</td>
                                <td>{!! $value->value_to !!}</td>
                                <td>{!! $value->approve_required !!}</td>
                                <td>{!! $approver !!}</td>
                                <td>{!! $value->comments !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>
@endsection

