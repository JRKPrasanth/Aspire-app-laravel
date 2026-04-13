@extends('layouts.header')
@section('content')
  <h3 class="text-danger">
    <?php if ($pageurl == "materialissues") { ?>
    Material Issue
    <?php } else if ($pageurl == "mtlissuedetails") { ?>
    Material Issue Details
    <?php  } else if ($pageurl == 'materialrequest') { ?>
    Material Issue Request
    <?php  } else if ($pageurl == "packingmaterialissues") { ?>
    Packing Material Issue
    <?php  } else { ?>
    Packing Material Issue Details
    <?php  } ?>
  </h3>
  @include('layouts.breadcrumb')



  <div class="card shadow-lg rounded-4 border-0">
    <div class="container mt-4">
      <div class="table-responsive">
        <table id="MatTbl" class="table table-bordered table-striped w-100" style="width: 150% !important;">
          <thead>
            <tr class="table-warning">

              <th>Actions</th>
              <th>Plan No</th>
              <th>Job No</th>
              <th>Job Date</th>
              <th>Issue Date</th>
              <th>Product Code</th>
              <th>Product Name</th>
              <th>Uom Code</th>
              <th>Batch Number</th>
              <th>Job Qty</th>
              <th>Job Status</th>
              <th>Process Name</th>
              <th>Remarks</th>
              <th>Created By</th>


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
            </tr>
          </thead>
          <tbody>
            {{-- DataTable will populate via AJAX --}}
          </tbody>
        </table>
      </div>
    </div>
  </div>


  <!-- Bootstrap 5 Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content shadow-lg rounded-4 border-0">

        <!-- Modal Header -->
        <div class="modal-header bg-primary text-white rounded-top-4">
          <h5 class="modal-title fw-bold" id="myModalLabel">
            <i class="bi bi-box-seam me-2"></i> Material Issue Confirmation
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal Body -->
        <div class="modal-body text-center py-4">
          <h5 class="mb-3 fw-semibold">✅ Material Issue Completed</h5>
          <p class="text-muted">Do you want to perform another Material Issue?</p>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer d-flex justify-content-center">
          <button type="button" id="yes" class="btn btn-success px-4" value="yes">
            <i class="bi bi-check-circle me-1"></i> Yes
          </button>
          <button type="button" id="cancel" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> No
          </button>
        </div>

      </div>
    </div>
  </div>





@endsection
@push('scripts')

  <script>

    // table data

    $(document).ready(function () {

      var status = "{{ $status }}";
      var type = "{{ $type }}";

      var table = $('#MatTbl').DataTable({
        processing: true,
        serverSide: false,
        order: [[3, 'desc']],
        scrollX: true,
        scrollY: "50vh",
        
        ajax: "getmaterialData?status=" + status + "&type=" + type,

        columns: [

          {

            data: 'w_jobs_hdr_id',
            name: 'actions',
            orderable: false,
            searchable: false,
            render: function (data, type, row) {
              let buttons = '';

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'view')) {
                buttons += `
                              <button type="button" class="btn btn-sm btn-warning view-btn me-1"
                                  data-id="${row.w_jobs_hdr_id}">
                                  <i class="bi bi-eye"></i>
                              </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'mtl_view')) {
                buttons += `
                              <button type="button" class="btn btn-sm btn-primary mtlview-btn me-1"
                                  data-id="${row.w_materialissue_hdr_id}">
                                   <i class="bi bi-eye"></i>
                              </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'mtlissue')) {
                buttons += `
                              <button type="button" class="btn btn-sm btn-primary issue-btn me-1"
                                  data-id="${row.w_jobs_hdr_id}"
                  data-status="${row.job_status}">
                                 Issue
                              </button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'print')) {
                buttons += `
                              <button type="button" class="btn btn-sm btn-success print-btn me-1"
                                  data-id="${row.w_jobs_hdr_id}"
                  data-id1="${row.quality_spec_trx_hdr_id}"
                  data-id2="${row.i_qoh_detail_id}"
                  data-status="${row.job_status}">
                             <i class="bi bi-printer"></i>
                              </button>`;
              }


              return buttons;
            },

          },


          { data: 'plan_no', name: 'plan_no' },
          { data: 'job_no', name: 'job_no' },
          { data: 'job_date', name: 'job_date' },
          { data: 'mtl_issue_date', name: 'mtl_issue_date' },
          { data: 'product_code', name: 'product_code' },
          { data: 'concatenated_product', name: 'concatenated_product' },
          { data: 'uom_code', name: 'uom_code' },
          { data: 'batch_no', name: 'batch_no' },
          { data: 'job_adjusted_qty', name: 'job_adjusted_qty' },
          { data: 'job_status', name: 'job_status' },
          { data: 'job_process', name: 'job_process' },
          { data: 'remarks', name: 'remarks' },
          { data: 'first_name', name: 'first_name' },
          { data: 'w_materialissue_hdr_id', name: 'w_materialissue_hdr_id', visible: false },

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

      var url = "{{URL::to('jobcardview')}}/" + id + "?pageurl=" + pageurl;
      window.location.replace(url);

    });

    // print

    $(document).on('click', '.print-btn', function () {

      const id = $(this).data('id');


      var url = "{{URL::to('materialprint')}}/" + id;
      window.open(url, '_blank');


    });

    // material issue
    $(document).on('click', '.issue-btn', function () {

      const id = $(this).data('id');
      const status = $(this).data('status');
      var source = "{{ $source }}";
      var url = "<?php echo $pageMethod;?>";


      if (status != "MATERIAL RECEIVED") {
        if (url == "materialrequest") {
          window.location.replace('materialrequestview/' + id);
        } else {
          if (status == "MATERIAL ISSUED") {
            $('#myModal').modal('show');
            $('#yes').click(function () {
              var val = $(this).val();
              if (val == 'yes') {
                window.location.replace('materialissuescreate/' + id + '?source=' + source);
              }
            });
          } else {
            window.location.replace('materialissuescreate/' + id + '?source=' + source);
          }
        }
      } else {

        showCustomAlert("Material Received record should not be allow", 'warning');
      }

    });

    // mtlview

    $(document).on('click', '.mtlview-btn', function () {

      const id = $(this).data('id');
      const status = $(this).data('status');
      var pageurl = "<?php echo $pageurl; ?>";

      if (status != 'OPEN') {
        var url = "{{URL::to('materialissueview')}}/" + id + "?pageurl=" + pageurl;
        window.location.replace(url);
      } else {
        showCustomAlert("Material Issued record only allow to View", 'error');
      }

    })



  </script>

@endpush