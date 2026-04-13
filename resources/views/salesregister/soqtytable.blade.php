@extends('layouts.header')
@section('content')
<h3 class="text-danger"> Sales Qty Supplied Report</h3>
@include('layouts.breadcrumb')

<div class="card shadow-lg rounded-4 border-0">
    <div class="card-body p-4">
        <form method="post" action="" id="job_card_reprot" class="needs-validation" novalidate enctype="multipart/form-data">
            @csrf

            <!-- First Row: Date Inputs -->
                           <div class="row g-4 mb-3">
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

        <!-- Second Row: Centered Search Button -->
        <div class="row">
          <div class="col-md-12 text-center mt-2">
            <button type="button" class="btn btn-primary px-4 report_search" id="report_search">
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
            <th class="freeze">Invoice Number</th>
            <th>Customer Name</th>
            <th>Product Name</th>
            <th> Qty</th>
            <th>Batch No</th>

          </tr>
          <tr class="table-info">
          <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Invoice Number</span></th>
          <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Customer Name</span></th>
          <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product Name</span></th>
          <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Qty</span></th>
          <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Batch No</span></th>
          
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
      ajax: {
        url: "{{ url('getsalesqtysupplieddata') }}",
        type: "GET",
        data: function(d) {
          d.start_date = $('#start_date').val();
		  d.end_date = $('#end_date').val();
	
        }
      },
      columns: [
        { class:'freeze', data: "invoice_number" },
        { data: "customer_name" },
        { data: "concatenated_product" },
        { data: "qty" },
        { data: "batch_number" }
    
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
