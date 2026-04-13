@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Machine Log Details Report</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0 p-4">
    <div class="row g-4 align-items-end">
      <div class="col-md-4">
        <div class="form-group">
          <label for="start_date" class="form-label">From Date</label>
          <input type="text" class="form-control start_date" id="start_date" name="start_date" required autocomplete="off"
            placeholder="YYYY-MM-DD">
        </div>
      </div>

      <div class="col-md-4">
        <div class="form-group">
          <label for="end_date" class="form-label">To Date</label>
          <input type="text" class="form-control end_date" id="end_date" name="end_date" required autocomplete="off"
            placeholder="YYYY-MM-DD">
        </div>
      </div>

      <div class="col-md-2 text-start mt-md-0 mt-3">
        <button type="button" class="btn btn-primary report_search" id="report_search" value="SAVE"><i
            class="bi bi-search"></i> Search</button>
      </div>
    </div>
  </div>


  <div class="card">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3"></div>
      <div class="table-responsive">
        <table id="ReportTbl" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">
              <th>Department Name</th>
              <th>Machine Name</th>
              <th>Product Name</th>
              <th>Batch Number</th>
              <th>Quantity</th>
              <th>Damages</th>
              <th>Activity Date</th>
              <th>Running Time</th>
              <th>Remarks</th>
              <th>Created By</th>
            </tr>
            <tr class="table-success">
              <th><input type="text" placeholder="Search" /><span style="display: none;">Department Name</span></th>
              <th><input type="text" placeholder="Filter" /><span style="display: none;">Machine Name</span></th>
              <th><input type="text" placeholder="Filter" /><span style="display: none;">Product Name</span></th>
              <th><input type="text" placeholder="Filter" /><span style="display: none;">Batch Number</span></th>
              <th><input type="text" placeholder="Filter" /><span style="display: none;">Quantity</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Damages</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Activity Date</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Running Time</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Remarks</span></th>
              <th><input type="text" placeholder="Search" /><span style="display: none;">Created By</span></th>
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
          url: "{{ url('getmachinelogdetails') }}",
          type: "GET",
          data: function (d) {
            d.start_date = $('#start_date').val();
            d.end_date = $('#end_date').val();
          }
        },
        columns: [
          { data: 'process_dept' },
          { data: 'machine_name' },
          { data: 'concatenated_product' },
          { data: 'batch_number' },
          { data: 'quantity' },
          { data: 'damages' },
          { data: 'date' },
          { data: 'running_hours' },
          { data: 'remarks' },
          { data: 'first_name' },
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