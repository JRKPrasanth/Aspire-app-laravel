@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Move To Inventory</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between mb-3">
      </div>
      <div class="table-responsive">
        <table id="QcTbl" class="table table-bordered table-striped w-100">
          <thead>
            <tr class="table-warning">
              <th>Actions</th>
              <th>Reference No</th>
              <th>QA Status</th>
              <th>QA Approved Date</th>
              <th>Job No</th>
              <th>Job Date</th>
              <th>Batch No</th>
              <th>Product Code</th>
              <th>Product</th>
              <th>Production Qty</th>
              <th>Remarks</th>

            </tr>
            <tr class="table-info">
              <th></th>
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

      var table = $('#QcTbl').DataTable({
        processing: true,
        serverSide: false,
        order: [[3, 'desc']],
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: "{{ route('msqasubmitstageappData') }}",
        columns: [
          {
            data: 'qa_submitstage_trx_hdr_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            className: 'text-center',
            width: '140px',
            render: function (data, type, row) {
              return `
                      <button class="btn btn-sm btn-primary me-1 storemove" data-id="${data}">
                          Move
                      </button>`;
            }
          },
          { data: 'reference_no', name: 'reference_no' },
          { data: 'qa_status', name: 'qa_status' },
          { data: 'created_at', name: 'created_at' },
          { data: 'job_no', name: 'job_no' },
          { data: 'job_date', name: 'job_date' },
          { data: 'batch_no', name: 'batch_no' },
          { data: 'product_code', name: 'product_code' },
          { data: 'concatenated_product', name: 'concatenated_product' },
          { data: 'production_qty', name: 'production_qty' },
          { data: 'remarks', name: 'remarks' }
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

    // store move
    $(document).on('click', '.storemove', function () {

      var cellValue = $(this).data('id');

      var url = "{{ URL::to('movetocreate') }}";
      var editUrl = url + '/' + cellValue + '?status=movetoinventoryqa';
      window.location.replace(editUrl);

    });


  </script>
    @endpush