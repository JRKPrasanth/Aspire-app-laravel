@extends('layouts.header')
@section('content')

<form>
  {{ csrf_field() }}

<div class="card shadow-lg rounded-4 border-0">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="text-danger mb-0">Common Lookup Details</h5>
      <a href="{{ url('lookup') }}" class="btn btn-danger btn-sm">
        <i class="bi bi-x-lg"></i> Close
      </a>
    </div>

    <div class="card-body">
      <div class="mb-4 text-center">
        <h6 class="text-muted">Field Option: <strong>{{ $vdata[0]->lookup_type }}</strong></h6>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead class="table-light">
            <tr>
              <th scope="col">Line No</th>
              <th scope="col">Field Option</th>
              <th scope="col">Field Option Meaning</th>
              <th scope="col">Active</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($vlinesdata as $key => $value)
              <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $value->lookup_code }}</td>
                <td>{{ $value->lookup_meaning }}</td>
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













