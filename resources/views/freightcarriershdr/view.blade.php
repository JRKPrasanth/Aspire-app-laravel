@extends('layouts.header')
@section('content')
<h3 class="text-danger">Freightcarrier Details</h3>
@include('layouts.breadcrumb')

<form>
    {{ csrf_field() }}

   <div class="card shadow-lg rounded-4 border-0">
        <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
            <h5 class="mb-0 text-white text-center">Freight Carrier Details</h5>
            @if($urlname == 'purchasefreightcarriershdrview')
                <a href="../purchasefreightcarriershdr" class="btn btn-sm btn-danger">Close</a>
            @else
                <a href="../freightcarriershdr" class="btn btn-sm btn-danger">Close</a>
            @endif
        </div>

        <div class="card-body">
            <div id="section-to-print" class="invoice-box">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <p><strong>Carrier Name:</strong> {{ $values['carrier_name'] }}</p>
                        <p><strong>Source Type:</strong> {{ $values['source_type_id'] }}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Active:</strong> {{ $values['active'] }}</p>
                        <p><strong>Remarks:</strong> {{ $values['remarks'] }}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Created By:</strong> {{ $created_by }}</p>
                    </div>
                </div>

                <h5 class="mb-3 text-primary">Additional Details</h5>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <p><strong>Location:</strong> {{ $location_name }}</p>
                        <p><strong>Default Currency:</strong> {{ $currency_code }}</p>
                        <p><strong>Shipping Method:</strong> {{ $values['shipping_method'] }}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Charging UOM:</strong> {{ $values['charging_uom'] }}</p>
                        <p><strong>Charging Rating:</strong> {{ $values['charging_rating'] }}</p>
                        <p><strong>Rating Value:</strong> {{ $values['charging_rating_value'] }}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Minimum Time:</strong> {{ $values['minimum_time'] }}</p>
                        <p><strong>Minimum Distance:</strong> {{ $values['minimum_distance'] }}</p>
                        <p><strong>Reliability %:</strong> {{ $values['reliablity_percentage'] }}</p>
                    </div>
                </div>

                <h5 class="mb-3 text-primary">Carrier Lines</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Sno</th>
                                <th>Carrier Number</th>
                                <th>Registration</th>
                                <th>Permit Number</th>
                                <th>Address</th>
                                <th>Country</th>
                                <th>State</th>
                                <th>City</th>
                                <th>Active</th>
                                <th>Comments</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vlinesdata as $key => $value)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{!! $value->carrier_number !!}</td>
                                    <td>{!! $value->carrier_registration !!}</td>
                                    <td>{!! $value->permit_number !!}</td>
                                    <td>{!! $value->carrier_address !!}</td>
                                    <td>{!! $value->country_name !!}</td>
                                    <td>{!! $value->state_name !!}</td>
                                    <td>{!! $value->city_name !!}</td>
                                    <td>{{ $value->active == "1" ? "Yes" : "No" }}</td>
                                    <td>{!! $value->comments !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div> <!-- /section-to-print -->
        </div> <!-- /card-body -->
    </div> <!-- /card -->
</form>
  

@endsection
