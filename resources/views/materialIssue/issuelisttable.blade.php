@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Material Issue Difference</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <div class="table-responsive">
        <table id="ReportTbl" class="table table-bordered table-striped w-100" style="width: 150% !important;">
          <thead>
            <tr class="table-warning">

              <th class="freeze">Job No</th>
              <th>Date</th>
              <th>Product Code</th>
              <th>Product Name</th>
              <th>Uom Code</th>
              <th>Issue Quantity</th>
              <th>Receive Quantity</th>
              <th>Variance Quantity</th>



            </tr>

            <tr class="table-danger">

              <th class="freeze"><input type="text" class="form-control form-control-sm column-search"
                  placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>

            </tr>
          </thead>
          <tbody>
            {{-- DataTable will populate via AJAX --}}
          </tbody>
        </table>
      </div>
    </div>
  </div>

@endsection
@push('scripts')

  <script>

    // table data

    $(document).ready(function () {

      var type = "{{ $type }}"

      var table = $('#ReportTbl').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: {
          url: "{{ URL::to('issuelistdata') }}?type=" + type,
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
            d.account_code_line = $('#account_line_id').val();
          }
        },
        columns: [
          { class: 'freeze', data: 'job_no', name: 'job_no' },
          { data: 'mtl_issue_date', name: 'mtl_issue_date' },
          { data: 'product_code', name: 'product_code' },
          { data: 'concatenated_product', name: 'concatenated_product' },
          { data: 'uom_code', name: 'uom_code' },
          { data: 'mtl_issue_qty', name: 'mtl_issue_qty' },
          { data: 'receive_qty', name: 'receive_qty' },
          { data: 'variance_qty', name: 'variance_qty' }
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