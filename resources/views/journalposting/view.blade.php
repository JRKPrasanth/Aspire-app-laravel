@extends('layouts.header')
@section('content')
    <h3 class="text-danger">Journal Posting</h3>
    @include('layouts.breadcrumb')

    <div class="container-fluid py-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0"><i class="bi bi-journal-text me-2"></i>Journal Posting Details</h4>
                <a href="../journalposting" class="btn btn-danger btn-sm">
                    <i class="bi bi-x-lg"></i> Close
                </a>
            </div>

            <input type="hidden" class="journal_entry_id" name="journal_entry_id" value="{!! $journal_entry_id!!}">

            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="border rounded p-3 bg-light">
                            <p class="mb-2"><strong>Journal Name:</strong> {!! $journal_name !!}</p>
                            <p class="mb-2"><strong>Journal Date:</strong> {!! $journal_date !!}</p>
                            <p class="mb-0"><strong>Journal Type:</strong> {!! $journal_type !!}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3 bg-light">
                            <p class="mb-2"><strong>Journal Reference:</strong> {!! $journal_reference !!}</p>
                            <p class="mb-0"><strong>Journal Status:</strong>
                                <span
                                    class="badge bg-{{ $journal_status == 'POSTED' ? 'success' : ($journal_status == 'REVERSED' ? 'danger' : 'warning') }}">
                                    {!! $journal_status !!}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle text-center shadow-sm">
                        <thead class="table-primary">
                            <tr>
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
                                    <td>{{ $account_id }}</td>
                                    <td>{{ number_format($value->debit_amount, 2) }}</td>
                                    <td>{{ number_format($value->credit_amount, 2) }}</td>
                                </tr>
                            @endforeach

                            <tr class="fw-bold table-light">
                                <td class="text-end">Total</td>
                                <td>{{ number_format($debit_total, 2) }}</td>
                                <td>{{ number_format($credit_total, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="text-center">
                    <button type="button" id="post" class="btn btn-success px-4 py-2" data-value="POSTED">
                        <i class="bi bi-upload me-2"></i>Post Journal
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')

    <script type="text/javascript">
        $(document).ready(function () {
            $("#post").click(function () {
                var id = $(".journal_entry_id").val();
                var status = $(this).data('value');
                if (id) {
                    var $btn = $(this);
                    $btn.prop('disabled', true);
                    var url = "{{ URL::to('getJournalpost') }}/" + id + "/" + status;
                    $.get(url, function (data) {
                        var status = data.status;
                        var msg = data.message;
                        showCustomAlert(msg, status);
                        setTimeout(function () {
                            window.location.href = "{{ url('journalposting') }}";
                        }, 1500);
                    });
                } else {
                    showCustomAlert("Please select a journal entry.", 'info');
                }
            });
        });

    </script>

@endpush