@extends('layouts.header')
@section('content')
  <h3 class="text-danger"> Expiry Product Near By Report</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg border-0 rounded-4 mb-4">
    <div class="card-header bg-primary bg-gradient text-white fw-semibold">
      <i class="bi bi-funnel me-2"></i>Filter Options
    </div>
    <div class="card-body">
      <form method="post" action="" id="job_card_reprot" class="needs-validation" novalidate
        enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
          <div class="col-md-2"></div>
          <div class="col-md-4">
            <label for="start_date" class="form-label fw-semibold">As On Date</label>
            <input type="text" class="form-control start_date" id="start_date" name="start_date" required
              autocomplete="off">
            <div class="invalid-feedback">Please select a start date.</div>
          </div>
          <div class="col-md-4">
            <button type="button" class="btn bg-primary bg-gradient text-white px-4 report_search mt-4"
              id="report_search">
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
              <th>

                Product Name
              </th>
              <th>

                Product Group Name
              </th>
              <th>

                Locator Name
              </th>
              <th>

                Locator Code
              </th>
              <th>

                Batch Number
              </th>
              <th>

                Mfg Date
              </th>
              <th>

                Expiry Date
              </th>
              <th>

                QOH
              </th>
              <th>

                Product Expiry
              </th>

            </tr>
            <tr class="table-danger">
              <th>
                <input type="text" class="column-search" placeholder="Search" />
                <span style="display: none;">Product Name</span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search" />
                <span style="display: none;">Product Group Name</span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search" />
                <span style="display: none;">Locator Name</span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search" />
                <span style="display: none;">Locator Code</span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search" />
                <span style="display: none;">Batch Number</span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search" />
                <span style="display: none;">Mfg Date</span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search" />
                <span style="display: none;">Expiry Date</span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search" />
                <span style="display: none;">QOH</span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search" />
                <span style="display: none;">Product Expiry</span>
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
          url: "{{ url('getexpiryprdnearby') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
          }
        },
        columns: [
          { data: 'concatenated_product', name: 'concatenated_product' },
          { data: 'group_name', name: 'group_name' },
          { data: 'locator_name', name: 'locator_name' },
          { data: 'locator_code', name: 'locator_code' },
          { data: 'batch_number', name: 'batch_number' },
          { data: 'manufacturer_date', name: 'manufacturer_date' },
          { data: 'product_expire_date', name: 'product_expire_date' },
          { data: 'd', name: 'd' },
          { data: 'expiry', name: 'expiry' }
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