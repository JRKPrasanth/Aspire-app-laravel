@extends('layouts.header')
@section('content')
<h3 class="text-danger">Bank Cheque Details</h3>
@include('layouts.breadcrumb')

<form>
    {{ csrf_field() }}

    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
            <h5 class="mb-0 fw-bold">Bank Cheque Details</h5>
            <a href="../bankcheque" class="btn btn-sm btn-danger"></a>
        </div>

        <div class="card-body">
            <!-- Summary Info -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Bank Name:</strong> {{ $bank_name }}</p>
                    <p><strong>Branch Name:</strong> {{ $branch_name }}</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p><strong>Account Number:</strong> {{ $account_number }}</p>
                </div>
            </div>

            <!-- Cheque Details Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Cheque From No</th>
                            <th>Cheque To No</th>
                            <th>Cheque Book No</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vlinesdata as $value) 
                        <tr>
                            <td>{{ $value->cheque_from_no }}</td>
                            <td>{{ $value->cheque_to_no }}</td>
                            <td>{{ $value->cheque_book_no }}</td>
                            <td>{{ \Carbon\Carbon::parse($value->start_date)->format('d-m-Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($value->end_date)->format('d-m-Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>



@endsection



