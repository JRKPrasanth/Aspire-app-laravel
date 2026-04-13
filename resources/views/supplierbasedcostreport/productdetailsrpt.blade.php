@extends('layouts.header')
@section('content')

  <h3 class="text-danger"> Product Details Report </h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3"></div>
      <div class="table-responsive">
        <table id="ReportTbl" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">
              <th class="freeze">Group Name</th>
              <th>Category Name</th>
              <th>Subcategory Name</th>
              <th>Product Classification</th>
              <th>Group Classification</th>
              <th>Product Code</th>
              <th>Barcode Code Number</th>
              <th>Product Name</th>
              <th>Product Image Attached</th>
              <th>Product Type</th>
              <th>Product Variant Name</th>
              <th>Product Pack Name</th>
              <th>Product Pack Type</th>
              <th>UOM Code</th>
              <th>Product Alternate Name</th>
              <th>Locator Control</th>
              <th>Subinventory Name</th>
              <th>Locator Code</th>
              <th>Tax Credit</th>
              <th>Default HSN Code</th>
              <th>TAX PERCENTAGE</th>
              <th>Batch Number</th>
              <th>Active</th>
              <th>Product Status</th>
              <th>Expiry Days</th>
              <th>Std Cost</th>
              <th>MPQ</th>
              <th>Min Order Qty</th>
              <th>Min Stock Level2</th>
              <th>Min Stock Level3</th>
              <th>Max Order Qty</th>
              <th>Reorder Level Qty</th>
              <th>QC Type</th>
              <th>QC Check</th>
              <th>QC No of Days</th>
              <th>gross Weight</th>
              <th>Net Weight</th>
              <th>Product Spec Status</th>
              <th>Created Date</th>
              <th>Created By</th>

            </tr>
            <tr class="table-danger">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Group Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Category
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Subcategory
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Classification</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Group
                  Classification</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Barcode Code
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product Image
                  Attached</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Type</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Variant Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product Pack
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product Pack
                  Type</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">UOM
                  Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Alternate Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Locator
                  Control</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Subinventory
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Locator
                  Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Tax
                  Credit</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Default HSN
                  Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">TAX
                  PERCENTAGE</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Batch
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Active</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Status</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Expiry
                  Days</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Std
                  Cost</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">MPQ</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Min Order
                  Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Min Stock
                  Level2</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Min Stock
                  Level3</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Max Order
                  Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Reorder Level
                  Qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">QC Type</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">QC
                  Check</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">QC No of
                  Days</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">gross
                  Weight</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Net
                  Weight</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product Spec
                  Status</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Created
                  Date</span></th>
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

      var table = $('#ReportTbl').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: {
          url: "{{ url('getproductdetails') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [
          { class: "freeze", data: "group_name" },
          { data: "category_name" },
          { data: "subcategory_name" },
          { data: "product_classification" },
          { data: "group_classification" },
          { data: "product_code" },
          { data: "barcode_number" },
          { data: "concatenated_product" },
          { data: "prd_attach" },
          { data: "product_type" },
          { data: "product_variant_name" },
          { data: "pack_name" },
          { data: "product_pack_type_name" },
          { data: "uom_code" },
          { data: "product_alternate_name" },
          { data: "locator_control" },
          { data: "subinventory_name" },
          { data: "locator_code" },
          { data: "tax_credit" },
          { data: "classification_code" },
          { data: "tax_group_name" },
          { data: "batch_no" },
          { data: "active" },
          { data: "product_status" },
          { data: "expiry_days" },
          { data: "std_cost" },
          { data: "mpq_qty" },
          { data: "min_order_qty" },
          { data: "min_stock_level2" },
          { data: "min_stock_level3" },
          { data: "max_order_qty" },
          { data: "re_order_level" },
          { data: "qc_type" },
          { data: "qc_check" },
          { data: "qc_no_of_days" },
          { data: "gross_weight" },
          { data: "net_weight" },
          { data: "spec_status" },
          { data: "created_at" },
          { data: "first_name" }
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