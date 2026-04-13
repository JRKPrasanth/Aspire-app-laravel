@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Journal Reverse</h3>
  @include('layouts.breadcrumb')

  <div class="container-fluid py-4">

    <div class="card shadow-lg border-0 rounded-4">
      <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-4">
        <h4 class="mb-0"><i class="bi bi-journal-text me-2"></i>Journal Details</h4>
        <a href="../journalreverse" class="btn btn-danger btn-sm">
          <i class="bi bi-x-lg"></i> Close
        </a>
      </div>

      <input type="hidden" class="journal_entry_id" name="journal_entry_id" value="{!! $journal_entry_id !!}">
      <input type="hidden" class="account_id" name="account_id" value="{!! $account_id !!}">

      <div class="card-body">
        <div class="row mb-4">
          <div class="col-md-6">
            <div class="border p-3 rounded-3 bg-light-subtle">
              <p class="mb-2"><strong>Journal Name:</strong> <span class="text-dark">{!! $journal_name !!}</span></p>
              <p class="mb-2"><strong>Journal Date:</strong> <span class="text-dark">{!! $journal_date !!}</span></p>
              <p class="mb-0"><strong>Journal Type:</strong> <span class="text-dark">{!! $journal_type !!}</span></p>
            </div>
          </div>

          <div class="col-md-6">
            <div class="border p-3 rounded-3 bg-light-subtle">
              <p class="mb-2"><strong>Journal Reference:</strong> <span
                  class="text-dark">{!! $journal_reference !!}</span></p>
              <p class="mb-0"><strong>Journal Status:</strong>
                @if($journal_status == 'APPROVED')
                  <span class="badge bg-success">{{ $journal_status }}</span>
                @elseif($journal_status == 'REJECTED')
                  <span class="badge bg-danger">{{ $journal_status }}</span>
                @elseif($journal_status == 'SUBMITTED')
                  <span class="badge bg-warning text-dark">{{ $journal_status }}</span>
                @else
                  <span class="badge bg-secondary">{{ $journal_status }}</span>
                @endif
              </p>
            </div>
          </div>
        </div>

        <h5 class="fw-bold mb-3"><i class="bi bi-list-check me-2"></i>Journal Line Details</h5>

        <div class="table-responsive">
          <table class="table table-striped table-bordered align-middle">
            <thead class="table-primary text-center">
              <tr>
                <th>Date</th>
                <th>Account</th>
                <th>Debit Amount</th>
                <th>Credit Amount</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($vlinesdata as $key => $value)
                <tr>
                  <td>{{ $journal_date }}</td>
                  <td>{{ $concatenated_segments }}</td>
                  <td class="text-end">{{ number_format($value->debit_amount, 2) }}</td>
                  <td class="text-end">{{ number_format($value->credit_amount, 2) }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="text-center mt-4">
          <button type="button" id="reverse" class="btn btn-success px-4">
            <i class="bi bi-arrow-repeat me-2"></i>Reverse
          </button>
        </div>

      </div>
    </div>

  </div>

@endsection
@push('scripts')

  <script type="text/javascript">

    $(document).ready(function () {
      $("#reverse").click(function () {
        var id = $(".journal_entry_id").val();
        var account = $(".account_id").val();
        var debit = $(".debit_amount").val();
        var credit = $(".credit_amount").val();

        if (id) {
          var $btn = $(this);
          $btn.prop('disabled', true);
          var url = "{{ URL::to('reversejournal') }}/" + id;
          $.get(url, function (data) {
            var status = data.status;
            var msg = data.message;
            showCustomAlert(msg, status);
            setTimeout(function () {
              window.location.href = "{{ url('journalreverse') }}";
            }, 1500);
          });
        } else {
          showCustomAlert("info", "Please Select Row");
        }
      });
    });
  </script>

@endpush