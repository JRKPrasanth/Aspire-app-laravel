@extends('layouts.header')
@section('content')

		
			
<form>
    <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Subinventory & Locator Details</span>
            <a href="../subinventory" class="btn btn-danger btn-sm">
                <i class="fa fa-times"></i>
            </a>
        </div>

        <div class="card-body">
            <!-- Subinventory Info -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <p class="mb-2"><strong>Subinventory Name:</strong> {!! $subinventory_name !!}</p>
                    <p class="mb-2"><strong>Description:</strong> {!! $description !!}</p>
                    <p class="mb-2"><strong>Production Store:</strong> {!! $production_store !!}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-2"><strong>Active:</strong> {!! $active !!}</p>
                    <p class="mb-2"><strong>Created By:</strong> {!! $created_by !!}</p>
                </div>
            </div>

            <!-- Locator Table -->
            <h5 class="fw-semibold mb-3">Locator Details</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>Line No</th>
                            <th>Rack No</th>
                            <th>Row No</th>
                            <th>Bin No</th>
                            <th>Locator Code</th>
                            <th>Locator Name</th>
                            <th>Active</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vlinesdata as $key => $value)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $value->rack_no }}</td>
                                <td>{{ $value->row_no }}</td>
                                <td>{{ $value->bin_no }}</td>
                                <td>{{ $value->locator_code }}</td>
                                <td>{{ $value->locator_name }}</td>
                                <td>{{ $value->active }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>

		
		
	

@endsection