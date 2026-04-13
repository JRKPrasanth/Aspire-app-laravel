@extends('layouts.header')
@section('content')
  <h3 class="text-danger mb-4"> Depreciation Method Report </h3>
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
          <button type="button" class="btn bg-success bg-gradient text-white px-4 report_search" id="report_search">
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
              <th class="freeze">Depreciation Method Name</th>
              <th>Created Date</th>
              <th>Asset Type Name</th>
              <th>Asset Category Name</th>
              <th>PO Number</th>
              <th>Product Name</th>
              <th>Unit Price</th>
              <th>Salvage</th>
              <th>Salvage Percentage</th>
              <th>Useful Life</th>
              <th>Salvage Value</th>
              <th>Depreciable Base</th>
              <th>Annual Depreciation Expense</th>
              <th>Created By</th>

            </tr>
            <tr class="table-danger">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Depreciation Method Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Created
                  Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Asset Type
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Asset Category
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">PO
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Unit
                  Price</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Salvage</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Salvage
                  Percentage</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Useful
                  Life</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Salvage
                  Value</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Depreciable
                  Base</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Annual
                  Depreciation Expense</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Created
                  By</span></th>

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

      $('#ReportTbl').DataTable({
        processing: true,
        serverSide: false,
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: {
          url: "{{ url('getdepreciationreportData') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [
          { class: 'freeze', data: "depreciation_method_name" },
          { data: "created_at" },
          { data: "asset_type_name" },
          { data: "asset_category_name" },
          { data: "po_number" },
          { data: "product_name" },
          { data: "unit_price" },
          { data: "salvage" },
          { data: "salvage_percentage" },
          { data: "useful_life" },
          { data: "salvage_value" },
          { data: "depreciable_base" },
          { data: "depreciation_value" },
          { data: "username" }
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