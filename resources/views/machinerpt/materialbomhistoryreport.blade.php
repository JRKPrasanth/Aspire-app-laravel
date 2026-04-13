@extends('layouts.header')
@section('content')
  <h3 class="text-danger">
    Material BOM History Report
  </h3>
  @include('layouts.breadcrumb')


  <form method="post" action="" id="job_card_reprot" class="needs-validation" enctype="multipart/form-data" novalidate>
    {{ csrf_field() }}

    <div class="card shadow-lg rounded-4 border-0">
      <div class="card-body row justify-content-center mb-4">
        <div class="col-md-6">
          <label for="product_id" class="form-label fw-semibold">Product Name</label>
          <select id="product_id" name="product_id" class="form-select select2 product_id" tabindex="1"
            data-show-subtext="true" data-live-search="true" required>
            {!! $product_id !!}
          </select>
          <div class="invalid-feedback">Please select a Product.</div>
        </div>
      </div>

      <div class="row justify-content-center">
        <div class="col-md-3 text-center">
          <button type="button" class="btn btn-primary px-4 report_search" id="searchBtn">
            <i class="bi bi-search"></i> Search
          </button>
        </div>
      </div>
    </div>
  </form>

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3"></div>
      <div class="table-responsive">
        <table id="ReportTbl" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">
              <th class="freeze">Parent Product Name</th>
              <th>Child Product Name</th>
              <th>Process Level</th>
              <th>Process Name</th>
              <th>BOM Created at</th>
              <th>BOM Updated at</th>
              <th>Production plan created at</th>
              <th>Production plan updated at</th>
              <th>Component Qty</th>

            </tr>
            <tr class="table-info">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Parent Product Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Child Product
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Process
                  Level</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Process
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">BOM Created
                  at</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">BOM Updated
                  at</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Production
                  plan created at</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Production
                  plan updated at</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Component
                  Qty</span></th>




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
          url: "{{ url('getmaterialbomhistoryreport') }}",
          type: "GET",
          data: function (d) {
            d.product_id = $('#product_id').val();
          }
        },
        columns: [
          { class: 'freeze', data: "parent_product_name" },
          { data: "child_product_name" },
          { data: "process_level" },
          { data: "process_name" },
          { data: "bom_created_at" },
          { data: "bom_updated_at" },
          { data: "prod_created_at" },
          { data: "prod_updated_at" },
          { data: "component_qty" }

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