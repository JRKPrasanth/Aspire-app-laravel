@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Material Re Acknowledgement Details</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="reackTbl" class="table table-bordered table-striped w-100">
          <thead>
            <tr class="table-warning">
              <th>Actions</th>
              <th>Plan No</th>
              <th>Job No</th>
              <th>Job Date</th>
              <th>Product Code</th>
              <th>Product Name</th>
              <th>Batch Number</th>
              <th>Uom Code</th>
              <th>Job Qty</th>
              <th>Job Status</th>

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

      var table = $('#reackTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('getmaterialrereceiveData') }}",
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

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'materialrereceive')) {
                buttons += `
                              <button type="button" class="btn btn-sm btn-primary rereceive-btn me-1"
                                  data-id="${row.w_jobs_hdr_id}">
                                  Re-Receive
                              </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `
                              <button type="button" class="btn btn-sm btn-warning view-btn"
                                  data-id="${row.w_materialissue_hdr_id}">
                                  <i class="bi bi-eye"></i>
                              </button>`;
              }

              return buttons;

            },
          },
          { data: 'plan_no', name: 'plan_no' },
          { data: 'job_no', name: 'job_no' },
          { data: 'job_date', name: 'job_date' },
          { data: 'product_code', name: 'product_code' },
          { data: 'concatenated_product', name: 'concatenated_product' },
          { data: 'batch_no', name: 'batch_no' },
          { data: 'uom_code', name: 'uom_code' },
          { data: 'job_adjusted_qty', name: 'job_adjusted_qty' },
          { data: 'job_status', name: 'job_status' },
          { data: 'w_materialissue_hdr_id', name: 'w_materialissue_hdr_id', visible: false },
        ]
      });

      // Individual column search
      $('#reackTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });


    //view function
    $(document).on('click', '.view-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('materialissueview') }}/" + id;
      window.location.href = url;
    });


    // re receive

    $(document).on('click', '.rereceive-btn', function () {
      const id = $(this).data('id');
      const url = "{{ url('materialrereceivecreate') }}/" + id;
      window.location.href = url;
    });


  </script>


@endpush