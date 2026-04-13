@extends('layouts.header')
@section('content')

  <h3 class="text-danger mb-4">Pricelist Detailed Report</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg border-0 rounded-4 mb-4">
    <div class="card-body">
      <form method="post" action="" id="job_card_reprot" class="needs-validation" novalidate
        enctype="multipart/form-data">
        @csrf

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
              <th class="freeze">Pricelist Name</th>
              <th>Pricelist Type</th>
              <th>Description</th>
              <th>Prodcut Name</th>
              <th>Batch Number</th>
              <th>Unit Price</th>
              <th>MRP</th>
              <th>Active Status</th>
              <th>Start Date</th>
              <th>End Date</th>

            </tr>
            <tr class="table-info">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Pricelist Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Pricelist
                  Type</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Description</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Prodcut
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Batch
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Unit
                  Price</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">MRP</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Active
                  Status</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Start
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">End
                  Date</span></th>
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
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: {
          url: "{{ url('getpricelistdetails') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();

          }
        },
        columns: [
          { class: 'freeze', data: "pricelist_name" },
          { data: "price_list_type" },
          { data: "description" },
          { data: "concatenated_product" },
          { data: "batch_number" },
          { data: "unit_price" },
          { data: "std_price" },
          { data: "active" },
          { data: "start_date" },
          { data: "end_date" }
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