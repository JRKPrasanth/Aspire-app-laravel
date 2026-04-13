@extends('layouts.header')

@section('content')

<h3 class="text-danger mb-4">Supplierwise Invoice / Expense Report</h3>
@include('layouts.breadcrumb')

<div class="card shadow-lg border-0 rounded-4 mb-4">
    <div class="card-header bg-info bg-gradient text-white fw-semibold">
        <i class="bi bi-funnel me-2"></i>Filter Options
    </div>
    <div class="card-body">
        <form method="post" action="" id="job_card_reprot" class="needs-validation" novalidate enctype="multipart/form-data">
            @csrf

            <div class="row g-4">
                <div class="col-md-2"></div>
                <div class="col-md-4">
                    <label for="start_date" class="form-label fw-semibold">From Date</label>
                    <input type="text" class="form-control start_date" id="start_date" name="start_date" required autocomplete="off">
                    <div class="invalid-feedback">Please select a start date.</div>
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label fw-semibold">To Date</label>
                    <input type="text" class="form-control end_date" id="end_date" name="end_date" required autocomplete="off">
                    <div class="invalid-feedback">Please select an end date.</div>
                </div>
            </div>

            <div class="d-flex justify-content-center mt-4">
                <button type="button" class="btn bg-info bg-gradient text-white px-4 report_search" id="report_search">
                    <i class="bi bi-search-heart me-1"></i> Search
                </button>
            </div>
        </form>
    </div>
</div>


<div id="accordion">
    <div class="panel-heading" role="tab">
        <h4 class="panel-title">
            <a role="button">Supplierwise Invoice Expense Report</a>
        </h4>
    </div>

   <div class="card shadow-lg rounded-4 border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-3"></div>
    <div class="table-responsive">
      <table id="ReportTbl" class="table table-striped table-bordered">
        <thead>
          <tr class="table-warning">
            <th class="freeze">Supplier Name</th>
            <th>GST Number</th>
            <th>MSME Status</th>
            <th>PO Number</th>
            <th>PO Date</th>
            <th>PO Status</th>
            <th>PO Amount</th>
            <th>GRN Number</th>
            <th>GRN Date</th>
            <th>Invoice Number</th>
            <th>Invoice Date</th>
            <th>Invoice Amount</th>
            <th>Payment Number</th>
            <th>Payment Date</th>
            <th>Payment Amount</th>
            <th>Balance Amount</th>
            <th>Duration</th>
            <th>Payment BRS Status</th>
            <th>Source</th>

          </tr>
          <tr class="table-danger">
            <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Supplier Name</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">GST Number</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">MSME Status</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">PO Number</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">PO Date</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">PO Status</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">PO Amount</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">GRN Number</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">GRN Date</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Invoice Number</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Invoice Date</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Invoice Amount</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Payment Number</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Payment Date</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Payment Amount</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Balance Amount</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Duration</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Payment BRS Status</span></th>
            <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Source</span></th>
            
            
            
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
      pageLength:5,
      ajax: {
        url: "{{ url('supplierwiseinvexpreportdata') }}",
        type: "GET",
        data: function(d) {
              d.start_date = $('#start_date').val();
              d.end_date = $('#end_date').val();
        }
      },
      columns: [
         {class:'freeze', data: "supplier_name" },
         { data: "gst_number"},
        { data: "msme_status"},
        { data: "po_number" },
        { data: "po_date" },
        { data: "po_status" },
        { data: "po_grand_total" },
        { data: "grn_number" },
        { data: "grn_date" },
        { data: "bill_number" },
        { data: "invoice_date" },
        { data: "invoice_grand_total" },
        { data: "payment_number" },
        { data: "payment_date" },
        { data: "payment_amount" },
        { data: "balance_amount" },
        { data: "days"},
        { data: "brs_payment" },
        { data: "payment_source" }
      ],
      dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
      buttons: [
        {
          extend: 'excelHtml5',
          title: 'Supplierwise Expense Invoice Report',
          exportOptions: {
            columns: ':visible'
          }
        }
      ]
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