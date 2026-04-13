@extends('layouts.header')
@section('content')

<h3 class="text-danger mb-4"> Supplier Balance Report </h3>
@include('layouts.breadcrumb')

<div class="card shadow-sm rounded-4 border-0">

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
                <label for="type" class="form-label fw-semibold">Supplier Name</label>
                <select id="supplier_id" name='supplier_id' rows='5'  class='form-control supplier_id select2' tabindex="1" data-show-subtext="true" data-live-search="true" required>
                    {!! $supplier_id !!}
            </select>
            </div>

        </div>

        <!-- Search Button -->
        <div class="row mt-4">
            <div class="col text-center">
                <button type="button" class="btn bg-success bg-gradient text-white px-4 report_search">
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
        <button type="button" class="btn btn-info px-4 mb-2" id="printBtn">
            <i class="fa fa-print"></i> Print
        </button>

      <table id="ReportTbl" class="table table-striped table-bordered">
        <thead>
          <tr class="table-warning">
            <th class="freeze">Journal Name</th>
            <th>Date</th>
            <th>Month</th>
            <th>Finance Year</th>
            <th>Account</th>
            <th>Debit Amount</th>
            <th>Credit Amount</th>
            <th>Balance Amount</th>

          </tr>
          <tr class="table-danger">
            <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Journal Name</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Date</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Month</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Finance Year</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Account</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Debit Amount</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Credit Amount</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Balance Amount</span></th>
            
            

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
      order: [[1, 'asc']],
      ajax: {
        url: "{{ url('getsupplierbalance') }}",
        type: "GET",
        data: function(d) {
          d.start_date = $('#start_date').val();
		  d.end_date = $('#end_date').val();
          d.supplier_id = $('#supplier_id').val();
	
        }
      },
      columns: [
        { class:'freeze',data: "journal_name" },
        { data: "journal_date" },
        { data: "monyr" },
        { data: "financial_year" },
        { data: "concatenated_segments" },
        { data: "debit_amounts" },
        { data: "credit_amounts" },
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
  
    let pageDebit = api.column(5, { page: 'current' }).data()
        .reduce((a, b) => num(a) + num(b), 0);

    let pageCredit = api.column(6, { page: 'current' }).data()
        .reduce((a, b) => num(a) + num(b), 0);

    /*let pageBalance = api.column(7, { page: 'current' }).data()
        .reduce((a, b) => num(a) + num(b), 0);*/
    let pageBalance  = pageDebit - pageCredit;    

    // -------------------------
    // GRAND TOTAL (ALL rows)
    // -------------------------
    
    let grandDebit = api.column(5).data()
        .reduce((a, b) => num(a) + num(b), 0);

    let grandCredit = api.column(6).data()
        .reduce((a, b) => num(a) + num(b), 0);

    /*let grandBalance = api.column(7).data()
        .reduce((a, b) => num(a) + num(b), 0);*/
    let grandBalance = grandDebit - grandCredit;    

    // PAGE TOTAL row (1st footer row)
    
    $(api.column(5).footer()).closest('tfoot').find('tr:eq(0) th:eq(5)')
        .html(pageDebit.toFixed(2));
    $(api.column(6).footer()).closest('tfoot').find('tr:eq(0) th:eq(6)')
        .html(pageCredit.toFixed(2));
    $(api.column(7).footer()).closest('tfoot').find('tr:eq(0) th:eq(7)')
        .html(pageBalance.toFixed(2));

    // GRAND TOTAL row (2nd footer row)
    
    $(api.column(5).footer()).closest('tfoot').find('tr:eq(1) th:eq(5)')
        .html(grandDebit.toFixed(2));
    $(api.column(6).footer()).closest('tfoot').find('tr:eq(1) th:eq(6)')
        .html(grandCredit.toFixed(2));
    $(api.column(7).footer()).closest('tfoot').find('tr:eq(1) th:eq(7)')
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
	

    $('#printBtn').on('click', function () {

    let start_date = $('#start_date').val();
    let end_date   = $('#end_date').val();
    let supplier   = $('#supplier_id').val();

    if (!supplier) {
        showCustomAlert('Please select supplier','error');
        return;
    }

    let url = "{{ url('getsupplierbalance') }}"
        + "?start_date=" + encodeURIComponent(start_date)
        + "&end_date=" + encodeURIComponent(end_date)
        + "&supplier_id=" + supplier
        + "&print=1";

    window.open(url, '_blank');
});


</script>
@endpush
