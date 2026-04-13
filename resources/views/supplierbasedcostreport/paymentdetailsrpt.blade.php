@extends('layouts.header')
@section('content')
  <h3 class="text-danger mb-4"> Payment Details Report </h3>
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
              <th class="freeze">Payment Number</th>
              <th>Payment Date</th>
              <th>Payment Month</th>
              <th>Payment Type</th>
              <th>Cheque Number</th>
              <th>Payment Source</th>
              <th>UTR Number</th>
              <th>Invoice Number</th>
              <th>Invoice Date</th>
              <th>Supplier Invoice Date</th>
              <th>Invoice Amount</th>
              <th>Payment Amount</th>
              <th>Bank Name</th>
              <th>Employee Name</th>
              <th>Imprest Employee Name</th>
              <th>Customer Name</th>
              <th>Supplier Name</th>
              <th>Supplier Favouring Name</th>
              <th>Status</th>
              <th>Supplier Bank Name</th>
              <th>Bank Date</th>
              <th>Account Name</th>
              <th>Narration</th>
              <th>Cancel Status</th>

            </tr>
            <tr class="table-danger">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Payment Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Payment
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Payment
                  Month</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Payment
                  Type</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Cheque
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Payment
                  Source</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">UTR
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Invoice
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Invoice
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Supplier
                  Invoice Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Invoice
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Payment
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Bank
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Employee
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Imprest
                  Employee Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Customer
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Supplier
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Supplier
                  Favouring Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Status</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Supplier Bank
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Bank
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Account
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Narration</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Cancel
                  Status</span></th>
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

      $('#ReportTbl').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: {
          url: "{{ url('getpaymentdetails') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [
          { class: 'freeze', data: "payment_number" },
          { data: "payment_date" },
          { data: "payment_month" },
          { data: "payment_type_id" },
          { data: "cheque_no" },
          { data: "payment_source" },
          { data: "payment_reference" },
          { data: "bill_number" },
          { data: "invoice_date" },
          { data: "supplier_invoice_date" },
          { data: "invoice_grand_total" },
          { data: "payment_amount" },
          { data: "bank_name" },
          { data: "first_name" },
          { data: "imprest_employee_name" },
          { data: "customer_name" },
          { data: "supplier_name" },
          { data: "favouring_name" },
          { data: "batch_status" },
          { data: "supplier_bank_id" },
          { data: "bank_date" },
          { data: "supplier_account_name" },
          { data: "remarks" },
          { data: "cancel_status" }
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