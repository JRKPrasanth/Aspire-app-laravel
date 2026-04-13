@extends('layouts.header')
@section('content')
  <h3 class="text-danger">
    Production Cost Report
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
              <th class="freeze">Plan NO</th>
              <th>Job No</th>
              <th>Job Date</th>
              <th>Month YY</th>
              <th>Group</th>
              <th>Product Category</th>
              <th>Product Sub Category</th>
              <th>Product Group</th>
              <th>Product Variant</th>
              <th>Product Type</th>
              <th>UOM Code</th>
              <th>Product Name</th>
              <th>Type</th>
              <th>Batch Number</th>
              <th>Employee ID</th>
              <th>Component Product Name</th>
              <th>Component Batch Number</th>
              <th>Qty</th>
              <th>Rate</th>
              <th>Value</th>

            </tr>
            <tr class="table-info">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Plan NO</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Job No</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Job
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Month
                  YY</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Group</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Category</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product Sub
                  Category</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Group</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Variant</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Type</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">UOM
                  Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Type</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Batch
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Employee
                  ID</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Component
                  Product Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Component
                  Batch Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Qty</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Rate</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Value</span>
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
          url: "{{ url('getcostrptData') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [
          { class: 'freeze', data: "plan_no" },
          { data: "job_number" },
          { data: "job_date" },
          { data: "Month" },
          { data: "cat_grp" },
          { data: "category_name" },
          { data: "subcategory_name" },
          { data: "group_name" },
          { data: "product_variant_name" },
          { data: "product_type" },
          { data: "uom_code" },
          { data: "product_name" },
          { data: "e_type" },
          { data: "batch_number" },
          { data: "employee_number" },
          { data: "com_product" },
          { data: "com_batch" },
          { data: "qty" },
          { data: "rate" },
          { data: "total" }

        ],
                buttons: [
          {
            extend: 'colvis',
            text: '<i class="bi bi-layout-three-columns"></i> Columns',
            className: 'btn bg-primary btn-sm',
            postfixButtons: ['colvisRestore'] 
          },
          {
            text: 'Excel',
            action: function () {

              var start_date = $('#start_date').val();
              var end_date = $('#end_date').val();

              window.location.href =
                "{{ url('getcostrptData/export') }}" +
                "?start_date=" + start_date +
                "&end_date=" + end_date;
            }
          }
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