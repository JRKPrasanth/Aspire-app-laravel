@extends('layouts.header')
@section('content')
  <h3 class="text-danger"> B2C Report </h3>
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
              <th>Invoice Date</th>
              <th>Invoice Number</th>
              <th class="freeze">Customer Name</th>
              <th>Customer GST No</th>
              <th>State</th>
              <th>Taxable Value</th>
              <th>IGST</th>
              <th>CGST</th>
              <th>SGST</th>

            </tr>
            <tr class="table-danger">
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Invoice
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">SGST</span>
              </th>
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Customer Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Customer GST
                  No</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">State</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Taxable
                  Value</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">IGST</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">CGST</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">SGST</span>
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
        <th></th>
    </tr>
  </tfoot>
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
          url: "{{ url('getbtwocreport') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [
          { data: "invoice_date" },
          { data: "invoice_number" },
          { class: 'freeze', data: "cusname" },
          { data: "gst_no" },
          { data: "statename" },
          { data: "accessablevalu" },
          { data: "igst" },
          { data: "cgst" },
          { data: "sgst" }
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
    let pageOpening = api.column(5, { page: 'current' }).data()
        .reduce((a, b) => num(a) + num(b), 0);

    let pageDebit = api.column(6, { page: 'current' }).data()
        .reduce((a, b) => num(a) + num(b), 0);

    let pageCredit = api.column(7, { page: 'current' }).data()
        .reduce((a, b) => num(a) + num(b), 0);

    let pageBalance = api.column(8, { page: 'current' }).data()
        .reduce((a, b) => num(a) + num(b), 0);


    // -------------------------
    // GRAND TOTAL (ALL rows)
    // -------------------------
    let grandOpening = api.column(5).data()
        .reduce((a, b) => num(a) + num(b), 0);

    let grandDebit = api.column(6).data()
        .reduce((a, b) => num(a) + num(b), 0);

    let grandCredit = api.column(7).data()
        .reduce((a, b) => num(a) + num(b), 0);

    let grandBalance = api.column(8).data()
        .reduce((a, b) => num(a) + num(b), 0);


    // PAGE TOTAL row (1st footer row)
    $(api.column(5).footer()).closest('tfoot').find('tr:eq(0) th:eq(5)')
        .html(pageOpening.toFixed(2));
    $(api.column(6).footer()).closest('tfoot').find('tr:eq(0) th:eq(6)')
        .html(pageDebit.toFixed(2));
    $(api.column(7).footer()).closest('tfoot').find('tr:eq(0) th:eq(7)')
        .html(pageCredit.toFixed(2));
    $(api.column(8).footer()).closest('tfoot').find('tr:eq(0) th:eq(8)')
        .html(pageBalance.toFixed(2));
    
    // GRAND TOTAL row (2nd footer row)
    $(api.column(5).footer()).closest('tfoot').find('tr:eq(1) th:eq(5)')
        .html(grandOpening.toFixed(2));
    $(api.column(6).footer()).closest('tfoot').find('tr:eq(1) th:eq(6)')
        .html(grandDebit.toFixed(2));
    $(api.column(7).footer()).closest('tfoot').find('tr:eq(1) th:eq(7)')
        .html(grandCredit.toFixed(2));
    $(api.column(8).footer()).closest('tfoot').find('tr:eq(1) th:eq(8)')
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