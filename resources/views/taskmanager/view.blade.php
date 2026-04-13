@extends('layouts.header')
@section('content')

<form>
    {{ csrf_field() }}

   <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-danger">WebOps Track Details</h5>
            <a href="{{ url('taskmanager') }}" class="btn btn-sm btn-danger">Close</a>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12">

                    <div class="invoice-box" id="section-to-print">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Department:</strong> {{ $department }}</p>
                                <p><strong>Start Date:</strong> {{ $taskmanager->start_date }}</p>
                                <p><strong>Department Lead:</strong> {{ $department_lead }}</p>
                                <p><strong>Created By:</strong> {{ $created_by }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Assigned To:</strong> {{ $assigned_to }}</p>
                                <p><strong>End Date:</strong> {{ $taskmanager->end_date }}</p>
                                <p><strong>Task:</strong> {{ $taskmanager->task }}</p>
                                <p><strong>Description:</strong> {{ $taskmanager->description }}</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</form>

@endsection
