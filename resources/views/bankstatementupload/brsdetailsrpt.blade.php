@extends('layouts.header')
@section('content')
  <h3 class="text-danger mb-4">BRS Summary Report </h3>
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
          <button type="button" class="btn bg-primary bg-gradient text-white px-4 report_search" id="report_search">
            <i class="bi bi-search-heart me-1"></i> Search
          </button>
        </div>
      </form>
    </div>
  </div>

  <div class="row g-3 mb-4 text-center">

    <div class="col-md-3">
      <button type="button" class="btn btn-info w-100 tablinks1 summarywise">
        Summary
      </button>
    </div>

    <div class="col-md-3">
      <button type="button" class="btn btn-success w-100 tablinks1 brsmapped">
        BRS Mapped
      </button>
    </div>

    <div class="col-md-3">
      <button type="button" class="btn btn-warning w-100 tablinks1 brsunmapped">
        BRS UnMapped
      </button>
    </div>

    <div class="col-md-3">
      <button type="button" class="btn btn-danger w-100 tablinks1 summarydeatils">
        Summary Details
      </button>
    </div>

  </div>

  <div class="card shadow-lg rounded-4 border-0 summarywisediv">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3"></div>
      <div class="table-responsive">
        <table id="summarywisetbl" class="table table-striped table-bordered">
          <thead>
            <tr class="table-info">
              <th>Transaction Number</th>
              <th>Transaction Date</th>
              <th>Transaction Reference</th>
              <th>Debit</th>
              <th>Credit</th>
              <th>Supplier/Customer Name</th>
              <th>Employee Name</th>
              <th>Order No</th>
              <th>Invoice No</th>
              <th>BRS Id</th>
              <th>Bank Name</th>
              <th>Bank Date</th>
              <th>Account No</th>
              <th>Narration</th>
              <th>Mode</th>
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


  <div class="card shadow-lg rounded-4 border-0 brsmappeddiv" style="display:none;">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3"></div>
      <div class="table-responsive">
        <table id="brsmappedtbl" class="table table-striped table-bordered">
          <thead>
            <tr class="table-success">
              <th>Transaction Number</th>
              <th>Transaction Date</th>
              <th>Transaction Reference</th>
              <th>Debit</th>
              <th>Credit</th>
              <th>Supplier/Customer Name</th>
              <th>Employee Name</th>
              <th>Order No</th>
              <th>Invoice No</th>
              <th>BRS Id</th>
              <th>Bank Name</th>
              <th>Bank Date</th>
              <th>Account No</th>
              <th>Narration</th>
              <th>Mode</th>
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



  <div class="card shadow-lg rounded-4 border-0 brsunmappeddiv" style="display:none;">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3"></div>
      <div class="table-responsive">
        <table id="brsunmappedtbl" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">
              <th>Transaction Number</th>
              <th>From Bank Name</th>
              <th>Transaction Date</th>
              <th>Transaction Reference</th>
              <th>Debit</th>
              <th>Credit</th>
              <th>Supplier/Customer Name</th>
              <th>Employee Name</th>
              <th>Order No</th>
              <th>Invoice No</th>
              <th>BRS Id</th>
              <th>Bank Name</th>
              <th>Bank Date</th>
              <th>Account No</th>
              <th>Narration</th>
              <th>Mode</th>
              <th>Cancel Status</th>
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



  <div class="card shadow-lg rounded-4 border-0 summarydeatilsdiv" style="display:none;">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3"></div>
      <div class="table-responsive">
        <table id="summarydeatilstbl" class="table table-striped table-bordered">
          <thead>
            <tr class="table-danger">
              <th>Month</th>
              <th>Year</th>
              <th>Overall Count</th>
              <th>Payment Count</th>
              <th>Receipts Count</th>
              <th>Payment Mapped Count</th>
              <th>Receipts Mapped Count</th>
              <th>Mapped BRS</th>
              <th>Payments Unmapped Count</th>
              <th>Receipts Unmapped Count</th>
              <th>Unmapped BRS</th>
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

    $('.tablinks1').on('click', function () {
      let tab = $(this).text().trim();

      // Hide all divs first
      $(".summarywisediv, .brsmappeddiv, .brsunmappeddiv, .summarydeatilsdiv").hide();

      if (tab === 'Summary') {
        $(".summarywisediv").show();
        $('#summarywisetbl').DataTable().ajax.reload();
      } else if (tab === 'BRS Mapped') {
        $(".brsmappeddiv").show();
        $('#brsmappedtbl').DataTable().ajax.reload();
      } else if (tab === 'BRS UnMapped') {
        $(".brsunmappeddiv").show();
        $('#brsunmappedtbl').DataTable().ajax.reload();
      } else if (tab === 'Summary Details') {
        $(".summarydeatilsdiv").show();  // class name has "deatils"
        $('#summarydeatilstbl').DataTable().ajax.reload();
      }
    });





    $('#summarywisetbl').DataTable({

      processing: true,
      serverSide: false,
      scrollX: true,
      scrollY: "50vh",
      orderCellsTop: true,
      ajax: {
        url: "{{ url('getbrssummaryreport') }}",
        type: "GET",
        data: function (d) {
          d.start_date = $('#start_date').val();
          d.end_date = $('#end_date').val();
        }
      },
      columns: [
        { class: 'freeze', data: 'payment_number', name: 'payment_number', width: "120px" },
        { data: 'payment_date', name: 'payment_date', width: "120px" },
        { data: 'payment_source', name: 'payment_source', width: "120px" },
        { data: 'p_payment_amount', name: 'p_payment_amount', width: "120px" },
        { data: 'r_payment_amount', name: 'r_payment_amount', width: "120px" },
        { data: 'supplier_name', name: 'supplier_name', width: "120px" },
        { data: 'employee_name', name: 'employee_name', width: "120px" },
        { data: 'po_no', name: 'po_no', width: "120px" },
        { data: 'invoice_no', name: 'invoice_no', width: "120px" },
        { data: 'bankstmt_id', name: 'bankstmt_id', width: "120px" },
        { data: 'bank_name', name: 'bank_name', width: "120px" },
        { data: 'date', name: 'date', width: "120px" },
        { data: 'account_number', name: 'account_number', width: "120px" },
        { data: 'narration', name: 'narration', width: "120px" },
        { data: 'tmode', name: 'tmode', width: "120px" }
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
    

    let pageDebit = api.column(3, { page: 'current' }).data()
        .reduce((a, b) => num(a) + num(b), 0);

    let pageCredit = api.column(4, { page: 'current' }).data()
        .reduce((a, b) => num(a) + num(b), 0);

    
    // -------------------------
    // GRAND TOTAL (ALL rows)
    // -------------------------
    

    let grandDebit = api.column(3).data()
        .reduce((a, b) => num(a) + num(b), 0);

    let grandCredit = api.column(4).data()
        .reduce((a, b) => num(a) + num(b), 0);

    


    // PAGE TOTAL row (1st footer row)
    
    $(api.column(3).footer()).closest('tfoot').find('tr:eq(0) th:eq(3)')
        .html(pageDebit.toFixed(2));
    $(api.column(4).footer()).closest('tfoot').find('tr:eq(0) th:eq(4)')
        .html(pageCredit.toFixed(2));
    
    // GRAND TOTAL row (2nd footer row)
    
    $(api.column(3).footer()).closest('tfoot').find('tr:eq(1) th:eq(3)')
        .html(grandDebit.toFixed(2));
    $(api.column(4).footer()).closest('tfoot').find('tr:eq(1) th:eq(4)')
        .html(grandCredit.toFixed(2));
    
    },

      dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
      buttons: [
        {
          extend: 'colvis',
          text: '<i class="bi bi-layout-three-columns"></i> Columns',
          className: 'btn bg-primary btn-sm',
          postfixButtons: ['colvisRestore'] // adds "Restore Columns" button
        },
        { extend: 'excelHtml5', title: "menuText", exportOptions: { columns: ':visible' } }
      ]
    });


    // table 2

    $('#brsmappedtbl').DataTable({

      processing: true,
      serverSide: false,
      scrollX: true,
      scrollY: "50vh",
      orderCellsTop: true,
      ajax: {
        url: "{{ url('getbrsmappedreport') }}",
        type: "GET",
        data: function (d) {
          d.start_date = $('#start_date').val();
          d.end_date = $('#end_date').val();
        }
      },
      columns: [
        { class: 'freeze', data: 'payment_number', name: 'payment_number', width: "120px" },
        { data: 'payment_date', name: 'payment_date', width: "120px" },
        { data: 'payment_source', name: 'payment_source', width: "120px" },
        { data: 'p_payment_amount', name: 'p_payment_amount', width: "120px" },
        { data: 'r_payment_amount', name: 'r_payment_amount', width: "120px" },
        { data: 'supplier_name', name: 'supplier_name', width: "120px" },
        { data: 'employee_name', name: 'employee_name', width: "120px" },
        { data: 'po_no', name: 'po_no', width: "120px" },
        { data: 'invoice_no', name: 'invoice_no', width: "120px" },
        { data: 'bankstmt_id', name: 'bankstmt_id', width: "120px" },
        { data: 'bank_name', name: 'bank_name', width: "120px" },
        { data: 'date', name: 'date', width: "120px" },
        { data: 'account_number', name: 'account_number', width: "120px" },
        { data: 'narration', name: 'narration', width: "120px" },
        { data: 'tmode', name: 'tmode', width: "120px" }
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
    

    let pageDebit = api.column(3, { page: 'current' }).data()
        .reduce((a, b) => num(a) + num(b), 0);

    let pageCredit = api.column(4, { page: 'current' }).data()
        .reduce((a, b) => num(a) + num(b), 0);

    
    // -------------------------
    // GRAND TOTAL (ALL rows)
    // -------------------------
    

    let grandDebit = api.column(3).data()
        .reduce((a, b) => num(a) + num(b), 0);

    let grandCredit = api.column(4).data()
        .reduce((a, b) => num(a) + num(b), 0);

    


    // PAGE TOTAL row (1st footer row)
    
    $(api.column(3).footer()).closest('tfoot').find('tr:eq(0) th:eq(3)')
        .html(pageDebit.toFixed(2));
    $(api.column(4).footer()).closest('tfoot').find('tr:eq(0) th:eq(4)')
        .html(pageCredit.toFixed(2));
    
    // GRAND TOTAL row (2nd footer row)
    
    $(api.column(3).footer()).closest('tfoot').find('tr:eq(1) th:eq(3)')
        .html(grandDebit.toFixed(2));
    $(api.column(4).footer()).closest('tfoot').find('tr:eq(1) th:eq(4)')
        .html(grandCredit.toFixed(2));
    
    },
      dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
      buttons: [
        {
          extend: 'colvis',
          text: '<i class="bi bi-layout-three-columns"></i> Columns',
          className: 'btn bg-primary btn-sm',
          postfixButtons: ['colvisRestore'] // adds "Restore Columns" button
        },
        { extend: 'excelHtml5', title: "menuText", exportOptions: { columns: ':visible' } }
      ]
    });

    // table 3
    $('#brsunmappedtbl').DataTable({

      processing: true,
      serverSide: false,
      scrollX: true,
      scrollY: "50vh",
      orderCellsTop: true,
      ajax: {
        url: "{{ url('getbrsunmapped') }}",
        type: "GET",
        data: function (d) {
          d.start_date = $('#start_date').val();
          d.end_date = $('#end_date').val();
        }
      },
      columns: [
        { class: 'freeze', data: 'payment_number', name: 'payment_number', width: "120px" },
        { data: 'from_bank_name', name: 'from_bank_name', width: "120px" },
        { data: 'payment_date', name: 'payment_date', width: "120px" },
        { data: 'payment_source', name: 'payment_source', width: "120px" },
        { data: 'p_payment_amount', name: 'p_payment_amount', width: "120px" },
        { data: 'r_payment_amount', name: 'r_payment_amount', width: "120px" },
        { data: 'supplier_name', name: 'supplier_name', width: "120px" },
        { data: 'employee_name', name: 'employee_name', width: "120px" },
        { data: 'po_no', name: 'po_no', width: "120px" },
        { data: 'invoice_no', name: 'invoice_no', width: "120px" },
        { data: 'bankstmt_id', name: 'bankstmt_id', width: "120px" },
        { data: 'bank_name', name: 'bank_name', width: "120px" },
        { data: 'date', name: 'date', width: "120px" },
        { data: 'account_number', name: 'account_number', width: "120px" },
        { data: 'narration', name: 'narration', width: "120px" },
        { data: 'tmode', name: 'tmode', width: "120px" },
        { data: 'cancel_status', name: 'cancel_status', width: "120px" }
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
    

    let pageDebit = api.column(3, { page: 'current' }).data()
        .reduce((a, b) => num(a) + num(b), 0);

    let pageCredit = api.column(4, { page: 'current' }).data()
        .reduce((a, b) => num(a) + num(b), 0);

    
    // -------------------------
    // GRAND TOTAL (ALL rows)
    // -------------------------
    

    let grandDebit = api.column(3).data()
        .reduce((a, b) => num(a) + num(b), 0);

    let grandCredit = api.column(4).data()
        .reduce((a, b) => num(a) + num(b), 0);

    


    // PAGE TOTAL row (1st footer row)
    
    $(api.column(3).footer()).closest('tfoot').find('tr:eq(0) th:eq(3)')
        .html(pageDebit.toFixed(2));
    $(api.column(4).footer()).closest('tfoot').find('tr:eq(0) th:eq(4)')
        .html(pageCredit.toFixed(2));
    
    // GRAND TOTAL row (2nd footer row)
    
    $(api.column(3).footer()).closest('tfoot').find('tr:eq(1) th:eq(3)')
        .html(grandDebit.toFixed(2));
    $(api.column(4).footer()).closest('tfoot').find('tr:eq(1) th:eq(4)')
        .html(grandCredit.toFixed(2));
    
    },
      dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
      buttons: [
        {
          extend: 'colvis',
          text: '<i class="bi bi-layout-three-columns"></i> Columns',
          className: 'btn bg-primary btn-sm',
          postfixButtons: ['colvisRestore'] // adds "Restore Columns" button
        },
        { extend: 'excelHtml5', title: "menuText", exportOptions: { columns: ':visible' } }
      ]
    });

    // table 4

    $('#summarydeatilstbl').DataTable({

      processing: true,
      serverSide: false,
      scrollX: true,
      scrollY: "50vh",
      orderCellsTop: true,
      autoWidth: false, // prevent DataTables from overriding your width
      ajax: {
        url: "{{ url('getbrssummarydeatilsrpt') }}",
        type: "GET",
        data: function (d) {
          d.start_date = $('#start_date').val();
          d.end_date = $('#end_date').val();
        }
      },
      columns: [
        { class: 'freeze', data: 'm', name: 'm', width: "120px" },
        { data: 'Y', name: 'Y', width: "120px" },
        { data: 'payment_cout', name: 'payment_cout', width: "120px" },
        { data: 'pay_count', name: 'pay_count', width: "120px" },
        { data: 'recpt_count', name: 'recpt_count', width: "120px" },
        { data: 'brs_pay_count', name: 'brs_pay_count', width: "120px" },
        { data: 'brs_receipt_count', name: 'brs_receipt_count', width: "120px" },
        { data: 'brs', name: 'brs', width: "120px" },
        { data: 'unbrs_pay_count', name: 'unbrs_pay_count', width: "120px" },
        { data: 'unbrs_receipt_count', name: 'unbrs_receipt_count', width: "120px" },
        { data: 'unbrs', name: 'unbrs', width: "120px" }
      ],
      dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
      buttons: [
        {
          extend: 'colvis',
          text: '<i class="bi bi-layout-three-columns"></i> Columns',
          className: 'btn bg-primary btn-sm',
          postfixButtons: ['colvisRestore'] // adds "Restore Columns" button
        },
        { extend: 'excelHtml5', title: "menuText", exportOptions: { columns: ':visible' } }
      ]
    });


    $('.report_search').on('click', function () {
      $('#summarywisetbl').DataTable().ajax.reload();
      $('#brsmappedtbl').DataTable().ajax.reload();
      $('#brsunmappedtbl').DataTable().ajax.reload();
      $('#summarydeatilstbl').DataTable().ajax.reload();
    });

  </script>

@endpush