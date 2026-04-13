@extends('layouts.header')
@section('content')
<h3 class="text-danger">Scheme Details</h3>
@include('layouts.breadcrumb')


<form>
  <div class="card shadow-sm rounded-4 border-0">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Scheme Details</h5>
      <a href="{{ url('schemes') }}" class="btn btn-danger btn-sm">
        Close
      </a>
    </div>

    <div class="card-body">
      <div id="section-to-print">
        <!-- Scheme Info -->
        <div class="row mb-4">
          <div class="col-md-6">
            <p><strong>Schemes Name:</strong> {!! $row->schemes_name !!}</p>
            <p><strong>Start Date:</strong> 
              {{ date(\Session::get('p_date_format'), strtotime($row->start_date)) }}
            </p>
          </div>
          <div class="col-md-6 text-md-end">
            <p><strong>End Date:</strong> 
              {{ date(\Session::get('p_date_format'), strtotime($row->end_date)) }}
            </p>
            <p><strong>Location:</strong> {!! $row->location !!}</p>
          </div>
        </div>

        <!-- Scheme Line Details -->
        <div class="table-responsive">
          <table class="table table-bordered table-striped align-middle">
            <thead class="table-primary">
              <tr>
                <th>Line No</th>
                <th>Product Name</th>
                <th>Scheme Base</th>
                <th>Scheme Base Value From</th>
                <th>Scheme Base Value To</th>
                <th>Scheme Type</th>
                <th>Scheme Type Value</th>
                <th>Comments</th>
              </tr>
            </thead>
            <tbody>
              @foreach($linedata as $key => $value)
              <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $value->product_id }}</td>
                <td>{{ $value->scheme_base }}</td>
                <td>{{ $value->scheme_base_value_from }}</td>
                <td>{{ $value->scheme_base_value_to }}</td>
                <td>{{ $value->schemes_type }}</td>
                <td>{{ $value->schemes_type_value }}</td>
                <td>{{ $value->comments }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</form>

				

@endsection
