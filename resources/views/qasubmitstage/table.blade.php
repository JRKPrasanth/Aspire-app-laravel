@extends('layouts.header')
@section('content')
  <h3 class="text-danger">
    <?php if ($pageMethod == "qasubmitstage" || $pageMethod == "packingqasubmitstage") { ?>Job Card Completion <?php } else { ?>
    Job Card Completion Details<?php } ?>
  </h3>
  @include('layouts.breadcrumb')


  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <div class="table-responsive">
        <table id="jobComTbl" class="table table-bordered table-striped w-100" style="width: 150% !important;">
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
              <th>Work Order No</th>
              <th>Process</th>
              <th>Process Name</th>
              <th>Product Code</th>
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

      var pageMethod = "<?php echo $pageMethod; ?>";

      var table = $('#jobComTbl').DataTable({
        processing: true,
        serverSide: false,
        order: [[4, 'desc']],
        scrollX: true,
        scrollY: "50vh",
        orderCellsTop: true,
        ajax: "getqasubmitstageData?pagemethod=" + pageMethod,

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
                                  data-id="${row.qa_submitstage_trx_hdr_id}">
                                  <i class="bi bi-eye"></i>
                              </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'create')) {
                buttons += `
                              <button type="button" class="btn btn-sm btn-primary create-btn me-1"
                                   data-id="${row.w_jobs_hdr_id}"
                                   data-status="${row.store_move_status}">
                                 Create
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
          { data: 'reference_no', name: 'reference_no' },
          { data: 'bom_process', name: 'bom_process' },
          { data: 'job_process', name: 'job_process' },
          { data: 'product_code', name: 'product_code' },
          { data: 'store_move_status', name: 'store_move_status', visible: false },
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

      var url = "{{URL::to('qasubmitstageview')}}/" + id + "?pageurl=" + pageurl;
      window.location.replace(url);

    });


    // create
    $(document).on('click', '.create-btn', function () {


      const id = $(this).data('id');
      const status = $(this).data('status');
      var pageurl = "<?php echo $pageurl; ?>";
      var pageMethod = "<?php echo $pageMethod; ?>";

      if (pageMethod == "packingqasubmitstage") {

        if (status == 1) {

          var url = "{{ URL::to('qasubmitstagecreate') }}";
          var editUrl = url + '/' + id + "?src=JOB&pageurl=" + pageurl;
          window.location.replace(editUrl);

        } else {

          showCustomAlert("Store Move Not Completed For this Job", 'error');
        }

      } else {
        var url = "{{ URL::to('qasubmitstagecreate') }}";
        var editUrl = url + '/' + id + "?src=JOB&pageurl=" + pageurl;
        window.location.replace(editUrl);

      }

    });


  </script>

@endpush