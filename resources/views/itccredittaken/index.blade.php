@extends('layouts.header')
@section('content')

  <h3 class="text-danger"> ITC Credit taken Summary Report </h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg border-0 rounded-4 mb-4">
    <div class="card-header bg-success bg-gradient text-white fw-semibold">
      <i class="bi bi-funnel me-2"></i>Filter Options
    </div>
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

  <div class='row'>
    <div class='col-4'>
      <h5 class="text-primary fw-bold text-center">Purchage Register Aspire</h5>
      <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body">
          <div class="d-flex justify-content-between mb-3"></div>
          <div class="table-responsive">
            <table id="ReportTbl1" class="table table-striped table-bordered">
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class='col-4'>
      <h5 class="text-success fw-bold text-center">GST 2B</h5>
      <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body">
          <div class="d-flex justify-content-between mb-3"></div>
          <div class="table-responsive">
            <table id="ReportTbl2" class="table table-striped table-bordered">

            </table>
          </div>
        </div>
      </div>
    </div>

    <div class='col-4'>
      <h5 class="text-danger fw-bold text-center">Aspire Vs GST 2B</h5>
      <div class="card shadow-lg rounded-4 border-0">
        <div class="card-body">
          <div class="d-flex justify-content-between mb-3"></div>
          <div class="table-responsive">
            <table id="ReportTbl3" class="table table-striped table-bordered">
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <h5 class="text-primary fw-bold text-center mb-2 mt-4">Aspire Vs GST 2B Detail Report</h5>
  <div class="card shadow-lg rounded-4 border-0 mt-4">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3"></div>
      <div class="table-responsive">
        <table id="ReportTbl4" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">
              <th class="freeze">Invoice Date</th>
              <th>Invoice Number</th>
              <th>GST Number</th>
              <th>Supplier Name</th>
              <th>Taxable Value</th>
              <th>Credit Taken</th>
              <th>Status</th>


            </tr>
            <tr class="table-danger">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Invoice Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Invoice
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">GST
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Supplier
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Taxable
                  Value</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Credit
                  Taken</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Status</span>
              </th>

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

    //table 1
    $(document).ready(function () {

      var table = $('#ReportTbl1').DataTable({
        processing: true,
        serverSide: false,
        pageLength: 5,
        ajax: {
          url: "{{ url('getitcreport') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [
          { data: "invoice_date", title: "Month", width: "19%" },
          { data: "tax_value", title: "Tax Value", width: "19%" },
          { data: "igst", title: "IGST", width: "18%" },
          { data: "cgst", title: "CGST", width: "18%" },
          { data: "sgst", title: "SGST", width: "18%" }
        ],
        dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
        buttons: [
          {
            extend: 'excelHtml5',
            title: 'PUR_REG_ASPIRE',
            exportOptions: {
              columns: ':visible'
            }
          }
        ]
      });

      // Column search
      $('#ReportTbl1 thead').on('keyup change', ".column-search", function () {
        var index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });
    // table 2	
    $(document).ready(function () {

      var table = $('#ReportTbl2').DataTable({
        processing: true,
        serverSide: false,
        pageLength: 5,
        ajax: {
          url: "{{ url('gstulpdreport') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [
          { data: "invoice_date", title: "Month", width: "19%" },
          { data: "tax_value", title: "Tax Value", width: "19%" },
          { data: "igst", title: "IGST", width: "18%" },
          { data: "cgst", title: "CGST", width: "18%" },
          { data: "sgst", title: "SGST", width: "18%" }
        ],
        dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
        buttons: [
          {
            extend: 'excelHtml5',
            title: 'GST_2B Report',
            exportOptions: {
              columns: ':visible'
            }
          }
        ]
      });

      // Column search
      $('#ReportTbl2 thead').on('keyup change', ".column-search", function () {
        var index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });

    // table 3	

    $(document).ready(function () {

      var table = $('#ReportTbl3').DataTable({
        processing: true,
        serverSide: false,
        pageLength: 5,
        ajax: {
          url: "{{ url('gstcomparereport') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [
          { data: "invoice_date", title: "Month", width: "19%" },
          { data: "tax_value", title: "Tax Value", width: "19%" },
          { data: "igst", title: "IGST", width: "18%" },
          { data: "cgst", title: "CGST", width: "18%" },
          { data: "sgst", title: "SGST", width: "18%" }
        ],
        dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
        buttons: [
          {
            extend: 'excelHtml5',
            title: 'ASPIRE_VS_GST_2B',
            exportOptions: {
              columns: ':visible'
            }
          }
        ]
      });

      // Column search
      $('#ReportTbl3 thead').on('keyup change', ".column-search", function () {
        var index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });


    //table 4

    $(document).ready(function () {

      var table = $('#ReportTbl4').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: {
          url: "{{ url('getcommanreport') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [
          { class: 'freeze', data: "invoice_date" },
          { data: "bill_number" },
          { data: "gst_number" },
          { data: "supplier_name" },
          { data: "tax_value" },
          { data: "credit_taken" },
          { data: "status" }
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

      // Column search
      $('#ReportTbl4 thead').on('keyup change', ".column-search", function () {
        var index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });





    // Trigger search
    $('.report_search').on('click', function () {

      $('#ReportTbl1').DataTable().ajax.reload();
      $('#ReportTbl2').DataTable().ajax.reload();
      $('#ReportTbl3').DataTable().ajax.reload();
      $('#ReportTbl4').DataTable().ajax.reload();


    });


  </script>
@endpush