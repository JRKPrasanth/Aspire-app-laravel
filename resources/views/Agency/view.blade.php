@extends('layouts.header')
@section('content')

<form>
  {{ csrf_field() }}

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0 text-danger">Agency Details</h5>
      <a href="{{ URL::to('agency') }}" class="btn btn-danger btn-sm">Close</a>
    </div>

    <div class="card-body">
      <div class="row gy-4">
        <div class="col-md-4">
          <div class="mb-2">
            <strong>Agency Name:</strong>
            <div>{!! $header->agency_name !!}</div>
          </div>
          <div class="mb-2">
            <strong>Mobile No:</strong>
            <div>{!! $header->mobile_no !!}</div>
          </div>
          <div class="mb-2">
            <strong>Country:</strong>
            <div>{!! $header->country_name !!}</div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="mb-2">
            <strong>State:</strong>
            <div>{!! $header->state_name !!}</div>
          </div>
          <div class="mb-2">
            <strong>City:</strong>
            <div>{!! $header->city_name !!}</div>
          </div>
          <div class="mb-2">
            <strong>Email:</strong>
            <div>{!! $header->email !!}</div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="mb-2">
            <strong>Address:</strong>
            <div>{!! $header->address !!}</div>
          </div>
          <div class="mb-2">
            <strong>Active:</strong>
            <div>{!! $header->active !!}</div>
          </div>
          <div class="mb-2">
            <strong>Created By:</strong>
            <div>{!! $header->username !!}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>

@endsection
