@extends('layouts.header')
@section('content')
  <h3 class="text-danger mb-4">Employee Balance Report </h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg border-0 rounded-4 mb-4">
    <div class="card-body">
      <form method="post" action="" id="job_card_reprot" class="needs-validation" novalidate
        enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
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

        <div class="d-flex justify-content-center mt-4">
          <button type="button" class="btn btn-success bg-gradient px-4 report_search" id="report_search">
            <i class="bi bi-search-heart me-1"></i> Search
          </button>
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
              <th class="freeze">Employee Name</th>
              <th>Employee Number</th>
              <th>Employee Type</th>
              <th>Employee Status</th>
              <th>OpeningBalance</th>
              <th>Debit Amount</th>
              <th>Credit Amount</th>
              <th>Balance</th>

            </tr>
            <tr class="table-danger">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Employee Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Employee
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Employee
                  Type</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Employee
                  Status</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">OpeningBalance</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Debit
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Credit
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Balance</span>
              </th>
            </tr>
          </thead>
            <tfoot>
              <tr class="table-info fw-bold">
                  <th class="freeze">PAGE TOTAL</th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
              </tr>
              <tr class="table-success fw-bold">
                  <th class="freeze">GRAND TOTAL</th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
              </tr>
          </tfoot>

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

      $('#ReportTbl').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: {
          url: "{{ url('getemployeebalanceall') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [
          { class: 'freeze', data: "employee_name" },
          { data: "employee_number" },
          { data: "employee_type" },
          { data: "employee_status" },
          { data: "op_balance" },
          { data: "debit_amount" },
          { data: "credit_amount" },
          { data: "balance" }
        ],

        footerCallback: function () {

    let api = this.api();

    let num = function (i) {
        return typeof i === 'string'
            ? i.replace(/,/g, '') * 1
            : typeof i === 'number'
            ? i
            : 0;
    };

    // -------------------------
    // PAGE TOTAL (visible rows)
    // -------------------------

    let pageOpening = api.column(4, { page: 'current' }).data()
        .reduce((a, b) => num(a) + num(b), 0);

    let pageDebit = api.column(5, { page: 'current' }).data()
        .reduce((a, b) => num(a) + num(b), 0);

    let pageCredit = api.column(6, { page: 'current' }).data()
        .reduce((a, b) => num(a) + num(b), 0);

    let pageBalance = api.column(7, { page: 'current' }).data()
        .reduce((a, b) => num(a) + num(b), 0);    

    // -------------------------
    // GRAND TOTAL (ALL rows)
    // -------------------------

    let grandOpening = api.column(4).data()
        .reduce((a, b) => num(a) + num(b), 0);

    let grandDebit = api.column(5).data()
        .reduce((a, b) => num(a) + num(b), 0);

    let grandCredit = api.column(6).data()
        .reduce((a, b) => num(a) + num(b), 0);

    let grandBalance = api.column(7).data()
        .reduce((a, b) => num(a) + num(b), 0);
            
    // PAGE TOTAL row (1st footer row)
    
    $(api.column(4).footer()).closest('tfoot').find('tr:eq(0) th:eq(4)')
        .html(pageOpening.toFixed(2));
    $(api.column(5).footer()).closest('tfoot').find('tr:eq(0) th:eq(5)')
        .html(pageDebit.toFixed(2));
    $(api.column(6).footer()).closest('tfoot').find('tr:eq(0) th:eq(6)')
        .html(pageCredit.toFixed(2));
    $(api.column(7).footer()).closest('tfoot').find('tr:eq(0) th:eq(7)')
        .html(pageBalance.toFixed(2));
            
    // GRAND TOTAL row (2nd footer row)
    
    $(api.column(4).footer()).closest('tfoot').find('tr:eq(1) th:eq(4)')
        .html(grandOpening.toFixed(2));
    $(api.column(5).footer()).closest('tfoot').find('tr:eq(1) th:eq(5)')
        .html(grandDebit.toFixed(2));
    $(api.column(6).footer()).closest('tfoot').find('tr:eq(1) th:eq(6)')
        .html(grandCredit.toFixed(2));
    $(api.column(7).footer()).closest('tfoot').find('tr:eq(1) th:eq(7)')
        .html(grandBalance.toFixed(2));    
},

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