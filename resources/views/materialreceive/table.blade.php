@extends('layouts.header')
@section('content')
  <h3 class="text-danger">
    Material Acknowledgement
  </h3>
  @include('layouts.breadcrumb')

  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <div class="table-responsive">
        <table id="AckTbl" class="table table-bordered table-striped w-100" style="width: 150% !important;">
          <thead>
            <tr class="table-warning">

              <th>Actions</th>
              <th>Plan No</th>
              <th class="freeze">Job No</th>
              <th>Job Date</th>
              <th>Job Status</th>
              <th>Product Name</th>
              <th>Batch Number</th>
              <th>Job Qty</th>
              <th>Product Code</th>
              <th>Uom Code</th>
              <th>Process Name</th>
              <th></th>

            </tr>

            <tr class="table-info">

              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
              <th class="freeze"><input type="text" class="form-control form-control-sm column-search" placeholder="Search" /></th>
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

      var table = $('#AckTbl').DataTable({
        processing: true,
        serverSide: false,
        scrollX: true,
        scrollY: "50vh",
        order: [[0, 'desc']],
        ajax: "{{ URL::to('getmaterialreceiveData') }}?type=" + type,

        columns: [

          {

            data: 'w_materialissue_hdr_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
              let buttons = '';

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `
                                <button type="button" class="btn btn-sm btn-warning view-btn me-1"
                                    data-id="${row.w_materialissue_hdr_id}">
                                    <i class="bi bi-eye"></i>
                                </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'materialreceive')) {
                buttons += `
                                <button type="button" class="btn btn-sm btn-primary mtlrecive-btn me-1"
                                    data-id="${row.w_jobs_hdr_id}" data-status="${row.job_status}">
                                  Receive
                                </button>`;
              }

              return buttons;
            },

          },


          { data: 'plan_no', name: 'plan_no' },
          { data: 'job_no', name: 'job_no', class: 'freeze' },
          { data: 'job_date', name: 'job_date' },
          { data: 'job_status', name: 'job_status' },
          { data: 'concatenated_product', name: 'concatenated_product' },
          { data: 'batch_no', name: 'batch_no' },
          { data: 'job_adjusted_qty', name: 'job_adjusted_qty' },
           { data: 'product_code', name: 'product_code' },
          { data: 'uom_code', name: 'uom_code' },
          
          
          
          { data: 'job_process', name: 'job_process' },
          { data: 'w_jobs_hdr_id', name: 'w_jobs_hdr_id', visible: false },

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



    // view
    $(document).on('click', '.view-btn', function () {

      const id = $(this).data('id');

      var pageurl = "<?php echo $pageurl; ?>";

      var url = "{{URL::to('materialissueview')}}/" + id + "?pageurl=" + pageurl;
      window.location.replace(url);

    });

    // receive

    $(document).on('click', '.mtlrecive-btn', function () {

      const id = $(this).data('id');
      const status = $(this).data('status');
      var source = "{{ $source }}";

      if(status=="MATERIAL ISSUED"){
      window.location.replace('materialreceivecreate/' + id + '?source=' + source);

      }else{

      showCustomAlert("Select Material Issued JobCard","error")
      }
    });

  </script>

@endpush