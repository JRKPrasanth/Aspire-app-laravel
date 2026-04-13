@extends('layouts.header')
@section('content')
<h3 class="text-danger">Bank Account Details</h3>
@include('layouts.breadcrumb')


<form>
    {{ csrf_field() }}

		<div class="card shadow-lg rounded-4 border-0">
        <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
            <h5 class="mb-0 fw-bold">Bank Account Details</h5>
            <a href="../bankaccount" class="btn btn-sm btn-danger">
                <i class="bi bi-x-circle"></i>
            </a>
        </div>

        <div class="card-body">
            <!-- Top Bank Info -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <p><strong>Bank Name:</strong> {!! $bank_name !!}</p>
                    <p><strong>Supplier Name:</strong> {!! $supplier_name !!}</p>
                    <p><strong>Active:</strong> {!! $active !!}</p>
                </div>

                <div class="col-md-4">
                    <p><strong>Bank Type:</strong> {!! $bank_type !!}</p>
                    <p><strong>Customer Name:</strong> {!! $customer_name !!}</p>
                    <p><strong>Created By:</strong> {!! $username !!}</p>
                </div>

                <div class="col-md-4">
                    <p><strong>Bank Source:</strong> {!! $bank_source !!}</p>
                    <p><strong>Company Name:</strong> {!! $company_name !!}</p>
                </div>
            </div>

            <!-- Branch Details Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th>Branch Name</th>
                            <th>Branch Address</th>
                            <th>IFSC Code</th>
                            <th>MICR Code</th>
                            <th>Account Type</th>
                            <th>Name In Account</th>
                            <th>Nickname</th>
                            <th>Account Number</th>
                            <th>Favouring Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Active</th>
                            <th>Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vlinesdata as $key => $value)
                        <tr>
                            <td>{{ $value->branch_name }}</td>
                            <td>{{ $value->branch_address }}</td>
                            <td>{{ $value->ifsc_code }}</td>
                            <td>{{ $value->MICR_code }}</td>
                            <td>{{ $value->account_type }}</td>
                            <td>{{ $value->name_in_account }}</td>
                            <td>{{ $value->nickname_in_acoount }}</td>
                            <td>{{ $value->account_number }}</td>
                            <td>{{ $value->favouring_name }}</td>
                            <td>{{ $value->start_date }}</td>
                            <td>{{ $value->end_date }}</td>
                            <td>{{ $value->active }}</td>
                            <td>{{ $value->comments }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</form>






@endsection


