@extends('layouts.header')
@section('content')

  <h3 class="text-danger"> Product Specification Report</h3>
  @include('layouts.breadcrumb')



  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3"></div>
      <div class="table-responsive">
        <table id="ReportTbl" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">
              <th class="freeze">Product Group</th>
              <th>Product Category Name</th>
              <th>Product Name</th>
              <th>Uom Code</th>
              <th>Parameter Number</th>
              <th>Criteria</th>
              <th>From Value</th>
              <th>To Value</th>
              <th>Comment</th>

            </tr>
            <tr class="table-danger">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Product Group</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Category Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Uom
                  Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Parameter
                  Number</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Criteria</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">From
                  Value</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">To
                  Value</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Comment</span>
              </th>



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
          url: "{{ url('getproductspecdata') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [
          { class: "freeze", data: "group_name" },
          { data: "category_name" },
          { data: "concatenated_product" },
          { data: "uom" },
          { data: "parameter" },
          { data: "lookup_code" },
          { data: "spec_value_from" },
          { data: "spec_value_to" },
          { data: "comments" }
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