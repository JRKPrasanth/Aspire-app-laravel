@extends('layouts.header')
@section('content')
<h3 class="text-danger mb-4"> Ledger Balance Report </h3>
@include('layouts.breadcrumb')


<div class="card shadow-sm rounded-4 border-0">
    <div class="card-header bg-secondary bg-gradient text-white fw-semibold">
    <i class="bi bi-funnel me-2"></i>Filter Options
</div>
<div class="card-body p-4">
    <form method="post" action="" id="job_card_reprot" class="needs-validation" enctype="multipart/form-data" novalidate>
        {{ csrf_field() }}

        <div class="row g-4 align-items-end">

            <!-- From Date -->
            <div class="col-md-4">
                <label for="start_date" class="form-label fw-semibold">From Date</label>
                <input type="text" class="form-control start_date" id="start_date" name="start_date" required>
                <div class="invalid-feedback">Please select a start date.</div>
            </div>

            <!-- To Date -->
            <div class="col-md-4">
                <label for="end_date" class="form-label fw-semibold">To Date</label>
                <input type="text" class="form-control end_date" id="end_date" name="end_date" required>
                <div class="invalid-feedback">Please select an end date.</div>
            </div>

            <!-- Type -->
            <div class="col-md-4">
                <label for="type" class="form-label fw-semibold">Ledger Name</label>
                <select id="account_id" name='account_id' rows='5'  class='form-control account_id select2' tabindex="1" data-show-subtext="true" data-live-search="true" required>
                    {!! $account_id !!}
            </select>
            </div>

        </div>

        <!-- Search Button -->
        <div class="row mt-4">
            <div class="col text-center">
                <button type="button" class="btn btn-secondary bg-gradient px-4 report_search">
                    <i class="bi bi-search"></i> Search
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
            <th class="freeze">Journal Name</th>
            <th>Type</th>
            <th>Date</th>
            <th>Reference Source</th>
            <th>Reference Name</th>
            <th>Product Qty</th>
            <th>Batch Number</th>
            <th>Account</th>
            <th>Debit Amount</th>
            <th>Credit Amount</th>
            <th>Balance</th>

          </tr>
          <tr class="table-danger">
            <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Journal Name</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Type</span></th>
            <th><input type="text" class="column-search start_date" placeholder="Search"><span style="display:none;">Date</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Reference Source</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Reference Name</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product Qty</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Batch Number</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Account</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Debit Amount</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Credit Amount</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Balance</span></th>
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

    var table = $('#ReportTbl').DataTable({
      processing: true,
      serverSide: false,
      ordering: false,
      scrollX: true,
      scrollY: "50vh",
      ajax: {
        url: "{{ url('getledgerbalance') }}",
        type: "GET",
        data: function(d) {
          d.start_date = $('#start_date').val();
		      d.end_date = $('#end_date').val();
          d.account_id = $('#account_id').val();
        }
      },
      columns: [
        { class:'freeze',data: "journal_name" },
        { data:"journal_type" },
        { data: "journal_date" },
        { data: "reference_source" },
        { data: "reference_name" },
        { data: "product_qty" },
        { data: "batch_number" },
        { data: "concatenated_segments" },
        { data: "debit_amounts" },
        { data: "credit_amounts" },
        { data: "balance" }
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
        },
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
    
    let pageDebit = api.column(8, { page: 'current' }).data()
        .reduce((a, b) => num(a) + num(b), 0);

    let pageCredit = api.column(9, { page: 'current' }).data()
        .reduce((a, b) => num(a) + num(b), 0);

    let pageBalance = api.column(10, { page: 'current' }).data()
        .reduce((a, b) => num(a) + num(b), 0);

    // -------------------------
    // GRAND TOTAL (ALL rows)
    // -------------------------

    let grandDebit = api.column(8).data()
        .reduce((a, b) => num(a) + num(b), 0);

    let grandCredit = api.column(9).data()
        .reduce((a, b) => num(a) + num(b), 0);

    let grandBalance = api.column(10).data()
        .reduce((a, b) => num(a) + num(b), 0);

    // PAGE TOTAL row (1st footer row)

    $(api.column(8).footer()).closest('tfoot').find('tr:eq(0) th:eq(8)')
        .html(pageDebit.toFixed(2));
    $(api.column(9).footer()).closest('tfoot').find('tr:eq(0) th:eq(9)')
        .html(pageCredit.toFixed(2));
    $(api.column(10).footer()).closest('tfoot').find('tr:eq(0) th:eq(10)')
        .html(pageBalance.toFixed(2));

    // GRAND TOTAL row (2nd footer row)

    $(api.column(8).footer()).closest('tfoot').find('tr:eq(1) th:eq(8)')
        .html(grandDebit.toFixed(2));
    $(api.column(9).footer()).closest('tfoot').find('tr:eq(1) th:eq(9)')
        .html(grandCredit.toFixed(2));
    $(api.column(10).footer()).closest('tfoot').find('tr:eq(1) th:eq(10)')
        .html(grandBalance.toFixed(2));
}


    });
    
    // Trigger search
    $('.report_search').on('click', function () {
      $('#ReportTbl').DataTable().ajax.reload();
    });
      
      
        // Column search
        $('#ReportTbl thead').on('keyup change', ".column-search", function () {
          var index = $(this).closest('th').index();
          table.column(index).search(this.value).draw();
        });
      });	
	
</script>
@endpush
