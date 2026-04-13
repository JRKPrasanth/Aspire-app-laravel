@extends('layouts.header')
@section('content')
  <h3 class="text-danger">
    Ledger Report
  </h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">

    <div class="card-body p-4">
      <form method="post" action="" id="job_card_reprot" class="needs-validation" novalidate
        enctype="multipart/form-data">
        @csrf

        <!-- First Row: Date Inputs -->
        <div class="row g-4 mb-3">
          <div class="col-md-2"></div>
          <div class="col-md-4">
            <label for="start_date" class="form-label fw-semibold">From Date</label>
            <input type="text" class="form-control start_date" id="start_date" name="start_date" required
              autocomplete="off">
            <div class="invalid-feedback">Please select a start date.</div>
          </div>

          <div class="col-md-4">
            <label for="end_date" class="form-label fw-semibold">To Date</label>
            <input type="text" class="form-control end_date" id="end_date" name="end_date" required autocomplete="off">
            <div class="invalid-feedback">Please select an end date.</div>
          </div>
        </div>

        <!-- Second Row: Centered Search Button -->
        <div class="row">
          <div class="col-md-12 text-center">
            <button type="button" class="btn btn-success px-4 report_search" id="report_search">
              <i class="bi bi-search-heart me-1"></i> Search
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
              <th class="freeze">Main Account</th>
              <th>Sub1</th>
              <th>Sub2</th>
              <th>Sub3</th>
              <th>Sub4</th>
              <th>Opening Balance</th>
              <th>Debit</th>
              <th>Credit</th>
              <th>Balance</th>


            </tr>
            <tr class="table-danger">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Main Account</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Sub1</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Sub2</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Sub3</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Sub4</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Opening
                  Balance</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Debit</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Credit</span>
              </th>
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
          url: "{{ url('journalledgerreport') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [
          { class: 'freeze', data: "main" },
          { data: "sub1" },
          { data: "sub2" },
          { data: "sub3" },
          { data: "sub4" },
          { data: "opening_balance" },
          { data: "debit" },
          { data: "credit" },
          { data: "balance" },
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