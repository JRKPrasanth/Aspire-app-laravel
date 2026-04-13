@extends('layouts.header')
@section('content')

<form>
  {{ csrf_field() }}

  <div class="col-lg-12">
    <div class="card shadow rounded-4">
      <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
        <h5 class="mb-0">Machine Details</h5>
        <a href="{{ URL::to('machine') }}" class="btn btn-danger btn-sm">Close</a>
      </div>

      <div class="card-body">
        <div class="row">
          <div class="col-12">
            <div id="section-to-print">
              <h5 class="mb-4 text-secondary border-bottom pb-2">Machine Details</h5>

              <div class="row mb-4">
                <div class="col-md-6">
                  <p><strong>Machine Code:</strong> {{ $machine_code }}</p>
                  <p><strong>Machine Name:</strong> {{ $machine_name }}</p>
                  <p><strong>Machine Capacity:</strong> {{ $capacity }}</p>
                </div>
                <div class="col-md-6">
                  <p><strong>Assigned To:</strong> {{ $assigned_name }}</p>
                  <p><strong>Remarks:</strong> {{ $remarks }}</p>
                  <p><strong>Organization:</strong> {{ $organization_id }}</p>
                </div>
              </div>

              <h5 class="mb-3 text-secondary">Line Product Details</h5>
              <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                  <thead class="table-dark">
                    <tr>
                      <th scope="col">Line No</th>
                      <th scope="col">Product Type</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($linesdata as $key => $value)
                      <tr>
                        <td>{{ $value->line_no }}</td>
                        <td>{{ $value->product_type_name }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>

            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</form>

@endsection
