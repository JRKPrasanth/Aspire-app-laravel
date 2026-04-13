@extends('layouts.header')
@section('content')

<form>
    {{ csrf_field() }}

         <div class="card shadow-lg rounded-4 border-0 p-4 mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
			<h4 class="mb-4 text-danger">Product Mapping Details</h4>
            <a href="{{ url('productmapping') }}" class="btn btn-sm btn-danger">Close</a>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12">

                    <div id="section-to-print">
                        

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Employee Name:</strong> {!! $headerdata->first_name !!}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Description:</strong> {!! $headerdata->description !!}</p>
                            </div>
                        </div>

                        <h5 class="mb-3 text-danger">Additional Details</h5>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Line No</th>
                                        <th>Product Group</th>
                                        <th>Product Category</th>
                                        <th>Product Name</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($linesdata as $key => $value)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $value->group_name }}</td>
                                            <td>{{ $value->category_name }}</td>
                                            <td>{{ $value->concatenated_product }}</td>
                                            <td>{{ $value->description }}</td>
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
</form>

@endsection
