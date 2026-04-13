@extends('layouts.header')
@section('content')

  <h3 class="text-danger"> GST-ITC Purchase And Expense Report </h3>
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
              <th class="freeze">Month</th>
              <th>Invoice Date</th>
              <th>Invoice Number</th>
              <th>GST Number</th>
              <th>Supplier Name</th>
              <th>Product Name</th>
              <th>Type</th>
              <th>Taxable Value</th>
              <th>Transport Amount</th>
              <th>Total Taxable Value</th>
              <th>IGST</th>
              <th>CGST</th>
              <th>SGST</th>
              <th>RCM</th>
              <th>Credit Taken</th>
              <th>Credit Taken Month</th>

            </tr>
            <tr class="table-danger">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Month</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Invoice
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Invoice
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">GST
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Supplier
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Type</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Taxable
                  Value</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Transport
                  Amount</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Total Taxable
                  Value</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">IGST</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">CGST</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">SGST</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">RCM</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Credit
                  Taken</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Credit Taken
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
          url: "{{ url('getgstpurchasereport') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [
          { class: 'freeze', data: "monyr" },
          { data: "invoice_date" },
          { data: "bill_number" },
          { data: "gst_number" },
          { data: "supplier_name" },
          { data: "concatenated_product" },
          { data: "type" },
          { data: "tax_value" },
          { data: "transport_amt" },
          { data: "total_tax_value" },
          { data: "igst" },
          { data: "cgst" },
          { data: "sgst" },
          { data: "rcm" },
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