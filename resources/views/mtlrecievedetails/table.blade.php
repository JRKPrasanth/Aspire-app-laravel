@extends('layouts.header')
@section('content')
  <h3 class="text-danger">Material Recieve Details</h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <div class="table-responsive">
        <table id="ReciveTbl" class="table table-bordered table-striped w-100">
          <thead>
            <tr class="table-warning">

              <th>Actions</th>
              <th>Job No</th>
              <th>Receive Date</th>
              <th>Job Status</th>
              <th>Product Name</th>
              <th>Batch Number</th>
              <th>Job Qty</th>
              <th>Uom Code</th>


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

      var table = $('#ReciveTbl').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ URL::to('getmtlrecievedetails') }}?type=" + type,

        columns: [

          {

            data: 'w_materialreceive_hdr_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
              let buttons = '';

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `
                              <button type="button" class="btn btn-sm btn-warning view-btn me-1"
                                  data-id="${row.w_materialreceive_hdr_id}">
                                  <i class="bi bi-eye"></i>
                              </button>`;
              }

              return buttons;
            },

          },


          { data: 'job_no', name: 'job_no' },
          { data: 'mtl_receive_date', name: 'mtl_receive_date' },
          { data: 'job_status', name: 'job_status' },
          { data: 'concatenated_product', name: 'concatenated_product' },
          { data: 'batch_no', name: 'batch_no' },
          { data: 'job_qty', name: 'job_qty' },
          { data: 'uom_code', name: 'uom_code' },

        ]
      });


      $('#ReciveTbl thead').on('keyup change', '.column-search', function () {
        let index = $(this).closest('th').index();
        table.column(index).search(this.value).draw();
      });
    });


    // view
    $(document).on('click', '.view-btn', function () {

      const id = $(this).data('id');

      var pageurl = "<?php echo $pageurl; ?>";

      var url = "{{URL::to('materialrecieveview')}}/" + id + "?pageurl=" + pageurl;
      window.location.replace(url);

    });


  </script>

@endpush