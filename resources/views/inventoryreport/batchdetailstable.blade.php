@extends('layouts.header')
@section('content')
  <h3 class="text-danger">
    Batch Details Report
  </h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body p-4">
      <form method="post" action="" id="job_card_reprot" class="needs-validation" novalidate
        enctype="multipart/form-data">
        @csrf
        <div class="row g-4 align-items-end">
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

          <div class="col-md-2 d-grid">
            <button type="button" class="btn btn-primary report_search" id="report_search">
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
              <th class="freeze">Req No</th>
              <th>Month</th>
              <th>Product Name</th>
              <th>Batch No</th>
              <th>Batch Size (lts/kgs)</th>
              <th>Mfg Date</th>
              <th>Exp date</th>
              <th>Production start date</th>
              <th>Production end date</th>
              <th>QC Approval Date</th>
              <th>Production yield</th>
              <th>Iss to Lab</th>
              <th>Production loss</th>
              <th>Filled in KG</th>
              <th>Filling/Others loss in KG</th>
              <th>Total Loss</th>
              <th>Diff of Yield & Filled</th>
              <th>Remarks</th>

            </tr>
            <tr class="table-info">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Req No</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Month</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Batch
                  No</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Batch Size
                  (lts/kgs)</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Mfg
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Exp
                  date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Production
                  start date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Production end
                  date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">QC Approval
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Production
                  yield</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Iss to
                  Lab</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Production
                  loss</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Filled in
                  KG</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Filling/Others
                  loss in KG</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Total
                  Loss</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Diff of Yield
                  & Filled</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Remarks</span>
              </th>
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

      var table = $('#ReportTbl').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: {
          url: "{{ url('getbatchdetailsdata') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [
          { class: 'freeze', data: "job_no" },
          { data: "month" },
          { data: "concatenated_product" },
          { data: "batch_no" },
          { data: "job_adjusted_qty" },
          { data: "manufacturer_date" },
          { data: "product_expire_date" },
          { data: "job_date" },
          { data: "job_completion_date" },
          { data: "created_at" },
          { data: "sfgqty" },
          { data: "issuetolab" },
          { data: "production_loss" },
          { data: "filledkg" },
          { data: "other_loss" },
          { data: "total_loss" },
          { data: "diffqty" },
          { data: "remarks" }


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


      // Column search
      $('#ReportTbl thead').on('keyup change', ".column-search", function () {
        var index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });
  </script>
@endpush