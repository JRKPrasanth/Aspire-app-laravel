@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Material RE Issue Against Jobcard</h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="reissueTbl" class="table table-bordered table-striped " style="width:140% !important;">
          <thead>
            <tr class="table-warning">
              <th>Actions</th>
              <th>Job No</th>
              <th>Plan No</th>
              <th>Job Date</th>
              <th>Job Completion Date</th>
              <th>Product Code</th>
              <th>Product</th>
              <th>Batch Number</th>
              <th>Uom Code</th>
              <th>Job Qty</th>
              <th>Job Adjusted Qty</th>
              <th>Job Completion Qty</th>
              <th>Job Balance Qty</th>
              <th>Job Status</th>
              <th>Process Name</th>
              <th>Job Created By</th>
              <th>Remarks</th>

            </tr>
            <tr class="table-info">
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>



@endsection
@push('scripts')

  <script>

    $(document).ready(function () {

      var table = $('#reissueTbl').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: "{{ route('getmatissuejcdata') }}",
        columns: [
          {
            data: 'w_jobs_hdr_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',
            width: '140px',
            render: function (data, type, row) {
              let buttons = '';

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'Reissue')) {
                buttons += `
                              <button type="button" class="btn btn-sm btn-primary issue-btn me-1"
                                  data-id="${row.w_jobs_hdr_id}"
                                  data-status="${row.job_status}">
                                  Re-Issue
                              </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `
                              <button type="button" class="btn btn-sm btn-warning view-btn"
                                  data-id="${row.w_jobs_hdr_id}">
                                  <i class="bi bi-eye"></i>
                              </button>`;
              }

              return buttons;

            },
          },
          { data: 'job_no', name: 'job_no' },
          { data: 'plan_no', name: 'plan_no' },
          { data: 'job_date', name: 'job_date' },
          { data: 'job_completion_date', name: 'job_completion_date' },
          { data: 'product_code', name: 'product_code' },
          { data: 'concatenated_product', name: 'concatenated_product' },
          { data: 'batch_no', name: 'batch_no' },
          { data: 'uom_code', name: 'uom_code' },
          { data: 'job_qty', name: 'job_qty' },
          { data: 'job_adjusted_qty', name: 'job_adjusted_qty' },
          { data: 'production_qty', name: 'production_qty' },
          { data: 'balancejob_qty', name: 'balancejob_qty' },
          { data: 'job_status', name: 'job_status' },
          { data: 'job_process', name: 'job_process' },
          { data: 'first_name', name: 'first_name' },
          { data: 'remarks', name: 'remarks' },

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

    });


    //view function
    $(document).on('click', '.view-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('materialreissueview') }}/" + id;
      window.location.href = url;
    });


    // re issue

    $(document).on('click', '.issue-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('materialreissue') }}/" + id;
      window.location.href = url;
    });


  </script>


@endpush