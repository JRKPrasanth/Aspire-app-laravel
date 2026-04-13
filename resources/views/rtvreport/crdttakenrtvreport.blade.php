@extends('layouts.header')
@section('content')

  <h3 class="text-danger"> Credit Taken Report - RTV </h3>
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
              <th class="freeze">Return Invoice Number</th>
              <th>Return Date</th>
              <th>PO Number</th>
              <th>GRN Number</th>
              <th>PO Invoice Number</th>
              <th>QC Number</th>
              <th>Supplier Name</th>
              <th>Product Name</th>
              <th>Return Qty</th>
              <th>Unit Price</th>
              <th>Tax Group</th>
              <th>Tax Amount</th>
              <th>Total</th>
              <th>Reason</th>
              <th>RTV Status</th>
              <th>Credit Taken</th>
              <th>Credit Date</th>

            </tr>
            <tr class="table-danger">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Return Invoice Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Return
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">PO
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">GRN
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">PO Invoice
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">QC
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Supplier
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Return
                  Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Unit
                  Price</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Tax
                  Group</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Tax
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Total</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Reason</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">RTV
                  Status</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Credit
                  Taken</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Credit
                  Date</span></th>


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
          url: "{{ url('getcredittakenrtv') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [
          { class: 'freeze', data: "return_invoice_number" },
          { data: "return_date" },
          { data: "po_number" },
          { data: "grn_number" },
          { data: "bill_number" },
          { data: "qc_number" },
          { data: "supplier_name" },
          { data: "concatenated_product" },
          { data: "reject_qty" },
          { data: "unit_price" },
          { data: "tax_group_name" },
          { data: "tax_amount" },
          { data: "line_total" },
          { data: "reason" },
          { data: "p_return_status" },
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