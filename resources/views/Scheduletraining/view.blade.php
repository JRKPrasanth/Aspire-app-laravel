@extends('layouts.header')
@section('content')

<form>
    {{ csrf_field() }}

    <div class="card shadow rounded-4">
        <div class="card-header d-flex justify-content-between align-items-center bg-primary">
            <h5 class="mb-0 text-white">Training Schedule Details</h5>
            <a href="../scheduletraining" class="btn btn-sm btn-danger">Close</a>
        </div>

        <div class="card-body">
            <div class="container-fluid px-0">

                <div class="row mb-4">
                    <div class="col-md-4">
                        <p><strong>Topic:</strong> {{ $values->topic_name }}</p>
                        <p><strong>Schedule Date:</strong> {{ $values->schedule_date }}</p>
                    </div>
                    <div class="col-md-4">
                        <p>
                            <strong>Trainer Name:</strong>
                            {{ $values->trainer_type == 'Internal' ? $values->trainer_names : $values->trainer_name }}
                        </p>
                        <p><strong>Remarks:</strong> {{ $values->remarks }}</p>
                        <p><strong>Description:</strong> {{ $values->description }}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Created By:</strong> {{ $created_by }}</p>
                    </div>
                </div>

                <!-- Section: Additional Details -->
                <h5 class="mb-3 border-bottom pb-2 text-danger">Additional Details</h5>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <p><strong>Need Exam:</strong> {{ $values->need_exam }}</p>
                        <p><strong>Start Time:</strong> {{ $values->start_time }}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Trainer Type:</strong> {{ $values->trainer_type }}</p>
                        <p><strong>End Time:</strong> {{ $values->end_time }}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Reference:</strong> {{ $values->reference_id }}</p>
                    </div>
                </div>

                <!-- Section: Employee Table -->
                <h5 class="mb-3 border-bottom pb-2 text-danger">Employee Attendance</h5>
                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light topfreeze">
                            <tr>
                                <th>S.No</th>
                                <th>Employee Name</th>
                                <th>Active</th>
                                <th>Attend Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vlinesdata as $key => $value)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $value->first_name }}</td>
                                    <td>{{ $active }}</td>
                                    <td>{{ $value->attend_status ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div> <!-- /.container-fluid -->
        </div> <!-- /.card-body -->
    </div> <!-- /.card -->
</form>

@endsection
