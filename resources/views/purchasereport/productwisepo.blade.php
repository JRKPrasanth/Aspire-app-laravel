@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Product Wise PO Report</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body p-4">
      <form method="post" action="" id="job_card_reprot" class="needs-validation" novalidate
        enctype="multipart/form-data">
        @csrf
        <div class="row g-4 align-items-end">
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

          <div class="col-md-2 d-grid">
            <button type="button" class="btn btn-primary report_search" id="report_search">
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
              <th class="freeze">Supplier Name</th>
              <th>Product Group Name</th>
              <th>Product Name</th>
              <th>Supplier Invoice Date</th>
              <th>Po Date</th>
              <th>Supplier Invoice No</th>
              <th>Po Number</th>
              <th>Po Status</th>
              <th>Uom Code</th>
              <th>Qty</th>
              <th>Rate</th>
              <th>Line Total</th>
            </tr>
            <tr class="table-success">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Supplier Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Product
                  Group Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Product
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Supplier
                  Invoice Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Po
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Supplier
                  Invoice No</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Po
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Po
                  Status</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Uom
                  Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Qty</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Rate</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Line
                  Total</span></th>
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
          url: "{{ url('getproductwisepo') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();

          }
        },
        columns: [

          { class: 'freeze', data: "supplier_name" },
          { data: "group_name" },
          { data: "concatenated_product" },
          { data: "supplier_invoice_date" },
          { data: "po_date" },
          { data: "bill_number" },
          { data: "po_number" },
          { data: "po_status" },
          { data: "uom_code" },
          { data: "qty" },
          { data: "unit_price" },
          { data: "line_total" }
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