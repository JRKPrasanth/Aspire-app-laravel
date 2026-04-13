@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Machine Capacity Details Report</h3>
  @include('layouts.breadcrumb')




  <form method="post" action="" id="job_card_reprot" class="needs-validation" enctype="multipart/form-data" novalidate>
    {{ csrf_field() }}

    <div class="card shadow-lg rounded-4 border-0">
      <div class="card-body">
        <div class="row justify-content-center mb-4">
          <div class="col-md-6">
            <label for="machine_id" class="form-label fw-semibold">Machine Name</label>
            <select id="machine_id" name="machine_id" class="form-select select2 machine_id" tabindex="1"
              data-show-subtext="true" data-live-search="true" required>
              {!! $machine_id !!}
            </select>
            <div class="invalid-feedback">Please select a machine.</div>
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
    </div>
  </form>


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3"></div>
      <div class="table-responsive">
        <table id="ReportTbl" class="table table-striped table-bordered">
          <thead>
            <tr class="table-warning">
              <th class="freeze">Machine Name</th>
              <th>Machine Code</th>
              <th>Cretated By</th>
              <th>Remarks</th>
              <th>Product</th>
              <th>From Value</th>
              <th>To Value</th>
              <th>Hours</th>
              <th>Comments</th>

            </tr>
            <tr class="table-danger">
              <th class="freeze"><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Machine Name</span></th>
              <th><input type="text" class="column-search start_date" placeholder="Search"><span
                  style="display:none;">Machine Code</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Cretated
                  By</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Remarks</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Product</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">From
                  Value</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">To
                  Value</span></th>
              <th><input type="text" class="column-search" placeholder="Search"><span style="display:none;">Hours</span>
              </th>
              <th><input type="text" class="column-search" placeholder="Search"><span
                  style="display:none;">Comments</span></th>
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
          url: "{{ url('machinecapacitydetails') }}",
          type: "GET",
          data: function (d) {
            d.machine_id = $('#machine_id').val();

          }
        },
        columns: [
          { class: 'freeze', data: "machine_name" },
          { data: "machine_code" },
          { data: "username" },
          { data: "remarks" },
          { data: "product" },
          { data: "range_from" },
          { data: "range_to" },
          { data: "hours" },
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