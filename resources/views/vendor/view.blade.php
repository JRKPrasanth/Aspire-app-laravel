@extends('layouts.header')
@section('content')

<form>
    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="mb-0 text-danger">Vendor Information</h2>
            <a href="{{ url('newvendor') }}" class="btn btn-danger btn-sm">
                <i class="bi bi-x-lg"></i> Close
            </a>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                        <h4 class="mb-4"></h4>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Vendor Name:</strong> {{ $headerdata->vendor_name }}</p>
                                <p><strong>Address:</strong> {{ $headerdata->address }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Mail ID:</strong> {{ $headerdata->email_id }}</p>
                                <p><strong>Contact No:</strong> {{ $headerdata->contact_no }}</p>
                                <p><strong>Active:</strong> {{ $headerdata->active }}</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</form>

@endsection













