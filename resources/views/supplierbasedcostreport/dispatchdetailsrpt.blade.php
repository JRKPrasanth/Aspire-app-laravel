@extends('layouts.header')
@section('content')

<h3 class="text-danger mb-4"> Dispatch Details Report</h3>
@include('layouts.breadcrumb')

<div class="card shadow-lg border-0 rounded-4 mb-4">
    <div class="card-header bg-danger text-white fw-semibold">
        <i class="bi bi-funnel me-2"></i>Filter Options
    </div>
    <div class="card-body">
        <form method="post" action="" id="job_card_reprot" class="needs-validation" novalidate enctype="multipart/form-data">
            @csrf

            <div class="row g-4">
                <div class="col-md-6">
                    <label for="start_date" class="form-label fw-semibold">From Date</label>
                    <input type="text" class="form-control start_date" id="start_date" name="start_date" required autocomplete="off">
                    <div class="invalid-feedback">Please select a start date.</div>
                </div>
                <div class="col-md-6">
                    <label for="end_date" class="form-label fw-semibold">To Date</label>
                    <input type="text" class="form-control end_date" id="end_date" name="end_date" required autocomplete="off">
                    <div class="invalid-feedback">Please select an end date.</div>
                </div>
            </div>

            <div class="d-flex justify-content-center mt-4">
                <button type="button" class="btn btn-outline-danger px-4 report_search" id="report_search">
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
            <th class="freeze">Customer Name</th>
            <th>Dispatch Number</th>
            <th>Dispatch Date</th>
            <th>Dispatch Status</th>
            <th>Product Name</th>
            <th>Batch Number</th>
            <th>Locator ID</th>
            <th>Subinventory ID</th>
            <th>Reference Order Number</th>
            <th>SO Qty</th>
            <th>Dispatch Qty</th>
            <th>Issue QOH</th>
            <th>Order Type</th>
            <th>Packaging Qty</th>
            <th>Pack Weight</th>
            

          </tr>
          <tr class="table-danger">
          <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Customer Name</span></th>
          <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Dispatch Number</span></th>
          <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Dispatch Date</span></th>
          <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Dispatch Status</span></th>
          <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product Name</span></th>
          <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Batch Number</span></th>
          <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Locator ID</span></th>
          <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Subinventory ID</span></th>
          <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Reference Order Number</span></th>
          <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">SO Qty</span></th>
          <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Dispatch Qty</span></th>
          <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Issue QOH</span></th>
          <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Order Type</span></th>
          <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Packaging Qty</span></th>
          <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Pack Weight</span></th>
          
          
          
          
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

    $('#ReportTbl').DataTable({
      processing: true,
      serverSide: false,
	  pageLength:5,
      ajax: {
        url: "{{ url('getdispatchdetails') }}",
        type: "GET",
        data: function(d) {
          d.start_date = $('#start_date').val();
		  d.end_date = $('#end_date').val();
	
        }
      },
      columns: [
        { class:'freeze', data: "customer_name" },
        { data: "dispatch_number" },
        { data: "dispatch_date" },
        { data: "dispatch_status" },
        { data: "concatenated_product" },
        { data: "batch_no" },
        { data: "sublocator_id" },
        { data: "subinventory_id" },
        { data: "reference_no" },
        { data: "so_qty" },
        { data: "dispatch_qty" },
        { data: "issue_qoh" },
        { data: "free_val" },
        { data: "packaging_qty" },
        { data: "pack_weight" }

      ],
      dom: '<"row mb-2"<"col-md-6"l><"col-md-6 text-end"Bf>>rtip',
      buttons: [
        {
          extend: 'excelHtml5',
          title: 'Dispatch Details Report',
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