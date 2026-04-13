@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Material BOM Report</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3"></div>
      <div class="table-responsive">
        <table id="ReportTbl" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">
              <th class="freeze">Production Product</th>
              <th>Uom Code</th>
              <th>Active</th>
              <th>Remarks</th>
              <th>Process</th>
              <th>Component Product</th>
              <th>Component Uom</th>
              <th>Component qty</th>
              <th>Process Level</th>
              <th>process Name</th>
              <th>machine</th>
              <th>comments</th>


            </tr>
            <tr class="table-danger">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Production Product</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Uom
                  Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Active</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Remarks</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Process</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Component
                  Product</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Component
                  Uom</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Component
                  qty</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Process
                  Level</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">process
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">machine</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">comments</span></th>

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
          url: "{{ url('getmaterialbomreport') }}",
          type: "GET",
        },
        columns: [
          { class: 'freeze', data: "assembly_product" },
          { data: "uom_code" },
          { data: "active" },
          { data: "remarks" },
          { data: "process" },
          { data: "component_product" },
          { data: "uom_code_remain" },
          { data: "component_qty" },
          { data: "process_level" },
          { data: "process_name" },
          { data: "machine_name" },
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


      // Column search
      $('#ReportTbl thead').on('keyup change', ".column-search", function () {
        var index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });
  </script>
@endpush