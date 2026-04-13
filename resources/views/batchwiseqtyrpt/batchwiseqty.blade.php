@extends('layouts.header')
@section('content')
  <h3 class="text-danger"> Batch Wise Quantity Report</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg border-0 rounded-4 mb-4">
    <div class="card-body">
      <form method="post" action="" id="job_card_reprot" class="needs-validation" novalidate
        enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
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

        <div class="d-flex justify-content-center mt-4">
          <button type="button" class="btn bg-primary bg-gradient text-white px-4 report_search" id="report_search">
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
              <th class="freeze">Group Name</th>
              <th>Date</th>
              <th>Category Name</th>
              <th>Subcategory Name</th>
              <th>Product Name</th>
              <th>UOM Code</th>
              <th>Batch Number</th>
              <th>Subinventory Name</th>
              <th>SubLocator</th>
              <th>Quantity</th>
              <th>Source</th>
              <th>Reference Number</th>
              <th>Dispatch Number</th>
              <th>Parent Product</th>

            </tr>
            <tr class="table-danger">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Group Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Date</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Category
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Subcategory
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">UOM
                  Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Batch
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Subinventory
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">SubLocator</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Quantity</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Source</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Reference
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Dispatch
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Parent
                  Product</span></th>
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
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: {
          url: "{{ url('getbatchwiseqty') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [
          { class: 'freeze', data: "group_name" },
          { data: "created_at" },
          { data: "category_name" },
          { data: "subcategory_name" },
          { data: "concatenated_product" },
          { data: "uom_code" },
          { data: "batch_number" },
          { data: "subinventory_name" },
          { data: "locator_code" },
          { data: "qoh_trx_qty" },
          { data: "qoh_source" },
          { data: "job_no" },
          { data: "dispatch_number" },
          { data: "parent_pro" }

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