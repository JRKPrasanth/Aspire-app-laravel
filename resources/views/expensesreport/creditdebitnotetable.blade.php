@extends('layouts.header')
@section('content')

  <h3 class="text-danger mb-4">Credit Debit Note Detail Report</h3>
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
          <button type="button" class="btn bg-success bg-gradient text-white px-4 report_search" id="report_search">
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
              <th class="freeze">Debit/Credit No</th>
              <th>Debit/Credit Date</th>
              <th>Source Type</th>
              <th>Debit/Credit Type</th>
              <th>Debit/Credit Amount</th>
              <th>Reference No</th>
              <th>Supplier Name</th>
              <th>Customer Name</th>
              <th>Invoice Number</th>
              <th>Account Name</th>
              <th>HSN Code</th>
              <th>Tax Group Name</th>
              <th>Description</th>
              <th>Remarks</th>
              <th>Balance Amount</th>
              <th>Paid Amount</th>
              <th>Debit/Credit Line Amount</th>
              <th>Tax Amount</th>
              <th>Debit/Credit Status</th>
              <th>Credit taken</th>
              <th>Credit taken Month</th>

            </tr>
            <tr class="table-danger">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Debit/Credit No</span></th>
              <th><input type="text" class="column-search start_date" placeholder="Search"><span
                  style="display:none;">Debit/Credit Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Source
                  Type</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Debit/Credit
                  Type</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Debit/Credit
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Reference
                  No</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Supplier
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Customer
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Invoice
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Account
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">HSN
                  Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Tax Group
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Description</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Remarks</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Balance
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Paid
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Debit/Credit
                  Line Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Tax
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Debit/Credit
                  Status</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Credit
                  taken</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Credit taken
                  Month</span></th>

            </tr>
          </thead>
          <tbody>
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
          url: "{{ url('getcreditdebitnoteData') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [
          { class: 'freeze', data: "debitcredit_no" },
          { data: "debitcredit_date" },
          { data: "source_type" },
          { data: "debitcredit_type" },
          { data: "debitcredit_amount" },
          { data: "reference_no" },
          { data: "supplier_name" },
          { data: "customer_name" },
          { data: "invoice_number" },
          { data: "concatenated_segments" },
          { data: "classification_code" },
          { data: "tax_group_name" },
          { data: "description" },
          { data: "remarks" },
          { data: "balance_amount" },
          { data: "paid_amount" },
          { data: "debitcredit_line_amount" },
          { data: "tax_amount" },
          { data: "debitcredit_status" },
          { data: "credit_taken" },
          { data: "credit_date" }
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