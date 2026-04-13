@extends('layouts.header')
@section('content')


<form>
    <div class="card shadow-lg rounded-4 border-0">
        <!-- Card Header -->
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Price List Details</h5>
            <div>
                @if($urlname=="purchasepricelist" || $urlname=="purchasepricelistview")
                    <a href="{{ url('purchasepricelist') }}" class="btn btn-danger btn-sm">Close</a>
                @elseif($urlname=="salespricelistcopyview")
                    <a href="{{ url('salespricelistcopy') }}" class="btn btn-danger btn-sm">Close</a>
                @elseif($urlname=="purchasepricelistcopyview")
                    <a href="{{ url('purchasepricelistcopy') }}" class="btn btn-danger btn-sm">Close</a>
                @else
                    <a href="{{ url('salespricelist') }}" class="btn btn-danger btn-sm">Close</a>
                @endif
            </div>
        </div>

        <!-- Card Body -->
        <div class="card-body">
            <!-- Summary Section -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <p class="mb-1"><strong>Price List Name:</strong> {!! $pricelist_name !!}</p>
                    <p class="mb-1"><strong>Active:</strong> {!! $active !!}</p>
                </div>
                <div class="col-md-4">
                    <p class="mb-1"><strong>Description:</strong> {!! $description !!}</p>
                    <p class="mb-1"><strong>Created By:</strong> {!! $username !!}</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <p class="mb-1"><strong>Price List Type:</strong> {!! $price_list_type !!}</p>
                </div>
            </div>

            <!-- Line Items Table -->
            <div class="table-responsive" style="height: 500px;">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-primary sticky">
                        <tr>
                            <th width="57">Line No</th>
                            <th width="350">Product Name</th>
                            @if($price_list_type=="Sales")
                           <th>Batch Number</th>
                            @endif
                            <th>Unit Price</th>
                            <th>Std Price</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Active</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($linedata as $key=>$value)
                            <tr title="Scroll to see all price list details">
                                <td>{{ $key+1 }}</td>
                                <td>{{ $value->concatenated_product }}</td>
                                @if($price_list_type=="Sales")
                                    <td>{{ $value->batch_number }}</td>
                                @endif
                                <td>{{ $value->unit_price }}</td>
                                <td>{{ $value->std_price }}</td>
                                <td>{{ date(\Session::get('p_date_format'),strtotime($value->start_date)) }}</td>
                                <td>{{ date(\Session::get('p_date_format'),strtotime($value->end_date)) }}</td>
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
