@extends('layouts.header')
@section('content')
  <h3 class="text-danger"> Material Movement Data Report</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body p-4">
      <form id="productForm">

        <div class="row g-4 align-items-end">

          <!-- Product -->
          <div class="col-md-4">
            <label for="product_id" class="form-label fw-semibold">Product</label>
            <select name="product_id" id="product_id" class="form-select select2 product_id" required>
              <!-- options dynamically populated -->
            </select>
          </div>

          <!-- From Date -->
          <div class="col-md-4">
            <label for="start_date" class="form-label fw-semibold">From Date</label>
            <input type="text" class="form-control start_date" id="start_date" name="start_date" required
              autocomplete="off">
          </div>

          <!-- To Date -->
          <div class="col-md-4">
            <label for="end_date" class="form-label fw-semibold">To Date</label>
            <input type="text" class="form-control end_date" id="end_date" name="end_date" required autocomplete="off">
          </div>

        </div>

        <!-- Search Button -->
        <div class="row mt-4">
          <div class="col text-center">
            <button type="button" class="btn btn-primary px-4 report_search" id="search">
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
          <thead class="table-warning">
            <tr>
              <th class="freeze">Date</th>
              <th>Supplier Name</th>
              <th>Consumed Product</th>
              <th>Open Stock</th>
              <th>Inward Qty</th>
              <th>Outward Qty</th>
              <th>In Stock</th>
            </tr>
            <tr class="table-success">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Date</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Supplier
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Consumed
                  Product</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Open
                  Stock</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Inward_Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Outward_Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">In
                  Stock</span></th>

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
          url: "{{ url('movementdata') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
            d.product = $('#product_id').val();
          }
        },
        columns: [
          { class: 'freeze', data: "date" },
          { data: "supplier_name" },
          { data: "consumed" },
          { data: "opn_stk" },
          { data: "inward" },
          { data: "outward" },
          { data: "qoh" }
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

    var url = "{{ URL::to('jcomboformallchecknew') }}?table=m_products_t:product_id:product_code|concatenated_product&condition=yes";

    $.ajax({
      url: url,
      type: 'GET',
      success: function (data) {
        if (typeof data === "string") {
          try {
            data = JSON.parse(data);
          } catch (e) {
            console.error("Invalid JSON response:", data);
            return;
          }
        }

        $('.product_id').html('<option value="">-- Select Product --</option>');

        $.each(data, function (i, item) {
          let selected = item.val == "{{ $row->product_id ?? '' }}" ? 'selected' : '';
          $('.product_id').append(`<option value="${item.val}" ${selected}>${item.option_name}</option>`);
        });

        $('.product_id').trigger('change.select2'); // Optional: trigger select2 refresh
      },
      error: function (xhr, status, error) {
        console.error("AJAX Error:", error);
      }
    });

  </script>
@endpush