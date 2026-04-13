@extends('layouts.header')
@section('content')
  <h3 class="text-danger"> Product Based Quantity Report</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3"></div>
      <div class="table-responsive">
        <table id="ReportTbl" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">

              <th>Product Name</th>
              <th>Quantity</th>

            </tr>
            <tr class="table-success">
              <th><input type="text" class="column-search" placeholder="Search" /><span style="display: none;">Product
                  Name</span></th>
              <th><input type="text" class="column-search" placeholder="Search" /><span
                  style="display: none;">Quantity</span></th>
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
        ajax: {
          url: "{{ url('getproductbasedqty') }}",
          type: "GET",
        },
        columns: [

          { data: "concatenated_product" },
          { data: "qty" }
        ]

      });


      // Column search
      $('#ReportTbl thead').on('keyup change', ".column-search", function () {
        var index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });
  </script>
@endpush