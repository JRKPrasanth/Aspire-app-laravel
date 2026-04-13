@extends('layouts.header')
@section('content')
  <h3 class="text-danger">
    Cash Book
  </h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-sm rounded-4 border-0">

    <div class="card-body p-4">
      <form id="filterForm">
        <div class="row g-4 align-items-center">

          <!-- From Date -->
          <div class="col-md-4">
            <label for="start_date" class="form-label fw-semibold">From Date</label>
            <input type="text" class="form-control start_date" id="start_date" name="start_date" autocomplete="off"
              required>
          </div>

          <!-- To Date -->
          <div class="col-md-4">
            <label for="end_date" class="form-label fw-semibold">To Date</label>
            <input type="text" class="form-control end_date" id="end_date" name="end_date" autocomplete="off" required>
          </div>

          <!-- Cash Checkbox -->
          <div class="col-md-4">
            <label class="form-label fw-semibold d-block">Cash</label>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="cash" name="cash" checked>
              <label class="form-check-label" for="cash">Include Cash</label>
            </div>
          </div>
        </div>

        <!-- Search Button -->
        <div class="row mt-4">
          <div class="col text-center">
            <button type="button" class="btn btn-success px-4 report_search" id="search">
              <i class="bi bi-search"></i> Search
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3"></div>
      <div class="table-responsive">
        <table id="ReportTbl" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">
              <th class="freeze">Journal Name</th>
              <th>Date</th>
              <th>Reference Source</th>
              <th>Reference Name</th>
              <th>Narration</th>
              <th>Account</th>
              <th>Debit Amount</th>
              <th>Credit Amount</th>
              <th>Balance</th>
            </tr>
            <tr class="table-danger">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Journal Name</span></th>
              <th><input type="text" class="column-search start_date" placeholder="Search"><span
                  style="display:none;">Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Reference
                  Source</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Reference
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Narration</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Account</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Debit
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Credit
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Balance</span>
              </th>


            </tr>
          </thead>

          <tbody>
            <!-- Your dynamic row data goes here -->
          </tbody>
        </table>
      </div>
    </div>
  </div>

@endsection
@push('scripts')

  <script>

    $(document).ready(function () {

      var table = $('#ReportTbl').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: {
          url: "{{ url('getaccounttransaction') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
            d.cash = $('#cash').is(':checked') ? '1' : '0';

          }
        },
        columns: [
          { class: 'freeze', data: "journal_name" },
          { data: "journal_date" },
          { data: "reference_source" },
          { data: "reference_name" },
          { data: "narration" },
          { data: "concatenated_segments" },
          { data: "debit_amounts" },
          { data: "credit_amounts" },
          { data: "balance" }


        ],
        initComplete: function () {
          var api = this.api();

          // get the real visible header inside the scroll container
          var $scrollHead = $(api.table().container())
            .find('.dataTables_scrollHead thead');

          // second header row (index 1) has the inputs
          $scrollHead.find('tr:eq(1) th').each(function (colIndex) {
            var th = this;
            $('input.column-search', th).on('keyup change', function () {
              if (api.column(colIndex).search() !== this.value) {
                api.column(colIndex).search(this.value).draw();
              }
            });
          });
        }
      });

      // Trigger search
      $('.report_search').on('click', function () {
        $('#ReportTbl').DataTable().ajax.reload();
      });

    });


  </script>

@endpush