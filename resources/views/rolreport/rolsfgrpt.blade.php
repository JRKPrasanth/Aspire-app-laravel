@extends('layouts.header')
@section('content')

  <h3 class="text-danger"> ROL SFG Report </h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3"></div>
      <div class="table-responsive">
        <table id="ReportTbl" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">
              <th class="freeze">

                Product Name
              </th>
              <th>

                Category Name
              </th>
              <th>

                Subcategory Name
              </th>
              <th>

                Min_Stk
              </th>
              <th>

                SFG_Stk
              </th>
              <th>

                PRD_REQ
              </th>
              <th>

                Re Order Qty

              </th>


            </tr>
            <tr class="table-danger">
              <th class="freeze">
                <input type="text" class="column-search" placeholder="Search">
                <span style="display:none;">Product Name</span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search">
                <span style="display:none;">Category Name</span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search">
                <span style="display:none;">Subcategory Name</span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search">
                <span style="display:none;">Min_Stk</span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search">
                <span style="display:none;"> SFG_Stk </span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search">
                <span style="display:none;">PRD_REQ </span>
              </th>
              <th>
                <input type="text" class="column-search" placeholder="Search">
                <span style="display:none;">Re Order Qty </span>
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
          url: "{{ url('getrolsfgreport') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [
          { class: "freeze", data: "concatenated_product" },
          { data: "category_name" },
          { data: "subcategory_name" },
          { data: "min_order_qty" },
          { data: "Stock" },
          { data: "SFG_Req" },
          { data: "re_order_level" }







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