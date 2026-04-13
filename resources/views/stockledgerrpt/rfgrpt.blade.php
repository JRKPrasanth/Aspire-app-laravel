@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Semi Finished Goods Report </h3>
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
              <th class="freeze">Product Name</th>
              <th>Category Name</th>
              <th>Sub Category Name</th>
              <th>Batch No</th>
              <th>Manufacturer Date</th>
              <th>Product Expiry Date</th>
              <th>Opening Balance</th>
              <th>Qoh in</th>
              <th>Qoh out</th>
              <th>Closing Balance</th>

            </tr>
            <tr class="table-success">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Product Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Category
                  Name.</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Sub Category
                  Name.</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Batch
                  No.</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Manufacturer
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product Expiry
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Opening
                  Balance</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Qoh in</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Qoh out</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Closing
                  Balance</span></th>



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
          url: "{{ url('getsemifinishedgoodsrpt') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [
          { class: 'freeze', data: "concatenated_product" },
          { data: "cate_name" },
          { data: "sub_cate" },
          { data: "batch_number" },
          { data: "manufacturer_date" },
          { data: "product_expire_date" },
          { data: "opening_balance" },
          { data: "qoh_in" },
          { data: "qoh_out" },
          { data: "closing" },

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