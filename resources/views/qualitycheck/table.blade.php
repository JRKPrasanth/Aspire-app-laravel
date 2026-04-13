@extends('layouts.header')
@section('content')
  <h3 class="text-danger"><?php if ($pageMethod == "qualitycheck") { ?>Quality Check
    <?php  } else if ($pageMethod == "qcanalytical") { ?> Quality Analytical<?php  } else {?> Quality Approval <?php  } ?>
  </h3>
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
              <th style="display:none;"></th>
              <th>Reference No</th>
              <th>QA Status</th>
              <th>Quality Check status</th>
              <th>Job No</th>
              <th>Job Date</th>
              <th>Batch No</th>
              <th>Product Code</th>
              <th>Product</th>
              <th>Production Qty</th>
              <th>Quality Type</th>
              <th>Remarks</th>


            </tr>

            <tr class="table-info">
              <th></th>
              <th style="display:none;"></th>
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


  <!-- Modal -->

  <form method="post" action="" id="indentreducesaves" data-parsley-validate>
    <div class="modal fade" id="myModal2" tabindex="-1" aria-labelledby="indentModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">

          <div class="modal-header">
            <h3 class="modal-title" id="indentModalLabel">Quality Indent</h3>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            <table class="table w-100">
              <thead>
                <tr>
                  <th>Product Name</th>
                  <th>UOM Code</th>
                  <th>Qty</th>
                  <th>Used Qty</th>
                </tr>
              </thead>
              <tbody class="indentdata">
                <!-- Data will be injected here -->
              </tbody>
            </table>
          </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-success indentreducesave">Submit</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>

        </div>
      </div>
    </div>
  </form>




@endsection
@push('scripts')

  <script>

    $(document).ready(function () {

      var status = "{{ $status }}";
      var pageMethod = "{{ $pageMethod }}";

      var table = $('#QcTbl').DataTable({
            processing: true,
            serverSide: false,
            order: [[5, 'desc']],
            scrollX: true,
            scrollY: "50vh",
            orderCellsTop: true,
        ajax: "{{ url('qasubmitstageappData') }}?status=" + status + "&pageMethod=" + pageMethod,
        columns: [
          {
            data: 'quality_spec_trx_hdr_id',
            name: 'actions',
            className: 'text-center',
            width: '140px',
            render: function (data, type, row) {
              let buttons = '';
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'qualitycheck')) {
                buttons += `
              <button  class="btn btn-sm btn-success me-1 qualitycheck" data-id="${data}"
              data-status="${row.qa_status}"
              data-qa_id="${row.qa_submitstage_trx_hdr_id}"
                  data-bs-toggle="tooltip" 
                  data-bs-placement="top" 
                  title="Quality Check"><i class="bi bi-check2-circle"></i></button>`;
              }

              if (window.toolbarButtons?.some(btn => btn.attr.id === 'qualityindent')) {
                buttons += `
              <button  class="btn btn-sm btn-primary me-1 qcindent" data-id="${data}"><i class="bi bi-check2-circle"></i> Indent</button>`;
              }
              if (window.toolbarButtons?.some(btn => btn.attr.id === 'approve')) {
                buttons += `
              <button  class="btn btn-sm btn-success me-1 approved" data-id="${data}"><i class="bi bi-check2-square"></i></button>`;
              }
              return buttons;
            }

          },
          { data: 'qa_submitstage_trx_hdr_id', name: 'qa_submitstage_trx_hdr_id', visible: false },
          { data: 'reference_no', name: 'reference_no' },
          { data: 'qa_status', name: 'qa_status' },
          { data: 'status', name: 'status' },
          { data: 'job_no', name: 'job_no' },
          { data: 'job_date', name: 'job_date' },
          { data: 'batch_no', name: 'batch_no' },
          { data: 'product_code', name: 'product_code' },
          { data: 'concatenated_product', name: 'concatenated_product' },
          { data: 'production_qty', name: 'production_qty' },
          { data: 'quality_type', name: 'quality_type' },
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

      // Individual column search
      $('#QcTbl thead').on('keyup change', ".column-search", function () {
        var colIndex = $(this).parent().index();
        table.column(colIndex).search(this.value).draw();
      });
    });

    // QC check	
    $(document).on('click', '.qualitycheck', function () {

      var cellValue = $(this).data('qa_id');
      var cellValue1 = $(this).data('id');
      var qastatus = $(this).data('status');
      var pageMethod = "{{ $pageMethod }}";


      var source = "";
      if (qastatus == "0") {
        source = "QUALITYREQUEST";
      } else if (qastatus == 'REJECTED') {
        source = "QCEDIT";
      } else {
        source = "QASUBMIT";
      }

      var url = "{{ URL::to('qualitycheckcreate') }}";
      var status = 'QCCHECK';
      if (source == 'QCEDIT') {
        var editUrl = url + '/' + cellValue1 + '/' + status + "?source=" + source + "&pageMethod=" + pageMethod;
      } else {
        var editUrl = url + '/' + cellValue + '/' + status + "?source=" + source + "&pageMethod=" + pageMethod;
      }
      window.location.replace(editUrl);


    });


    // 	qualityindent
    $(document).on('click', '.qcindent', function () {

      var qualityhdr = $(this).data('id');

      $('#myModal2').modal('show');
      var url = "{{URL::to('getindentquantity')}}/" + qualityhdr + '?status=productionqc';
      $.get(url, function (data) {
        $('.indentdata').html(data);
        $('.readonlydiv').css("pointer-events", "none");
      });




    });
    // approve 
    $(document).on('click', '.approved', function () {


      var id = $(this).data('id');

      var status = 'QCAPPROVE';
      var url = "{{ URL::to('qualitycheckappcreate') }}/"
      window.location.replace(url + id + '/' + status);

    });

    //	check qty

    $(document).on('change', '.used_qty', function () {
      var index = $(this).data('val');
      var used_qty = parseFloat($(this).val());
      var remaining_qty = parseFloat($('.remaining_qty' + index).val());
      if (used_qty > remaining_qty) {

        $('.used_qty' + index).val('');
        showCustomAlert("Not greater than Remaining Quantity", 'warning');

      }
    });

    // save function

    $(document).on('click', '.indentreducesave', function () {
      var save = $(this).val();
      var url = "{{ url('indentreducesave') }}";
      var red_url = "{{ url('qualitycheck') }}";

      change_date();
      var formdata = $('#indentreducesaves').serialize();
      var form = $('#indentreducesaves');
      $.post(url, formdata, function (data) {
        var status = data.status;
        var msg = '<span style="color:#090065">' + data.message + '</span>  ';
        var id = data.id;
        if (save = 'SAVE') {

          showCustomAlert(msg, status);
          setTimeout(function () {
            window.location.href = red_url;
          }, 1500);
        }
        else {
          showCustomAlert(msg, status);
          setTimeout(function () {
            window.location.href = create_url;
          }, 1500);
        }
      });



    });


  </script>
@endpush