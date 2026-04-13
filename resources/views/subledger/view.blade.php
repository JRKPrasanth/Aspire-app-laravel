@extends('layouts.header')
@section('content')
<h3 class="text-danger">Sub Ledger</h3>
@include('layouts.breadcrumb')

<div class="container-fluid py-4">
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="bi bi-journal-bookmark me-2"></i>Journal Entry Details</h4>
            <a href="../subledger" class="btn btn-danger btn-sm">
                <i class="bi bi-x-lg"></i> Close
            </a>
        </div>

        <div class="card-body">
            <!-- Journal Header Info -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="border rounded p-3 bg-light">
                        <p class="mb-2"><strong>Journal Name:</strong> {!! $journal_name !!}</p>
                        <p class="mb-0"><strong>Journal Status:</strong> 
                            <span class="badge bg-{{ $journal_status == 'POSTED' ? 'success' : ($journal_status == 'REVERSED' ? 'danger' : 'warning') }}">
                                {!! $journal_status !!}
                            </span>
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3 bg-light">
                        <p class="mb-2"><strong>Journal Date:</strong> {!! $journal_date !!}</p>
                        <p class="mb-0"><strong>Journal Type:</strong> {!! $journal_type !!}</p>
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-center justify-content-center">
                    <h5 class="text-primary fw-semibold mb-0">Journal Entry Summary</h5>
                </div>
            </div>

            <!-- Journal Lines Table -->
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center shadow-sm">
                    <thead class="table-primary">
                        <tr>
                            <th>Line No</th>
                            <th>Date</th>
                            <th>Account</th>
                            <th>Debit Amount</th>
                            <th>Credit Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $debit_total = 0;
                            $credit_total = 0;
                        @endphp
                        @foreach ($vlinesdata as $key => $value)
                            @php
                                $debit_total += $value->debit_amount;
                                $credit_total += $value->credit_amount;
                            @endphp
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $value->journal_date }}</td>
                                <td class="text-start">{{ $value->concatenated_segments }}</td>
                                <td>{{ number_format($value->debit_amount, 2) }}</td>
                                <td>{{ number_format($value->credit_amount, 2) }}</td>
                            </tr>
                        @endforeach
                        <tr class="fw-bold table-light">
                            <td colspan="3" class="text-end">Total</td>
                            <td>{{ number_format($debit_total, 2) }}</td>
                            <td>{{ number_format($credit_total, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
